<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>Survey Site</title>
		<link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontawesome/css/font-awesome.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('plugins/sumoselect/css/sumoselect.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
		@yield('styles')
	</head>
	<body>
		@yield('body')

		<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('plugins/sumoselect/js/jquery.sumoselect.min.js') }}"></script>
		@yield('scripts')
		<script>
			$(function() {
				$('.sumo-select').SumoSelect();
			});
		</script>
	</body>
</html>