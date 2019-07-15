@extends('layouts.default')
@section('content')
    <h2 class="heading mb-4">Account</h2>
    @if(session()->has('info-message-success'))
        <div class="alert alert-success">
            {{ session()->get('info-message-success') }}
        </div>
    @endif
    @if(session()->has('password-message-success'))
        <div class="alert alert-success">
            {{ session()->get('password-message-success') }}
        </div>
    @endif
    @include('components.errors')
    <div class="row">
    	<div class="col-sm-6">
    		<div class="card">
    			<div class="card-header text-center">
    				Your information
    			</div>
    			<div class="card-body">
    				<form action="/user/{{ $user->id }}" method="POST">
					@csrf
					@method('PATCH')
					<div class="form-group">
						<label for='name'>Name</label>
						<input type="text" name="name" class="form-control" value='{{ $user->name }}'>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-success">Update info</button>
					</div>
				</form>	
				<form onsubmit="return confirm('Are you sure? All your data will be lost forever...');" class="form" action="/user/{{ $user->id }}" method="POST">
					@csrf
					@method('DELETE')
					<button type="submit" class="btn btn-danger my-3">Delete my account</button>
				</form>	
    			</div>
    		</div>
    	</div>
		<div class="col-sm-6">
		    		<div class="card">
		    			<div class="card-header text-center">
		    				Your password
		    			</div>
		    			<div class="card-body">
		    				<form action="/user/edit-password/{{ $user->id }}" method="POST">
							@csrf
							@method('PATCH')
							<div class="form-group">
								<label for='name'>Old password</label>
								<input type="password" name="old_password" class="form-control">
							</div>
							<div class="form-group">
								<label for='name'>New password</label>
								<input type="password" name="new_password" class="form-control">
							</div>
							<div class="form-group">
								<label for='name'>New password confirmation</label>
								<input type="password" name="new_password_confirmation" class="form-control">
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-success">Update info</button>
							</div>
						</form>		
		    			</div>
		    		</div>
		    	</div>
    </div>
@endsection