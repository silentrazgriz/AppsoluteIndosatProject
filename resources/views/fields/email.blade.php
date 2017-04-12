<div class="form-group {{ $errors->has($field['key']) ? 'has-error' : '' }}">
	@if(isset($field['text']))
		<label for="{{ $field['key'] }}">{{ $field['text'] }}</label>
	@endif
	<input id="{{ $field['key'] }}"
	       type="email"
	       class="form-control {{ isset($field['class']) ? $field['class'] : 'border-round' }}"
	       name="{{ $field['key'] }}"
	       value="{{ old($field['key']) ?? $field['value'] ?? '' }}"
	       @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif
	       @if(isset($field['required']) && $field['required']) required @endif
	       @if(isset($field['readonly']) && $field['readonly']) readonly @endif>
	@if ($errors->has($field['key']))
		<span class="help-block"><strong>{{ $errors->first($field['key']) }}</strong></span>
	@endif
</div>