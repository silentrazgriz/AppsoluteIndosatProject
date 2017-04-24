@extends('admin.app')

@section('content')
	<div class="container">
		<form action="{{ route('dashboard') }}" method="GET">
			<div class="row">
				<div class="col-xs-4">
					@include('fields.dropdown', [
						'field' => [
							'key' => 'event_id',
							'text' => 'Event',
							'values' => $data['eventLists'],
							'placeholder' => 'Event',
							'class' => 'right-10'
						]
					])
				</div>
				<div class="col-xs-4">
					@include('fields.date', [
						'field' => [
							'key' => 'from',
							'text' => 'Dari',
							'value' => $data['date']['from'],
							'placeholder' => 'Dari'
						]
					])
				</div>
				<div class="col-xs-4">
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
	</div>
@append