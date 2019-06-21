@extends('layouts.default')

@section('content')
<h2>Edit a game</h2>
<form class="form" action="/games/{{ $game->id }}" method="POST">

    @csrf
    @method('PATCH')
    <h3>Team 1</h3>
        <label for="player1">Player 1</label>
        <select class="select" name="player1" required>
            <option value="">Select a player</option>
            @foreach($players as $player)
                <option value="{{ $player->id }}"
                    @if ($player->id === (int) $game->player1)
                        {{ "selected" }}
                    @endif>
                    {{ $player->name }}
                </option>

            @endforeach
        </select>

        <label for="player2">Player 2</label>
        <select class="select" name="player2" required>
            <option value="">Select a player</option>
            @foreach($players as $player)
                <option value="{{ $player->id }}"
                    @if ($player->id === (int) $game->player2)
                        {{ "selected" }}
                    @endif>
                    {{ $player->name }}
                </option>

            @endforeach
        </select>

    <h3>Team 2</h3>
        <label for="player3">Player 3</label>
        <select class="select" name="player3" required>
            <option value="">Select a player</option>
            @foreach($players as $player)
                <option value="{{ $player->id }}"
                    @if ($player->id === (int) $game->player3)
                        {{ "selected" }}
                    @endif>
                    {{ $player->name }}
                </option>

            @endforeach
        </select>

        <label for="player4">Player 4</label>
        <select class="select" name="player4" required>
            <option value="">Select a player</option>
            @foreach($players as $player)
                <option value="{{ $player->id }}"
                    @if ($player->id === (int) $game->player4)
                        {{ "selected" }}
                    @endif>
                    {{ $player->name }}
                </option>

            @endforeach
        </select>

        <button type="submit"> Edit</button>
</form>

<form class="form" action="/games/{{ $game->id }}" method="POST">

    @csrf
    @method('DELETE')

        <button type="submit"> Delete</button>
</form>
    {{-- Return form errors --}}
    @if ($errors->any())

    @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach

    @endif
@endsection
