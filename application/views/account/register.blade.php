@layout('master')

@section('content')

<div class="container-fluid">
	<div class="form-register">
	    <h2>Sign Up</h2>
	    @render('errors')

	    {{ Form::open('account/register', 'POST') }}
	    {{ Form::token() }}

	    <div class="control-group {{ $errors->has('email') ? 'error' : '' }}">
		    {{ Form::label('email', 'Email:') }}
		    {{ Form::text('email') }}
		</div>

		<div class="control-group {{ $errors->has('username') ? 'error' : '' }}">
		    {{ Form::label('username', 'Username:') }}
		    {{ Form::text('username') }}
		</div>

		<div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
		    {{ Form::label('password', 'Password:<span class="required">*</span>', null, false) }}
		    {{ Form::password('password') }}
	    </div>

	    <div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
		    {{ Form::label('password_confirmation', 'Confirm Password:') }}
		    {{ Form::password('password_confirmation') }}
		</div>

	    <div class="control-group {{ $errors->has('first_name') ? 'error' : '' }}">
		    {{ Form::label('first_name', 'First Name:') }}
		    {{ Form::text('first_name') }}
		</div>

	    <div class="control-group {{ $errors->has('last_name') ? 'error' : '' }}">
		    {{ Form::label('last_name', 'Last Name:') }}
		    {{ Form::text('last_name') }}
		</div>

	    <div class="control-group {{ $errors->has('terms') ? 'error' : '' }}">
		    {{ Form::checkbox('terms', null, true) }}
		    {{ Form::label('terms', 'I agree with the Terms and Conditions.', array('style' => 'display:inline'), false) }}
		</div>

		<div style="margin-top:10px">
	    	{{ Form::submit('Register', array('class' => 'btn btn-primary')) }}
		</div>

	    {{ Form::close() }}

	</div>
</div>

@endsection