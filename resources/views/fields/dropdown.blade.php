<div class="form-group {{ $errors->has($field['key']) ? 'has-error' : '' }}">
	<label for="{{ $field['key'] }}">{{ $field['text'] }}</label>
	<span class="select">
		<select id="{{ $field['key'] }}" class="form-control border-round" name="{{ $field['key'] }}" >
			@foreach($field['values'] as $value)
				<option value="{{ $value['key'] }}">{{ $value['text'] }}</option>
			@endforeach
		</select>
	</span>
	@if ($errors->has($field['key']))
		<span class="help-block"><strong>{{ $errors->first($field['key']) }}</strong></span>
	@endif
</div>