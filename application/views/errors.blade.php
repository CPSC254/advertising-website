@if($errors->messages)
<div class="alert-box alert">
  @foreach($errors->messages as $e)
    <li> {{ $e[0] }} </li>
  @endforeach
</div>
@endif
