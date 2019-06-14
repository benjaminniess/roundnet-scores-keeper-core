@extends('layouts.default');

@section('content')
    <h2>Games list</h2>
    <ul>
        @foreach($games as $game)
            <li><a href="{{ url('/games') }}/{{ $game->id }}">{{ $game->player1 }} - {{ $game->player2 }} - {{ $game->player3 }} - {{ $game->player4 }}</a></li>
        @endforeach
    </ul>
@endsection
