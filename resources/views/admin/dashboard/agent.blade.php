@extends('admin.app')

@section('content')
	<div class="container">
		<form action="{{ route('dashboard-agent') }}" method="GET">
			<div class="row">
				<div class="col-xs-3">
					@include('fields.dropdown', [
						'field' => [
							'key' => 'event_id',
							'text' => 'Event',
							'values' => $data['eventLists'],
							'value' => $data['form']['event_id'] ?? $data['eventLists'][0]['key'],
							'placeholder' => 'Event',
							'class' => 'right-5'
						]
					])
				</div>
				<div class="col-xs-3">
					@include('fields.dropdown', [
						'field' => [
							'key' => 'user_id',
							'text' => 'Agent',
							'values' => $data['userLists'],
							'value' => $data['form']['user_id'] ?? $data['userLists'][0]['key'],
							'placeholder' => 'Agent',
							'class' => 'right-5'
						]
					])
				</div>
				<div class="col-xs-3">
					@include('fields.date', [
						'field' => [
							'key' => 'from',
							'text' => 'Dari',
							'value' => $data['date']['from'],
							'placeholder' => 'Dari'
						]
					])
				</div>
				<div class="col-xs-3">
					@include('fields.date', [
						'field' => [
							'key' => 'to',
							'text' => 'Sampai',
							'value' => $data['date']['to'],
							'placeholder' => 'Sampai'
						]
					])
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					@include('fields.submit', [
						'field' => [
							'text' => 'Kirim'
						]
					])
				</div>
			</div>
		</form>
		<div class="row">
			<div class="col-xs-12">
				@foreach ($data['chartData'] as $report)
					@include('charts.chart', [
						'field' => $report
					])
				@endforeach
			</div>
		</div>
		<div class="row">
			<h3>Daftar Jawaban</h3>
			@include('admin.component.table', ['data' => $data])
		</div>
	</div>
@append