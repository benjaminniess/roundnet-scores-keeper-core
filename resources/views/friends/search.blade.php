@extends('layouts.default')

@section('content')
    <h2 class="heading">Search results</h2>

    <ul class="list-group">
        @foreach( $results as $result )
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $result->name }}
                @if ( ! $result->is_friend( $current_user->id ) )
                    <a href="/friends/request/{{ $result->id }}">Request friendship</a>
                @else
                <span class="badge badge-success"> Friend</span>
                @endif
            </li>
        @endforeach
    </ul>
@endsection
