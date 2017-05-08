@extends('admin.app')

@section('content')
	<div class="container">
		<form action="#" id="report-form" method="GET">
			<div class="row">
				<div class="col-xs-4">
					@include('fields.dropdown', [
						'field' => [
							'key' => 'event_id',
							'text' => 'Event',
							'values' => $data['eventLists'],
							'value' => $data['form']['event_id'] ?? $data['eventLists'][0]['key'],
							'placeholder' => 'Event'
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
				<div class="col-xs-4">
					@include('fields.dropdown', [
						'field' => [
							'key' => 'sales_area_id',
							'text' => 'Area',
							'values' => $data['areas'],
							'value' => $data['form']['sales_area_id'] ?? '0'
						]
					])
				</div>
				<div class="col-xs-4">
					@include('fields.dropdown', [
						'field' => [
							'key' => 'user_id',
							'text' => 'Buddies',
							'values' => $data['users'],
							'value' => $data['form']['user_id'] ?? '0'
						]
					])
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="form-group text-center">
						<a href="{{ route('export-kpi') }}" id="export-kpi-link" class="btn btn-primary border-round">Export KPI</a>
						<a href="{{ route('export-answer') }}" id="export-answer-link" class="btn btn-primary border-round">Export Answer</a>
						<a href="{{ route('export-sales') }}" id="export-sales-link" class="btn btn-primary border-round">Export Sales</a>
					</div>
				</div>
			</div>
		</form>
	</div>
@append

@section('scripts')
	<script>
		let kpiUrl = '{{ route('export-kpi') }}';
		let answerUrl = '{{ route('export-answer') }}';
		let salesUrl = '{{ route('export-sales') }}';
		$(function() {
			$('#export-kpi-link').click(function(e) {
				getLink('#export-kpi-link', kpiUrl);
			});
			$('#export-answer-link').click(function(e) {
				getLink('#export-answer-link', answerUrl);
			});
			$('#export-sales-link').click(function(e) {
				getLink('#export-sales-link', salesUrl);
			});
		});

		function getLink(selector, url) {
			let query = $.param($('#report-form').serializeArray());
			$(selector).attr('href', url + '?' + query);
		}
	</script>
@append