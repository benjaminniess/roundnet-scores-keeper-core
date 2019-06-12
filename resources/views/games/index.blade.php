@extends('layouts.default');

@section('content')

    <h2>Games list</h2>

    <ul>
        @foreach($games as $game)
            <li><a href="http://127.0.0.1:8000/games/{{ $game->id }}">{{ $game->title }}</a></li>
        @endforeach
    </ul>


@endsection
