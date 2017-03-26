<div class="form-group {{ $errors->has($field['key']) ? 'has-error' : '' }}">
	@if(isset($field['text']))
		<label for="{{ $field['key'] }}">{{ $field['text'] }}</label>
	@endif
	<div class="radio" id="{{ $field['key'] }}">
		@foreach ($field['values'] as $key => $value)
			<label>
				<input type="radio"
				       name="{{ $field['key'] }}"
				       value="{{ $value['key'] }}"
				       @if(isset($value['checked']) && $value['checked']) checked @endif>
				<span class="indicator"><span></span></span>
				{{ $value['text'] }}
			</label>
		@endforeach
	</div>
	@if ($errors->has($field['key']))
		<span class="help-block"><strong>{{ $errors->first($field['key']) }}</strong></span>
	@endif
</div>