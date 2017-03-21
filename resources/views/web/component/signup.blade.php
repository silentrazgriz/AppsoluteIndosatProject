<form role="form" method="POST" action="{{ route('register') }}">
	{{ csrf_field() }}
	<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
		<label for="email">Email</label>
		<input id="email" type="email" class="form-control border-round" name="email" value="{{ old('email') }}" required>
		@if ($errors->has('email'))
			<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
		@endif
	</div>
	<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
		<label for="password">Password</label>
		<input id="password" type="password" class="form-control border-round" name="password" required>
		@if ($errors->has('password'))
			<span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
		@endif
	</div>
	<div class="form-group">
		<label for="password-confirm">Konfirmasi password</label>
		<input id="password-confirm" type="password" class="form-control border-round" name="password_confirmation" required>
	</div>
	<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
		<label for="name">Nama</label>
		<input id="name" type="text" class="form-control border-round" name="name" value="{{ old('name') }}" required autofocus>
		@if ($errors->has('name'))
			<span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
		@endif
	</div>
	<div class="form-group {{ $errors->has('gender') ? 'has-error' : '' }}">
		<label for="gender">Gender</label>
		<div class="radio">
			<label>
				<input type="radio" name="gender" id="gender" value="male" checked>
				Laki-laki
			</label>
			<label>
				<input type="radio" name="gender" id="gender" value="female">
				Perempuan
			</label>
		</div>
		@if ($errors->has('gender'))
			<span class="help-block"><strong>{{ $errors->first('gender') }}</strong></span>
		@endif
	</div>
	<div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
		<label for="phone">No telepon</label>
		<input id="phone" type="text" class="form-control border-round" name="phone" value="{{ old('phone') }}" required autofocus>
		@if ($errors->has('phone'))
			<span class="help-block"><strong>{{ $errors->first('phone') }}</strong></span>
		@endif
	</div>
	<div class="form-group {{ $errors->has('balance') ? 'has-error' : '' }}">
		<label for="balance">Saldo awal</label>
		<input id="balance" type="text" class="form-control border-round" name="balance" value="{{ old('balance') }}" required autofocus>
		@if ($errors->has('balance'))
			<span class="help-block"><strong>{{ $errors->first('balance') }}</strong></span>
		@endif
	</div>
	<div class="form-group text-center">
		<a href="#" class="btn btn-danger border-round">CANCEL</a>
		<button type="submit" class="btn btn-success border-round">SUBMIT</button>
	</div>
</form>