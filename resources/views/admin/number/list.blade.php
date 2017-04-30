@extends('admin.app')

@section('content')
	<div class="container">
		<h3>Daftar Nomor HP</h3>
		@include('admin.component.table', ['data' => $data])
		<div class="text-center">
			{{ $data['pages']->links() }}
		</div>
	</div>
@endsection