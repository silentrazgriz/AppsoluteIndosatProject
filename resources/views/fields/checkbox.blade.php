<div class="form-group {{ $errors->has($field['key']) ? 'has-error' : '' }}">
	<input type="hidden" name="{{ $field['key'] }}" value="0">
	<label class="checkbox">
		<input type="checkbox" name="{{ $field['key'] }}" value="1">
		<span class="indicator"><span></span></span>
		{{ $field['text'] }}
	</label>
	@if ($errors->has($field['key']))
		<span class="help-block"><strong>{{ $errors->first($field['key']) }}</strong></span>
	@endif
</div>