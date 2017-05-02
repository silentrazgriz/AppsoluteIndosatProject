<hr/>
<h4>Survey</h4>
<div id="survey-data">
</div>
<div class="form-group">
	<button type="button" class="btn btn-info border-round" onclick="addStep()">Tambah Langkah</button>
</div>
<input type="hidden" name="survey" value="@if(isset($field['value'])){{ json_encode($field['value']) }}@endif">

@section('scripts')
	<script>
		let surveyEdit = {{ isset($edit) ? 'true' : 'false' }};
	</script>
	<script src="{{ asset('js/survey.editor.js') }}"></script>
	<script>
		$(function () {
			if (surveyEdit) {
				processCurrentData();
			} else {
				addStep();
			}
		});
	</script>
@append