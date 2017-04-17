@extends('admin.app')

@section('content')
	<div class="container">
		<form action="{{ route('dashboard') }}" method="GET">
			<div class="row">
				<div class="col-xs-5">
					@include('fields.date', [
						'field' => [
							'key' => 'from',
							'text' => 'Dari',
							'value' => $data['date']['from'],
							'placeholder' => 'Dari'
						]
					])
				</div>
				<div class="col-xs-5">
					@include('fields.date', [
						'field' => [
							'key' => 'to',
							'text' => 'Sampai',
							'value' => $data['date']['to'],
							'placeholder' => 'Sampai'
						]
					])
				</div>
				<div class="col-xs-2">
					@include('fields.submit', [
						'field' => [
							'text' => 'Buat Laporan',
							'class' => 'margin-top-30 border-round'
						]
					])
				</div>
			</div>
		</form>
		<div class="row">
			<div class="col-xs-12">
				@include('charts.chart', [
					'field' => [
						'key' => 'salesAgent',
						'data' => $data['chartData']
					]
				])
			</div>
		</div>
	</div>
@append