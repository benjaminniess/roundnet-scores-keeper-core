@extends('layouts.default')

@section('content')
    <h2>Games list</h2>
    @if (!$games->isEmpty())
        <table align=center border=1>
            <th>Status</th>
            <th>Date</th>
            <th>Player 1</th>
            <th>Player 2</th>
            <th>Player 3</th>
            <th>Player 4</th>
            <th colspan="2">Actions</th>
            @foreach($games as $game)
                <tr>
                    <td>{{ $game->status }}</td>
                    <td>
                        {{ $game->get_date() }}
                    </td>
                    @foreach( $game->players as $player)
                        <td>
                            {{ $player->name }}
                        </td>
                    @endforeach
                    <td>
                        <a href="{{ url('/games') }}/{{ $game->id }}">
                            View
                        </a>
                    </td>
                    <td>
                        <a href="{{ url('/games') }}/{{ $game->id }}/edit">
                            Edit
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    @else {{ 'there is no game' }}
    @endif
        <a href="{{ url('/games/create') }}"> Add a new game </a>

@endsection
