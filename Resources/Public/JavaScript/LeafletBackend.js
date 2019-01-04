define(['jquery'], function ($) {
    'use strict';

    var LeafBE = {
        $element: null,
        $gLatitude: null,
        $gLongitude: null,
        $latitude: null,
        $longitude: null,
        $fieldLat: null,
        $fieldLon: null,
        $geoCodeUrl: null,
        $map: null
    };

    $(function () {
        LeafBE.$element = $('#location-map-container-a');
        LeafBE.$latitude = LeafBE.$element.attr('data-lat');
        LeafBE.$longitude = LeafBE.$element.attr('data-lon');
        LeafBE.$geoCodeUrl = LeafBE.$element.attr('data-geocodeurl');
        LeafBE.$fieldLat = LeafBE.$element.attr('data-namelat');
        LeafBE.$fieldLon = LeafBE.$element.attr('data-namelon');

        // add the container to display the map nice as an overlay
        $('body').append(
            '<div id="t3js-location-map-wrap">' +
            '<div class="t3js-location-map-title">' +
            '<div class="btn-group"><a href="#" class="btn btn-default" title="Close" id="t3js-ttaddress-close-map">' +
            '<img src="/typo3/sysext/core/Resources/Public/Icons/T3Icons/actions/actions-close.svg" width="16" height="16"></a>' +
            '<a class="btn btn-default" href="#" title="Import marker position to edit-form" id="t3js-ttaddress-import-position">' +
            'Import coordinates</a></div> Location Selector ' +
            '</div>' +
            '<div class="t3js-location-map-container" id="t3js-location-map-container">' +
            '</div>' +
            '</div>'
        );

        LeafBE.$element.on('click', function () {
            // generate map on first click and bind events
            if (LeafBE.$map == null) {
                // Geocode only first time
                // Geocode only if lat OR lon is empty AND wie have geocoding url
                // this saves geocoding calls
                if ((LeafBE.$latitude == null || LeafBE.$longitude == null) && LeafBE.$geoCodeUrl != null) {
                    function geocode(callback) {
                        var temp = $.getJSON(LeafBE.$geoCodeUrl, function (data) {
                            callback(data);
                        });
                    }
                    geocode(function (data) {
                        $.each(data[0], function (key, value) {
                            if (key == "lat") {
                                LeafBE.$latitude = value;
                            }
                            if (key == "lon") {
                                LeafBE.$longitude = value;
                                // call createmap after geocoding success
                                createMap();
                            }
                        });
                    });
                } else {
                    // @TODO fallback if really no lon/lat is available (gLatitude, gLongitude)
                    // coordinates of Kopenhagen are taken.
                    createMap();
                }
            }
            // display map if button clicked
            $('#t3js-location-map-wrap').addClass('active');
        });
        function createMap() {
            LeafBE.$map = L.map('t3js-location-map-container', {
                center: [LeafBE.$latitude, LeafBE.$longitude],
                zoom: 13
            });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(LeafBE.$map);

            var marker = L.marker([LeafBE.$latitude, LeafBE.$longitude], {
                draggable: true
            }).addTo(LeafBE.$map);

            marker.on('dragend', function (event) {
                var marker = event.target;
                var position = marker.getLatLng();
                console.log(position.lat);
                console.log(position.lng);
            });
            // import coordinates and close overlay
            $('#t3js-ttaddress-import-position').on('click', function () {
                // set visual coordinates
                $('input[data-formengine-input-name="' + LeafBE.$fieldLat + '"]').val(marker.getLatLng().lat);
                $('input[data-formengine-input-name="' + LeafBE.$fieldLon + '"]').val(marker.getLatLng().lng);
                // set hidden fields values
                $('input[name="' + LeafBE.$fieldLat + '"]').val(marker.getLatLng().lat);
                $('input[name="' + LeafBE.$fieldLon + '"]').val(marker.getLatLng().lng);
                //$('input[data-formengine-input-name="' + LeafBE.$fieldLat + '"]').val(marker.getLatLng().lat);
                $('#t3js-location-map-wrap').removeClass('active');
            });
            // close overlay without any further action
            $('#t3js-ttaddress-close-map').on('click', function () {
                $('#t3js-location-map-wrap').removeClass('active');
            });
        }

    });
    return LeafBE;
});
