@extends('admin.app')

@section('content')
	<div class="container">
		<form role="form" method="POST" action="{{ route('post-area') }}">
			{{ csrf_field() }}
			<h3>Tambah Area</h3>
			@include('fields.text', [
				'field' => [
					'key' => 'description',
					'text' => 'Sales Area',
					'required' => true
				]
			])
			<div class="form-group text-center">
				<button type="reset" class="btn btn-danger border-round">Batal</button>
				<button type="submit" class="btn btn-success border-round">Tambah Area</button>
			</div>
		</form>
	</div>
@endsection