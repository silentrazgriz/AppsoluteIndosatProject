@extends('admin.app')

@section('content')
	<div class="container">
		<form role="form" method="POST" action="{{ route('post-sales') }}">
			{{ csrf_field() }}
			<h3>Tambah Event</h3>
			@include('fields.text', [
				'field' => [
					'key' => 'name',
					'text' => 'Nama Event',
					'required' => true
				]
			])
			@include('fields.date', [
				'field' => [
					'key' => 'date',
					'text' => 'Tanggal Event',
					'required' => true
				]
			])
			@include('admin.component.survey')
			<div class="form-group text-center">
				<button type="reset" class="btn btn-danger border-round">Batal</button>
				<button type="submit" class="btn btn-success border-round">Buat Event</button>
			</div>
		</form>
	</div>
@endsection