<!-- Modal Pemindai Dynamic QR Presensi Kantor -->
<div id="modal-qr-scanner" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm hidden transition-all duration-300">
    <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-3xl shadow-2xl border border-slate-200 dark:border-gray-700 overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
        
        <!-- Header Modal -->
        <div class="px-5 py-4 bg-teal-50/80 dark:bg-teal-950/60 border-b border-teal-100 dark:border-teal-900/60 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-teal-600 text-white flex items-center justify-center text-base shadow-2xs">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div>
                    <h3 id="qr-modal-title" class="text-sm font-extrabold text-slate-900 dark:text-gray-100 leading-tight">
                        Pindai Dynamic QR Kantor
                    </h3>
                    <p class="text-[11px] text-teal-700 dark:text-teal-400 font-medium">Arahkan kamera ke layar Kiosk / TV kantor</p>
                </div>
            </div>
            <button type="button" onclick="closeQrScannerModal()" class="w-8 h-8 rounded-full bg-white dark:bg-gray-700 text-slate-400 hover:text-slate-700 dark:hover:text-white flex items-center justify-center text-xs shadow-2xs border border-slate-200 dark:border-gray-600 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body Modal: Camera Viewfinder & Status -->
        <div class="p-5 space-y-4 overflow-y-auto">
            
            <!-- Dual-Factor Status Indicators (GPS + Security Session) -->
            <div class="grid grid-cols-2 gap-2 text-[11px] font-bold">
                <div id="qr-gps-badge" class="px-3 py-2 rounded-xl bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 flex items-center gap-2 border border-slate-200 dark:border-gray-600">
                    <i class="fas fa-satellite-dish text-teal-600 animate-pulse" id="qr-gps-icon"></i>
                    <span id="qr-gps-text">Kunci GPS...</span>
                </div>
                <div id="qr-sec-badge" class="px-3 py-2 rounded-xl bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 flex items-center gap-2 border border-slate-200 dark:border-gray-600">
                    <i class="fas fa-shield-alt text-teal-600 animate-spin" id="qr-sec-icon"></i>
                    <span id="qr-sec-text">Sesi Keamanan...</span>
                </div>
            </div>

            <!-- Viewfinder Camera Area -->
            <div class="relative bg-slate-950 rounded-2xl overflow-hidden min-h-[260px] flex items-center justify-center border border-slate-800">
                <div id="qr-reader" class="w-full h-full min-h-[260px]"></div>
                
                <!-- Loading Overlay before camera ready -->
                <div id="qr-loading-overlay" class="absolute inset-0 bg-slate-950 flex flex-col items-center justify-center text-center p-4 text-white z-10">
                    <i class="fas fa-camera text-3xl text-teal-400 mb-2 animate-bounce"></i>
                    <p class="text-xs font-bold">Menyiapkan Kamera Pemindai...</p>
                    <p class="text-[10px] text-slate-400 mt-1 max-w-xs">Izinkan browser mengakses kamera perangkat Anda.</p>
                </div>

                <!-- Camera Error Display -->
                <div id="qr-error-overlay" class="absolute inset-0 bg-slate-950/95 hidden flex flex-col items-center justify-center text-center p-4 text-white z-20">
                    <div class="w-12 h-12 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center text-xl mb-2">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                    <h4 class="text-xs font-bold text-rose-300" id="qr-error-title">Akses Kamera Terkendala</h4>
                    <p class="text-[11px] text-slate-400 mt-1 max-w-xs" id="qr-error-desc">Pastikan izin kamera diizinkan di peramban Anda.</p>
                    <button type="button" onclick="startScanner()" class="mt-3 px-4 py-1.5 rounded-xl bg-teal-600 hover:bg-teal-500 text-white text-xs font-bold shadow-2xs">
                        <i class="fas fa-sync-alt mr-1"></i> Coba Lagi
                    </button>
                </div>
            </div>

            <!-- Hint text -->
            <p class="text-[11px] text-center text-slate-500 dark:text-gray-400 font-medium">
                Pastikan kode QR di layar kantor berada tepat di dalam bingkai kotak kamera.
            </p>

        </div>

        <!-- Footer Modal -->
        <div class="px-5 py-3 bg-slate-50 dark:bg-gray-900 border-t border-slate-100 dark:border-gray-700 flex items-center justify-between">
            <button type="button" onclick="closeQrScannerModal()" class="w-full py-2.5 bg-slate-200 hover:bg-slate-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-slate-700 dark:text-gray-200 text-xs font-bold rounded-xl transition">
                Batal
            </button>
        </div>

    </div>
</div>

