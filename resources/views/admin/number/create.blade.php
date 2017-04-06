@extends('admin.app')

@section('content')
	<div class="container">
		<form role="form" method="POST" action="{{ route('post-number') }}">
			{{ csrf_field() }}
			<h3>Tambah Nomor HP</h3>
			@include('fields.textarea', [
				'field' => [
					'key' => 'number',
					'text' => 'Daftar Nomor HP',
					'class' => '',
					'required' => true
				]
			])
			<div class="form-group text-center">
				<button type="reset" class="btn btn-danger border-round">Batal</button>
				<button type="submit" class="btn btn-success border-round">Tambah Nomor</button>
			</div>
		</form>
	</div>
@endsection