@extends('web.app')

@section('navigation')
	<div class="status-bar @if(!Auth::guest()) small @endif">
		<div class="title text-center">
			@if (Auth::guest())
			<img src="{{ asset("images/im3_ooredoo.png") }}" alt="IM3 ooredoo logo" class="logo">
			<h4>Activations Reporting Tools</h4>
			@else
				<h4>Welcome, {{ $user['name'] }}</h4>
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