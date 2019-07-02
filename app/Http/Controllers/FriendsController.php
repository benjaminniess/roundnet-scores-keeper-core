<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserRelationships;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $pending_auth_user_friends = $user_obj->get_friend_requests();
        $blocked_auth_user_friends = $user_obj->friends(UserRelationships::BLOCKED_STATUS);

        return view('friends.show',compact('active_auth_user_friends','pending_auth_user_friends','blocked_auth_user_friends'));
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
            // TODO: manage laravel $errors var
            die('You are already friends');
        }

        // TODO: Check that user exists

        $relationship = new \App\UserRelationships();
        $relationship->user_id_1 = $user_obj->id;
        $relationship->user_id_2 = $user_id;
        $relationship->status = 'pending';

        $relationship->save();

        // TODO: Send notif

        return redirect('/friends');
    }
}
