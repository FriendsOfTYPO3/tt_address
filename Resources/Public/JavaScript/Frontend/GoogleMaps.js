function ttAddressGoogleMaps() {
    var obj = {};
    var mapId = document.getElementById("ttaddress_google_maps").getAttribute("data-mapId");

    obj.map = null;
    obj.markers = [];

    obj.run = function () {
        var mapOptions = {
            center: new google.maps.LatLng(48.3057664, 14.2873126),
            zoom: 11,
            maxZoom: 15,
            streetViewControl: false,
            fullscreenControl: false,
            mapId: mapId, // Map ID is required for advanced markers.
        };
        obj.map = new google.maps.Map(document.getElementById('ttaddress__map'), mapOptions);
        var infoWindow = new google.maps.InfoWindow();

        var bounds = new google.maps.LatLngBounds();

        var records = document.getElementById("ttaddress__records").children;
        for (var i = 0; i < records.length; i++) {
            var item = records[i];
            var recordId = item.getAttribute('data-id');
            var position = new google.maps.LatLng(item.getAttribute('data-lat'), item.getAttribute('data-lng'));

            const marker = new google.maps.marker.AdvancedMarkerElement({
                map: obj.map,
                position: position,
                gmpClickable: true,
                title: recordId,
            });

            google.maps.event.addListener(marker, 'click', function (e) {
                infoWindow.setContent(document.getElementById('ttaddress__record-' + marker.title).innerHTML);
                infoWindow.open(obj.map, this);

                var allLabels = document.querySelectorAll('.ttaddress__label');
                for (var i = 0; i < allLabels.length; i++) {
                    allLabels[i].classList.remove('active')
                }
                document.getElementById('ttaddress__label-' + marker.title).classList.add('active');
            });

            bounds.extend(position);
            obj.markers.push(marker);
        }
        obj.map.fitBounds(bounds);
    };

    obj.openMarker = function (markerId) {
        google.maps.event.trigger(obj.markers[markerId], 'click');
    };

    return obj;
}

document.addEventListener("DOMContentLoaded", function () {
    var ttAddressMapInstance = ttAddressGoogleMaps();
    ttAddressMapInstance.run();

    document.addEventListener('click', function (event) {
        if (!event.target.matches('.ttaddress__markerlink')) {
            return;
        }
        event.preventDefault();
        var element = event.target;
        ttAddressMapInstance.openMarker(element.getAttribute('data-iteration-id'));
    }, false);

});
