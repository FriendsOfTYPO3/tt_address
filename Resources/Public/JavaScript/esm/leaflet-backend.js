import DocumentService from "@typo3/core/document-service.js";
import Icons from "@typo3/backend/icons.js";
import FormEngine from "@typo3/backend/form-engine.js";
import * as L from "@friendsoftypo3/tt-address/leaflet-src.esm.js";

class LeafletBackendModule {
  constructor() {
    this.element = null;
    this.gLatitude = null;
    this.gLongitude = null;
    this.latitude = null;
    this.longitude = null;
    this.fieldLat = null;
    this.fieldLon = null;
    this.fieldLatActive = null;
    this.geoCodeUrl = null;
    this.geoCodeUrlShort = null;
    this.tilesUrl = null;
    this.tilesCopy = null;
    this.zoomLevel = 13;
    this.marker = null;
    this.map = null;
    this.iconClose = null;
    Icons.getIcon("actions-close", Icons.sizes.small, null, null).then(
      (markup) => {
        this.iconClose = markup;
      },
    );

    DocumentService.ready().then(() => {
      const locationMapWizard = document.querySelector(".locationMapWizard");
      this.reinitialize = FormEngine.reinitialize;
      FormEngine.reinitialize = () => {
        this.reinitialize();
        if (locationMapWizard) {
          this.initialize(locationMapWizard);
        }
      };
      if (locationMapWizard) {
        this.initialize(locationMapWizard);
      }
    });
  }

  initialize(element) {
    element.addEventListener("click", () => {
      if (this.map !== null) {
        this.map.remove();
        this.map = null;
      }
      this.element = element;
      this.labelTitle = element.dataset.labelTitle;
      this.labelClose = element.dataset.labelClose;
      this.labelImport = element.dataset.labelImport;
      this.latitude = element.dataset.lat;
      this.longitude = element.dataset.lon;
      this.gLatitude = element.dataset.glat;
      this.gLongitude = element.dataset.glon;
      this.tilesUrl = element.dataset.tiles;
      this.tilesCopy = element.dataset.copy;
      this.geoCodeUrl = element.dataset.geocodeurl;
      this.geoCodeUrlShort = element.dataset.geocodeurlshort;
      this.fieldLat = element.dataset.namelat;
      this.fieldLon = element.dataset.namelon;
      this.fieldLatActive = element.dataset.namelatActive;

      // add the container to display the map as a nice overlay
      if (!document.getElementById("t3js-location-map-wrap")) {
        this.addMapMarkup();
      }

      this.createMap();
      document.getElementById("t3js-location-map-wrap").classList.add("active");
    });
  }

  addMapMarkup() {
    const locationMapDiv = document.createElement("div");
    locationMapDiv.innerHTML = `<div id="t3js-location-map-wrap">
        <div class="t3js-location-map-title">
        <div class="btn-group">
          <a href="#" class="btn btn-icon btn-default" title="${this.labelClose}" id="t3js-ttaddress-close-map">${this.iconClose}</a>
          <a class="btn btn-default" href="#" title="Import marker position to form" id="t3js-ttaddress-import-position">${this.labelImport}</a></div>${this.labelTitle}
        </div>
        <div class="t3js-location-map-container" id="t3js-location-map-container"></div>
      </div>`;
    document.body.appendChild(locationMapDiv);
  }

  createMap() {
    if (
      (!this.latitude ||
        !this.longitude ||
        (this.latitude == 0 && this.longitude == 0)) &&
      this.geoCodeUrl != null
    ) {
      this.geocode();
    }

    // The ultimate fallback: if one of the coordinates is empty, fallback to Kopenhagen.
    // Thank you Kaspar for TYPO3 and its great community! ;)
    if (this.latitude == null || this.longitude == null) {
      this.latitude = this.gLatitude;
      this.longitude = this.gLongitude;
      // set zoomlevel lower for faster navigation
      this.zoomLevel = 4;
    }
    this.map = L.map("t3js-location-map-container", {
      center: [this.latitude, this.longitude],
      zoom: this.zoomLevel,
    });
    L.tileLayer(this.tilesUrl, {
      attribution: this.tilesCopy,
    }).addTo(this.map);

    this.marker = L.marker([this.latitude, this.longitude], {
      draggable: true,
    }).addTo(this.map);

    let position = this.marker.getLatLng();

    this.marker.addEventListener("dragend", (event) => {
      this.marker = event.target;
      position = this.marker.getLatLng();
    });
    this.map.addEventListener("click", (event) => {
      this.marker.setLatLng(event.latlng);
    });
    // import coordinates and close overlay
    document
      .getElementById("t3js-ttaddress-import-position")
      .addEventListener("click", () => {
        // set visual coordinates
        const latField = document.querySelector(
          `input[data-formengine-input-name="${this.fieldLat}"`,
        );
        if (latField) {
          latField.value = this.marker.getLatLng().lat;
        }
        const lngField = document.querySelector(
          `input[data-formengine-input-name="${this.fieldLon}"`,
        );
        if (lngField) {
          lngField.value = this.marker.getLatLng().lng;
        }
        // set hidden fields values
        const latHiddenField = document.querySelector(
          `input[name="${this.fieldLat}"`,
        );
        if (latHiddenField) {
          latHiddenField.value = this.marker.getLatLng().lat;
        }
        const lngHiddenField = document.querySelector(
          `input[name="${this.fieldLon}"`,
        );
        if (lngHiddenField) {
          lngHiddenField.value = this.marker.getLatLng().lng;
        }
        // enable also latitude, if not already done by user.
        // Find the input element by its ID
        const inputElement = document.querySelector(
          `input[id="${this.fieldLatActive}"]`,
        );

        if (inputElement) {
          // Traverse ancestors until an element with the class "form-group" is found
          let currentElement = inputElement.parentElement;
          while (
            currentElement &&
            !currentElement.classList.contains("form-group")
          ) {
            // Remove the "disabled" class from the current element
            currentElement.classList.remove("disabled");
            currentElement = currentElement.parentElement;
          }
          inputElement.checked = true;
        }

        // mark fields as changed for re-evaluation and revalidate the form,
        // this is e.g. needed when this wizard is used on inline elements
        FormEngine.Validation.markFieldAsChanged(latHiddenField);
        FormEngine.Validation.markFieldAsChanged(lngHiddenField);
        FormEngine.Validation.validate();

        // close map after import of coordinates.
        this.closeLocationMap();
      });

    document
      .getElementById("t3js-ttaddress-close-map")
      .addEventListener("click", () => {
        // close overlay without any further action
        this.closeLocationMap();
      });
  }

  closeLocationMap() {
    document
      .getElementById("t3js-location-map-wrap")
      .classList.remove("active");
  }

  async geocode() {
    try {
      let response = await fetch(this.geoCodeUrl);
      let data = await response.json();

      if (data.length === 0) {
        response = await fetch(this.geoCodeUrlShort);
        data = await response.json();
      }

      if (data.length !== 0) {
        const firstResult = data[0];
        if ("lat" in firstResult) {
          this.latitude = firstResult.lat;
        }
        if ("lon" in firstResult) {
          this.longitude = firstResult.lon;
        }
      }
    } catch (error) {
      console.error("Error fetching geo data:", error);
    }
  }
}

export default new LeafletBackendModule();

