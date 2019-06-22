@extends('layouts.default')

@section('content')
    <h2>Your active friends</h2>

    <table align=center border=1>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
        @foreach ($active_auth_user_friends as $active_auth_user_friend)
            <tr>
                <td>{{ $active_auth_user_friend->name }}</td>
                <td>{{ $active_auth_user_friend->email }}</td>
                <td>{{ $active_auth_user_friend->status }}</td>
            </tr>
        @endforeach
    </table>

    <h2>Your pending friends request</h2>

    <table align=center border=1>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
        @foreach ($pending_auth_user_friends as $pending_auth_user_friend)
            <tr>
                <td>{{ $pending_auth_user_friend->name }}</td>
                <td>{{ $pending_auth_user_friend->email }}</td>
                <td>{{ $pending_auth_user_friend->status }}</td>
            </tr>
        @endforeach
    </table>

    <h2>Your blocked friends</h2>

    <table align=center border=1>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
        @foreach ($blocked_auth_user_friends as $blocked_auth_user_friend)
            <tr>
                <td>{{ $blocked_auth_user_friend->name }}</td>
                <td>{{ $blocked_auth_user_friend->email }}</td>
                <td>{{ $blocked_auth_user_friend->status }}</td>
            </tr>
        @endforeach
    </table>
@endsection
