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