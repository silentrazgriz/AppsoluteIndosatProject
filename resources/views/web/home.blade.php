@extends('web.app')

@section('navigation')
	<div class="status-bar @if(!Auth::guest()) small @endif">
		<div class="title text-center">
			@if (Auth::guest())
			<img src="{{ asset("images/indosat_ooredoo.png") }}" alt="Indosat ooredoo logo" class="logo">
			<h3>Activations Reporting Tools</h3>
			@else
				<h3>Welcome, {{ $user['name'] }}</h3>
			@endif
		</div>
	</div>
@endsection

@section('content')
	@if (Auth::guest())
		@include('web.component.login')
	@else
		@include('web.component.home')
	@endif
@endsection