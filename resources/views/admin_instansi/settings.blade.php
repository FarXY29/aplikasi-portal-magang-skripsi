<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-cog text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Pengaturan Instansi') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div>
                <a href="{{ route('dinas.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" class="mb-4">
                    {{ session('success') }}
                </x-ui.alert>
            @endif

            {{-- CARD 1: JAM OPERASIONAL ABSENSI --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/60 flex items-center justify-center text-blue-600 dark:text-blue-400 text-xl border border-blue-100 dark:border-blue-900/50 flex-shrink-0">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Jam Operasional Absensi</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tentukan batas waktu pembukaan absen datang dan pulang bagi peserta magang.</p>
                    </div>
                </div>
                
                <div class="p-6 sm:p-8">
                    <form action="{{ route('dinas.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Jam Buka Absen Datang</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </span>
                                    <input type="time" name="jam_mulai_masuk" value="{{ $instansi->jam_mulai_masuk }}" 
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 text-sm font-bold shadow-xs [color-scheme:dark]">
                                </div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">*Tombol absen datang aktif setelah jam ini.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Jam Buka Absen Pulang</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </span>
                                    <input type="time" name="jam_mulai_pulang" value="{{ $instansi->jam_mulai_pulang }}" 
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 text-sm font-bold shadow-xs [color-scheme:dark]">
                                </div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">*Tombol absen pulang aktif setelah jam ini.</p>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-md transition flex items-center active:scale-95">
                                <i class="fas fa-save mr-2 text-sm"></i> Simpan Jam Kerja
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CARD 2: PENGATURAN LOKASI & RADIUS ABSENSI (GEOFENCING) --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-red-950/60 flex items-center justify-center text-red-600 dark:text-red-400 text-xl border border-red-100 dark:border-red-900/50 flex-shrink-0">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Lokasi Kantor & Radius Absensi</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tentukan titik koordinat kantor dan batas radius toleransi absensi (Geofencing).</p>
                        </div>
                    </div>
                    <button type="button" onclick="useCurrentLocation(event)" class="px-4 py-2 bg-red-50 dark:bg-red-950/40 hover:bg-red-100 dark:hover:bg-red-900/60 text-red-700 dark:text-red-300 rounded-xl font-bold text-xs border border-red-200 dark:border-red-800/60 transition flex items-center shadow-xs">
                        <i class="fas fa-crosshairs mr-1.5"></i> Gunakan Lokasi Saya (GPS)
                    </button>
                </div>
                
                <div class="p-6 sm:p-8">
                    <form action="{{ route('dinas.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        {{-- Peta Leaflet --}}
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2 flex items-center justify-between flex-wrap gap-2">
                                <span>Peta Titik Kantor Instansi</span>
                                <span class="text-xs font-normal text-gray-500 dark:text-gray-400 lowercase italic"><i class="fas fa-info-circle text-teal-500 mr-1"></i> Klik atau geser penanda (marker) pada peta untuk mengubah koordinat</span>
                            </label>
                            <div id="map-instansi" class="w-full h-80 sm:h-96 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-inner z-10 relative"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Latitude (Garis Lintang)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                        <i class="fas fa-globe"></i>
                                    </span>
                                    <input type="number" step="any" id="input_latitude" name="latitude" value="{{ old('latitude', $instansi->latitude ?? '-3.316694') }}" required
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-red-500 focus:ring-red-500 font-mono text-xs font-bold shadow-xs">
                                </div>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">Koordinat Lintang Kantor</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Longitude (Garis Bujur)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                        <i class="fas fa-globe"></i>
                                    </span>
                                    <input type="number" step="any" id="input_longitude" name="longitude" value="{{ old('longitude', $instansi->longitude ?? '114.590111') }}" required
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-red-500 focus:ring-red-500 font-mono text-xs font-bold shadow-xs">
                                </div>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">Koordinat Bujur Kantor</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2 flex items-center justify-between">
                                    <span>Radius Toleransi</span>
                                    <span class="text-xs font-black text-red-700 dark:text-red-300 font-mono bg-red-50 dark:bg-red-950/60 px-2 py-0.5 rounded-md border border-red-200 dark:border-red-800/60" id="radius-display">{{ old('radius_absen', $instansi->radius_absen ?? 100) }} Meter</span>
                                </label>
                                <div class="relative flex items-center gap-3 pt-1">
                                    <input type="range" id="input_radius_slider" min="10" max="2000" step="10" value="{{ old('radius_absen', $instansi->radius_absen ?? 100) }}"
                                        class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer accent-red-600">
                                    <input type="number" id="input_radius" name="radius_absen" min="10" max="10000" value="{{ old('radius_absen', $instansi->radius_absen ?? 100) }}" required
                                        class="w-24 px-3 py-1.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-red-500 focus:ring-red-500 font-mono text-xs text-center font-bold shadow-xs">
                                </div>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">Batas jarak maksimal peserta magang untuk absen.</p>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-md transition flex items-center active:scale-95">
                                <i class="fas fa-save mr-2 text-sm"></i> Simpan Lokasi & Radius
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- SECTION 3: DATA PENANDATANGAN DOKUMEN & PREVIEW TAMPILAN SIDE-BY-SIDE --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                {{-- CARD 3: DATA PENANDATANGAN DOKUMEN --}}
                <div class="lg:col-span-7 bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/60 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl border border-teal-100 dark:border-teal-900/50 flex-shrink-0">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Data Penandatangan Dokumen</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Digunakan untuk legalisasi otomatis pada Sertifikat dan Transkrip Nilai.</p>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8">
                        <div class="bg-teal-50/60 dark:bg-teal-950/40 border border-teal-200 dark:border-teal-800/60 rounded-2xl p-4 mb-6 flex items-start gap-3">
                            <i class="fas fa-info-circle text-teal-600 dark:text-teal-400 mt-0.5 text-base flex-shrink-0"></i>
                            <p class="text-xs text-teal-800 dark:text-teal-300 leading-relaxed font-medium">
                                Data pejabat di bawah ini akan otomatis tercetak pada bagian tanda tangan dokumen resmi (Sertifikat & Transkrip). Pastikan data sudah sesuai.
                            </p>
                        </div>

                        <form action="{{ route('dinas.pejabat.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Jabatan Pejabat</label>
                                        <input type="text" name="jabatan_pejabat" value="{{ old('jabatan_pejabat', $instansi->jabatan_pejabat) }}"
                                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-xs font-bold shadow-xs"
                                            placeholder="Contoh: Kepala Dinas Kominfo">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Nama Pejabat</label>
                                        <input type="text" name="nama_pejabat" value="{{ old('nama_pejabat', $instansi->nama_pejabat) }}"
                                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-xs font-bold shadow-xs"
                                            placeholder="Nama Lengkap beserta gelar">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">NIP Pejabat</label>
                                        <input type="text" name="nip_pejabat" value="{{ old('nip_pejabat', $instansi->nip_pejabat) }}"
                                            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-xs font-mono font-bold shadow-xs"
                                            placeholder="19xxxxxxxx xxx x xxx">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Scan Tanda Tangan (PNG Transparan)</label>
                                    
                                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900 h-36 flex items-center justify-center mb-3 relative overflow-hidden group">
                                        <img id="preview-ttd" src="{{ $instansi->ttd_kepala ? asset('storage/' . $instansi->ttd_kepala) : '' }}" class="h-24 object-contain z-10 {{ $instansi->ttd_kepala ? '' : 'hidden' }}">
                                        
                                        <div id="no-ttd-text" class="text-center text-gray-400 dark:text-gray-500 {{ $instansi->ttd_kepala ? 'hidden' : '' }}">
                                            <i class="fas fa-image text-2xl mb-1"></i>
                                            <p class="text-xs font-semibold">Belum ada tanda tangan</p>
                                        </div>

                                        <div id="ttd-hover-text" class="absolute inset-0 bg-white/90 dark:bg-gray-800/90 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 z-20 {{ $instansi->ttd_kepala ? '' : 'hidden' }}">
                                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Tanda tangan saat ini</span>
                                        </div>
                                    </div>

                                    <input type="file" id="ttd_kepala_input" name="ttd_kepala" accept="image/png" onchange="previewTtd(this)"
                                        class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 dark:file:bg-teal-950/60 file:text-teal-700 dark:file:text-teal-300 hover:file:bg-teal-100 cursor-pointer border border-gray-300 dark:border-gray-700 rounded-xl p-1 bg-white dark:bg-gray-900">
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1.5 ml-1">
                                        *Format wajib <strong>PNG Transparan</strong> agar hasil cetak rapi.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                                <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-md transition flex items-center active:scale-95">
                                    <i class="fas fa-check-circle mr-2 text-sm"></i> Simpan Data Pejabat
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- PREVIEW TAMPILAN DOKUMEN (SEBELAH KANAN - DIPERBESAR) --}}
                <div class="lg:col-span-5 bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-8">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 flex items-center justify-center text-amber-600 dark:text-amber-400 text-xl border border-amber-100 dark:border-amber-900/50 flex-shrink-0">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Preview Tampilan Dokumen</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Hasil cetak tanda tangan pada berkas resmi.</p>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8">
                        <div class="bg-gray-50 dark:bg-gray-900 p-6 sm:p-8 rounded-2xl border border-gray-200 dark:border-gray-700 text-left shadow-inner space-y-2">
                            <div>
                                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Mengetahui,</p>
                                <p class="text-base font-bold text-gray-800 dark:text-gray-100 mt-0.5">{{ $instansi->jabatan_pejabat ?? '[Jabatan Kosong]' }}</p>
                            </div>
                            
                            <div class="h-32 sm:h-36 flex items-center justify-start my-3 relative">
                                <img id="doc-preview-ttd" src="{{ $instansi->ttd_kepala ? asset('storage/' . $instansi->ttd_kepala) : '' }}" class="h-28 sm:h-32 object-contain {{ $instansi->ttd_kepala ? '' : 'hidden' }}">
                                <div id="doc-no-ttd" class="w-full h-full border-2 border-dashed border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800/60 flex items-center justify-center text-sm text-gray-400 dark:text-gray-500 italic rounded-2xl {{ $instansi->ttd_kepala ? 'hidden' : '' }}">
                                    Area Tanda Tangan
                                </div>
                            </div>

                            <div>
                                <p class="text-base font-bold text-gray-900 dark:text-gray-100 border-b-2 border-gray-900 dark:border-gray-200 inline-block pb-0.5 mb-1">
                                    {{ $instansi->nama_pejabat ?? '[Nama Pejabat Kosong]' }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold">NIP. {{ $instansi->nip_pejabat ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let adminMap = null;
        let adminMarker = null;
        let adminCircle = null;

        function initAdminMap() {
            const mapContainer = document.getElementById('map-instansi');
            if (!mapContainer) return;
            if (mapContainer._leaflet_id && adminMap) {
                adminMap.remove();
                mapContainer._leaflet_id = null;
            }

            const latInput = document.getElementById('input_latitude');
            const lngInput = document.getElementById('input_longitude');
            const radiusInput = document.getElementById('input_radius');
            const radiusSlider = document.getElementById('input_radius_slider');
            const radiusDisplay = document.getElementById('radius-display');

            let initialLat = parseFloat(latInput.value) || -3.316694;
            let initialLng = parseFloat(lngInput.value) || 114.590111;
            let initialRadius = parseInt(radiusInput.value) || 100;

            adminMap = L.map('map-instansi').setView([initialLat, initialLng], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
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
                lat = parseFloat(lat.toFixed(6));
                lng = parseFloat(lng.toFixed(6));
                latInput.value = lat;
                lngInput.value = lng;
                adminMarker.setLatLng([lat, lng]);
                adminCircle.setLatLng([lat, lng]);
            }

            adminMarker.on('dragend', function (e) {
                const pos = adminMarker.getLatLng();
                updatePosition(pos.lat, pos.lng);
            });

            adminMap.on('click', function (e) {
                updatePosition(e.latlng.lat, e.latlng.lng);
            });

            latInput.addEventListener('change', function() {
                const lat = parseFloat(latInput.value) || initialLat;
                const lng = parseFloat(lngInput.value) || initialLng;
                updatePosition(lat, lng);
                adminMap.setView([lat, lng], adminMap.getZoom());
            });

            lngInput.addEventListener('change', function() {
                const lat = parseFloat(latInput.value) || initialLat;
                const lng = parseFloat(lngInput.value) || initialLng;
                updatePosition(lat, lng);
                adminMap.setView([lat, lng], adminMap.getZoom());
            });

            function updateRadius(val) {
                val = parseInt(val) || 100;
                radiusInput.value = val;
                if (radiusSlider) radiusSlider.value = val <= 2000 ? val : 2000;
                if (radiusDisplay) radiusDisplay.innerText = val + ' Meter';
                if (adminCircle) adminCircle.setRadius(val);
            }

            radiusInput.addEventListener('input', function() {
                updateRadius(this.value);
            });

            if (radiusSlider) {
                radiusSlider.addEventListener('input', function() {
                    updateRadius(this.value);
                });
            }

            setTimeout(() => {
                if (adminMap) adminMap.invalidateSize();
            }, 300);
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
                    if (latInput) latInput.value = lat.toFixed(6);
                    if (lngInput) lngInput.value = lng.toFixed(6);

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

        document.addEventListener('DOMContentLoaded', initAdminMap);
        document.addEventListener('turbo:load', initAdminMap);
    </script>
    @endpush
</x-app-layout>