<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserRelationships;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;

class FriendsController extends Controller
{

    /**
     * Display the list of the user friends
     *
     * @return \Illuminate\Http\Response
     */
    public function show() {
        /** @var User $user_obj */
        $user_obj = User::find(Auth::id());
        if ( empty( $user_obj ) ) {
          return redirect(url('/') );
        }

        $active_auth_user_friends = $user_obj->friends(UserRelationships::ACTIVE_STATUS);
        $guest_auth_user_friends = $user_obj->friends(UserRelationships::GUEST_STATUS);
        $pending_auth_user_friends = $user_obj->get_friend_requests();
        $blocked_auth_user_friends = $user_obj->friends(UserRelationships::BLOCKED_STATUS);

        return view('friends.show',compact('active_auth_user_friends', 'guest_auth_user_friends', 'pending_auth_user_friends','blocked_auth_user_friends'));
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
	    if ( empty( $relationship ) || (int) $relationship->user_id_2 !== (int) $user_obj->id ) {
		    abort(403, 'Cheating?');
	    }

        $relationship->update_status($attributes);

        return back();
    }

    /**
     * Search for friends
     *
     * @return $this
     */
    public function search() {
	    /** @var \App\User $user_obj */
	    $user_obj = User::find(Auth::id());

        $friends = \App\User::where( 'name', 'like', '%' . request('nickname') . '%' )->get();

        return view( 'friends.search', [
        	'results'      => $friends,
	        'current_user' => $user_obj,
        ] );
    }

    /**
     * Manage a request ask
     *
     * @param Request $request
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function request(Request $request, $user_id) {
        /** @var \App\User $user_obj */
        $user_obj = User::find(Auth::id());

        if( $user_obj->is_friend( $user_id )) {
	        abort(403, 'You are already friends');
        }

        $friend_obj = User::find( $user_id );
        if ( empty( $friend_obj ) ) {
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

    public function invite(Request $request) {
	    /** @var \App\User $user_obj */
	    $user_obj = User::find(Auth::id());

	    if ( ! $user_obj->is_friend( (int) request('guest_id') ) ) {
		    abort(403, 'Cheating?');
	    }

	    $validator = Validator::make($request->all(), [
	    	'guest_email' => 'required|email|max:255|unique:users',
	    ]);

	    if ( $validator->fails() ) {
		    return redirect('friends')
			    ->withErrors($validator)
			    ->withInput();
	    }

	    $user_exists = \App\User::where( 'email', '=', request('guest_email') )->first();
	    if ( ! empty( $user_exists ) ) {
		    $validator->errors()->add('guest_email_' . (int) request('guest_id'), 'This email is already taken');
		    return redirect('friends')
			    ->withErrors($validator)
			    ->withInput();
	    }

	    $guest_obj = User::find( (int) request('guest_id') );

	    $friendship_obj = \App\UserRelationships::where(
	    	[
	    		'user_id_1' => $user_obj->id,
			    'user_id_2' => $guest_obj->id
		    ]
	    )->first();
	    if ( empty( $friendship_obj ) ) {
		    abort(403, 'Cheating?');
	    }

	    $friendship_obj->status = 'active';
	    $friendship_obj->save();

	    $guest_obj->email = request('guest_email');
	    $guest_obj->save();

        $credentials = ['email' => $guest_obj->email ];
        Password::sendResetLink($credentials);

	    return redirect()->back()->with('message', 'Invitation sent successfully');
    }
}
