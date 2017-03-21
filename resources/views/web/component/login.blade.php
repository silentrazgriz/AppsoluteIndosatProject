<form role="form" method="POST" action="{{ route('login') }}">
	{{ csrf_field() }}
	<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
		<input id="email" type="email" class="form-control border-bottom-only no-border-radius" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
		@if ($errors->has('email'))
			<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
		@endif
	</div>
	<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
		<input id="password" type="password" class="form-control border-bottom-only no-border-radius"  name="password" placeholder="Password" required>
		@if ($errors->has('password'))
			<span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
		@endif
	</div>
	<div class="form-group text-center">
		<p><button type="submit" class="btn btn-primary border-round">LOGIN</button></p>
		<p><a href="{{ route("register") }}" class="btn btn-primary border-round">SIGN UP</a></p>
	</div>
</form>