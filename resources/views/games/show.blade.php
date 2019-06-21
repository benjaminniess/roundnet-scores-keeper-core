@extends('layouts.default')

@section('content')
    <h2>Game title {{ $game->title }}</h2>
    <h3>Players</h3>
    <ul>
        @foreach ($players as $player_key => $player)
            <li>{{ $player_key . ' : ' . $player->name }}</li>
        @endforeach
    </ul>
    <a href="{{ url('/games') }}/{{ $game->id }}/edit">
        Edit
    </a>
@endsection
