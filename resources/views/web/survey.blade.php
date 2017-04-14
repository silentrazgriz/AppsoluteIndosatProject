@extends('web.app')

@section('navigation')
	<div class="status-bar small">
		<div class="title row">
			<div class="col-xs-12">
				<h4 class="inline-block"><i class="fa fa-calendar-plus-o"></i> Report {{ $count }}</h4>
				<div class="step-indicator pull-right">
					<span id="step-index">1</span>
					<span id="step-description"></span>
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
		let step = 0;

		$(function() {
			// Set first step as first form
			showStep(step);

			$('.btn-next').click(function(e) {
				console.log(isRequiredFilled());
				if (isRequiredFilled()) {
					let next = $(this).data('next-step');
					step = next;
					$('#step-input').val(next);
					showStep(next);
				} else {
					alert(getErrorMessage());
				}
				e.preventDefault();
			});
		});

		function getErrorMessage() {
			let selectors = getRequiredSelectors();
			if ($(selectors.text).length > 0) {
				return getLabelTextFromInput($(selectors.text).attr('id')) + ' belum diisi';
			} else if ($(selectors.image).length > 0) {
				return getLabelTextFromInput($(selectors.image).attr('id')) + ' belum diisi';
			}
			return '';
		}

		function showStep(index) {
			let target = surveyData[index];
			$('.survey-step').hide();
			$('#' + target.key).show();
			$('#step-index').html((index + 1));
			$('#step-description').html(target.description);
		}

		function isRequiredFilled() {
			let selectors = getRequiredSelectors();
			return ($(selectors.text).length != 0 && $(selectors.text).val().length != 0) ||
				($(selectors.image).length != 0 && $(selectors.image).val().length != 0) ||
				($(selectors.text).length == 0 && $(selectors.image).length == 0);
		}

		function getLabelTextFromInput(id) {
			return $('label[for=' + id + ']').html();
		}

		function getRequiredSelectors() {
			return {
				text: '#' + surveyData[step]['key'] + ' input[type=text].required',
				image: '#' + surveyData[step]['key'] + ' input[type=file].required'
			};
		}
	</script>
@append