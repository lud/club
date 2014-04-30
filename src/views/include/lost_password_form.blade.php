{{ Form::model($defaults, array('url' => URL::route('club.lost_password'))) }}

	@foreach($errors->all() as $error)
		<p class="text-danger bg-danger">{{ $error }}</p>
	@endforeach

	@if( Session::has('success'))
		<p class="text-success bg-success">{{ Session::get('success') }}</p>
	@endif

	<div class="form-group">
		{{ Form::label('email', Lang::get('validation.attributes.email')) }}
		{{ Form::text('email', null, array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::submit(Lang::get('club::club.buttons.lost_password'), array('class' => 'btn btn-default')) }}
	</div>

{{ Form::close() }}
