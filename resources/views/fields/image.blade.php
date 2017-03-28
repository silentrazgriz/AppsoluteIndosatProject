<div class="form-group {{ $errors->has($field['key']) ? 'has-error' : '' }}">
	@if(isset($field['text']))
		<label>{{ $field['text'] }}</label>
	@endif
	<div>
		<input id="{{ $field['key'] }}"
		       type="file"
		       accept="image/*"
		       capture="camera"
		       name="{{ $field['key'] }}">
		<label for="{{ $field['key'] }}" class="btn btn-danger border-round">Upload file</label>
	</div>
	@if ($errors->has($field['key']))
		<span class="help-block"><strong>{{ $errors->first($field['key']) }}</strong></span>
	@endif
</div>