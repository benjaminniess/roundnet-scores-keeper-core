@extends('layouts.default')

@section('content')

<h2 class="heading mb-4">Create a new game</h2>

@include('components.errors')

<form action="/games" method="POST">
    @csrf

<div class="row my-2">
    <div class="col-sm-6 my-2">
      <div class="card">
          <h5 class="card-header">Team 1</h5>
          <div class="card-body">
                <div class="form-group">
                    <label for="player1">Player 1</label>
                    <select class="form-control friend-selector {{ $errors->has('4players') || $errors->has('player-in-game') ? 'is-invalid' : '' }}" data-player="1" name="player1" @php echo empty( old('guest1') ) ? 'required"' : '' @endphp >
                        <option value="">Select a player</option>
                        @foreach($players as $player)

                            <option @php echo old('player1') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

                        @endforeach
                    </select>
                    or <a href="#" class="add-guest" data-player="1">add a guest.</a>
                    <input type="text" @php echo empty( old('guest1') ) ? 'style="display: none"' : '' @endphp name="guest1" data-player="1" value="{{ old('guest1') }}" class="form-control guest-field {{ $errors->has('guest1') ? 'is-invalid' : '' }}" placeholder="Enter your buddy's nickname">
                </div>

                <div class="form-group">
                    <label for="player2">Player 2</label>
                    <select class="form-control friend-selector {{ $errors->has('4players') || $errors->has('player-in-game') ? 'is-invalid' : '' }}" data-player="2" name="player2" @php echo empty( old('guest2') ) ? 'required"' : '' @endphp >
                        <option value="">Select a player</option>
                        @foreach($players as $player)

                            <option @php echo old('player2') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

                        @endforeach
                    </select>
                    or <a href="#" class="add-guest" data-player="2">add a guest.</a>
                    <input type="text" @php echo empty( old('guest2') ) ? 'style="display: none"' : '' @endphp name="guest2" data-player="2" value="{{ old('guest2') }}" class="form-control guest-field {{ $errors->has('guest2') ? 'is-invalid' : '' }}" placeholder="Enter your buddy's nickname">
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
                        <select class="form-control friend-selector {{ $errors->has('4players') || $errors->has('player-in-game') ? 'is-invalid' : '' }}" data-player="3" name="player3" @php echo empty( old('guest3') ) ? 'required"' : '' @endphp >
                            <option value="">Select a player</option>
                            @foreach($players as $player)

                                <option @php echo old('player3') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

                            @endforeach
                        </select>
                    or <a href="#" class="add-guest" data-player="3">add a guest.</a>
                    <input type="text" @php echo empty( old('guest3') ) ? 'style="display: none"' : '' @endphp name="guest3" data-player="3" value="{{ old('guest3') }}" class="form-control guest-field {{ $errors->has('guest3') ? 'is-invalid' : '' }}" placeholder="Enter your buddy's nickname">
                </div>

                <div class="form-group">
                    <label for="player4">Player 4</label>
                    <select class="form-control friend-selector {{ $errors->has('4players') || $errors->has('player-in-game') ? 'is-invalid' : '' }}" data-player="4" name="player4" @php echo empty( old('guest4') ) ? 'required"' : '' @endphp >
                        <option value="">Select a player</option>
                        @foreach($players as $player)

                            <option @php echo old('player4') == $player->id ? 'selected' : '' @endphp value="{{ $player->id }}">{{ $player->name }}</option>

                        @endforeach
                    </select>
                    or <a href="#" class="add-guest" data-player="4">add a guest.</a>
                    <input type="text" @php echo empty( old('guest4') ) ? 'style="display: none"' : '' @endphp name="guest4" data-player="4" value="{{ old('guest4') }}" class="form-control guest-field {{ $errors->has('guest4') ? 'is-invalid' : '' }}" placeholder="Enter your buddy's nickname">
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
                <div class="form-group">
                    <label for="first-to-serve">First to serve</label>
                    <select class="form-control {{ $errors->has('first-to-serve') ? 'is-invalid' : '' }}" name="first_to_serve">
                        <option value="rand">Random</option>
                        <option value="1">Player 1</option>
                        <option value="2">Player 2</option>
                        <option value="3">Player 3</option>
                        <option value="4">Player 4</option>
                    </select>
                </div>
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
