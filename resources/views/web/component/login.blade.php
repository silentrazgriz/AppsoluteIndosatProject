<form role="form" method="POST" action="{{ route('post-login') }}">
	{{ csrf_field() }}
	@include('fields.email', [
		'field' => [
			'key' => 'email',
			'class' => 'border-bottom-only no-border-radius',
			'placeholder' => 'Email',
			'required' => 1
		]
	])
	@include('fields.password', [
		'field' => [
			'key' => 'password',
			'class' => 'border-bottom-only no-border-radius',
			'placeholder' => 'Password',
			'required' => 1
		]
	])
	@include('fields.text', [
		'field' => [
			'key' => 'auth_code',
			'class' => 'border-bottom-only no-border-radius',
			'placeholder' => 'Kode otentikasi',
			'value' => $event['auth_code'],
			'required' => 1
		]
	])
	@include('fields.geomap', [
		'field' => ['key' => 'map']
	])
	@include('fields.submit', [
		'field' => ['text' => 'LOGIN']
	])
</form>