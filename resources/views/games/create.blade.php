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

            <option @php echo old('player1') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

        @endforeach
    </select>

    <label for="player2">Player 2</label>
    <select class="select" name="player2" required>
        <option value="">Select a player</option>
        @foreach($players as $player)

            <option @php echo old('player2') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

        @endforeach
    </select>

    <h3>Team 2</h3>
    <label for="player3">Player 3</label>
    <select class="select" name="player3" required>
        <option value="">Select a player</option>
        @foreach($players as $player)

            <option @php echo old('player3') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

        @endforeach
    </select>

    <label for="player4">Player 4</label>
    <select class="select" name="player4" required>
        <option value="">Select a player</option>
        @foreach($players as $player)

            <option @php echo old('player4') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

        @endforeach
    </select>

    <h3>Options</h3>

    <label for="referee">Referee</label>
    <select class="select" name="referee">
        <option value="">Select a referee if necessary</option>
        @foreach($players as $player)

            <option @php echo old('referee') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

        @endforeach
    </select>


    <p>
        <input type="number" name="points_to_win" id="game_points" required value="{{ old('points_to_win', 21) }}">
        <label for="game_points">points to win</label>
    </p>

    <!-- Disable for MVP
    <p>
        <input type="checkbox" name="enable_turns" id="enable_turns" checked>
        <label for="enable_turns">Turn every 5 points?</label>
    </p>
    -->

    <p>
        <input type="checkbox" name="start_now" id="start_now" checked>
        <label for="start_now">Start now?</label>
    </p>
    <button type="submit"> Add project</button>
</form>

    {{-- Return form errors --}}
    @if ($errors->any())

    @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach

    @endif
@endsection
