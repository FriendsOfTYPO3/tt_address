function ttAddressGoogleMaps() {
    var obj = {};

    obj.map = null;
    obj.markers = [];

    obj.run = function () {
        const mapOptions = {
            center: new google.maps.LatLng(48.3057664, 14.2873126),
            zoom: 11,
            maxZoom: 15,
            streetViewControl: false,
            fullscreenControl: false
        };
        obj.map = new google.maps.Map(document.getElementById('ttaddress__map'), mapOptions);
        infoWindow = new google.maps.InfoWindow();

        var bounds = new google.maps.LatLngBounds();

        var records = document.getElementById("ttaddress__records");
        for (var i = 0; i < records.childNodes.length; i++) {
            var item = records.childNodes[i];

            var marker = new google.maps.Marker({
                map: obj.map,
                position: new google.maps.LatLng(item.getAttribute('data-lat'), item.getAttribute('data-lng')),
                infowindow: infoWindow,
                recordId: item.getAttribute('data-id')
            });

            google.maps.event.addListener(marker, 'click', function (e) {
                infoWindow.setContent(document.getElementById('ttaddress__record-' + this.recordId).innerHTML);
                infoWindow.open(obj.map, this);

                var allLabels = document.querySelectorAll('.ttaddress__label');
                for (var i = 0; i < allLabels.length; i++) {
                    allLabels[i].classList.remove('active')
                }
                document.getElementById('ttaddress__label-' + this.recordId).classList.add('active');

            });
            bounds.extend(marker.getPosition());
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
