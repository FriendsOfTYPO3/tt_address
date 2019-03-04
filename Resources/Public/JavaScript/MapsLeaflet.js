var map = L.map('ttaddress__map').setView([51.505, -0.09], 13);
var mapBounds = L.latLngBounds();
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
        '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    id: 'mapbox.streets'
}).addTo(map);


var popup = L.popup();

var markers = [];

function initAutocomplete() {

    var records = document.getElementById("ttaddress__records");
    for (var i = 0; i < records.childNodes.length; i++) {
        var item = records.childNodes[i];

        var marker = L.marker([item.getAttribute('data-lat'), item.getAttribute('data-lng')]).addTo(map)
            .bindPopup(document.getElementById('ttaddress__record-' + item.getAttribute('data-id')).innerHTML);
        markers.push(marker);
    }
    var group = new L.featureGroup(markers);

    map.fitBounds(group.getBounds().pad(Math.sqrt(2) / 2));
}

document.addEventListener("DOMContentLoaded", function () {
    initAutocomplete();
});
document.addEventListener('click', function (event) {
    if (!event.target.matches('.ttaddress__markerlink')) return;
    event.preventDefault();
    var element = event.target;
    markers[element.getAttribute('data-iteration-id')].openPopup();
}, false);
