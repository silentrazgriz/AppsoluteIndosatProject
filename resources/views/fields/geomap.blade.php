<div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
	<div id="{{ $field['key'] }}" class="gmap"></div>
	@if ($errors->has('location'))
		<span class="help-block"><strong>{{ $errors->first('location') }}</strong></span>
	@endif
	<input type="hidden" id="location" name="location" value="">
</div>
@section('scripts')
	<script>
		function initMap() {
			let map = new google.maps.Map(document.getElementById('{{ $field['key'] }}'), {
				center: {lat: -6.1807822, lng: 106.8211985},
				zoom: 15
			});

			let marker = new google.maps.Marker({map: map});

			// Try HTML5 geolocation.
			if (navigator.geolocation) {
				let pos = {lat: -6.1807822, lng: 106.8211985};
				navigator.geolocation.getCurrentPosition(function(position) {
					pos = {
						lat: position.coords.latitude,
						lng: position.coords.longitude
					};

					setMarker(map, marker, pos);
				}, function(e) {
					let latLong;
					$.getJSON("http://ipinfo.io", function(ipinfo){
						latLong = ipinfo.loc.split(",");
						pos = {
							lat: parseFloat(latLong[0]),
							lng: parseFloat(latLong[1])
						};

						setMarker(map, marker, pos);
					});
					//console.log(e);
					console.log('Geolocation service failed, using ipinfo.io');
				});
			} else {
				// Browser doesn't support Geolocation
				console.log('Browser doesn\'t support geolocation');
			}
		}

		function setMarker(map, marker, pos) {
			document.getElementById('location').value = JSON.stringify(pos);

			marker.setPosition(pos);
			map.setCenter(pos);
		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDAhnt_qpkhG5FlFACby9KAZUjMVZqWGo&callback=initMap"></script>
@append