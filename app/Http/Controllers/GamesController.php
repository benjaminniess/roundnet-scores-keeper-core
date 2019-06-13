<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Game;
use Illuminate\Support\Facades\Auth;

class GamesController extends Controller
{
  // GET ALL GAMES AND RETURN INFO TO THE VIEW
  function index()
  {
    $games = Game::all();
    return view('games.index')->withGames($games);
  }

  // GET ONE GAME BY ID AND RETURN INFO TO THE VIEW
  function show($id)
  {
    $game = Game::where('id',$id)->first();
    return view('games.show')->withGame($game);
  }

  function live()
  {
        // Get the currently authenticated user's ID...
        $id = Auth::id();

        if ( (int) $id <= 0 ) {
            return redirect(url('/') );

        }

        $game_live = Game::where( [
            [ 'status', 'live' ],
        ])->first();

        if ( empty( $game_live ) ) {
            return redirect(url('/') );
        }

        return view('games.live');
  }
}
