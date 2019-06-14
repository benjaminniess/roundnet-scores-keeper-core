@extends('layouts.default');

@section('content')
<h2>Create a new game</h2>

<form class="form" action="/games" method="POST">


    {{ csrf_field() }}
    <h3>Team 1</h3>
        <label for="player1">Player 1</label>
        <select class="select" name="player1">
            <option value="1">Charley</option>
            <option value="2">Axel</option>
            <option value="3">Bniess</option>
            <option value="4">Sleps</option>
        </select>

        <label for="player2">Player 2</label>
        <select class="select" name="player2">
            <option value="1">Charley</option>
            <option value="2">Axel</option>
            <option value="3">Bniess</option>
            <option value="4">Sleps</option>
        </select>

        <h3>Team 2</h3>
            <label for="player3">Player 3</label>
            <select class="select" name="player3">
                <option value="1">Charley</option>
                <option value="2">Axel</option>
                <option value="3">Bniess</option>
                <option value="4">Sleps</option>
            </select>

        <label for="player4">Player 4</label>
        <select class="select" name="player4">
            <option value="1">Charley</option>
            <option value="2">Axel</option>
            <option value="3">Bniess</option>
            <option value="4">Sleps</option>
        </select>

        <button type="submit"> envoyer</button>
</form>
@endsection
