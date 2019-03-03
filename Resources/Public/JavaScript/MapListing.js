var map;
var markers = [];

function initAutocomplete() {
    const mapOptions = {
        center: new google.maps.LatLng(48.3057664, 14.2873126),
        zoom: 11,
        maxZoom: 15,
        streetViewControl: false,
        fullscreenControl: false
    };
    map = new google.maps.Map(document.getElementById('ttaddress-map'), mapOptions);
    infoWindow = new google.maps.InfoWindow();

    var bounds = new google.maps.LatLngBounds();

    var records = document.getElementById("ttaddress-map-records");
    for (var i = 0; i < records.childNodes.length; i++) {
        var item = records.childNodes[i];

        var marker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng(item.getAttribute('data-lat'), item.getAttribute('data-lng')),
            infowindow: infoWindow,
            recordId: item.getAttribute('data-id')
        });

        google.maps.event.addListener(marker, 'click', function (e) {
            infoWindow.setContent(document.getElementById('ttaddress-map-record-' + this.recordId).innerHTML);
            infoWindow.open(map, this);
        });

        bounds.extend(marker.getPosition());
        markers.push(marker);
    }
    map.fitBounds(bounds);
}

document.addEventListener("DOMContentLoaded", function () {
    initAutocomplete();
});
document.addEventListener('click', function (event) {
    if (!event.target.matches('.marker-link')) return;
    event.preventDefault();
    var element = event.target;
    google.maps.event.trigger(markers[element.getAttribute('data-iteration-id')], 'click');
}, false);
