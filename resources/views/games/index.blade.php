@extends('layouts.default')

@section('content')

    <h1 class="heading mb-4">Games list</h1>
    @include('components.errors')

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    @if (!$games->isEmpty())
    @foreach ($games->chunk(2) as $gamesRow)
        <div class="row my-2">
            @foreach($gamesRow as $game)
                <div class="col-sm-6 my-2">
                    <div class="card text-center">
                        <div class="card-header">
                            <span class="badge
                                @if ( $game->status === 'pending' )
                                    {{ 'badge-dark' }}
                                @endif
                                @if ( $game->status === 'live' )
                                    {{ 'badge-success' }}
                                @endif
                                @if ( $game->status === 'closed' && $game->winning_game === 'Lost' && !$game->is_referee )
                                    {{ 'badge-danger' }}
                                @endif
                                @if ( $game->status === 'closed' && $game->winning_game === 'Won' )
                                    {{ 'badge-success' }}
                                @endif
                                @if ( $game->is_referee )
                                    {{ 'badge-primary' }}
                                @endif
                            ">
                            @if ($game->status !== 'closed')
                                {{ $game->status }}
                            @elseif ( $game->is_referee )
                                {{ 'Referee' }}
                            @else
                                {{ $game->winning_game }}
                            @endif
                        </span>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <h2 class="card-title">Team A</h2>
                                    <ul class="list-group list-group-flush">
                                        @foreach($game->players->chunk(2)[0] as $player)
                                        <li class="list-group-item">{{ $player->name }}</li>
                                        @endforeach
                                    </ul>
                                    <h2 class="heading">{{ $game->score_team_1 }}</h2>
                                </div>
                                <div class="col-sm-6 col-6">
                                    <h2 class="card-title">Team B</h2>
                                        <ul class="list-group list-group-flush">
                                            @foreach($game->players->chunk(2)[1] as $player)
                                            <li class="list-group-item">{{ $player->name }}</li>
                                            @endforeach
                                        </ul>
                                        <h2 class="heading">{{ $game->score_team_2 }}</h2>
                                </div>
                            </div>
                                <div class="row mt-3">
                                    <div class="col-md-6 offset-md-3">
                                        @if ( $game->status == 'pending' )
                                            <a href="{{ url('/games/' . $game->id . '/start') }}" class="btn btn-success"> Start </a>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#set-score-modal-{{ $game->id }}">
                                              Set score
                                            </button>
                                        @else
                                            <a href="{{ url('/games') }}/{{ $game->id }}" class="btn btn-primary"> View </a>
                                        @endif

                                        <form onsubmit="return confirm('Do you really want to delete this game?');" class="form" action="/games/{{ $game->id }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger my-3">Delete game</button>
                                        </form>

                                        <!--<a href="{{ url('/games') }}/{{ $game->id }}/edit" class="btn btn-primary"> Edit </a>-->
                                    </div>
                                </div>
                        </div>

                        <div class="card-footer text-muted"> {{ $game->get_date() }} </div>

                    </div>
                </div>

    {{-- Set score modal --}}
    @component('components.modal')
        @slot('modal_id', 'set-score-modal-'.$game->id)
        @slot('title', 'Set score')

        @slot('modal_content')
            <form action="/games/set-score/{{ $game->id }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group mt-3">
                    <label for="score_team_1">Score team A ({{ $game->players[0]->name . ' - ' . $game->players[1]->name}})</label>
                    <input type="number" name="score_team_1" id="score_team_a" class="form-control {{ $errors->has('set_scores') ? 'is-invalid' : '' }}" placeholder="Leave empty if you want to track points">
                </div>
                <div class="form-group">
                    <label for="score_team_2">Score team B ({{ $game->players[2]->name . ' - ' . $game->players[3]->name}})</label>
                    <input type="number" name="score_team_2" id="score_team_b" class="form-control {{ $errors->has('set_scores') ? 'is-invalid' : '' }}" placeholder="Leave empty if you want to track points">
                </div>
                <button type="submit" class="btn btn-primary">Set score</button>
            </form>
        @endslot
    @endcomponent
            @endforeach
        </div>
    @endforeach
    {{ $games->links() }}

    @else
    <div class="alert alert-info"> There is no game</div>
    @endif

    <div class="row my-5 justify-content-md-center">
        <div class="">
            <a href="{{ url('/games/create') }}" class="btn-lg btn-block btn-primary"> Add a new game </a>
        </div>
    </div>

@endsection
