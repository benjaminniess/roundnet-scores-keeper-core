@extends('layouts.default')

@section('content')

<h1 class="heading mb-4">Game recap</h1>
<div class="row my-2">
    <div class="col-sm-12 my-2">
        <div class="card text-center">
            <div class="card-header">
                {{ $game->get_date() }}
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

<h2 class="heading my-4">Game history</h2>
<table class="table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Type</th>
        <th scope="col">Player</th>
        <th scope="col">Rally duration</th>
        <th scope="col">Score</th>
    </tr>
    </thead>
    <tbody>
    @foreach($game->points as $point)
        <tr>
            <td>{{ $point->action_type->name }}</td>
            <td>{{ $point->player->name }}</td>
            <td>{{ $point->get_duration() }}</td>
            <td>{{ $point->score_team_1 }} - {{ $point->score_team_2 }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="">
    {{-- <a class="btn btn-info btn-lg m-3" href="{{ url('/games') }}/{{ $game->id }}/edit">Edit</a> --}}
    <form class="form" action="/games/{{ $game->id }}" method="POST">

        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-danger my-3">Delete game</button>
    </form>
</div>

@endsection
