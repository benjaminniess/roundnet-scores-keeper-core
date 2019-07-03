@extends('layouts.default')

@section('content')



<div class="row my-2">
    <div class="col-sm-12 my-2">
        <div class="card text-center">
            <div class="card-header">
                {{ $game->start_date }}
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-6">
                        <h2 class="card-title">Team A</h2>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">{{ $players[1]->name }}</li>
                            <li class="list-group-item">{{ $players[2]->name }}</li>
                        </ul>
                        <h2 class="heading">{{ $game->score_team_1 }}</h2>
                    </div>
                    <div class="col-sm-6 col-6">
                        <h2 class="card-title">Team B</h2>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">{{ $players[3]->name }}</li>
                                <li class="list-group-item">{{ $players[4]->name }}</li>
                            </ul>
                            <h2 class="heading">{{ $game->score_team_2 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

            <h3>Game history</h3>
        </div>
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th>Type</th>
                <th>Player</th>
                <th>Rally duration</th>
                <th>Score</th>
            </tr>
            </thead>
            <tbody>
            @foreach($game->points as $point)
                <tr>
                    <td>{{ $point->action_type_id }}</td>
                    <td>{{ $point->player_id }}</td>
                    <td>{{ $point->get_duration() }}</td>
                    <td>{{ $point->score_team_1 }} - {{ $point->score_team_2 }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="">
            <a class="btn btn-info btn-lg m-3" href="{{ url('/games') }}/{{ $game->id }}/edit">Edit</a>
            <form class="form" action="/games/{{ $game->id }}" method="POST">

                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger btn-lg m-3">Delete</button>
            </form>

        </div>
@endsection
