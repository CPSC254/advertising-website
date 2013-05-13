<!-- Contact Form Modal Dialog -->

<div id="contact-form" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="contact-form-title" aria-hidden="true">
	{{ Form::open(URL::to_action('posts@contact'), 'post') }}

		<div class="modal-header">
			<h3 id="contact-form-title">Contact {{ $user->first_name }} {{ $user->last_name }}</h3>
		</div>
		<div class="modal-body">
			{{ Form::label('name', 'Your name:') }}
			{{ Form::text('name') }}

			{{ Form::label('phone', 'Phone:') }}
			{{ Form::text('phone') }}

			{{ Form::label('email', 'Email:') }}
			{{ Form::text('email') }}

			{{ Form::label('subject', 'Subject:') }}
			{{ Form::text('subject') }}

			{{ Form::label('message', 'Message:') }}
			{{ Form::textarea('message') }}

			{{ Form::hidden('post_id', $post->id) }}

		</div>
		<div class="modal-footer">
			<div id="contact-form-spinner"></div>
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			<button class="btn btn-primary" id="send" type="submit">Send</button>
		</div>
	{{ Form::close() }}
</div>