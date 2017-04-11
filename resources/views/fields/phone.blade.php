<div class="form-group {{ $errors->has($field['key']) ? 'has-error' : '' }}">
	@if(isset($field['text']))
		<label for="{{ $field['key'] }}">{{ $field['text'] }}</label>
	@endif
	<div class="input-group">
		<span class="input-group-addon border-round">+62</span>
		<input id="{{ $field['key'] }}"
		       type="tel"
		       class="form-control {{ isset($field['class']) ? $field['class'] : 'border-round' }}"
		       name="{{ $field['key'] }}"
		       @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif
		       value="{{ old($field['key']) ?? $field['value'] ?? '' }}"
		       @if(isset($field['required']) && $field['required']) required @endif
		       @if(isset($field['readonly']) && $field['readonly']) readonly @endif>
	</div>
	@if ($errors->has($field['key']))
		<span class="help-block"><strong>{{ $errors->first($field['key']) }}</strong></span>
	@endif
</div>