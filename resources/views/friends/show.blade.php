@extends('layouts.default')

@section('content')
    <h2>Search for friends</h2>
    <form method="POST" action="/friends/search/">
        @csrf
        <input type="text" placeholder="nickname" name="nickname" />
        <input type="submit" value="Search" />
    </form>
    <h2>Your active friends</h2>

    <table align=center border=1>
        <tr>
            <th>Name</th>
            <th>Email</th>
        </tr>
        @foreach ($active_auth_user_friends as $active_auth_user_friend)
            <tr>
                <td>{{ $active_auth_user_friend->name }}</td>
                <td>{{ $active_auth_user_friend->email }}</td>
            </tr>
        @endforeach
    </table>

    <h2>Your pending friends request</h2>

    <table align=center border=1>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th colspan="2">Actions</th>
        </tr>
        @foreach ($pending_auth_user_friends as $pending_auth_user_friend)
            <tr>
                <td>{{ $pending_auth_user_friend->name }}</td>
                <td>{{ $pending_auth_user_friend->email }}</td>
                <td>
                    <form class="form" action="/friends/{{ $pending_auth_user_friend->id }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="friend_id" value="{{ $pending_auth_user_friend->id }}">
                        <button name="status" type="submit" value="active">Accept</button>
                </td>
                <td>
                        <button name="status" type="submit" value="declined">Deny</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

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
