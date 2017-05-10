<div id="survey-group">
	<h4>Survey</h4>
	<div id="survey-data" class="form-horizontal">
	</div>
	<div class="form-group">
		<button type="button" class="btn btn-info border-round" onclick="addStep()">Tambah Langkah</button>
	</div>
	<input type="hidden" name="survey" value="{{ old('survey') ?? ((isset($field['value'])) ? json_encode($field['value']) : config('constants.EVENT.DEFAULT_SURVEY')) }}">
</div>

@section('scripts')
	<script>
		let surveyEdit = {{ isset($edit) ? 'true' : 'false' }};
	</script>
	<script src="{{ asset('js/survey.editor.js') }}"></script>
	<script>
		$(function () {
			processSurveyCurrentData();
		});
	</script>
@append