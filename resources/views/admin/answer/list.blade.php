@extends('admin.app')

@section('content')
	<div class="container">
		<h3>Hasil Survey {{ $data['summary']['name'] }}</h3>
		@include('admin.component.table', ['data' => $data])
	</div>
@endsection