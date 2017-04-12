<div id="terminate-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="terminate-label">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" id="close-terminate" class="modal-close"><i class="fa fa-times"></i></button>
				<div class="form-group">
					<label for="terminate" id="terminate-label">Reasons Terminated</label>
					<textarea id="terminate" name="terminate"></textarea>
				</div>
				<div class="text-center">
					<button type="submit" class="btn btn-danger border-round">TERMINATE</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="form-group text-center">
	<hr/>
	<input type="hidden" name="is_terminated" id="is-terminated" value="1">
	<button type="button" id="btn-terminate" class="btn btn-danger border-round"
	        data-toggle="modal" data-target="#terminate-modal" data-backdrop="static" data-keyboard="false">TERMINATE</button>
</div>

@section('scripts')
	<script>
		$(function() {
			$('.terminate-empty').change(function(e) {
				let terminateEmpty = $('.terminate-empty');
				let inputCount = terminateEmpty.length;
				$.each(terminateEmpty, function(i, input) {
					if (input.value == "") {
						return false;
					} else if (i == inputCount - 1) {
						$('#is-terminated').val(0);
					}
				});
			});

			$('#btn-terminate').click(function(e) {
				$('#terminate').prop('required', true);
				$('.required').prop('required', false);
				$('#is-terminated').val(1);
			});

			$('#close-terminate').click(function(e) {
				$('#terminate').prop('required', false);
				$('.required').prop('required', true);
				$('#is-terminated').val(0);
				$('#terminate-modal').modal('hide');
			});
		});
	</script>
@append