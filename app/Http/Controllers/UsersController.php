<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Action_Type;

class UsersController extends Controller
{
    /**
     * Display a listing of stats.
     *
     * @return 
     */
    public function stats()
    {
        // The logged user
    	$user_obj = User::find(auth()->id());

        // Get logged user points by type and count each
    	$positive_points = $user_obj->points_by_type(Action_Type::POSITIVE_POINTS)->count();
    	$negative_points = $user_obj->points_by_type(Action_Type::NEGATIVE_POINTS)->count();
    	$neutral_points = $user_obj->points_by_type(Action_Type::NEUTRAL_POINTS)->count();

        $games = $user_obj->games;

            	

        return view('users.stats',compact('positive_points','negative_points','neutral_points'));
    }
}
