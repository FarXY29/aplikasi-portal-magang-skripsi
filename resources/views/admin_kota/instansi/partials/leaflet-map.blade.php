{{--
    Partial: Inisialisasi peta Leaflet untuk form Instansi (create & edit).
    Berisi CSS + pengaturan marker/circle/geotagging.
    Asumsi markup host menyediakan:
      - <div id="map"></div>
      - input[name="latitude"], input[name="longitude"], input[name="radius_absen"]
--}}

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
<style>
    /* Ensure the map container has a defined height */
    #map { height: 250px !important; width: 100%; border-radius: 0.5rem; z-index: 1; }
    @media (min-width: 640px) { #map { height: 350px !important; } }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
<script>
    function ensureLeaflet(callback, maxWaitMs = 5000) {
        if (typeof L !== 'undefined') {
            callback();
            return;
        }
        const startTime = Date.now();
        const interval = setInterval(function() {
            if (typeof L !== 'undefined') {
                clearInterval(interval);
                callback();
            } else if (Date.now() - startTime > maxWaitMs) {
                clearInterval(interval);
                console.warn('Leaflet JS gagal dimuat dalam waktu 5 detik.');
            }
        }, 50);
    }

    function initLeafletMap() {
        ensureLeaflet(function() {
            var mapContainer = document.getElementById('map');
            if (!mapContainer) return;
            if (mapContainer._leaflet_id && window.instansiMap) {
                window.instansiMap.invalidateSize();
                return;
            }
            if (window.instansiMap) {
                try {
                    window.instansiMap.remove();
                } catch (e) {}
                window.instansiMap = null;
            }
            mapContainer._leaflet_id = null;

            // Default koordinat (Banjarmasin)
            var defaultLat = -3.316694;
            var defaultLng = 114.590111;

            var latInput = document.querySelector('input[name="latitude"]');
            var lngInput = document.querySelector('input[name="longitude"]');
            var radiusInput = document.querySelector('input[name="radius_absen"]');

            var initLat = latInput.value ? parseFloat(latInput.value) : defaultLat;
            var initLng = lngInput.value ? parseFloat(lngInput.value) : defaultLng;
            var initRadius = radiusInput.value ? parseInt(radiusInput.value) : 50;

            window.instansiMap = L.map('map').setView([initLat, initLng], 15);
            var map = window.instansiMap;

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            var marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);
            var circle = L.circle([initLat, initLng], {
                color: '#0d9488',
                fillColor: '#14b8a6',
                fillOpacity: 0.2,
                radius: initRadius
            }).addTo(map);

            // Fix display issues inside containers
            setTimeout(function() {
                map.invalidateSize();
            }, 500);

            function updateInputs(lat, lng) {
                latInput.value = Number(lat).toFixed(6);
                lngInput.value = Number(lng).toFixed(6);
            }

            function updateMapElements(lat, lng) {
                var latlng = new L.LatLng(lat, lng);
                marker.setLatLng(latlng);
                circle.setLatLng(latlng);
                map.panTo(latlng);
            }

            // Event saat marker di-drag
            marker.on('dragend', function(e) {
                var latlng = marker.getLatLng();
                updateInputs(latlng.lat, latlng.lng);
                circle.setLatLng(latlng);
            });

            // Event saat peta di-klik
            map.on('click', function(e) {
                updateInputs(e.latlng.lat, e.latlng.lng);
                updateMapElements(e.latlng.lat, e.latlng.lng);
            });

            // Event saat input manual berubah
            latInput.addEventListener('input', function() {
                if (this.value && lngInput.value) updateMapElements(this.value, lngInput.value);
            });
            lngInput.addEventListener('input', function() {
                if (this.value && latInput.value) updateMapElements(latInput.value, this.value);
            });

            // Event saat radius berubah
            radiusInput.addEventListener('input', function() {
                var r = parseInt(this.value);
                if (!isNaN(r) && r > 0) {
                    circle.setRadius(r);
                }
            });

            [50, 150, 300, 600, 1000].forEach(function(delay) {
                setTimeout(function() {
                    if (window.instansiMap) window.instansiMap.invalidateSize();
                }, delay);
            });

            if (window.ResizeObserver) {
                var ro = new ResizeObserver(function() {
                    if (window.instansiMap) window.instansiMap.invalidateSize();
                });
                ro.observe(mapContainer);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLeafletMap);
    } else {
        initLeafletMap();
    }
    window.addEventListener('load', initLeafletMap);
    document.addEventListener('turbo:load', initLeafletMap);
    document.addEventListener('turbo:render', initLeafletMap);
    document.addEventListener('livewire:navigated', initLeafletMap);
</script>
@endpush
