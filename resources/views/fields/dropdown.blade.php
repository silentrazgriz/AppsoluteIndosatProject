<div class="form-group {{ $errors->has($field['key']) ? 'has-error' : '' }}">
	@if(isset($field['text']))
		<label for="{{ $field['key'] }}">{{ $field['text'] }}</label>
	@endif
	<span class="select">
		<select id="{{ $field['key'] }}"
		        class="form-control sumo-select {{ isset($field['class']) ? $field['class'] : 'border-round' }}"
		        name="{{ $field['key'] }}"
		        @if(isset($field['disabled']) && $field['disabled']) disabled @endif>>
			@foreach($field['values'] as $value)
				<option value="{{ $value['key'] }}">{{ $value['text'] }}</option>
			@endforeach
		</select>
	</span>
	@if ($errors->has($field['key']))
		<span class="help-block"><strong>{{ $errors->first($field['key']) }}</strong></span>
	@endif
</div>