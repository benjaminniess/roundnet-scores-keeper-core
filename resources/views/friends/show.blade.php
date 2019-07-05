@extends('layouts.default')

@section('content')

        <div class="jumbotron jumbotron-fluid mt-5">
            <div class="container">
                <h1 class="display-4 text-center">Search for friends</h1>
                <div class="row justify-content-md-center">
                    <div class="col-md-6">
                        <form method="POST" action="/friends/search">
                            @csrf
                            <div class="row justify-content-md-center mt-3">
                                <div class="col-md-12">
                                    <input type="text" placeholder="Username" name="nickname" class="form-control"/>
                                </div>
                            </div>
                            <div class="row justify-content-md-center my-3">
                                <div class="col-md-3">
                                    <input type="submit" value="Search" class="btn btn-primary mb-2">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @if ( ! $pending_auth_user_friends->isEmpty() )
        <h2 class="heading mt-5">Your friend requests</h2>
            @foreach ($pending_auth_user_friends as $pending_auth_user_friend)
            <div class="row my-3">
                <div class="col-sm-12">
                    <div class="card py-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">{{ $pending_auth_user_friend->name . ' (' . $pending_auth_user_friend->email . ')'}}</h5>
                            <form class="form" action="/friends/{{ $pending_auth_user_friend->id }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="friend_id" value="{{ $pending_auth_user_friend->id }}">
                                <button name="status" class="btn btn-success mx-2" type="submit" value="active">Accept</button>
                                <button name="status" class="btn btn-danger mx-2" type="submit" value="declined">Deny</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
    @endif

        @if ( ! $active_auth_user_friends->isEmpty() )
        <h2 class="heading mt-5">Your active friends</h2>
        <div class="row my-3">
            <div class="col-sm-12">
                <ul class="list-group list-group-flush">
                    @foreach ($active_auth_user_friends as $active_auth_user_friend)
                                <li class="list-group-item py-3">{{ $active_auth_user_friend->name . ' (' . $active_auth_user_friend->email . ')' }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @else
            <div class="alert alert-info">
                You don't have any friend yet :(
            </div>
        @endif


        @if ( ! $guest_auth_user_friends->isEmpty() )
            <h2 id="guest-friends" class="heading mt-5">Your guests friends</h2>
            <p>Now that you have gest friends, you can send them an invitation to create their own account and keep their games history with you.</p>

            @if ($errors->any())

                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger my-3" role="alert">{{ $error }}</div>
                @endforeach

            @endif

            <div class="row my-3">
                <div class="col-sm-12">
                    <ul class="list-group list-group-flush">
                        @foreach ($guest_auth_user_friends as $guest_auth_user_friend)
                            <li class="list-group-item py-3">{{ $guest_auth_user_friend->name }}
                            <form class="form-inline" action="/friends/send-invitation#guest-friends" method="post">
                                @csrf
                                <input type="text" class="form-control {{ $errors->has('guest_email') ? 'is-invalid' : '' }}" name="guest_email" placeholder="Your friend's email">
                                <input type="hidden" name="guest_id" value="{{ $guest_auth_user_friend->id }}">
                                <input type="submit" class="btn btn-primary" value="Send invitation">
                            </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif


    <!-- Not necessary for MVP
    <h2>Your blocked friends</h2>

    <table align=center border=1>
        <tr>
            <th>Name</th>
            <th>Email</th>
        </tr>
        @foreach ($blocked_auth_user_friends as $blocked_auth_user_friend)
            <tr>
                <td>{{ $blocked_auth_user_friend->name }}</td>
                <td>{{ $blocked_auth_user_friend->email }}</td>
            </tr>
        @endforeach
    </table>
    -->
@endsection
