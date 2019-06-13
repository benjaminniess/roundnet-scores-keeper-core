<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Game;

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
}
