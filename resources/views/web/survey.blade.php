@extends('web.app')

@section('navigation')
	<div class="status-bar small">
		<div class="title row">
			<div class="col-xs-6">
				<h3><i class="fa fa-calendar-plus-o"></i> Report {{ $count }}</h3>
			</div>
			<div class="col-xs-6 text-center">
				<div class="step-indicator">
					@foreach($event['survey'] as $key => $step)
					<div id="{{ $step['key'] }}-indicator" class="survey-indicator">
						<span class="bubble">{{ $key+1 }}</span>
					</div>
					@endforeach
				</div>
				<div id="step-description" class="text-center"></div>
			</div>
		</div>
	</div>
@endsection

@section('content')
	<form method="POST" action="{{ route('post-survey', ['id' => $event['id']]) }}" enctype="multipart/form-data">
		{{ csrf_field() }}
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
				@if ($key != 0)
					<button type="button" class="btn btn-primary btn-prev border-round" data-next-step="{{ $key-1 }}"><i class="fa fa-arrow-circle-left"></i> PREV</button>
				@else
					<a href="{{ route('home') }}" class="btn btn-primary border-round"><i class="fa fa-home"></i> HOME</a>
				@endif
				@if ($key == count($event['survey']) - 1)
					<button type="submit" id="btn-submit" class="btn btn-success border-round">Kirim</button>
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
		let surveyData = JSON.parse('{!! json_encode($event['survey']) !!}');
		$(function() {
			// Set first step as first form
			showStep(0);

			$('.btn-next, .btn-prev').click(function(e) {
				let next = $(this).data('next-step');
				showStep(next);
				e.preventDefault();
			});
		});

		function showStep(index) {
			let target = surveyData[index];
			$('.survey-step').hide();
			$('#' + target.key).show();
			setIndicator(target);
		}

		function setIndicator(target) {
			$('.survey-indicator').removeClass('active');
			$('#' + target.key + '-indicator').addClass('active');

			$('#step-description').html(target.description);
		}
	</script>
@append