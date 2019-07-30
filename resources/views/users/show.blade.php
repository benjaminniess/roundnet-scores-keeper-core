@extends('layouts.default')


@section('content')

    <h2>Stats</h2>
    
    <div class="row">
    	<div class="col-md-12">
    		<ul>
    			<li><p>Time spent playing : {{ $user->time_spent_playing }}</p></li>
    			<li><p>Time spent refereing : {{ $user->time_spent_refereing }}</p></li>
    			<li><p>Total games : {{ $user->total_games }}</p></li>
    		</ul>		    
    	</div>
    </div>

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

@endsection