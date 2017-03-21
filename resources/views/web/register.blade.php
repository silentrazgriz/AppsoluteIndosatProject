@extends('web.base')

@section('navigation')
	<div class="status-bar small">
		<div class="title text-center">
			<h2>Sign Up</h2>
		</div>
	</div>
@endsection

@section('content')
	@component('web.component.signup')
	@endcomponent
@endsection