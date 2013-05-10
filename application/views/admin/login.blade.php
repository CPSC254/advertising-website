@layout('master')

@section('content')

  {{ Form::open('admin/login', 'post', array('class' => 'form-signin')) }}
    {{ Form::token() }}
    <h2 class="form-signin-heading">Admin Area</h2>

    @if (Session::has('error'))
    <div class="alert alert-error"><h4>Uh oh!</h4> {{ Session::get('error') }}</div>
    @endif

    {{ Form::text('username', '', array('class' => 'input-block-level', 'placeholder' => 'Username')) }}
    {{ Form::password('password', array('class' => 'input-block-level', 'placeholder' => 'Password')) }}

    {{ Form::password('admin_password', array('class' => 'input-block-level', 'placeholder' => 'Admin Password', 'style' => 'border:1px solid red')) }}

    <div style="margin-top:10px">
      <button class="btn btn-large btn-primary" type="submit">Sign in</button>
    </div>

  {{ Form::close() }}

@endsection