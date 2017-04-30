@extends('admin.app')

@section('content')
	<div class="container default">
		<h4 class="survey-title text-center">{{ $data['user']['name'] }}, {{ $data['survey']['area'] }}<br/>{{ $data['survey']['created_at'] }}</h4>
		<hr/>
		<form method="POST" action="{{ route('put-survey', ['id' => $data['survey']['id']]) }}" enctype="multipart/form-data">
			{{ csrf_field() }}
			<input type="hidden" name="_method" value="PUT"/>
			@foreach($data['event']['survey'] as $key => $step)
				<div id="{{ $step['key'] }}" class="survey-step">
					<h4>{{ $step['description'] }}</h4>
					@foreach($step['questions'] as $question)
						@if ($question['type'] != 'number_sales')
							@if (isset($question['key']))
								@include('fields.' . $question['type'], [
									'field' => array_merge($question, ['value' => $data['survey']['answer'][$question['key']]]),
									'edit' => true
								])
							@else
								@include('fields.' . $question['type'], [
									'field' => $question,
									'edit' => true
								])
							@endif
						@else
							@include('fields.' . $question['type'], [
								'field' => array_merge($question, ['value' => $data['survey']['answer'][$question['key']]]),
								'user' => $data['user'],
								'numbers' => $data['numbers'],
								'edit' => true
							])
						@endif
					@endforeach
				</div>
			@endforeach
			<div class="form-group text-center">
				<button type="submit" id="btn-submit" class="btn btn-success border-round">UBAH</button>
			</div>
		</form>
	</div>
@endsection