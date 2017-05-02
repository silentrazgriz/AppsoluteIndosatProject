@extends('admin.app')

@section('content')
	<div class="container medium">
		<form role="form" method="POST" action="{{ route('put-event', ['id' => $event['id']]) }}">
			{{ csrf_field() }}
			<input type="hidden" name="_method" value="PUT"/>
			<h3>Ubah Event</h3>
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
					'value' => $event['name'],
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
							'value' => $event['start_date'],
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
							'value' => $event['end_date'],
							'required' => true
						]
					])
				</div>
			</div>
			@include('admin.component.survey', [
				'field' => [
					'value' => $event['survey']
				],
				'edit' => true
			])
			<!--<div class="form-group text-center">
				<button type="reset" class="btn btn-danger border-round">Batal</button>
				<button type="submit" class="btn btn-success border-round">Buat Event</button>
			</div>-->
		</form>
	</div>
@endsection