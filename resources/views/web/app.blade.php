@extends('app')

@section('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/web.css') }}">
@append

@section('body')
	@yield('navigation')
	<div class="container">
		@yield('content')
	</div>
@endsection