@extends('web.base')

@section('navigation')
	<div class="status-bar small">
		<div class="title text-center">
			<h2>Survey</h2>
		</div>
	</div>
@endsection

@section('content')
	@foreach($survey['data']['items'] as $step)
		<div>{{ $step['description'] }}</div>
		@foreach($step['questions'] as $question)
			@include('fields.' . $question['type'], ['field' => $question])
		@endforeach
	@endforeach
@endsection

@section('child_styles')
	<link rel="stylesheet" href="{{ asset('plugins/sumoselect/css/sumoselect.min.css') }}">
@endsection

@section('child_scripts')
	<script src="{{ asset('plugins/sumoselect/js/jquery.sumoselect.min.js') }}"></script>
	<script>
		$(document).ready(function() {
			$('select').SumoSelect();
		});
	</script>
@endsection