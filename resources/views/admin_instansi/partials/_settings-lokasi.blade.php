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
                        <input type="range" id="input_radius_slider" min="10" max="10000" step="10" value="{{ old('radius_absen', $instansi->radius_absen ?? 100) }}"
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
