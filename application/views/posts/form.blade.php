@layout('master')

@section('content')

	<div class="container">
		<div class="row-fluid">
			<div class="span7 offset2 well">
				{{ Form::open_for_files('posts/create', 'POST') }}
					<fieldset>
						<legend>Create/Edit a Post</legend>

						@if (count($errors->all()) > 0)
							<div class="alert alert-error"><strong>Uh oh!</strong>
							@foreach ($errors->all('<li>:message</li>') as $error_message)
								{{ $error_message }}
							@endforeach
							</div>
						@endif

						<div class="control-group {{ $errors->has('title') ? 'error' : '' }}">
							{{ Form::label('title', 'Title: <span class="text-error">' . $errors->first('title') . '</span>', null, false) }}
							{{ Form::text('title', Input::old('title')) }}
						</div>

						<div class="control-group {{ $errors->has('location') ? 'error' : '' }}">
							{{ Form::label('location', 'Location:  <span class="text-error">' . $errors->first('location') . '</span>', null, false)}}
							<input type="text" id="location" name="location" value="{{ Input::old('location') }}" style="margin:0 auto;" data-provide="typeahead" data-items="4" data-source="[{{$cities}}]" autocomplete="off" />
						</div>

						<div class="control-group {{ $errors->has('main_photo') ? 'error' : '' }}">
							{{ Form::label('main_photo', 'Main Photo: <span class="text-error">' . $errors->first('main_photo') . '</span>', null, false) }}
							{{ Form::file('main_photo') }}
						</div>

						<div class="control-group {{ $errors->has('description') ? 'error' : '' }}">
							{{ Form::label('description', 'Description:  <span class="text-error">' . $errors->first('description') . '</span>', null, false)}}
							{{ Form::textarea('description', Input::old('description')) }}
						</div>

						<div class="form-actions">
					      <button class="btn btn-primary" type="submit">Submit</button>
					      <button class="btn" type="button">Cancel</button>
					    </div>

					</fieldset>

				{{ Form::close() }}
			</div>
		</div>
	</div>

@endsection