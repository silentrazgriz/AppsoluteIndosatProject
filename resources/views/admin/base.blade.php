@extends('skeleton')

@section('body')
@endsection

@section('styles')
	<link rel="stylesheet" href="{{ asset('plugins/adminlte/css/AdminLTE.min.css') }}"/>
	<link rel="stylesheet" href="{{ asset('css/admin.css') }}"/>
	@yield('child_styles')
@endsection

@section('scripts')
	<script type="text/javascript" src="{{ asset('plugins/adminlte/js/app.min.js') }}"></script>
	@yield('child_scripts')
@endsection