<div id="add-balance-modal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" id="close-add-balance" class="modal-close"><i class="fa fa-times"></i></button>
				<form action="{{ route('post-balance') }}" method="POST">
					{{ csrf_field() }}
					@include('fields.number', [
						'field' => [
							'key' => 'balance',
							'text' => 'Tambah Saldo',
							'value' => 100000,
							'step' => 50000,
							'required' => true
						]
					])
					@include('fields.submit', [
						'field' => [
							'text' => 'Tambah Saldo'
						]
					])
				</form>
			</div>
		</div>
	</div>
</div>

@section('scripts')
	<script>
		$(function() {
			@if ($errors->has('balance'))
				$('#add-balance-modal').modal('show');
			@endif

			$('#close-add-balance').click(function(e) {
				$('#add-balance-modal').modal('hide');
			});
		});
	</script>
@append