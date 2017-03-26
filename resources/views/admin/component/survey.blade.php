<hr/>
<h4>Daftar Pertanyaan</h4>
<div id="survey-data">
	<div id="step-1">
		<h5>Bagian 1</h5>
		<div class="form-group">
			<label for="step-1-key">Kode bagian</label>
			<input id="step-1-key" type="text" class="form-control border-round" name="step-1-key" required>
			@if ($errors->has('step-1-key'))
				<span class="help-block"><strong>{{ $errors->first('step-1-key') }}</strong></span>
			@endif
		</div>
		<div class="form-group">
			<label for="step-1-description">Judul bagian</label>
			<input id="step-1-description" type="text" class="form-control border-round" name="step-1-description" required>
			@if ($errors->has('step-1-description'))
				<span class="help-block"><strong>{{ $errors->first('step-1-description') }}</strong></span>
			@endif
		</div>
	</div>
</div>
<div class="form-group">
	<a href="#" class="btn btn-info border-round">Tambah Langkah</a>
</div>
<input type="hidden" name="survey" value=""/>

@section('scripts')
	<script>
		$(function() {

		});
	</script>
@append