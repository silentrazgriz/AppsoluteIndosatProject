@extends('web.app')

@section('navigation')
	<div class="status-bar small">
		<div class="title row">
			<div class="col-xs-5">
				<h4><i class="fa fa-calendar-plus-o"></i> Report {{ $count }}</h4>
			</div>
			<div class="col-xs-7 text-right">
				<div class="step-indicator">
					<div id="step-index">1</div>
					<div id="step-description" class="text-center"></div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('content')
	<form method="POST" action="{{ route('post-survey', ['id' => $event['id']]) }}" enctype="multipart/form-data">
		{{ csrf_field() }}
		<input type="hidden" id="step-input" name="step" value="0"/>
		<input type="hidden" name="area" value="{{ $user['area'] }}"/>
		@foreach($event['survey'] as $key => $step)
			<div id="{{ $step['key'] }}" class="survey-step" data-step="{{ $key }}">
				@foreach($step['questions'] as $question)
					@if ($question['type'] != 'balance')
						@include('fields.' . $question['type'], ['field' => $question])
					@else
						@include('fields.' . $question['type'], ['field' => $question, 'user' => $user])
					@endif
				@endforeach
				<div class="form-group text-center">
				@if ($key == count($event['survey']) - 1)
					<button type="submit" id="btn-submit" class="btn btn-success border-round">SUBMIT</button>
				@else
					<button type="button" class="btn btn-primary btn-next border-round" data-next-step="{{ $key+1 }}">NEXT <i class="fa fa-arrow-circle-right"></i></button>
				@endif
				</div>
			</div>
		@endforeach
		@include('fields.terminate')
	</form>
@endsection

@section('scripts')
	<script>
		let surveyData = {!! json_encode($event['survey']) !!};
		$(function() {
			// Set first step as first form
			showStep(0);

			$('.btn-next').click(function(e) {
				let next = $(this).data('next-step');
				$('#step-input').val(next);
				showStep(next);
				e.preventDefault();
			});
		});

		function showStep(index) {
			let target = surveyData[index];
			$('.survey-step').hide();
			$('#' + target.key).show();
			$('#step-index').html((index + 1));
			$('#step-description').html(target.description);
		}
	</script>
@append