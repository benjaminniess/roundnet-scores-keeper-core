@extends('layouts.default')

@section('content')
<div class="container">

            <h1 class="heading">Games list</h1>

    @if (!$games->isEmpty())
    @foreach ($games->chunk(3) as $gamesChunked)
        <div class="row my-3">
            @foreach($gamesChunked as $game)
                <div class="col-sm-4">
                    <div class="card text-center">
                        <div class="card-header">
                            <span class="badge 
                                @if ($game->status == 'pending')
                                    {{ 'badge-dark' }}
                                @endif
                                @if ($game->status == 'live')
                                    {{ 'badge-success' }}
                                @endif
                            ">{{ $game->status }}</span>
                        </div>

                        <div class="card-body">
                            <h2 class="card-title">Players</h2>
                                <ul class="list-group list-group-flush">
                                    @foreach( $game->players as $player)
                                    <li class="list-group-item">{{ $player->name }}</li>
                                    @endforeach
                                </ul>
                                <div class="row mt-3">
                                    <div class="col-md-6 offset-md-3">
                                        @if ( $game->status == 'pending' )
                                            <a href="{{ url('/games/' . $game->id . '/start') }}" class="btn btn-success"> Start </a>
                                        @else
                                            <a href="{{ url('/games') }}/{{ $game->id }}" class="btn btn-info"> View </a>
                                        @endif
                                            <a href="{{ url('/games') }}/{{ $game->id }}/edit" class="btn btn-primary"> Edit </a>
                                    </div>
                                </div>
                        </div>

                        <div class="card-footer text-muted"> {{ $game->get_date() }} </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    @else {{ 'there is no game' }}
    @endif
    
    <div class="row my-3">
        <div class="col-md-6 offset-md-3">
            <a href="{{ url('/games/create') }}" class="btn-lg btn-block btn-primary"> Add a new game </a>
        </div>
    </div>

</div>
@endsection
