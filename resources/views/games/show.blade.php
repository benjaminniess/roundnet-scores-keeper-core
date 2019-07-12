@extends('layouts.default')

@section('content')

<h1 class="heading mb-4">Game recap</h1>

<h2 class="heading my-4">Game history</h2>
<p>Game duration : {{ gmdate('H:i:s',$game->duration()) }}</p>
<p>number of rallies : {{ $game->count_points() }}</p>
<p>rallies average duration : {{ $game->points_average_duration() }}</p>

<div class="row my-2">
    <div class="col-sm-12 my-2">
        <div class="card text-center">
            <div class="card-header">
                {{ $game->get_date() }}
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-6">
                        @if ($game->get_winning_team() === 'team 1')
                            <div class="badge badge-success">Winner</div>
                        @else
                            <div class="badge badge-danger">Loser</div>
                        @endif
                        <h2 class="card-title">Team A</h2>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">{{ $players[1]->name }}</li>
                            <li class="list-group-item">{{ $players[2]->name }}</li>
                        </ul>
                        <h2 class="heading">{{ $game->score_team_1 }}</h2>
                    </div>
                    <div class="col-sm-6 col-6">
                        @if ($game->get_winning_team() === 'team 2')
                            <div class="badge badge-success">Winner</div>
                        @else
                            <div class="badge badge-danger">Loser</div>
                        @endif
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


<canvas id="canvas" width="400" height="400"></canvas>
<script>
    var ctx = document.getElementById('canvas').getContext('2d');
    var lineChartData = {!! $history_chart !!}

    window.myLine = Chart.Line(ctx, {
        data: lineChartData,
        options: {
            responsive: true,
            hoverMode: 'index',
            stacked: false,
            title: {
                display: true,
                text: 'Chart.js Line Chart - Multi Axis'
            },
            scales: {
                yAxes: [{
                    type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    position: 'left',
                    id: 'y-axis-1',
                }, {
                    type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    position: 'right',
                    id: 'y-axis-2',

                    // grid line settings
                    gridLines: {
                        drawOnChartArea: false, // only want the grid lines for one axis to show up
                    },
                }],
            }
        }
    });
</script>


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
            <td>
                {{ $point->action_type->name }}
                <span class="badge
                @if ($point->action_type->action_type === 'positive')
                    {{ 'badge-success' }}
                @endif
                @if ($point->action_type->action_type === 'negative')
                    {{ 'badge-danger' }}
                @endif
                @if ($point->action_type->action_type === 'neutral')
                    {{ 'badge-warning' }}
                @endif
                ">{{ $point->action_type->action_type }}</span>
            </td>
            <td>{{ $point->player->name }}</td>
            <td>{{ $point->get_duration() }}</td>
            <td>{{ $point->score_team_1 }} - {{ $point->score_team_2 }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="">
    {{-- <a class="btn btn-info btn-lg m-3" href="{{ url('/games') }}/{{ $game->id }}/edit">Edit</a> --}}
    <form onsubmit="return confirm('Do you really want to delete this game?');" class="form" action="/games/{{ $game->id }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger my-3">Delete game</button>
    </form>
</div>

@endsection
