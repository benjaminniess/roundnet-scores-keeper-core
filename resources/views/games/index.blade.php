@extends('layouts.default');

@section('content')
    <h2>Games list</h2>


    @if (!$games->isEmpty())
        <table align=center border=1>
            <th>Game id</th>
            <th>Player 1</th>
            <th>Player 2</th>
            <th>Player 3</th>
            <th>Player 4</th>
            @foreach($games as $game)
                <tr>
                    <td>
                        {{ $game->created_at->format('d/m/Y') }}
                    </td>
                    <td>
                        {{ $game->players['player1']->name }}
                    </td>
                    <td>
                        {{ $game->players['player2']->name }}
                    </td>
                    <td>
                        {{ $game->players['player3']->name }}
                    </td>
                    <td>
                        {{ $game->players['player4']->name }}
                    </td>
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
