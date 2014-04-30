{{ Form::model(Input::old() , array('url' => URL::route('club.signup'))) }}

	<div class="form-group">
		{{ Form::label('email', Lang::get('validation.attributes.email')) }}
		{{ Form::text('email', null, array('class' => 'form-control')) }}
		@if($errors->has('email'))
			<p class="text-danger bg-danger">{{ $errors->first('email') }}</p>
		@endif
	</div>

	<div class="form-group">
		{{ Form::label('password', Lang::get('validation.attributes.password')) }}
		{{	Form::password('password', array('class' => 'form-control')) }}
		@if($errors->has('password'))
			<p class="text-danger bg-danger">{{ $errors->first('password') }}</p>
		@endif
	</div>

	<div class="form-group">
		{{ Form::label('password_confirmation', Lang::get('validation.attributes.password_confirmation')) }}
		{{ Form::password('password_confirmation', array('class' => 'form-control')) }}
		@if($errors->has('password_confirmation'))
			<p class="text-danger bg-danger">{{ $errors->first('password_confirmation') }}</p>
		@endif
	</div>

	<div class="form-group">
		{{ Form::submit(Lang::get('club::club.buttons.signup'), array('class' => 'btn btn-default')) }}
	</div>

	<div class="form-group">
		<a href="{{ URL::route('club.login') }}">{{ Lang::get('club::club.labels.already_registered') }}</a>
	</div>

{{ Form::close() }}
