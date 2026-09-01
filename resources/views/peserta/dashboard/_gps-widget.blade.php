@if($activeApp && $jamKerja && $jamKerja->latitude && $jamKerja->longitude && $activeApp->display_status != 'selesai' && $activeApp->display_status != 'belum mulai')
<div id="gps-status-banner" 
     data-lat="{{ $jamKerja->latitude }}" 
     data-lng="{{ $jamKerja->longitude }}" 
     data-radius="{{ $jamKerja->radius_absen ?? 100 }}"
     class="px-4 py-3 sm:px-6 sm:py-4 bg-teal-50/60 dark:bg-teal-950/40 border-t border-slate-200/80 dark:border-teal-900/60 flex items-center justify-between flex-wrap gap-3 transition-all duration-300">
    <div class="flex items-center gap-3 sm:gap-3.5 min-w-0">
        <div id="gps-icon-wrapper" class="w-11 h-11 rounded-2xl bg-teal-700 text-white flex items-center justify-center text-lg shadow-2xs transition-all duration-300 shrink-0">
            <i class="fas fa-satellite-dish animate-pulse"></i>
        </div>
        <div class="min-w-0">
            <h4 id="gps-title" class="text-xs font-black text-teal-900 dark:text-teal-300 uppercase tracking-wider">Mendeteksi Lokasi GPS Otomatis...</h4>
            <p id="gps-desc" class="text-xs text-teal-700 dark:text-teal-400 font-medium leading-snug">Mohon tunggu, sistem sedang memvalidasi posisi Anda dengan koordinat kantor.</p>
        </div>
    </div>
    <div id="gps-badge" class="min-h-[44px] px-3.5 py-2 sm:py-1.5 rounded-xl bg-white dark:bg-gray-800 text-teal-800 dark:text-teal-300 text-xs font-bold shadow-2xs border border-slate-200/80 dark:border-teal-800/60 flex items-center gap-1.5 shrink-0">
        <i class="fas fa-spinner fa-spin text-teal-700 dark:text-teal-400"></i> Mencari Sinyal...
    </div>
</div>
@endif

