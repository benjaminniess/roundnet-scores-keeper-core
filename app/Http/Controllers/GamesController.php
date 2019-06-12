<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GamesController extends Controller
{
  function index()
  {

    $games = [
      [
        'id' => 1,
        'score' => '21 - 3',
        'date' => '22/05/2019',
      ],
      [
        'id' => 2,
        'score' => '10 - 21',
        'date' => '23/05/2019',
      ],
      [
        'id' => 3,
        'score' => '24 - 22',
        'date' => '24/05/2019',
      ],
    ];

    return view('games')->withGames($games);
  }

  function show($game)
  {
    $game = [
        'id' => 1,
        'score' => '21 - 3',
        'date' => '22/05/2019',
      ];
    return view('game')->withGame($game);
  }
}
