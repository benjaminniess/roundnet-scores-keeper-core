@extends('layouts.default')

@section('content')

<div class="jumbotron mt-5 text-center">
	<h1 class="display-4">Welcome on Roundnet Scores Keeper!</h1>
		<p class="lead">RSK (Roundnet Scores Keeper) is a <strong>free</strong> tool you can use to log all your Spikeball® games with your friends.</p>
		<hr class="my-4">
		<p>You just need to create a free account, find your friends and start logging your first game.</p>
		<div class="row">
			<div class="col col-md-12">
				<a class="btn btn-primary btn-lg" href="/login" role="button">Log in</a>	
			</div>
		</div>
		<div class="row mt-2">
			<div class="col col-md-12">
				<a href="/register" role="button">Or register now</a>
			</div>
		</div>
</div>
@endsection

