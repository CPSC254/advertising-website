@layout('master')

@section('content')
<table class="table table-bordered table-hover" style="background-color:#fff">
	<thead>
		<tr>
			<th>ID</th>
			<th><i class="icon-picture"></i></th>
			<th>Location</th>
			<th>Title</th>
			<th>Description</th>
			<th>Photos</th>
			<th><i class="icon-cog"></i></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($posts as $post)
			<tr>
				<td><a href="/posts/{{ $post->id }}">{{ $post->id }}</a></td>
				<td><a href="/posts/{{ $post->id }}"><img class="thumbnail" style="max-width:50px;max-height:50px" src="{{ URL::to_asset('photos/main/' . $post->main_photo_name) }}" /></a></td>
				<td>{{ $post->location }}</td>
				<td>{{ $post->title }}</td>
				<td>{{ $post->description }}</td>
				<td>{{ $post->photos()->count() }}</td>
				<td>
					<a class="btn btn-info" href="/posts/edit/{{ $post->id }}"><i class="icon-edit icon-white"></i></a>
					<a class="btn btn-danger" href="/posts/delete/{{ $post->id }}" onclick="javascript:return confirm('Are you sure you wish to delete this post?')"><i class="icon-trash icon-white"></i></a>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
@endsection