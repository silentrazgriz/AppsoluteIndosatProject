<div class="form-group {{ $errors->has($field['key']) ? 'has-error' : '' }}">
	<label for="{{ $field['key'] }}">{{ $field['text'] }}</label>
	<input id="{{ $field['key'] }}" type="text" class="form-control border-round" name="{{ $field['key'] }}" required>
	@if ($errors->has($field['key']))
		<span class="help-block"><strong>{{ $errors->first($field['key']) }}</strong></span>
	@endif
</div>