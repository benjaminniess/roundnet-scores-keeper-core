@extends('layouts.default')

@section('content')
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
                        @if ( $game->status == 'pending' )
                            <a href="{{ url('/games/' . $game->id . '/start') }}/{{ $game->id }}">
                                Start
                            </a>
                        @else
                            <a href="{{ url('/games') }}/{{ $game->id }}">
                                View
                            </a>
                        @endif
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
