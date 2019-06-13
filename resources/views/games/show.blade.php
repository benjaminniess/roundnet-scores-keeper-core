@extends('layouts.default');

@section('content')
    <h2>Game title {{ $game->title }}</h2>
    <h3>Players</h3>
    <ul>
      <li>Player 1 : {{ $game->player1 }}</li>
      <li>Player 2 : {{ $game->player2 }}</li>
      <li>Player 3 : {{ $game->player3 }}</li>
      <li>Player 4 : {{ $game->player4 }}</li>
    </ul>
@endsection
