@layout('master')

@section('content')

<div class="container-fluid">
	<div class="form-register">
	    <h2>Sign Up</h2>
	    @render('errors')

	    {{ Form::open('account/register', 'POST') }}
	    {{ Form::token() }}

	    @if (!$errors->messages)
	    	<div class="alert alert-warning"><strong>Note:</strong> All fields are required</div>
	    @endif

	    <div class="control-group {{ $errors->has('email') ? 'error' : '' }}">
		    {{ Form::label('email', 'Email: ', null, false) }}
		    {{ Form::text('email', Input::old('email')) }}
		</div>

		<div class="control-group {{ $errors->has('username') ? 'error' : '' }}">
		    {{ Form::label('username', 'Username: ', null, false) }}
		    {{ Form::text('username', Input::old('username')) }}
		</div>

		<div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
		    {{ Form::label('password', 'Password: ', null, false) }}
		    {{ Form::password('password') }}
	    </div>

	    <div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
		    {{ Form::label('password_confirmation', 'Confirm Password: ', null, false) }}
		    {{ Form::password('password_confirmation') }}
		</div>

	    <div class="control-group {{ $errors->has('first_name') ? 'error' : '' }}">
		    {{ Form::label('first_name', 'First Name: ', null, false) }}
		    {{ Form::text('first_name', Input::old('first_name')) }}
		</div>

	    <div class="control-group {{ $errors->has('last_name') ? 'error' : '' }}">
		    {{ Form::label('last_name', 'Last Name: ', null, false) }}
		    {{ Form::text('last_name', Input::old('last_name')) }}
		</div>

	    <div class="control-group {{ $errors->has('terms') ? 'error' : '' }}">
		    {{ Form::checkbox('terms', null, Input::old('terms') ?: false) }}
		    {{ Form::label('terms', 'I agree with the Terms and Conditions. ', array('style' => 'display:inline'), false) }}
		</div>

		<div style="margin-top:10px">
	    	{{ Form::submit('Register', array('class' => 'btn btn-primary')) }}
		</div>

	    {{ Form::close() }}

	</div>
</div>

@endsection