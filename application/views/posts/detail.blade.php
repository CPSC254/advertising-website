@layout('master')

@section('content')

ID: {{ $post->id }}
<br />
User: {{ $user->first_name }} {{ $user->last_name }}
<br />
Title: {{ $post->title }}
<br />
Location: {{ $post->location }}
<br />
Description: {{ $post->description }}
<br />
Main Photo: <img style="max-width:100px;max-height:100px" src="{{ URL::to_asset('photos/main/' . $post->main_photo_name) }}" />

<div class="container">
		<div class="row-fluid">
			<div class="span4">
				<div class="row-fluid main-content-border">
				{% thumbnail business.photo "400x300" crop="center" as im %}
	            <img src="{{ im.url }}" width="{{ im.width }}" height="{{ im.height }}">
	            {% endthumbnail %}
	        	</div>
	            <div class="row-fluid main-content-border" style="margin-top:15px">
	            	<div id="map-canvas" data-address="{{ business.address_string }}">
	            		<noscript>
	            		    <!-- http://code.google.com/apis/maps/documentation/staticmaps/ -->
	            		    <img src="http://maps.google.com/maps/api/staticmap?center={{ business.address_string|urlencode }}&amp;zoom=16&amp;size=512x512&amp;maptype=roadmap&amp;sensor=false" />
	            		</noscript>
	            	</div>
	            </div>
	            <div class="row-fluid" style="margin-top:15px">
					<div class="span1"><i class="icon-map-marker"></i></div>
					<div class="span11">
						<address>
							<a href="http://www.google.com/maps?q={{ business.address_string|urlencode }}" target="_blank">
								{% autoescape off %}
                                {{ business.address_html }}
                                {% endautoescape %}
							</a>
						</address>
					</div>
				</div>
			</div>
			<div class="span8 business-detail">
				<h1 class="business-name">{{ business.name }} <a href="{% url 'business_detail' business.id %}"><i class="icon-bookmark bookmark"></i></a></h1>
				{% for category in business.category.all %}
				<span class="label label-info">{{ category.name }}</span>
				{% endfor %}
				<div class="detail">
						<div class="span1"><i class="icon-star"></i></div>
						<div class="span11">
							{% for n in avg_rating %}
							<i class="icon-star"></i>
							{% endfor %}
						</div>
				</div>
				<div class="detail">
					<div class="row-fluid">
						<div class="span1"><img src="{% static 'img/glyphicons_163_iphone.png' %}" alt="Phone" /></div>
						<div class="span11">{{ business.formatted_phone }}</div>
					</div>
				</div>
				<div class="detail">
					<div class="row-fluid">
						<div class="span1"><i class="icon-globe"></i></div>
						<div class="span11"><a href="{{ business.website }}" target="_blank">{{ business.website }}</a></div>
					</div>
				</div>

				{% if business.description %}
				<div class="detail">
					<div class="row-fluid">
						<div class="span1"><i class="icon-align-left"></i></div>
						<div class="span11"><p>{{ business.description }}</p></div>
					</div>
				</div>
				{% endif %}
			</div>
		</div>
		<div class="row-fluid" style="margin-top:15px">
			<div class="span4">


				<p>

				</p>
			</div>
			<div class="span8">
				<h2>Reviews</h2>
				{% for review in reviews %}
					<div class="review">
						<div class="review_subject">{{ review.subject }}</div>
						<div class="detail">
							<div class="span1"><i class="icon-star"></i></div>
							<div class="span11">{{ review.rating }}</div>
						</div>
						{#	{{ review.user }} #}

						<div class="detail">
							<div class="row-fluid">
								<div class="span1"><i class="icon-align-left"></i></div>
								<div class="span11"><p>{{ business.description }}</p></div>
							</div>
						</div>
					</div>
				{% endfor %}

				{% crispy reviewform %}
			</div>
		</div>
	</div>

@endsection