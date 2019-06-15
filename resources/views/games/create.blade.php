@extends('layouts.default');

@section('content')
<h2>Create a new game</h2>
<form class="form" action="/games" method="POST">


    {{ csrf_field() }}
    <h3>Team 1</h3>
        <label for="player1">Player 1</label>
        <select class="select" name="player1">
            @foreach($players as $player)

                <option value="{{ $player->id }}">{{ $player->name }}</option>

            @endforeach
        </select>

        <label for="player2">Player 2</label>
        <select class="select" name="player2">
            @foreach($players as $player)

                <option value="{{ $player->id }}">{{ $player->name }}</option>

            @endforeach
        </select>

    <h3>Team 2</h3>
        <label for="player3">Player 3</label>
        <select class="select" name="player3">
            @foreach($players as $player)

                <option value="{{ $player->id }}">{{ $player->name }}</option>

            @endforeach
        </select>

        <label for="player4">Player 4</label>
        <select class="select" name="player4">
            @foreach($players as $player)

                <option value="{{ $player->id }}">{{ $player->name }}</option>

            @endforeach
        </select>

        <button type="submit"> envoyer</button>
</form>
@endsection
