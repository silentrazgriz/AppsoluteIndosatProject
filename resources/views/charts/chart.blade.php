<div class="chart">
	<canvas id="{{ $field['key'] }}"></canvas>
</div>

@section('scripts')
	<script>
		$(function() {
			let {{ $field['key'] }}Ctx = $('#{{ $field['key'] }}');
			let {{ $field['key'] }}Chart = new Chart({{ $field['key'] }}Ctx, {!! $field['data'] !!});
		});
	</script>
@append

<?php
/*
type: 'bar',
data: {
	labels: ['tanggal1', 'tanggal2', 'tanggal3', 'tanggal4'],
	datasets: [
		{
			type: 'bar',
			label: 'Edu',
			data: [10, 20, 15, 0],
			backgroundColor: 'rgba(255, 99, 132, 0.5)'
		},
		{
			type: 'bar',
			label: 'SM',
			data: [5, 7, 3, 4],
			backgroundColor: 'rgba(54, 162, 235, 0.5)'
		},
		{
			type: 'bar',
			label: 'SP',
			data: [6, 2, 4, 10],
			backgroundColor: 'rgba(255, 206, 86, 0.5)'
		}
	]
}
*/