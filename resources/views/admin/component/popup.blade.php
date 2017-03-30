<div id="{{ $data['key'] }}-popup" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				Detail Data
				<button type="button" id="close-{{ $data['key'] }}" class="modal-close"><i class="fa fa-times"></i></button>
			</div>
			<div class="modal-body">
				@foreach($data['detail'] as $key => $row)
				<div class="row">
					<div class="col-xs-3 text-right">{{ ucfirst(str_replace('_', ' ', $key)) }} :</div>
					<div class="col-xs-9 text-left">{!! ucfirst(str_replace('_', ' ', $row)) !!}</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>
</div>
<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#{{ $data['key'] }}-popup"><i class="fa fa-file-o" aria-hidden="true"></i> Detail</button>

@section('scripts')
	<script>
		$(function() {
			$('#close-{{ $data['key'] }}').click(function(e) {
				$('#{{ $data['key'] }}-popup').modal('hide');
			});
		});
	</script>
@append