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
                    {{ $game->id }}
                </td>
                <td>
                    {{ $game->player1 }}
                </td>
                <td>
                    {{ $game->player2 }}
                </td>
                <td>
                    {{ $game->player3 }}
                </td>
                <td>
                    {{ $game->player4 }}
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
