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
			<hr/>
			<div class="row">
				<div class="col-xs-12">
					<button type="button" id="survey-group-btn" class="btn btn-warning btn-switch-group border-round" onclick="showGroup('#survey-group')">Survey</button>
					<button type="button" id="kpi-group-btn" class="btn btn-warning btn-switch-group border-round" onclick="showGroup('#kpi-group')">KPI</button>
				</div>
			</div>
			<hr/>
			@include('admin.component.survey', [
				'field' => [
					'value' => $event['survey']
				],
				'edit' => true
			])
			@include('admin.component.kpi', [
				'field' => [
					'value' => $event['kpi']
				],
				'edit' => true
			])
			<div class="form-group text-center">
				<button type="reset" class="btn btn-danger border-round">Batal</button>
				<button type="submit" class="btn btn-success border-round">Ubah Event</button>
			</div>
		</form>
	</div>
@endsection

@section('scripts')
	<script>
		$(function() {
			showGroup('#survey-group');
		});

		function showGroup(selector) {
			$('#survey-group, #kpi-group').hide();
			$('#survey-group-btn, #kpi-group-btn').removeClass('active');
			$(selector).show();
			$(selector + '-btn').addClass('active');
		}
	</script>
@append