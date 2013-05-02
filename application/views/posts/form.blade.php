@layout('master')

@section('content')

	{{ Form::open_for_files('posts/create', 'POST') }}

	<h1>Create/Edit a Post</h1>
	{{ Form::label('title', 'Title: ') }}
	{{ Form::text('title') }}

	{{ Form::label('location', 'Location: ')}}
	{{ Form::text('location') }}

	{{ Form::label('main_photo', 'Main Photo:') }}
	{{ Form::file('main_photo') }}

	{{ Form::label('description', 'Description: ')}}
	{{ Form::textarea('description') }}

	<div style="margin-top:10px">
      <button class="btn btn-large btn-primary" type="submit">Submit</button>
    </div>

	{{ Form::close() }}

@endsection