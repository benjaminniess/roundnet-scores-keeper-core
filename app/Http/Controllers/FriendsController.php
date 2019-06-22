<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\User_Relationships;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{
    public function show() {
        $active_auth_user_friends = (new User_Relationships)->get_auth_user_friends(User_Relationships::ACTIVE_STATUS);
        $pending_auth_user_friends = (new User_Relationships)->get_auth_user_friends(User_Relationships::PENDING_STATUS);
        $blocked_auth_user_friends = (new User_Relationships)->get_auth_user_friends(User_Relationships::BLOCKED_STATUS);

        return view('friends',compact('active_auth_user_friends','pending_auth_user_friends','blocked_auth_user_friends'));
    }
}
