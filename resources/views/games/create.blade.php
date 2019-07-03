@extends('layouts.default')

@section('content')

<h2 class="heading mb-4">Create a new game</h2>

{{-- Return form errors --}}
    @if ($errors->any())

    @foreach ($errors->all() as $error)
        <div class="alert alert-danger my-3" role="alert">{{ $error }}</div>
    @endforeach

    @endif

<form action="/games" method="POST">
    @csrf

<div class="row my-2">
    <div class="col-sm-6 my-2">
      <div class="card">
          <h5 class="card-header">Team 1</h5>
          <div class="card-body">
                <div class="form-group">
                    <label for="player1">Player 1</label>
                    <select class="form-control {{ $errors->has('4players') || $errors->has('player-in-game') ? 'is-invalid' : '' }}" name="player1" required>
                        <option value="">Select a player</option>
                        @foreach($players as $player)

                            <option @php echo old('player1') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="player2">Player 2</label>
                    <select class="form-control {{ $errors->has('4players') || $errors->has('player-in-game') ? 'is-invalid' : '' }}" name="player2" required>
                        <option value="">Select a player</option>
                        @foreach($players as $player)

                            <option @php echo old('player2') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

                        @endforeach
                    </select>
                </div>
          </div>
        </div>  
    </div>

    <div class="col-sm-6 my-2">
        <div class="card">
            <h5 class="card-header">Team 2</h5>          
            <div class="card-body">
                <div class="form-group">
                    <label for="player3">Player 3</label>
                        <select class="form-control {{ $errors->has('4players') || $errors->has('player-in-game') ? 'is-invalid' : '' }}" name="player3" required>
                            <option value="">Select a player</option>
                            @foreach($players as $player)

                                <option @php echo old('player3') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

                            @endforeach
                        </select>
                </div>

                <div class="form-group">
                    <label for="player4">Player 4</label>
                    <select class="form-control {{ $errors->has('4players') || $errors->has('player-in-game') ? 'is-invalid' : '' }}" name="player4" required>
                        <option value="">Select a player</option>
                        @foreach($players as $player)

                            <option @php echo old('player4') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row my-2">
    <div class="col-sm-6 my-2">
        <div class="card">
            <h5 class="card-header">Options</h5>          
            <div class="card-body">
                <div class="form-group">
                    <label for="referee">Referee</label>
                    <select class="form-control {{ $errors->has('referee') ? 'is-invalid' : '' }}" name="referee">
                        <option value="">Select a referee if necessary</option>
                        @foreach($players as $player)

                            <option @php echo old('referee') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="game_points">points to win</label>
                    <input type="number" name="points_to_win" id="game_points" class="form-control" required value="{{ old('points_to_win', 21) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 my-2">
        <div class="card">
            <h5 class="card-header">Start game</h5>          
            <div class="card-body">
                <div class="form-group form-check">
                    <input type="checkbox" name="start_now" id="start_now" class="form-check-input" checked>
                    <label for="start_now" class="form-check-label">Start now?</label>
                </div>
            
            <button type="submit" class="btn btn-success">Create game</button>
            </div>
        </div>
    </div>

</div>


    <!-- Disable for MVP
    <p>
        <input type="checkbox" name="enable_turns" id="enable_turns" checked>
        <label for="enable_turns">Turn every 5 points?</label>
    </p>
    -->

</form>

@endsection
