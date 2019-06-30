@extends('layouts.default')

@section('content')

    <div class="card-body">
        <p>You don't have any live game yet.</p>

        <div class="">
            <a class="btn btn-info btn-lg m-3" href="/games/create">Start a new game</a>
            <a class="btn btn-info btn-lg m-3" href="/friends">Search for friends</a>
        </div>
    </div>
@endsection

