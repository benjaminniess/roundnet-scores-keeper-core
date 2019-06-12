<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Game;

class GamesController extends Controller
{
  function index()
  {

    $games = Game::all();

    return view('games.index')->withGames($games);
  }

  function show($id)
  {
    $game = Game::where('id',$id)->first();

    return view('games.show')->withGame($game);
  }
}
