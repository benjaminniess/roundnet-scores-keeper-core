<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Game;
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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get the currently authenticated user's ID...
        $id = Auth::id();

        if ( (int) $id > 0 ) {
            $game_live = Game::where( [
                [ 'status', 'live' ],
            ])->first();

            if ( ! empty( $game_live ) ) {
                return redirect(url('/games/live') );
            }
        }

        return view('home');
    }
}
