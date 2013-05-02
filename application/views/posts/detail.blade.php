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
Main Photo: <img src="{{ URL::to_asset('photos/main/' . $post->main_photo_name) }}" />

@endsection