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

        $points_types_chart = $user_obj->get_chart_js_points_types();
        $victory_stats_chart = $user_obj->get_chart_js_victory_stats();

        return view('users.stats',compact(
            'time_spent_playing',
            'time_spent_refereing',
            'victory_stats_chart',
            'points_types_chart'
        ));
    }
}
