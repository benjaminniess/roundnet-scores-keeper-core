@extends('layouts.default')

@section('content')
    <h2>Search for friends</h2>

    <ul>
        @foreach( $results as $result)

            <li>{{ $result->name }}
                @if ( ! $result->is_friend( $current_user->id ) )
                    <a href="/friends/request/{{ $result->id }}">Request friendship</a>
                @endif
            </li>
        @endforeach
    </ul>
@endsection
