<div class="row">
	<div class="col-xs-12 text-center">{{ $date }}</div>
</div>
<div class="sales-home">
	<div class="row">
		<div class="col-xs-2"><span class="icon-red"><i class="fa fa-calendar" aria-hidden="true"></i></span></div>
		<div class="col-xs-4">{{ $event['name'] }}</div>
		<div class="col-xs-2">
			<button type="button" id="btn-add-balance" class="icon-red" data-toggle="modal" data-target="#add-balance-modal">
				<i class="fa fa-dollar" aria-hidden="true"></i>
			</button>
		</div>
		<div class="col-xs-4">Rp. {{ number_format($user['balance']) }}</div>
	</div>
	<div class="row">
		<div class="col-xs-2"><span class="icon-red"><i class="fa fa-calendar-check-o" aria-hidden="true"></i></span></div>
		<div class="col-xs-4">{{ number_format($reports['success']) }} Success report</div>
		<div class="col-xs-2"><span class="icon-red"><i class="fa fa-calendar-times-o" aria-hidden="true"></i></span></div>
		<div class="col-xs-4">{{ number_format($reports['failed']) }} Failed report</div>
	</div>
	<div class="row">
		<div class="col-xs-2"><span class="icon-red"><i class="fa fa-map-marker" aria-hidden="true"></i></span></div>
		<div class="col-xs-10">{{ $location }}</div>
	</div>
</div>
<div class="row text-center">
	<a href="{{ route('survey', ['id' => $event['id']]) }}" class="btn btn-primary border-round"><i class="fa fa-plus-circle" aria-hidden="true"></i> REPORT</a>
</div>
@include('web.component.balance')
<hr/>
<div class="row text-center">
	<form method="POST" action="{{ route('post-logout') }}">
		@include('fields.geomap', ['field' => ['key' => 'map']])
		{{ csrf_field() }}
		<button type="submit" class="btn btn-primary border-round">LOGOUT</button>
	</form>
</div>

@section('scripts')
	<script>
		$(function() {
			let location = $.get("https://maps.googleapis.com/maps/api/geocode/json?latlng=-6.1992129,106.7842264&key=AIzaSyCNuAicyuHAcSTDb7fZVeJH-pU9Qns0KBk");
			console.log(location);
		});
	</script>
@append