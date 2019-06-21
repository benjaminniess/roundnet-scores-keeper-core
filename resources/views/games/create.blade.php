@extends('layouts.default')

@section('content')
<h2>Create a new game</h2>
<form class="form" action="/games" method="POST">
    @csrf

    <h3>Team 1</h3>
        <label for="player1">Player 1</label>
        <select class="select" name="player1" required>
            <option value="">Select a player</option>
            @foreach($players as $player)

                <option value="{{ $player->id }}">{{ $player->name }}</option>

            @endforeach
        </select>

        <label for="player2">Player 2</label>
        <select class="select" name="player2" required>
            <option value="">Select a player</option>
            @foreach($players as $player)

                <option value="{{ $player->id }}">{{ $player->name }}</option>

            @endforeach
        </select>

    <h3>Team 2</h3>
        <label for="player3">Player 3</label>
        <select class="select" name="player3" required>
            <option value="">Select a player</option>
            @foreach($players as $player)

                <option value="{{ $player->id }}">{{ $player->name }}</option>

            @endforeach
        </select>

        <label for="player4">Player 4</label>
        <select class="select" name="player4" required>
            <option value="">Select a player</option>
            @foreach($players as $player)

                <option value="{{ $player->id }}">{{ $player->name }}</option>

            @endforeach
        </select>

        <button type="submit"> Add project</button>
</form>

    {{-- Return form errors --}}
    @if ($errors->any())

    @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach

    @endif
@endsection
