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

        // get user total time spent playing
        $games_as_player = $user_obj->games;
        $player_games_duration = [];
        foreach ($games_as_player as $game) {
            $game->duration = $game->duration();
            array_push($player_games_duration,$game->duration());
        }
        $time_spent_playing = gmdate('H:i:s', array_sum($player_games_duration));

        // get user total time spent refereing
        $games_as_referee = $user_obj->games_as_referee()->get();
        $referee_games_duration = [];
        foreach ($games_as_referee as $game) {
            $game->duration = $game->duration();
            array_push($referee_games_duration,$game->duration());
        }
        $time_spent_refereing = gmdate('H:i:s', array_sum($referee_games_duration));

        // TODO Get user total winning games
        // TODO Get user total losing games
        // TODO Get user % of victory
        

        return view('users.stats',compact(
            'positive_points',
            'negative_points',
            'neutral_points',
            'time_spent_playing',
            'time_spent_refereing'
        ));
    }
}
