function ttAddressLeaflet() {
    var obj = {};

    obj.map = null;
    obj.markers = [];

    obj.run = function () {
        obj.map = L.map('ttaddress__map').setView([51.505, -0.09], 13);
        obj.map.scrollWheelZoom.disable();
        var mapBounds = L.latLngBounds();
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox.streets'
        }).addTo(obj.map);

        var records = document.getElementById("ttaddress__records").children;
        for (var i = 0; i < records.length; i++) {
            var item = records[i];

            var marker = L.marker([item.getAttribute('data-lat'), item.getAttribute('data-lng')]).addTo(obj.map)
                .bindPopup(document.getElementById('ttaddress__record-' + item.getAttribute('data-id')).innerHTML);
            obj.markers.push(marker);
        }
        var group = new L.featureGroup(obj.markers);
        obj.map.fitBounds(group.getBounds());

        // Zoom out if zoom level is too high
        // var zoomLevel = obj.map.getZoom();
        // if (zoomLevel >= 10) {
        //     obj.map.setZoom(8);
        // }
    };

    obj.openMarker = function (markerId) {
        obj.markers[markerId].openPopup();
    };

    return obj;
}

function ttAddressOnload() {
    var ttAddressMapInstance = ttAddressLeaflet();
    ttAddressMapInstance.run();

    document.addEventListener('click', function (event) {
        if (!event.target.matches('.ttaddress__markerlink')) {
            return;
        }
        event.preventDefault();
        var element = event.target;
        ttAddressMapInstance.openMarker(parseInt(element.getAttribute('data-iteration-id')));
    }, false);
}

/** event listener on DOMContentLoaded does not work with scripts which are loaded async.
  * With TYPO3 this could e.g. happen with EXT:scriptmerger
  * Thus we listen only if document.readyState is loading, otherwise we can already fire as DOM is loaded already.
 **/
if (document.readyState === 'loading') {
    document.addEventListener("DOMContentLoaded", function () {
        ttAddressOnload();
    });
} else {
    ttAddressOnload();
}
