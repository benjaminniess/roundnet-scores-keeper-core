@extends('layouts.default')

@section('content')

    <h1 class="heading mb-4">Games list</h1>

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
                                @if ($game->status == 'pending')
                                    {{ 'badge-dark' }}
                                @endif
                                @if ($game->status == 'live')
                                    {{ 'badge-success' }}
                                @endif
                                @if ($game->status == 'closed')
                                    {{ 'badge-danger' }}
                                @endif
                            ">{{ $game->status }}</span>
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
                                        @else
                                            <a href="{{ url('/games') }}/{{ $game->id }}" class="btn btn-info"> View </a>
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
