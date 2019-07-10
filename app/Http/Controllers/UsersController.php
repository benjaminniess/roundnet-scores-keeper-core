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
    	$user_obj = User::find(auth()->id());

    	$positive_points = $user_obj->points_by_type(Action_Type::POSITIVE_POINTS);
    	$negative_points = $user_obj->points_by_type(Action_Type::NEGATIVE_POINTS);
    	$neutral_points = $user_obj->points_by_type(Action_Type::NEUTRAL_POINTS);

    	

        return view('users.stats',compact('positive_points','negative_points','neutral_points'));
    }
}
