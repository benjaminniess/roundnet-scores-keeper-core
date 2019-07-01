@extends('layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="heading">Search for friends</h2>
                <form method="POST" action="/friends/search/">
                    @csrf
                    <input type="text" placeholder="nickname" name="nickname" />
                    <input type="submit" value="Search" />
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h2 class="heading">Your active friends</h2>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table align=center class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th><strong>Name</strong></th>
                            <th><strong>Email</strong></th>
                        </tr>
                    </thead>

                    @foreach ($active_auth_user_friends as $active_auth_user_friend)
                        <tr>
                            <td>{{ $active_auth_user_friend->name }}</td>
                            <td>{{ $active_auth_user_friend->email }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @if ( ! $pending_auth_user_friends->isEmpty() )
            <div class="row">
                <div class="col">
                    <h2 class="heading">Your pending friends request</h2>

                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th colspan="2">Actions</th>
                            </tr>
                        </thead>
                        @foreach ($pending_auth_user_friends as $pending_auth_user_friend)
                            <tr>
                                <td>{{ $pending_auth_user_friend->name }}</td>
                                <td>{{ $pending_auth_user_friend->email }}</td>
                                <td>
                                    <form class="form" action="/friends/{{ $pending_auth_user_friend->id }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="friend_id" value="{{ $pending_auth_user_friend->id }}">
                                        <button name="status" class="btn btn-info btn-lg m-3" type="submit" value="active">Accept</button>
                                </td>
                                <td>
                                    <button name="status" class="btn btn-danger btn-lg m-3" type="submit" value="declined">Deny</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        @endif
    </div>


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
