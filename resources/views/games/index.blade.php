@extends('layouts.default');

@section('content')
    <h2>Games list</h2>
    <ul>
        @foreach($games as $game)
            <li><a href="{{ url('/games') }}/{{ $game->id }}">{{ $game->score_team_1 }} - {{ $game->score_team_2 }}</a></li>
        @endforeach
    </ul>
@endsection
