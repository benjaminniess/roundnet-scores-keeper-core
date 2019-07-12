@extends('layouts.default')


@section('content')

    <h2>Stats</h2>

    <p>Number of positive points : {{ $positive_points }}</p>
    <p>Number of negative points : {{ $negative_points }}</p>
    <p>Number of neutral points : {{ $neutral_points }}</p>
    <p>Time spent playing : {{ $time_spent_playing }}</p>
    <p>Time spent refereing : {{ $time_spent_refereing }}</p>
    <p>Victory stats : {{ $victory_stats['percentage_victory'] . '% (' . $victory_stats['total_winning_games'] . '/' . $victory_stats['total_games'] . ')' }}</p>

@endsection