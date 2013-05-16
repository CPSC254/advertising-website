@layout('master')

@section('content')
<ul class="nav nav-pills">
  <li><a href="/admin">Main</a></li>
  <li><a href="/admin/log">Log</a></li>
  <li class="active"><a href="/admin/users">Users</a></li>
</ul>

<div class="row-fluid">
	<div class="span12">
		<table class="table table-bordered table-hover" style="background-color:#fff">
			<thead>
				<tr>
					<th>ID</th>
					<th><i class="icon-user"></i> Name</th>
					<th><i class="icon-user"></i> Username</th>
					<th><i class="icon-envelope"></i> Email</th>
					<th><i class="icon-cog"></i></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($users as $user)
					<tr>
						<td>{{ $user->id }}</td>
						<td>
							{{ $user->first_name }} {{ $user->last_name }}
							@if ($user->id == Auth::user()->id)
							(me)
							@endif
						</td>
						<td><a href="#" class="tooltip-toggle" data-toggle="tooltip" title="Last IP: {{ $user->last_ip() }}">{{ $user->username }}</a></td>
						<td>{{ $user->email }}</td>
						<td style="width:150px">
							@if (!$user->is_admin())
							<a class="btn btn-info" href="/admin/make/{{ $user->id }}">Make Admin</a>
							@endif
							<a class="btn btn-danger" href="/users/delete/{{ $user->id }}" onclick="javascript:return confirm('Are you sure you wish to delete this user?')"><i class="icon-trash icon-white"></i></a>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

@endsection