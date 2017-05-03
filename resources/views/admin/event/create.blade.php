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
			<hr/>
			<div class="row">
				<div class="col-xs-12">
					<button type="button" id="survey-group-btn" class="btn btn-warning btn-switch-group border-round" onclick="showGroup('#survey-group')">Survey</button>
					<button type="button" id="kpi-group-btn" class="btn btn-warning btn-switch-group border-round" onclick="showGroup('#kpi-group')">KPI</button>
				</div>
			</div>
			<hr/>
			@include('admin.component.survey')
			@include('admin.component.kpi')
			<div class="form-group text-center">
				<button type="reset" class="btn btn-danger border-round">Batal</button>
				<button type="submit" class="btn btn-success border-round">Buat Event</button>
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