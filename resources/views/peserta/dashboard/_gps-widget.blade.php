@if($activeApp && $jamKerja && $jamKerja->latitude && $jamKerja->longitude && $activeApp->display_status != 'selesai' && $activeApp->display_status != 'belum mulai')
<div id="gps-status-banner" 
     data-lat="{{ $jamKerja->latitude }}" 
     data-lng="{{ $jamKerja->longitude }}" 
     data-radius="{{ $jamKerja->radius_absen ?? 100 }}"
     class="px-6 py-4 bg-blue-50/60 dark:bg-blue-950/40 border-t border-blue-100 dark:border-blue-900/60 flex items-center justify-between flex-wrap gap-3 transition-all duration-300">
    <div class="flex items-center gap-3.5">
        <div id="gps-icon-wrapper" class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-lg shadow-xs shadow-blue-500/20 transition-all duration-300">
            <i class="fas fa-satellite-dish animate-pulse"></i>
        </div>
        <div>
            <h4 id="gps-title" class="text-xs font-black text-blue-900 dark:text-blue-300 uppercase tracking-wider">Mendeteksi Lokasi GPS Otomatis...</h4>
            <p id="gps-desc" class="text-xs text-blue-700 dark:text-blue-400 font-medium">Mohon tunggu, sistem sedang memvalidasi posisi Anda dengan koordinat kantor.</p>
        </div>
    </div>
    <div id="gps-badge" class="px-3.5 py-1.5 rounded-xl bg-white dark:bg-gray-800 text-blue-700 dark:text-blue-300 text-xs font-bold shadow-xs border border-blue-200 dark:border-blue-800/60 flex items-center gap-1.5">
        <i class="fas fa-spinner fa-spin text-blue-600 dark:text-blue-400"></i> Mencari Sinyal...
    </div>
</div>
@endif
