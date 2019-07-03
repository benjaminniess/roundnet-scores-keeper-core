@extends('layouts.default')

@section('content')

        <table class="table">
            <thead>
            <tr>
                <th scope="col" class="text-right">Team 1</th>
                <th scope="col" class="text-center">Score</th>
                <th scope="col">Team 2</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-right">{{ $players[1]->name }}
                    <hr>{{ $players[2]->name }}
                </td>
                <td class="align-middle text-center scores">{{ $game->score_team_1 }} - {{ $game->score_team_2 }}</td>
                <td>{{ $players[3]->name }}
                    <hr>{{ $players[4]->name }}
                </td>
            </tr>
            </tbody>
        </table>
        <div class="row row-separator">
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
