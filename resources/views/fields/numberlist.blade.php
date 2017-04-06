<div class="form-group {{ $errors->has($field['key']) ? 'has-error' : '' }}">
	@if(isset($field['text']))
		<label for="{{ $field['key'] }}">{{ $field['text'] }}</label>
	@endif
	<span class="select">
		<select id="{{ $field['key'] }}"
		        class="form-control sumo-autocomplete {{ isset($field['class']) ? $field['class'] : 'border-round' }}"
		        name="{{ $field['key'] }}"
		        @if(isset($field['disabled']) && $field['disabled']) disabled @endif>>
			@foreach(\App\Models\NumberList::where('is_taken', 0)->get()->toArray() as $value)
				<option value="{{ $value['number'] }}">{{ $value['number'] }}</option>
			@endforeach
		</select>
	</span>
	@if ($errors->has($field['key']))
		<span class="help-block"><strong>{{ $errors->first($field['key']) }}</strong></span>
	@endif
</div>