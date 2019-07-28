<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /** @var User $user_obj */
        $user_obj = \App\User::find(Auth::id());
        if (empty($user_obj)) {
            return view('home-not-logged');
        }

        if ('true' === request('flush_token')) {
            $user_obj->get_access_token(true);

            return redirect(url('/games/live'));
        }

        $live_game = $user_obj->get_live_game();
        if (!empty($live_game)) {
            return redirect(url('/games/live'));
        }

        $games = $user_obj->get_games_including_referee()->orderBy('end_date', 'desc')->take(3)->get();

        // Setting the ending game date
        foreach ($games as $game) {
            if ($game->end_date === NULL && $game->start_date === NULL) {
                $game->formated_end_date = "the game has not started yet";
            } elseif($game->end_date === NULL && $game->start_date !== NULL) {
                $game->formated_end_date = "the game is not finished yet";
            } else {
                $end_date = Carbon::createFromTimestamp($game->end_date / 1000);
                $game->formated_end_date = $end_date->toDayDateTimeString();
            }
        }

         // For each game, get logged user team
        foreach ($games as $game) {
            $game->referee = ($game->referee()) ? $game->referee() : NULL;
            $game->user_team = $user_obj->get_team($game->id);
            $game->winning_team = $game->get_winning_team();

            // Return true is the auth user is the game referee
            if ( isset($game->referee) && $game->referee->id === $user_obj->id ) {
                $game->is_referee = true;
            }else{
                $game->is_referee = false;
            }

            // Compare user team and game winning team
            if ($game->user_team === $game->winning_team) {
                $game->winning_game = 'Won';
            } else {
                $game->winning_game = 'Lost';
            }
        }

        return view('home', compact(
            'games'
        ));
    }
}
