<div class="form-group">
	@if(isset($field['text']))
		<label class="">{{ $field['text'] }}</label>
	@endif
	<div>
		<span class="icon-red"><i class="fa fa-dollar"></i></span>
		<span class="user-balance">Rp. {{ number_format($user['balance']) }}</span>
	</div>
</div>