<div class="form-group {{ $errors->has($field['key']) ? 'has-error' : '' }}">
	@if(isset($field['text']))
		<label for="{{ $field['key'] }}">{{ $field['text'] }}</label>
	@endif
	<input type="hidden" id="{{ $field['key'] }}-input" name="{{ $field['key'] }}" value="@if(isset($field['value'])) {{ json_encode($field['value']) }} @endif"/>
	@foreach($field['values'] as $key => $value)
		<label class="checkbox">
			<input type="checkbox" id="{{ $field['key'] }}-{{ $key }}" data-value="{{ $value['key'] }}" @if(isset($field['value']) && in_array($value['key'], $field['value'])) checked @endif>
			<span class="indicator"><span></span></span>
			{{ $value['text'] }}
		</label>
	@endforeach
	@if ($errors->has($field['key']))
		<span class="help-block"><strong>{{ $errors->first($field['key']) }}</strong></span>
	@endif
</div>

@section('scripts')
	<script>
		$(function() {
			@foreach($field['values'] as $key => $value)
				$('#{{ $field['key'] }}-{{ $key }}').change(function() {
					let data = $(this).data('value');
					processCheckboxesValues(data);
				});
			@endforeach
		});

		function processCheckboxesValues(data) {
			let fieldValue = $('#{{ $field['key'] }}-input').val();
			let values = (fieldValue == '') ? [] : JSON.parse(fieldValue);
			let index = values.indexOf(data);

			if (index == -1) {
				values.push(data);
			} else {
				values.splice(values.indexOf(data), 1);
			}

			$('#{{ $field['key'] }}-input').val(JSON.stringify(values));
		}
	</script>
@append