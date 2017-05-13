@extends('admin.app')

@section('content')
	<div class="container">
		<h3>Daftar Sales Area</h3>
		@include('admin.component.table', ['data' => $data])
	</div>
@endsection