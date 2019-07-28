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
                                    <input type="text" placeholder="Username" name="nickname" required class="form-control"/>
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
        @include('components.errors')

        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

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
                                <li class="list-group-item py-3">{{ $active_auth_user_friend['name'] . ' (' . $active_auth_user_friend['email'] . ')' }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        {{ $active_auth_user_friends->links() }}
        @else
            <div class="alert alert-info">
                You don't have any friend yet :(
            </div>
        @endif


        @if ( ! $guest_auth_user_friends->isEmpty() )
            <h2 id="guest-friends" class="heading mt-5">Your guests friends</h2>
            <p>Now that you have gest friends, you can send them an invitation to create their own account and keep their games history with you.</p>

            <div class="row my-3">
                <div class="col-sm-12">
                    <ul class="list-group list-group-flush">
                        @foreach ($guest_auth_user_friends as $guest_auth_user_friend)
                            <li class="list-group-item py-3">
                                <div class="row">
                                    <div class="col-8">
                                      {{ $guest_auth_user_friend->name }}  
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-6 text-right">
                                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#friend-invitation-modal-{{ $guest_auth_user_friend->id }}">
                                              Invite
                                            </button>  
                                            </div>
                                            <div class="col-md-6 text-left">
                                              <form onsubmit="return confirm('Do you really want to delete this guest friend?');" class="form" action="/friends/{{ $guest_auth_user_friend->id }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            {{-- Send guest invitation modal --}}
                            @component('components.modal')
                                @slot('modal_id', 'friend-invitation-modal-'.$guest_auth_user_friend->id)
                                @slot('title', 'Invite '.$guest_auth_user_friend->name.' to create an account')
                                @slot('modal_content')
                                    <form action="/friends/send-invitation#guest-friends" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="guest-email">{{ $guest_auth_user_friend->name . ' \'s email'}}</label>
                                            <input type="text" class="form-control {{ $errors->has('guest_email_' . $guest_auth_user_friend->id ) ? 'is-invalid' : '' }}" name="guest_email" placeholder="Your friend's email">
                                            <input type="hidden" name="guest_id" value="{{ $guest_auth_user_friend->id }}">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Send invitation </button>
                                    </form>
                                @endslot
                            @endcomponent
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
