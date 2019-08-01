<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserRelationships;
use App\Events\AFriendRequestHasBeenAccepted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\LengthAwarePaginator;

class FriendsController extends Controller
{
    /**
     * Display the list of the user friends
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        /** @var User $user_obj */
        $user_obj = User::find(Auth::id());

        $raw_active_auth_user_friends = $user_obj->friends(
            UserRelationships::ACTIVE_STATUS
        );
        $guest_auth_user_friends = $user_obj->friends(
            UserRelationships::GUEST_STATUS
        );

        $pending_auth_user_friends = $user_obj->get_friend_requests();
        $blocked_auth_user_friends = $user_obj->friends(
            UserRelationships::BLOCKED_STATUS
        );

        $perPage = 20;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($raw_active_auth_user_friends->toArray(), $perPage * ($currentPage - 1), $perPage);

        $active_auth_user_friends = new LengthAwarePaginator($currentItems, count($raw_active_auth_user_friends), $perPage, $currentPage);
        $active_auth_user_friends->setPath('/friends');

        return view(
            'friends.show',
            compact(
                'active_auth_user_friends',
                'guest_auth_user_friends',
                'pending_auth_user_friends',
                'blocked_auth_user_friends'
            )
        );
    }

    /**
     * Accept or deny a friend request
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $attributes = request()->validate([
            'status' => 'required'
        ]);

        /** @var \App\User $user_obj */
        $user_obj = User::find(Auth::id());

        $relationship = $user_obj->get_relationship($user->id);
        if (
            empty($relationship) ||
            (int) $relationship->user_id_2 !== (int) $user_obj->id
        ) {
            abort(403, 'Cheating?');
        }

        $relationship->update_status($attributes);

        if ( $relationship->status === 'active' ) {
            event(new AFriendRequestHasBeenAccepted($relationship));
        }

        return back();
    }

    /**
     * Search for friends
     *
     * @return $this
     */
    public function search()
    {
        /** @var \App\User $user_obj */
        $user_obj = User::find(Auth::id());

        if (empty(request('nickname'))) {
            abort(403, 'Cheating?');
        }

        $friends = \App\User::where(
            'name',
            'like',
            '%' . request('nickname') . '%'
        )->get();

        return view('friends.search', [
            'results' => $friends,
            'current_user' => $user_obj
        ]);
    }

    /**
     * Manage a request ask
     *
     * @param Request $request
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function request(Request $request, $user_id)
    {
        /** @var \App\User $user_obj */
        $user_obj = User::find(Auth::id());

        if ($user_obj->is_friend($user_id)) {
            abort(403, 'You are already friends');
        }

        $friend_obj = User::find($user_id);
        if (empty($friend_obj)) {
            abort(403, 'Cheating?');
        }

        $relationship = new \App\UserRelationships();
        $relationship->user_id_1 = $user_obj->id;
        $relationship->user_id_2 = $user_id;
        $relationship->status = 'pending';

        $relationship->save();

        // TODO: Send notif

        return redirect('/friends');
    }

    public function invite(Request $request)
    {
        /** @var \App\User $user_obj */
        $user_obj = User::find(Auth::id());

        $guest_id = (int) request('guest_id');
        $guest_email = request('guest_email');

        if (!$user_obj->is_friend($guest_id)) {
            abort(403, 'Cheating?');
        }

        $validator = Validator::make($request->all(), [
            'guest_email' => 'required|email|max:255'
        ]);

        if ($validator->fails()) {
            return redirect('friends')
                ->withErrors($validator)
                ->withInput();
        }

        $user_exists = \App\User::where('email', '=', $guest_email)->first();
        if (!empty($user_exists)) {
            $validator
                ->errors()
                ->add(
                    'guest_email_' . $guest_id,
                    'This email is already taken'
                );
            return redirect('friends')
                ->withErrors($validator)
                ->withInput();
        }

        $guest_obj = User::find($guest_id);
        if (empty($guest_obj)) {
            abort(403, 'Cheating?');
        }

        $friendship_obj = \App\UserRelationships::where([
            'user_id_1' => $user_obj->id,
            'user_id_2' => $guest_obj->id
        ])->first();
        if (empty($friendship_obj)) {
            abort(403, 'Cheating?');
        }

        $friendship_obj->status = 'active';
        $friendship_obj->save();

        $guest_obj->email = $guest_email;
        $guest_obj->save();

        $credentials = ['email' => $guest_obj->email];

        Mail::to($guest_obj->email)->send(
            new \App\Mail\GuestInvitation($guest_obj, $user_obj)
        );
        Password::sendResetLink($credentials);

        return redirect()
            ->back()
            ->with('message', 'Invitation sent successfully');
    }

     /**
     * Remove the specified relationship from storage.
     *
     * @param App\User $user
     */
    public function destroy(User $user)
    {
        $user_obj = User::find( auth()->id() );
        $relationship = $user_obj->get_relationship( $user->id );
        
        // Check if the auth user and his friend he wants to delete are really friends
        if( !$user_obj->is_friend($user->id) ){
            abort(403, 'Cheating?');
        }

        // Check if the user is a guest user
        if ($user->password !== 'guestpassword') {
            abort(403, 'Cheating?');
        }

        $user->destroy_user();
        $relationship->destroy_relationship();

        return redirect('/friends')
            ->with('message', 'Your friend has been deleted');
    }
}
