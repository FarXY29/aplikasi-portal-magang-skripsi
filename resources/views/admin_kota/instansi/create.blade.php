<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-plus-circle text-teal-600 dark:text-teal-400"></i>
                {{ __('Tambah Instansi Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('admin.instansi.index') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-sm">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Daftar Instansi
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                
                <form action="{{ route('admin.instansi.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    
                    <div class="mb-10">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100 dark:border-gray-700">
                            <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/50 flex items-center justify-center text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-900/40">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Informasi Instansi</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Isi data profil dinas atau badan terkait.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Instansi <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                        <i class="fas fa-landmark"></i>
                                    </div>
                                    <input type="text" name="nama_dinas" value="{{ old('nama_dinas') }}" 
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-teal-500 focus:ring focus:ring-teal-500/20 transition shadow-sm text-sm" 
                                        placeholder="Contoh: Dinas Komunikasi dan Informatika" required>
                                </div>
                                @error('nama_dinas') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Kode Unit Kerja <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                        <i class="fas fa-barcode"></i>
                                    </div>
                                    <input type="text" name="kode_unit_kerja" value="{{ old('kode_unit_kerja') }}"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-teal-500 focus:ring focus:ring-teal-500/20 transition shadow-sm text-sm" 
                                        required>
                                </div>
                                @error('kode_unit_kerja') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Alamat Kantor <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 top-3 pointer-events-none text-gray-400 dark:text-gray-500">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <textarea name="alamat" rows="1"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-teal-500 focus:ring focus:ring-teal-500/20 transition shadow-sm text-sm" 
                                        placeholder="Jl. RE Martadinata No..." required>{{ old('alamat') }}</textarea>
                                </div>
                                @error('alamat') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2 bg-gray-50 dark:bg-gray-900 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Scan Tanda Tangan Kepala Dinas</label>
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 flex items-center justify-center text-gray-400 dark:text-gray-500">
                                        <i class="fas fa-file-signature text-xl"></i>
                                    </div>
                                    <div class="flex-grow">
                                        <input type="file" name="ttd_kepala" accept="image/png, image/jpeg"
                                            class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-teal-600 file:text-white hover:file:bg-teal-700 cursor-pointer focus:outline-none border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: PNG Transparan (Disarankan) atau JPG. Maks 2MB.</p>
                                    </div>
                                </div>
                                @error('ttd_kepala') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2 bg-blue-50/50 dark:bg-blue-950/30 p-4 rounded-xl border border-blue-100 dark:border-blue-900/40">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex justify-between">
                                    <span>Titik Koordinat & Radius Absensi (Geotagging)</span>
                                    <span class="text-blue-600 dark:text-blue-400 text-xs font-normal flex items-center"><i class="fas fa-mouse-pointer mr-1"></i> Klik pada peta untuk memindahkan pin</span>
                                </label>
                                
                                <div class="mb-4">
                                    <div id="map" class="border border-blue-200 dark:border-blue-900/50 shadow-inner" style="height: 350px; width: 100%; border-radius: 0.5rem; z-index: 1;"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-gray-500 text-xs font-bold">LAT</span>
                                            <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}"
                                                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-blue-500 focus:ring focus:ring-blue-500/20 text-sm font-mono" 
                                                placeholder="-3.319xxx" required>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-gray-500 text-xs font-bold">LNG</span>
                                            <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"
                                                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-blue-500 focus:ring focus:ring-blue-500/20 text-sm font-mono" 
                                                placeholder="114.590xxx" required>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-gray-500 text-xs font-bold"><i class="fas fa-circle-notch"></i></span>
                                            <input type="number" name="radius_absen" id="radius_absen" value="{{ old('radius_absen', 50) }}"
                                                class="w-full pl-10 pr-12 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-blue-500 focus:ring focus:ring-blue-500/20 text-sm font-mono" 
                                                placeholder="50" min="10" required>
                                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 dark:text-gray-400 text-xs font-bold pointer-events-none">Meter</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i> Area lingkaran pada peta menunjukkan batas peserta bisa melakukan absensi.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100 dark:border-gray-700">
                            <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-950/40 flex items-center justify-center text-orange-600 dark:text-orange-400 border border-orange-200 dark:border-orange-900/40">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Buat Akun Admin</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Akun ini digunakan instansi untuk login dan mengelola magang.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Email Login <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <input type="email" name="email_admin" value="{{ old('email_admin') }}"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-orange-500 focus:ring focus:ring-orange-500/20 transition shadow-sm text-sm" 
                                        placeholder="admin.instansi@banjarmasin.go.id" required>
                                </div>
                                @error('email_admin') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Password <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <input type="password" name="password_admin"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-orange-500 focus:ring focus:ring-orange-500/20 transition shadow-sm text-sm" 
                                        placeholder="Minimal 8 karakter" required>
                                </div>
                                @error('password_admin') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('admin.instansi.index') }}" class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-bold hover:bg-gray-200 dark:hover:bg-gray-600 transition text-sm">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 shadow-lg shadow-teal-500/20 transition transform active:scale-95 flex items-center text-sm">
                            <i class="fas fa-save mr-2"></i> Simpan Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
<style>
    /* Ensure the map container has a defined height */
    #map { height: 350px !important; width: 100%; border-radius: 0.5rem; z-index: 1; }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
<script>
    function initLeafletMap() {
        if (typeof L === 'undefined') {
            setTimeout(initLeafletMap, 100);
            return;
        }

        var mapContainer = document.getElementById('map');
        if(!mapContainer || mapContainer._leaflet_id) return; // Prevent double init

        // Default koordinat (Banjarmasin)
        var defaultLat = -3.316694;
        var defaultLng = 114.590111;
        
        var latInput = document.querySelector('input[name="latitude"]');
        var lngInput = document.querySelector('input[name="longitude"]');
        var radiusInput = document.querySelector('input[name="radius_absen"]');
        
        var initLat = latInput.value ? parseFloat(latInput.value) : defaultLat;
        var initLng = lngInput.value ? parseFloat(lngInput.value) : defaultLng;
        var initRadius = radiusInput.value ? parseInt(radiusInput.value) : 50;

        var map = L.map('map').setView([initLat, initLng], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        var marker = L.marker([initLat, initLng], {draggable: true}).addTo(map);
        var circle = L.circle([initLat, initLng], {
            color: '#0d9488',
            fillColor: '#14b8a6',
            fillOpacity: 0.2,
            radius: initRadius
        }).addTo(map);

        // Fix display issues inside containers
        setTimeout(function(){
            map.invalidateSize();
        }, 500);

        function updateInputs(lat, lng) {
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
        }

        function updateMapElements(lat, lng) {
            var latlng = new L.LatLng(lat, lng);
            marker.setLatLng(latlng);
            circle.setLatLng(latlng);
            map.panTo(latlng);
        }

        // Event saat marker di-drag
        marker.on('dragend', function (e) {
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
            if(this.value && lngInput.value) updateMapElements(this.value, lngInput.value);
        });
        lngInput.addEventListener('input', function() {
            if(this.value && latInput.value) updateMapElements(latInput.value, this.value);
        });

        // Event saat radius berubah
        radiusInput.addEventListener('input', function() {
            var r = parseInt(this.value);
            if(!isNaN(r) && r > 0) {
                circle.setRadius(r);
            }
        });
        
        // If creating new, try to get user's current location to center map
        if (!latInput.value && navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                updateInputs(position.coords.latitude, position.coords.longitude);
                updateMapElements(position.coords.latitude, position.coords.longitude);
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLeafletMap);
    } else {
        initLeafletMap();
    }
</script>
@endpush
</x-app-layout>