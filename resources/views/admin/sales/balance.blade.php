@extends('admin.app')

@section('content')
	<div class="container">
		<form role="form" method="POST" action="{{ route('post-sales-balance') }}">
			<input type="hidden" name="_method" value="PATCH">
			{{ csrf_field() }}
			<h3>Tambah Saldo Buddies</h3>
			@include('fields.dropdown', [
				'field' => [
					'key' => 'id',
					'text' => 'Pilih Buddies',
					'values' => $data
				]
			])
			@include('fields.number', [
				'field' => [
					'key' => 'balance',
					'text' => 'Jumlah Saldo',
					'required' => true,
					'step' => 1,
					'value' => 50000
				]
			])
			<div class="form-group text-center">
				<button type="submit" class="btn btn-success border-round">Tambah Saldo</button>
			</div>
		</form>
	</div>
@endsection