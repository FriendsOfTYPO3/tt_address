
define(['jquery'], function ($) {
	'use strict';

	var GoogleMaps = {
		$element: null,
		$fieldLat: null,
		$fieldLng: null
	};

	/**
	 * write data back to hidden field
	 *
	 * @param location
	 */
	GoogleMaps.writeCoordinates = function(location) {
		GoogleMaps.$fieldLat.val(location.lat());
		GoogleMaps.$fieldLng.val(location.lng());
	};

	$(function () {
		GoogleMaps.$element = $('.t3js-google-maps');
		GoogleMaps.$fieldLat = $('input[name="' + GoogleMaps.$element.data('fieldLat') + '"]');
		GoogleMaps.$fieldLng = $('input[name="' + GoogleMaps.$element.data('fieldLng') + '"]');

		var center = new google.maps.LatLng(50.98600, 14.01900);
		if (GoogleMaps.$fieldLat.val()) {
			center = new google.maps.LatLng(
				parseFloat(GoogleMaps.$fieldLat.val()),
				parseFloat(GoogleMaps.$fieldLng.val())
			);
		}
		var map = new google.maps.Map(GoogleMaps.$element[0], {
			center: center,
			zoom: 5,
			scrollwheel: false,
			streetViewControl: false,
			mapTypeControl: false
		});

		var marker = new google.maps.Marker({
			position: center,
			map: map,
			draggable: true
		});
		marker.addListener('dragend', function() {
			GoogleMaps.writeCoordinates(marker.getPosition());
		});

		map.addListener('click', function(e) {
			var clickedLocation = e.latLng;
			marker.setPosition(clickedLocation);
			GoogleMaps.writeCoordinates(clickedLocation);
		});

		// When tab is displayed...
		var tabId = GoogleMaps.$element.parents('.tab-pane').attr('id');
		$('a[href="#' + tabId + '"]').on('shown.bs.tab', function() {
			var center = map.getCenter();
			// fixes map display
			google.maps.event.trigger(map, 'resize');
			map.setCenter(center);
		});
	});

	return GoogleMaps;
});
