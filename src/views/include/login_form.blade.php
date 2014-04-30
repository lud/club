{{ Form::model(array('remember'=>'1'), array('url' => URL::route('club.login'))) }}

	@foreach($errors->all() as $error)
		<p class="text-danger bg-danger">{{ $error }}</p>
	@endforeach

	{{-- This is for password recovery which redirects to login --}}
	@if( Session::has('success'))
		<p class="text-success bg-success">{{ Session::get('success') }}</p>
	@endif

	<div class="form-group">
		{{ Form::label('email', Lang::get('validation.attributes.email')) }}
		{{ Form::text('email', null, array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('password', Lang::get('validation.attributes.password')) }}
		{{ Form::password('password', array('class' => 'form-control')) }}
		<p>
			<a href="{{ URL::route('club.lost_password') }}">{{ Lang::get('club::club.labels.lost_password') }}</a>
		</p>
	</div>

	<div class="checkbox">
		<label>
			{{ Form::checkbox('remember', '1') }}
			{{ Lang::get('club::club.labels.remember_me') }}
		</label>
	</div>

	<div class="form-group">
		{{ Form::submit(Lang::get('club::club.buttons.login'), array('class' => 'btn btn-default')) }}
	</div>

	<div class="form-group">
		<a href="{{ URL::route('club.signup') }}">{{ Lang::get('club::club.labels.goto_signup') }}</a>
	</div>

{{ Form::close() }}
