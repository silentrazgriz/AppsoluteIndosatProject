<div class="form-group {{ $errors->has($field['key']) ? 'has-error' : '' }}">
	@if(isset($field['text']))
		<label for="{{ $field['key'] }}">{{ $field['text'] }}</label>
	@endif
	<textarea id="{{ $field['key'] }}"
	       name="{{ $field['key'] }}"
	       @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif
	       @if(isset($field['required']) && $field['required']) required @endif
	       @if(isset($field['readonly']) && $field['readonly']) readonly @endif>{{ old($field['key']) ?? $field['value'] ?? '' }}</textarea>
	@if ($errors->has($field['key']))
		<span class="help-block"><strong>{{ $errors->first($field['key']) }}</strong></span>
	@endif
</div>