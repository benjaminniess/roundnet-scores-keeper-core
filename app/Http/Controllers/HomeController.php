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

         // For each game, get logged user team
        foreach ($games as $game) {
            $game->formated_end_date = $game->set_end_date();
            $game->is_referee = $game->is_referee();
            $game->winning_game = $game->set_winning_game();

        }
        // dd($games);
        return view('home', compact(
            'games'
        ));
    }
}
