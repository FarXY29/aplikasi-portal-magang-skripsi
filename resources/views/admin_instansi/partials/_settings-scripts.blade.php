<script>
    function previewTtd(input) {
        const preview = document.getElementById('preview-ttd');
        const noTtdText = document.getElementById('no-ttd-text');
        const hoverText = document.getElementById('ttd-hover-text');
        
        // For the document preview at the right
        const docPreview = document.getElementById('doc-preview-ttd');
        const docNoTtd = document.getElementById('doc-no-ttd');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (noTtdText) noTtdText.classList.add('hidden');
                if (hoverText) {
                    hoverText.classList.remove('hidden');
                    hoverText.querySelector('span').innerText = 'Preview tanda tangan baru';
                }

                if (docPreview) {
                    docPreview.src = e.target.result;
                    docPreview.classList.remove('hidden');
                }
                if (docNoTtd) {
                    docNoTtd.classList.add('hidden');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@push('scripts')
<script>
    let adminMap = null;
    let adminMarker = null;
    let adminCircle = null;

    function ensureLeaflet(callback) {
        if (typeof L !== 'undefined') {
            callback();
            return;
        }
        if (window.SimagangVendor && window.SimagangVendor.leaflet) {
            window.SimagangVendor.leaflet().then(function(leafletLib) {
                window.L = leafletLib;
                callback();
            }).catch(function(err) {
                console.warn('Leaflet JS gagal dimuat:', err);
            });
            return;
        }
        console.warn('Vendor loader Leaflet tidak tersedia.');
    }

    function initAdminMap() {
        ensureLeaflet(function() {
            const mapContainer = document.getElementById('map-instansi');
            if (!mapContainer) return;

            if (adminMap) {
                try {
                    adminMap.remove();
                } catch (e) {}
                adminMap = null;
            }
            mapContainer._leaflet_id = null;

            const latInput = document.getElementById('input_latitude');
            const lngInput = document.getElementById('input_longitude');
            const radiusInput = document.getElementById('input_radius');
            const radiusSlider = document.getElementById('input_radius_slider');
            const radiusDisplay = document.getElementById('radius-display');

            if (!latInput || !lngInput) return;

            let initialLat = parseFloat(latInput.value) || -3.316694;
            let initialLng = parseFloat(lngInput.value) || 114.590111;
            let initialRadius = parseInt(radiusInput ? radiusInput.value : 100) || 100;

            try {
                adminMap = L.map('map-instansi', {
                    center: [initialLat, initialLng],
                    zoom: 16,
                    scrollWheelZoom: true
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(adminMap);

                adminMarker = L.marker([initialLat, initialLng], {
                    draggable: true
                }).addTo(adminMap);

                adminCircle = L.circle([initialLat, initialLng], {
                    color: '#dc2626',
                    fillColor: '#f87171',
                    fillOpacity: 0.25,
                    radius: initialRadius
                }).addTo(adminMap);

                function updatePosition(lat, lng) {
                    lat = parseFloat(Number(lat).toFixed(6));
                    lng = parseFloat(Number(lng).toFixed(6));
                    latInput.value = lat;
                    lngInput.value = lng;
                    if (adminMarker) adminMarker.setLatLng([lat, lng]);
                    if (adminCircle) adminCircle.setLatLng([lat, lng]);
                }

                adminMarker.on('dragend', function (e) {
                    const pos = adminMarker.getLatLng();
                    updatePosition(pos.lat, pos.lng);
                });

                adminMap.on('click', function (e) {
                    updatePosition(e.latlng.lat, e.latlng.lng);
                });

                latInput.addEventListener('input', function() {
                    const lat = parseFloat(latInput.value);
                    const lng = parseFloat(lngInput.value);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        if (adminMarker) adminMarker.setLatLng([lat, lng]);
                        if (adminCircle) adminCircle.setLatLng([lat, lng]);
                        if (adminMap) adminMap.setView([lat, lng], adminMap.getZoom());
                    }
                });

                lngInput.addEventListener('input', function() {
                    const lat = parseFloat(latInput.value);
                    const lng = parseFloat(lngInput.value);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        if (adminMarker) adminMarker.setLatLng([lat, lng]);
                        if (adminCircle) adminCircle.setLatLng([lat, lng]);
                        if (adminMap) adminMap.setView([lat, lng], adminMap.getZoom());
                    }
                });

                function updateRadius(val) {
                    val = parseInt(val) || 100;
                    val = Math.min(10000, Math.max(10, val));
                    if (radiusInput) radiusInput.value = val;
                    if (radiusSlider) radiusSlider.value = val;
                    if (radiusDisplay) radiusDisplay.innerText = val + ' Meter';
                    if (adminCircle) adminCircle.setRadius(val);
                }

                if (radiusInput) {
                    radiusInput.addEventListener('input', function() {
                        updateRadius(this.value);
                    });
                }

                if (radiusSlider) {
                    radiusSlider.addEventListener('input', function() {
                        updateRadius(this.value);
                    });
                }

                const refreshMapSize = function() {
                    if (adminMap) {
                        adminMap.invalidateSize();
                    }
                };

                [50, 150, 300, 600, 1000].forEach(function(delay) {
                    setTimeout(refreshMapSize, delay);
                });

                if (window.ResizeObserver) {
                    const resizeObserver = new ResizeObserver(function() {
                        refreshMapSize();
                    });
                    resizeObserver.observe(mapContainer);
                }
            } catch (err) {
                console.error('Error saat inisialisasi Leaflet map:', err);
            }
        });
    }

    function useCurrentLocation(e) {
        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung fitur geolokasi GPS.');
            return;
        }

        const btn = e ? e.currentTarget : document.querySelector('button[onclick*="useCurrentLocation"]');
        const originalText = btn ? btn.innerHTML : '';
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i> Mencari Titik GPS...';
            btn.disabled = true;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                const latInput = document.getElementById('input_latitude');
                const lngInput = document.getElementById('input_longitude');
                if (latInput) latInput.value = Number(lat).toFixed(6);
                if (lngInput) lngInput.value = Number(lng).toFixed(6);

                if (adminMarker && adminCircle && adminMap) {
                    adminMarker.setLatLng([lat, lng]);
                    adminCircle.setLatLng([lat, lng]);
                    adminMap.setView([lat, lng], 17);
                }

                if (btn) {
                    btn.innerHTML = '<i class="fas fa-check mr-1.5"></i> Titik GPS Ditemukan!';
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }, 2000);
                }
            },
            function(error) {
                alert('Gagal mengambil titik lokasi GPS Anda. Pastikan izin lokasi (Location Permission) diaktifkan di browser/HP Anda.');
                if (btn) {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    }

    function copyKioskUrl() {
        const input = document.getElementById('kiosk-public-url');
        if (input) {
            input.select();
            navigator.clipboard.writeText(input.value).then(() => {
                const textEl = document.getElementById('text-copy-kiosk');
                if (textEl) textEl.innerText = 'Tersalin!';
                setTimeout(() => {
                    if (textEl) textEl.innerText = 'Salin Tautan';
                }, 2000);
            }).catch(() => {
                document.execCommand('copy');
                const textEl = document.getElementById('text-copy-kiosk');
                if (textEl) textEl.innerText = 'Tersalin!';
                setTimeout(() => {
                    if (textEl) textEl.innerText = 'Salin Tautan';
                }, 2000);
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAdminMap);
    } else {
        initAdminMap();
    }
    document.addEventListener('turbo:load', initAdminMap);
</script>
@endpush
