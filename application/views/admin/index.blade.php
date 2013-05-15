@layout('master')

@section('content')
<ul class="nav nav-pills">
  <li class="active"><a href="/admin">Main</a></li>
  <li><a href="/admin/log">Log</a></li>
</ul>
<table class="table table-bordered table-hover" style="background-color:#fff">
	<thead>
		<tr>
			<th>ID</th>
			<th><i class="icon-picture"></i></th>
			<th><i class="icon-user"></i> User</th>
			<th><i class="icon-globe"></i> Location</th>
			<th><i class="icon-font"></i> Title</th>
			<th><i class="icon-align-left"></i> Description</th>
			<th><i class="icon-picture"></i> Photos</th>
			<th><i class="icon-cog"></i></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($posts as $post)
			<tr>
				<td><a href="/posts/{{ $post->id }}">{{ $post->id }}</a></td>
				<td>{{ $post->user->username }}</td>
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

<a href="/posts/create" class="btn btn-success">Create a new post</a>
@endsection