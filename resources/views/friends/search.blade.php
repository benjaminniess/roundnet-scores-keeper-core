@extends('layouts.default')

@section('content')
    <h2>Search for friends</h2>


    <ul>
        @foreach( $results as $result)
            <li>{{ $result->name }} - <a href="/friends/request/{{ $result->id }}">Request friendship</a></li>
        @endforeach
    </ul>
@endsection
