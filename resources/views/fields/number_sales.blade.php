<div id="number-sales">
	<input type="hidden" id="{{ $field['key'] }}-input" name="{{ $field['key'] }}" value="@if(isset($field['value'])){{ json_encode($field['value']) }}@endif">
</div>
@if (!isset($edit))
	<div class="form-group text-center">
		<button type="button" class="btn btn-primary border-round" onclick="addForm()">TAMBAH PEMBELIAN</button>
	</div>
@endif
<div class="form-group">
	<label>Saldo kamu</label>
	<div>
		<span class="icon-red"><i class="fa fa-dollar"></i></span>
		<span id="user-balance" class="user-balance">Rp. {{ number_format($user['balance']) }}</span>
	</div>
</div>

@section('scripts')
	<script>
		let numberList = {!! json_encode($numbers) !!};
		let fieldData = {!! json_encode($field) !!};
		let fieldKey = '#{{ $field['key'] }}-input';
		let balance = {{ $user['balance'] }};
		let salesEdit = {{ isset($edit) ? 'true' : 'false' }};
	</script>
	<script src="{{ asset('js/sales.editor.js') }}"></script>
	<script>
		$(function () {
			if (salesEdit) {
				processCurrentData();
			} else {
				addForm();
			}
		});
	</script>
@append