@extends('layouts.default')

@section('content')

<div class="container">
<h2 class="heading mb-4">Create a new game</h2>

<form action="/games" method="POST">
    @csrf
        <h3 class="heading mt-5">Team 1</h3>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="player1">Player 1</label>
                <select class="form-control" name="player1" required>
                    <option value="">Select a player</option>
                    @foreach($players as $player)

                        <option @php echo old('player1') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="player2">Player 2</label>
                <select class="form-control" name="player2" required>
                    <option value="">Select a player</option>
                    @foreach($players as $player)

                        <option @php echo old('player2') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

                    @endforeach
                </select>
            </div>
        </div>

    <h3 class="heading mt-5">Team 2</h3>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="player3">Player 3</label>
                <select class="form-control" name="player3" required>
                    <option value="">Select a player</option>
                    @foreach($players as $player)

                        <option @php echo old('player3') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

                    @endforeach
                </select>
        </div>
        <div class="form-group col-md-6">
            <label for="player4">Player 4</label>
            <select class="form-control" name="player4" required>
                <option value="">Select a player</option>
                @foreach($players as $player)

                    <option @php echo old('player4') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

                @endforeach
            </select>
        </div>
    </div>

    <h3 class="heading mt-5">Options</h3>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="referee">Referee</label>
            <select class="form-control" name="referee">
                <option value="">Select a referee if necessary</option>
                @foreach($players as $player)

                    <option @php echo old('referee') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

                @endforeach
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="game_points">points to win</label>
            <input type="number" name="points_to_win" id="game_points" class="form-control" required value="{{ old('points_to_win', 21) }}">
        </div>
    </div>

    <!-- Disable for MVP
    <p>
        <input type="checkbox" name="enable_turns" id="enable_turns" checked>
        <label for="enable_turns">Turn every 5 points?</label>
    </p>
    -->

<div class="form-row">
    <div class="form-group col-md-12">
        <input type="checkbox" name="start_now" id="start_now" class="form-check-input" checked>
        <label for="start_now" class="form-check-label">Start now?</label>
    </div>
</div>
    
    <button type="submit" class="btn btn-success">Create game</button>
</form>

    {{-- Return form errors --}}
    @if ($errors->any())

    @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach

    @endif
</div>
@endsection
