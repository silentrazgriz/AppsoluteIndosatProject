@extends('web.base')

@section('navigation')
	<div class="status-bar">
		<div class="title text-center">
			<img src="{{ asset("images/indosat_ooredoo.png") }}" alt="Indosat ooredoo logo" class="logo">
			<h2>WEB REPORTING</h2>
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