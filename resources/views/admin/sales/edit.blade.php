@extends('admin.app')

@section('content')
	<div class="container">
		<form role="form" method="POST" action="{{ route('put-sales', ['id' => $data['id']]) }}">
			<input type="hidden" name="_method" value="PUT">
			{{ csrf_field() }}
			<h3>Ubah Akun Buddies</h3>
			@include('fields.email', [
				'field' => [
					'key' => 'email',
					'text' => 'Email',
					'required' => true,
					'readonly' => true,
					'value' => $data['email']
				]
			])
			@include('fields.text', [
				'field' => [
					'key' => 'name',
					'text' => 'Nama',
					'required' => true,
					'value' => $data['name']
				]
			])
			@include('fields.radio', [
				'field' => [
					'key' => 'gender',
					'text' => 'Gender',
					'values' => [
						['key' => 'male', 'text' => 'Laki-laki', 'checked' => $data['gender'] == 'male'],
						['key' => 'female', 'text' => 'Perempuan', 'checked' => $data['gender'] == 'female']
					]
				]
			])
			@include('fields.phone', [
				'field' => [
					'key' => 'phone',
					'text' => 'No telepon',
					'required' => true,
					'value' => $data['phone']
				]
			])
			@include('fields.dropdown', [
				'field' => [
					'key' => 'sales_area_id',
					'text' => 'Sales area',
					'value' => $data['sales_area_id'],
					'values' => \App\Models\SalesArea::select('id as key', 'description as text')
						->get()
						->toArray()
				]
			])
			@include('fields.number', [
				'field' => [
					'key' => 'balance',
					'text' => 'Saldo awal',
					'required' => true,
					'step' => 50000,
					'value' => $data['balance']
				]
			])
			<div class="form-group text-center">
				<button type="reset" class="btn btn-danger border-round">Batal</button>
				<button type="submit" class="btn btn-success border-round">Ubah Akun</button>
			</div>
		</form>
	</div>
@endsection