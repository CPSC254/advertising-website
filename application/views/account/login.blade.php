@layout('master')

@section('content')

  {{ Form::open('account/login', 'POST', array('class' => 'form-signin')) }}
    {{ Form::token() }}
    <h2 class="form-signin-heading">Please sign in</h2>

    @if (Session::has('error'))
    <div class="alert alert-error"><h4>Uh oh!</h4> {{ Session::get('error') }}</div>
    @endif

    {{ Form::text('username', '', array('class' => 'input-block-level', 'placeholder' => 'Email address')) }}
    {{ Form::password('password', array('class' => 'input-block-level', 'placeholder' => 'Password')) }}

    {{ Form::checkbox('remember', 'true', true, array('style' => 'display:inline')) }}
    {{ Form::label('remember', 'Remember me', array('style' => 'display:inline')) }}

    <div style="margin-top:10px">
      <button class="btn btn-large btn-primary" type="submit">Sign in</button>
    </div>
  </form>

@endsection