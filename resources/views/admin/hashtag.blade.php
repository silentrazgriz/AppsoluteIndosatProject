@extends('admin.app')

@section('content')
	<div class="container">
		<form action="{{ route('hashtag') }}" method="GET" class="form-horizontal">
			<div class="row">
				<label class="col-xs-1 control-label" style="text-align: left;">Hashtag</label>
				<div class="col-xs-9">
					@include('fields.text', [
						'field' => [
							'key' => 'q',
							'value' => $q,
							'placeholder' => 'Hashtag'
						]
					])
				</div>
				<div class="col-xs-2">
					<div class="form-group text-center">
						<button type="submit" class="btn btn-primary border-round">Cari</button>
					</div>
				</div>
			</div>
		</form>
		<div class="tweet-results row">
			@foreach($data as $tweet)
				<div class="col-xs-4">
					<div class="tweet">
						<a href="https://twitter.com/{{ $tweet->user->screen_name }}">{{ '@' . $tweet->user->screen_name }}</a><br/>
						{!!
							preg_replace(
							[
								'#((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i',
								'~(^|\\s)#(\\w*[a-zA-Z_0-9]+\\w*)~',
								'~(^|\\s)@(\\w*[a-zA-Z_0-9]+\\w*)~'
							],
							[
								'<a href="$1" target="_blank">$3</a>$4',
								' <a href="https://twitter.com/hashtag/$2">#$2</a> ',
								' <a href="https://twitter.com/$2">@$2</a> ',
							],
							$tweet->text)
						!!}
					</div>
				</div>
			@endforeach
		</div>
	</div>
@append