@extends('admin.app')

@section('content')
	<div class="container">
		<form action="{{ route('dashboard-area') }}" method="GET">
			<div class="row">
				<div class="col-xs-2">
					@include('fields.dropdown', [
						'field' => [
							'key' => 'event_id',
							'text' => 'Event',
							'values' => $data['eventLists'],
							'value' => $data['form']['event_id'] ?? $data['eventLists'][0]['key'],
							'placeholder' => 'Event',
							'class' => 'right-10'
						]
					])
				</div>
				<div class="col-xs-3">
					@include('fields.dropdown', [
						'field' => [
							'key' => 'sales_area_id_1',
							'text' => 'Sales Area 1',
							'values' => $data['salesAreaLists'],
							'value' => $data['form']['sales_area_id_1'] ?? 1,
							'placeholder' => 'Sales Area 1',
							'class' => 'right-5'
						]
					])
				</div>
				<div class="col-xs-3">
					@include('fields.dropdown', [
						'field' => [
							'key' => 'sales_area_id_2',
							'text' => 'Sales Area 2',
							'values' => $data['salesAreaLists'],
							'value' => $data['form']['sales_area_id_2'] ?? 0,
							'placeholder' => 'Sales Area 2',
							'class' => 'right-5'
						]
					])
				</div>
				<div class="col-xs-2">
					@include('fields.date', [
						'field' => [
							'key' => 'from',
							'text' => 'Dari',
							'value' => $data['date']['from'],
							'placeholder' => 'Dari'
						]
					])
				</div>
				<div class="col-xs-2">
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
			@if (isset($data['form']['sales_area_id_1']) && $data['form']['sales_area_id_2'] != -1 && $data['form']['sales_area_id_1'] != $data['form']['sales_area_id_2'])
				<div class="col-xs-6">
					<h3>AREA 1</h3>
					@foreach ($data['chartData']['area1'] as $report)
						@include('charts.chart', [
							'field' => $report
						])
					@endforeach
				</div>
				<div class="col-xs-6">
					<h3>AREA 2</h3>
					@foreach ($data['chartData']['area2'] as $report)
						@include('charts.chart', [
							'field' => $report
						])
					@endforeach
				</div>
			@else
				<div class="col-xs-12">
					<h3>AREA 1</h3>
					@foreach ($data['chartData']['area1'] as $report)
						@include('charts.chart', [
							'field' => $report
						])
					@endforeach
				</div>
			@endif
		</div>
	</div>
@append