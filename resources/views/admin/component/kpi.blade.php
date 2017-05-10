<div id="kpi-group">
	<h4>KPI</h4>
	<div id="kpi-data" class="form-horizontal">
	</div>
	<div class="form-group">
		<button type="button" class="btn btn-info border-round" onclick="addKpi()">Tambah KPI</button>
	</div>
	<input type="hidden" name="kpi" value="{{ old('kpi') ?? ((isset($field['value'])) ? json_encode($field['value']) : config('constants.EVENT.DEFAULT_KPI')) }}">
</div>

@section('scripts')
	<script>
		let kpiEdit = {{ isset($edit) ? 'true' : 'false' }};
	</script>
	<script src="{{ asset('js/kpi.editor.js') }}"></script>
	<script>
		$(function () {
			processKpiCurrentData();
		});
	</script>
@append