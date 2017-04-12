<div class="form-group {{ $errors->has($field['key']) ? 'has-error' : '' }}">
	@if(isset($field['text']))
		<label for="{{ $field['key'] }}">{{ $field['text'] }}</label>
	@endif
	<input id="{{ $field['key'] }}"
	       type="number"
	       class="form-control {{ isset($field['class']) ? $field['class'] : 'border-round' }}"
	       name="{{ $field['key'] }}"
	       @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif
	       value="{{ old($field['key']) ?? $field['value'] ?? '' }}"
	       @if(isset($field['step'])) step="{{ $field['step'] }}" @endif
	       @if(isset($field['disabled']) && $field['disabled']) disabled @endif>
	@if ($errors->has($field['key']))
		<span class="help-block"><strong>{{ $errors->first($field['key']) }}</strong></span>
	@endif
</div>