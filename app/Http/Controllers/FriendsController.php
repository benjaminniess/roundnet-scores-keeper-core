<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserRelationships;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{

    /**
     * Display the list of the user friends
     *
     * @return \Illuminate\Http\Response
     */
    public function show() {
        /** @var User $user_obj */

        // Get the currently authenticated user
        $user_obj = User::find(Auth::id());
        if ( empty( $user_obj ) ) {
          return redirect(url('/') );
        }

        $active_auth_user_friends = $user_obj->friends(UserRelationships::ACTIVE_STATUS);
        $pending_auth_user_friends = $user_obj->friends(UserRelationships::PENDING_STATUS);
        $blocked_auth_user_friends = $user_obj->friends(UserRelationships::BLOCKED_STATUS);

        return view('friends.show',compact('active_auth_user_friends','pending_auth_user_friends','blocked_auth_user_friends'));
    }

    /**
     * Accept or deny a friend request
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\User $user
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
}
