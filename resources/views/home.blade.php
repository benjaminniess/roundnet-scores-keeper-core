@extends('layouts.default')

@section('content')
	<h2 class="heading">Your lastests games</h2>
	<div class="row my-3">
		@if (!$games->isEmpty())
			@foreach ($games as $game)
				@component('components.game-card')
					@slot('col_settings','col-sm-4')
		            @slot('header')
		                <span class="badge
		                        @if ( $game->status === 'pending' )
		                            {{ 'badge-dark' }}
		                        @endif
		                        @if ( $game->status === 'live' )
		                            {{ 'badge-success' }}
		                        @endif
		                        @if ( $game->status === 'closed' && $game->winning_game === 'Lost' && !$game->is_referee )
		                            {{ 'badge-danger' }}
		                        @endif
		                        @if ( $game->status === 'closed' && $game->winning_game === 'Won' )
		                            {{ 'badge-success' }}
		                        @endif
		                        @if ( $game->is_referee )
		                            {{ 'badge-primary' }}
		                        @endif
		                    ">
		                    @if ($game->status !== 'closed')
		                        {{ $game->status }}
		                    @elseif ( $game->is_referee )
		                        {{ 'Referee' }}
		                    @else
		                        {{ $game->winning_game }}
		                    @endif
		                </span>
		            @endslot
		            @slot('body')
		                <div class="row">
		                    <div class="col-sm-6 col-6">
		                        <h2 class="card-title">Team A</h2>
		                        <ul class="list-group list-group-flush">
		                            @foreach($game->players->chunk(2)[0] as $player)
		                            <li class="list-group-item">{{ $player->name }}</li>
		                            @endforeach
		                        </ul>
		                        <h2 class="heading">{{ $game->score_team_1 }}</h2>
		                    </div>
		                    <div class="col-sm-6 col-6">
		                        <h2 class="card-title">Team B</h2>
		                            <ul class="list-group list-group-flush">
		                                @foreach($game->players->chunk(2)[1] as $player)
		                                <li class="list-group-item">{{ $player->name }}</li>
		                                @endforeach
		                            </ul>
		                            <h2 class="heading">{{ $game->score_team_2 }}</h2>
		                    </div>
		                </div>
		                @endslot
		                @slot('footer')
		                    {{ $game->formated_end_date }}
		                @endslot
		        @endcomponent
			@endforeach
		@else
			<div class=" alert alert-info">You never played? start now!</div>
		@endif
	</div>
	<div class="row my-3 justify-content-md-center">
	    <div class="">
	        <a class="btn btn-primary" href="/games/create">Start a new game</a>
	        <a class="btn btn-primary" href="/games">See all games</a>
	    </div>
	</div>

@endsection

