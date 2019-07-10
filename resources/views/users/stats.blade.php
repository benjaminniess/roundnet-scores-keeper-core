@extends('layouts.default')


@section('content')

    <h2>Stats</h2>

    <p>Number of positive points : {{ $positive_points }}</p>
    <p>Number of negative points : {{ $negative_points }}</p>
    <p>Number of neutral points : {{ $neutral_points }}</p>

@endsection