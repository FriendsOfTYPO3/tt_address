function ttAddressLeaflet() {
    var obj = {};

    obj.map = null;
    obj.markers = [];

    obj.run = function () {
        obj.map = L.map('ttaddress__map').setView([51.505, -0.09], 13);
        var mapBounds = L.latLngBounds();
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox.streets'
        }).addTo(obj.map);

        var records = document.getElementById("ttaddress__records");
        for (var i = 0; i < records.childNodes.length; i++) {
            var item = records.childNodes[i];

            var marker = L.marker([item.getAttribute('data-lat'), item.getAttribute('data-lng')]).addTo(obj.map)
                .bindPopup(document.getElementById('ttaddress__record-' + item.getAttribute('data-id')).innerHTML);
            obj.markers.push(marker);
        }
        var group = new L.featureGroup(obj.markers);

        obj.map.fitBounds(group.getBounds());
    };

    obj.openMarker = function (markerId) {
        obj.markers[markerId].openPopup();
    };

    return obj;
}

document.addEventListener("DOMContentLoaded", function () {
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

});
