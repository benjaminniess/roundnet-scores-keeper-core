@extends('layouts.default')

@section('content')

    <div class="card-body">
        <div class=" alert alert-info">You don't have any live game yet.</div>

        <div class="">
            <a class="btn btn-primary" href="/games/create">Start a new game</a>
            <a class="btn btn-primary" href="/friends">Search for friends</a>
        </div>
    </div>
@endsection

