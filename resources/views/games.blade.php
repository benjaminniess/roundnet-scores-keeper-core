@extends('layouts.default');

@section('content')

    <h2>Games list</h2>

    <ul>
        @foreach($games as $game)
            <li>{{ $game['score'] }}</li>
        @endforeach
    </ul>


@endsection
