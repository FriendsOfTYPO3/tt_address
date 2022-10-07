define(['jquery', 'TYPO3/CMS/Backend/Icons', 'TYPO3/CMS/Backend/FormEngine', 'TYPO3/CMS/TtAddress/leaflet-core-1.4.0'], function ($, Icons, FormEngine) {
    'use strict';

    let LeafBE = {
        $element: null,
        $gLatitude: null,
        $gLongitude: null,
        $latitude: null,
        $longitude: null,
        $fieldLat: null,
        $fieldLon: null,
        $fieldLatActive: null,
        $geoCodeUrl: null,
        $geoCodeUrlShort: null,
        $tilesUrl: null,
        $tilesCopy: null,
        $zoomLevel: 13,
        $marker: null,
        $map: null,
        $iconClose: null
    };

    // Load icon via TYPO3 Icon-API and requireJS
    Icons.getIcon('actions-close', Icons.sizes.small, null, null).then(function(markup) {
        LeafBE['$iconClose']= markup;
    });

    LeafBE.init = function (element) {
        // basic variable initialisation, uses data vars on the trigger button
        LeafBE.$element = element;
        LeafBE.$labelTitle = LeafBE.$element.attr('data-label-title');
        LeafBE.$labelClose = LeafBE.$element.attr('data-label-close');
        LeafBE.$labelImport = LeafBE.$element.attr('data-label-import');
        LeafBE.$latitude = LeafBE.$element.attr('data-lat');
        LeafBE.$longitude = LeafBE.$element.attr('data-lon');
        LeafBE.$gLatitude = LeafBE.$element.attr('data-glat');
        LeafBE.$gLongitude = LeafBE.$element.attr('data-glon');
        LeafBE.$tilesUrl = LeafBE.$element.attr('data-tiles');
        LeafBE.$tilesCopy = LeafBE.$element.attr('data-copy');
        LeafBE.$geoCodeUrl = LeafBE.$element.attr('data-geocodeurl');
        LeafBE.$geoCodeUrlShort = LeafBE.$element.attr('data-geocodeurlshort');
        LeafBE.$fieldLat = LeafBE.$element.attr('data-namelat');
        LeafBE.$fieldLon = LeafBE.$element.attr('data-namelon');
        LeafBE.$fieldLatActive = LeafBE.$element.attr('data-namelat-active');

        // add the container to display the map as a nice overlay
        if (!$('#t3js-location-map-wrap').length) {
            LeafBE.addMapMarkup();
        }
    };

    LeafBE.addMapMarkup = function () {
        $('body').append(
            '<div id="t3js-location-map-wrap">' +
            '<div class="t3js-location-map-title">' +
            '<div class="btn-group"><a href="#" class="btn btn-icon btn-default" title="' + LeafBE.$labelClose + '" id="t3js-ttaddress-close-map">' +
            LeafBE.$iconClose +
            '</a>' +
            '<a class="btn btn-default" href="#" title="Import marker position to form" id="t3js-ttaddress-import-position">' +
            LeafBE.$labelImport +
            '</a></div>' +
            LeafBE.$labelTitle +
            '</div>' +
            '<div class="t3js-location-map-container" id="t3js-location-map-container">' +
            '</div>' +
            '</div>'
        );
    };

    LeafBE.createMap = function () {

        if (((!LeafBE.$latitude || !LeafBE.$longitude) || (LeafBE.$latitude == 0 && LeafBE.$longitude == 0)) && LeafBE.$geoCodeUrl != null) {
            LeafBE.geocode();
        }

        // The ultimate fallback: if one of the coordinates is empty, fallback to Kopenhagen.
        // Thank you Kaspar for TYPO3 and its great community! ;)
        if (LeafBE.$latitude == null || LeafBE.$longitude == null) {
            LeafBE.$latitude = LeafBE.$gLatitude;
            LeafBE.$longitude = LeafBE.$gLongitude;
            // set zoomlevel lower for faster navigation
            LeafBE.$zoomLevel = 4;
        }
        LeafBE.$map = L.map('t3js-location-map-container', {
            center: [LeafBE.$latitude, LeafBE.$longitude],
            zoom: LeafBE.$zoomLevel
        });
        L.tileLayer(LeafBE.$tilesUrl, {
            attribution: LeafBE.$tilesCopy
        }).addTo(LeafBE.$map);

        LeafBE.$marker = L.marker([LeafBE.$latitude, LeafBE.$longitude], {
            draggable: true
        }).addTo(LeafBE.$map);

        let position = LeafBE.$marker.getLatLng();

        LeafBE.$marker.on('dragend', function (event) {
            LeafBE.$marker = event.target;
            position = LeafBE.$marker.getLatLng();
        });
        LeafBE.$map.on('click', function (event) {
            LeafBE.$marker.setLatLng(event.latlng);
        });
        // import coordinates and close overlay
        $('#t3js-ttaddress-import-position').on('click', function () {
            // set visual coordinates
            $('input[data-formengine-input-name="' + LeafBE.$fieldLat + '"]').val(LeafBE.$marker.getLatLng().lat);
            $('input[data-formengine-input-name="' + LeafBE.$fieldLon + '"]').val(LeafBE.$marker.getLatLng().lng);
            // set hidden fields values
            $('input[name="' + LeafBE.$fieldLat + '"]').val(LeafBE.$marker.getLatLng().lat);
            $('input[name="' + LeafBE.$fieldLon + '"]').val(LeafBE.$marker.getLatLng().lng);
            // enable also latitude, if not already done by user.
            $('input[id="' + LeafBE.$fieldLatActive + '"]').parentsUntil('.form-group').removeClass('disabled');
            $('input[id="' + LeafBE.$fieldLatActive + '"]').prop('checked', true);

            // mark fields as changed for re-evaluation and revalidate the form,
            // this is e.g. needed when this wizard is used on inline elements
            FormEngine.Validation.markFieldAsChanged($('input[name="' + LeafBE.$fieldLat + '"]'));
            FormEngine.Validation.markFieldAsChanged($('input[name="' + LeafBE.$fieldLon + '"]'));
            FormEngine.Validation.validate();

            // close map after import of coordinates.
            $('#t3js-location-map-wrap').removeClass('active');
        });
        // close overlay without any further action
        $('#t3js-ttaddress-close-map').on('click', function () {
            $('#t3js-location-map-wrap').removeClass('active');
        });
    };

    LeafBE.geocode = function () {
        $.ajax({
            type: 'GET',
            url: LeafBE.$geoCodeUrl,
            async: false,
            dataType: 'json',
            success: function (data) {
                if (data.length == 0) {
                    $.ajax({
                        type: 'GET',
                        url: LeafBE.$geoCodeUrlShort,
                        async: false,
                        dataType: 'json',
                        success: function (data) {
                            if (data.length != 0) {
                                $.each(data[0], function (key, value) {
                                    if (key == "lat") {
                                        LeafBE.$latitude = value;
                                    }
                                    if (key == "lon") {
                                        LeafBE.$longitude = value;
                                    }
                                });
                            }
                        }
                    });
                } else {
                    $.each(data[0], function (key, value) {
                        if (key == "lat") {
                            LeafBE.$latitude = value;
                        }
                        if (key == "lon") {
                            LeafBE.$longitude = value;
                        }
                    });
                }
            }
        });
    };

    LeafBE.initializeEvents = function (element) {
        $(element).on('click', function () {
            if (LeafBE.$map !== null) {
                LeafBE.$map.remove();
                LeafBE.$map = null;
            }
            LeafBE.init($(this));
            LeafBE.createMap();
            $('#t3js-location-map-wrap').addClass('active');
        });
    };

    // reinit when form has changes, e.g. inline relations loaded using ajax
    LeafBE.reinitialize = FormEngine.reinitialize;
    FormEngine.reinitialize = function () {
        LeafBE.reinitialize();
        if ($('.locationMapWizard').length) {
            LeafBE.initializeEvents('.locationMapWizard');
        }
    };
    //LeafBE.addMapMarkup();
    LeafBE.initializeEvents('.locationMapWizard');
    return LeafBE;
});
