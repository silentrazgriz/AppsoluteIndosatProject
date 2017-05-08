@extends('admin.app')

@section('content')
	<div class="container">
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		<form role="form" method="POST" action="{{ route('post-sales') }}">
			{{ csrf_field() }}
			<h3>Buat Akun {{ $admin ? 'Admin' : 'Buddies' }}</h3>
			@include('fields.email', [
				'field' => [
					'key' => 'email',
					'text' => 'Email',
					'required' => true
				]
			])
			@include('fields.password', [
				'field' => [
					'key' => 'password',
					'text' => 'Password',
					'required' => true
				]
			])
			@include('fields.password', [
				'field' => [
					'key' => 'password_confirmation',
					'text' => 'Konfirmasi password',
					'required' => true
				]
			])
			@include('fields.text', [
				'field' => [
					'key' => 'name',
					'text' => 'Nama',
					'required' => true
				]
			])
			@include('fields.radio', [
				'field' => [
					'key' => 'gender',
					'text' => 'Gender',
					'values' => [
						['key' => 'male', 'text' => 'Laki-laki', 'checked' => true],
						['key' => 'female', 'text' => 'Perempuan']
					]
				]
			])
			@include('fields.phone', [
				'field' => [
					'key' => 'phone',
					'text' => 'No telepon',
					'required' => true
				]
			])
			@if ($admin)
				@include('fields.dropdown', [
					'field' => [
						'key' => 'is_admin',
						'text' => 'Level Admin',
						'values' => [
							[
								'key' => 1,
								'text' => 'Admin Level 1 - Tidak bisa akses dashboard dan event'
							],
							[
								'key' => 2,
								'text' => 'Admin Level 2 - Tidak bisa akses event'
							]
						]
					]
				])
				<input type="hidden" name="sales_area_id" value="1">
				<input type="hidden" name="balance" value="500000">
			@else
				@include('fields.dropdown', [
					'field' => [
						'key' => 'sales_area_id',
						'text' => 'Sales area',
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
						'step' => 1,
						'value' => 50000
					]
				])
			@endif
			<div class="form-group text-center">
				<button type="reset" class="btn btn-danger border-round">Batal</button>
				<button type="submit" class="btn btn-success border-round">Buat Akun</button>
			</div>
		</form>
	</div>
@endsection