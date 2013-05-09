@layout('master')

@section('header_js')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true"></script>
@endsection

@section('css')
{{ HTML::style('css/bootstrap-lightbox.css') }}
@endsection

@section('footer_js')
{{ HTML::script('js/bootstrap-lightbox.js') }}
@endsection

@section('content')

<div class="container">
	<div class="row-fluid">
		<div class="span4">
			<div class="row-fluid">
				<img class="main-content-border" style="max-width:300px;max-height:400px" src="{{ URL::to_asset('photos/main/' . $post->main_photo_name) }}" />
        	</div>
            <div class="row-fluid" style="margin-top:15px">
            	<div id="map-canvas" data-address="{{ $post->location }}">
            		<noscript>
            		    <!-- http://code.google.com/apis/maps/documentation/staticmaps/ -->
            		    <img src="http://maps.google.com/maps/api/staticmap?center={{ urlencode($post->location) }}&amp;zoom=16&amp;size=512x512&amp;maptype=roadmap&amp;sensor=false" />
            		</noscript>
            	</div>
            </div>
            <div class="row-fluid" style="margin-top:15px">
				<div class="span1"><i class="icon-map-marker"></i></div>
				<div class="span11">
					<address>
						<a href="http://www.google.com/maps?q={{ urlencode($post->location) }}" target="_blank">
							{{ $post->location }}
						</a>
					</address>
				</div>
			</div>
		</div>
		<div class="span8 post-detail">
			<h1 class="post-name">
				{{ $post->title }}
				<a href="{{ URL::to_action('posts@index', $post->id) }}">
					<i class="icon-bookmark bookmark"></i>
				</a>
			</h1>
			{{-- Should we implement categories? --}}
			{{-- Category::names($post->categories()->get()) --}}
			<!-- <span class="label label-info">{{ $post->categories }}</span> -->

			<div class="detail">
				<div class="row-fluid">
					<div class="span1"><i class="icon-envelope"></i></div>
					<div class="span11">
						<a data-toggle="modal" data-target="#contact-form" style="cursor:pointer">Contact {{ $user->first_name }} {{ $user->last_name }}</a>
					</div>
				</div>
			</div>

			<div class="detail">
				<div class="row-fluid">
					<div class="span1"><i class="icon-picture"></i></div>
					<div class="span11">
						<ul class="inline unstyled">

							@foreach ($post->photos as $photo)
							<li>
								<a data-toggle="lightbox" href="#photo-{{ $photo->id }}" class="thumbnail">
									<img style="max-width:100px;max-height:100px" src="{{ URL::to_asset('photos/posts/' . $photo->name) }}" />
								</a>
							</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>

			<div class="detail">
				<div class="row-fluid">
					<div class="span1"><i class="icon-align-left"></i></div>
					<div class="span11"><p>{{ $post->description }}</p></div>
				</div>
			</div>


		</div>
	</div>
</div>

<!-- Lightbox Modals -->
@foreach ($post->photos as $photo)
<div id="photo-{{ $photo->id }}" class="lightbox hide fade"  tabindex="-1" role="dialog" aria-hidden="true">
	<div class='lightbox-content'>
		<img src="{{ URL::to_asset('photos/posts/' . $photo->name) }}" />
	</div>
</div>
@endforeach

<!-- Contact Form Modal Dialog -->

<div id="contact-form" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="contact-form-title" aria-hidden="true">
	{{ Form::open(URL::to_action('posts@contact'), 'post') }}

		<div class="modal-header">
			<h3 id="contact-form-title">Contact {{ $user->first_name }} {{ $user->last_name }}</h3>
		</div>
		<div class="modal-body">
			{{ Form::label('name', 'Your name:') }}
			{{ Form::text('name') }}

			{{ Form::label('subject', 'Subject:') }}
			{{ Form::text('subject') }}

			{{ Form::label('message', 'Message:') }}
			{{ Form::textarea('message') }}

		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			<button class="btn btn-primary" type="submit">Send</button>
		</div>
	{{ Form::close() }}
</div>

@endsection