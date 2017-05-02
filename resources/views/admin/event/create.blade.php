@extends('admin.app')

@section('content')
	<div class="container medium">
		<form role="form" method="POST" action="{{ route('post-event') }}">
			{{ csrf_field() }}
			<h3>Tambah Event</h3>
			@if ($errors->has)
			<div class="form-group text-danger">
				@foreach ($errors->all() as $error)
				<div>{{ $error }}</div>
				@endforeach
			</div>
			@endif
			@include('fields.text', [
				'field' => [
					'key' => 'name',
					'text' => 'Nama Event',
					'placeholder' => 'Tulis nama event disini',
					'required' => true
				]
			])
			<div class="row">
				<div class="col-xs-6">
					@include('fields.date', [
						'field' => [
							'key' => 'start_date',
							'text' => 'Tanggal Mulai',
							'placeholder' => 'Pilih tanggal mulai',
							'required' => true
						]
					])
				</div>
				<div class="col-xs-6">
					@include('fields.date', [
						'field' => [
							'key' => 'end_date',
							'text' => 'Tanggal Selesai',
							'placeholder' => 'Pilih tanggal selesai',
							'required' => true
						]
					])
				</div>
			</div>
			@include('admin.component.survey')
			<!--<div class="form-group text-center">
				<button type="reset" class="btn btn-danger border-round">Batal</button>
				<button type="submit" class="btn btn-success border-round">Buat Event</button>
			</div>-->
		</form>
	</div>
@endsection