<div class="form-group {{ $errors->has('lat') || $errors->has('lng') ? 'has-error' : '' }}">
	<div id="map" class="gmap"></div>
	<input type="hidden" id="lat" name="lat" value="">
	<input type="hidden" id="lng" name="lng" value="">
</div>
<script>
	function initMap() {
		let map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: -6.1807822, lng: 106.8211985},
			zoom: 15
		});

		let marker = new google.maps.Marker({map: map});

		// Try HTML5 geolocation.
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				let pos = {
					lat: position.coords.latitude,
					lng: position.coords.longitude
				};

				document.getElementById('lat').value = pos.lat;
				document.getElementById('lng').value = pos.lng;

				marker.setPosition(pos);
				map.setCenter(pos);
			}, function() {
				handleLocationError(true, infoWindow, map.getCenter());
			});
		} else {
			// Browser doesn't support Geolocation
			handleLocationError(false, infoWindow, map.getCenter());
		}
	}

	function handleLocationError(browserHasGeolocation, infoWindow, pos) {
		infoWindow.setPosition(pos);
		infoWindow.setContent(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.');
	}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDAhnt_qpkhG5FlFACby9KAZUjMVZqWGo&callback=initMap"></script>