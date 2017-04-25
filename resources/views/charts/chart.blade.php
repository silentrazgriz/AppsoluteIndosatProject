<div class="chart">
	@if(isset($field['text']))
		<label>{{ $field['text'] }}</label>
	@endif
	<canvas id="{{ $field['key'] }}" @if(isset($field['dataCount'])) height="{{ ($field['dataCount'] * 50) }}" @endif></canvas>
</div>

@section('scripts')
	<script>
		let {{ $field['key'] }}Data = {!! $field['chartData'] !!};
		@if ($field['drawDataInside'])
		{{ $field['key'] }}Data.options.animation = {
			onComplete: function () {
				let ctx = this.chart.ctx;
				ctx.font = "bold 12px Helvetica, Arial, sans-serif";
				ctx.fillStyle = "#444";
				ctx.textAlign = "left";
				ctx.textBaseline = "bottom";

				this.data.datasets.forEach(function (dataset) {
					for (let i = 0; i < dataset.data.length; i++) {
						let model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model;
						let left = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._xScale.left;
						let text = dataset.data[i].toLocaleString();
						@if (isset($field['hiddenLabel']) && $field['hiddenLabel'])
							text = model.label + ' (' + text + ')';
						@endif
						ctx.fillText(text, left + 15, model.y + 8);
					}
				});
			}
		};
		@endif

		$(function () {
			let {{ $field['key'] }}Ctx = $('#{{ $field['key'] }}');
			let {{ $field['key'] }}Chart = new Chart({{ $field['key'] }}Ctx, {{ $field['key'] }}Data);
		});
	</script>
@append