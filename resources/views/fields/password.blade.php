<div class="form-group {{ $errors->has($field['key']) ? 'has-error' : '' }}">
	<label for="{{ $field['key'] }}">{{ $field['text'] }}</label>
	<input id="{{ $field['key'] }}" type="password" class="form-control border-round" name="{{ $field['key'] }}"  @if($field['required']) required @endif>
	@if ($errors->has($field['key']))
		<span class="help-block"><strong>{{ $errors->first($field['key']) }}</strong></span>
	@endif
</div>