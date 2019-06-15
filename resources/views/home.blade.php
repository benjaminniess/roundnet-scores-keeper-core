@extends('layouts.default')

@section('content')

    <h2>Home</h2>

    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

    </div>
@endsection

