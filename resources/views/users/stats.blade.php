@extends('layouts.default')


@section('content')

    <h2>Stats</h2>
    
    <div class="row">
    	<div class="col-md-6">
    		<ul>
    			<li><p>Number of positive points : {{ $positive_points }}</p></li>
    			<li><p>Number of negative points : {{ $negative_points }}</p></li>
    			<li><p>Number of neutral points : {{ $neutral_points }}</p></li>
    			<li><p>Time spent playing : {{ $time_spent_playing }}</p></li>
    			<li><p>Time spent refereing : {{ $time_spent_refereing }}</p></li>
    		</ul>		    
    	</div>
    	<div class="col-md-6">
    		<canvas id="victory-chart" width="400" height="400"></canvas>	
    	</div>
    </div>
    	
    </div>
    <script>
		var ctx = document.getElementById('victory-chart').getContext('2d');
		var doughnutChartData = {!! $victory_stats_chart !!}
		console.log(doughnutChartData)
		var myDoughnutChart = new Chart(ctx, {
		    type: 'doughnut',
		    data: doughnutChartData,
			options: {
				title: {
		        display: true,
		        responsive: true,
		        text: 'Victory versus defeats'
		      }
			}
		});
    </script>

@endsection