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

        $existing_token = Auth::user()->tokens()->first();
        if ( empty( $existing_token ) ) {
            Auth::user()->createToken('ReactToken')->accessToken;
            $existing_token = Auth::user()->tokens()->first();
        }

        return view('games.live')->withToken($existing_token);
    }

  // CREATE A NEW PROJECT
  function create()
  {

    return view('games.create');
  }

  function store()
  {

    $game = new Game();

    $game->player1 = request('player1');
    $game->player2 = request('player2');
    $game->player3 = request('player3');
    $game->player4 = request('player4');

    $game->save();

    return redirect('/games');
    }
}
