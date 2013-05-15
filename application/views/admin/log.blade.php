@layout('master')

@section('content')
<ul class="nav nav-pills">
  <li><a href="/admin">Main</a></li>
  <li class="active"><a href="/admin/log">Log</a></li>
</ul>

{{ Form::select('log', $logs, $selected_log) }}

<pre style="overflow:auto;height:700px"><code>{{ $log_contents }}</code></pre>

@endsection