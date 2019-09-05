@extends('layouts.default')


@section('content')
	<h1 class="heading">{{ $user->name }}</h1>
    
    <div class="row my-5">
    	<div class="col-md-4">
    		<div class="card">
			  <div class="card-header">
			    Info
			  </div>
			  <ul class="list-group list-group-flush">
			    <li class="list-group-item">Email: <strong>{{ $user->email }}</strong></li>
			    <li class="list-group-item">Member since: <strong>{{ $user->created_at->toFormattedDateString() }}</strong></li>
			    <li class="list-group-item">Friend since: <strong>{{ (!is_null($user->get_relationship(auth()->id()))) ? $user->get_relationship(auth()->id())->created_at->toFormattedDateString() : '-'  }}</strong></li>
			  </ul>
			</div>	    
    	</div>
    	<div class="col-md-4">
    		<div class="card">
			  <div class="card-header">
			    Stats
			  </div>
			  <ul class="list-group list-group-flush">
			    <li class="list-group-item">Time spent playing: <strong>{{ $user->time_spent_playing() }} hours</strong></li>
			    <li class="list-group-item">Time spent refereing: <strong>{{ $user->time_spent_refereing() }} hours</strong></li>
			    <li class="list-group-item">Total games: <strong>{{ $user->total_games( $status = 'closed' ) }}</strong></li>
			    <li class="list-group-item">Percentage victory:
			    	<strong>
			    	{{ $user->percentage_victory() }}@if ($user->percentage_victory() != 'no game')%@endif
			    	</strong>
			    </li>
			    <li class="list-group-item">Best series of victories: <strong>{{ $user->get_best_series_of_victories() }}</strong></li>
			  </ul>
			</div>	    
    	</div>
    	<div class="col-md-4">
    		<div class="card">
				<div class="card-header">
					badges
				</div>
				<div class="list-group">
			  	@foreach ($badges as $badge)
					<div class="list-group-item list-group-item-action flex-column align-items-start">
						<div class="d-flex w-100 justify-content-between">
							<h5 class="mb-1">{{ $badge->name }}</h5>
							<small class="text-muted">{{ $badge->pivot->created_at->diffForHumans() }}</small>
						</div>
							<p class="mb-1">{{ $badge->description }}</p>
					</div>
					@endforeach
				</div>
			</div>
    	</div>
    </div>

	@if ( $user->total_games() > 0 )
	    <div class="row">
	    	<div class="col-md-6">
	    		<canvas id="points-types-chart"></canvas>
	    	</div>
	    	<div class="col-md-6">
	    		<canvas id="victory-chart"></canvas>
	    	</div>
	    </div>
	    	
	    </div>
	    <script>
			var ctx = document.getElementById('victory-chart').getContext('2d');
			var doughnutChartData = {!! $victory_stats_chart !!}

			var myDoughnutChart = new Chart(ctx, {
			    type: 'doughnut',
			    data: doughnutChartData,
				options: {
					title: {
			        display: true,
			        responsive: true,
			        text: 'Victory versus defeats'		      }
				}
			});


			var ctx = document.getElementById('points-types-chart').getContext('2d');
			var doughnutChartData = {!! $points_types_chart !!}
			console.log(doughnutChartData)
			var myDoughnutChart = new Chart(ctx, {
			    type: 'doughnut',
			    data: doughnutChartData,
				options: {
					title: {
			        display: true,
			        responsive: true,
			        text: 'Points types repartition'		      }
				}
			});
	    </script>
	@endif

@endsection