@extends('layouts.default');

@section('content')

    <h2>Game number {{ $game['id'] }}</h2>
    <h3>Game info</h3>
    <ul>
      <li>Game score : {{ $game['score'] }}</li>
      <li>Game date : {{ $game['date'] }}</li>
    </ul>

@endsection
