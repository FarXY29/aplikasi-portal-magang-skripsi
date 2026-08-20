<x-guest-layout>
    <div class="py-8 sm:py-12 px-4 sm:px-6 lg:px-8 w-full max-w-4xl mx-auto" x-data="qrScannerManager()" x-init="init()">
        
        <!-- Navigation Back -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('home') }}" class="group inline-flex items-center text-xs sm:text-sm font-bold text-slate-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                <div class="w-8 h-8 rounded-xl bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 flex items-center justify-center mr-2.5 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-2xs transition">
                    <i class="fas fa-arrow-left text-xs text-slate-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                </div>
                Kembali ke Beranda
            </a>

            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-50 dark:bg-teal-950/60 border border-teal-200/60 dark:border-teal-800/60 text-teal-700 dark:text-teal-300 text-[11px] font-extrabold uppercase tracking-wider">
                <i class="fas fa-shield-halved text-xs"></i> Portal Verifikasi Resmi
            </span>
        </div>

        <!-- Main Card Container -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl border border-slate-200/80 dark:border-gray-700 overflow-hidden">
            
            <!-- Card Header -->
            <div class="p-6 sm:p-8 bg-gradient-to-b from-teal-50/50 via-transparent to-transparent dark:from-teal-950/20 text-center border-b border-slate-100 dark:border-gray-700/80">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center mx-auto mb-4 shadow-lg shadow-teal-500/25">
                    <i class="fas fa-qrcode text-2xl"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-gray-100 tracking-tight font-display">
                    Verifikasi Keabsahan Sertifikat & ID Card
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-slate-500 dark:text-gray-400 max-w-md mx-auto leading-relaxed font-medium">
                    Pindai kode QR pada ID Card peserta magang, sertifikat resmi, unggah berkas, atau cari berdasarkan nomor registrasi Pemerintah Kota Banjarmasin.
                </p>

                <!-- Dual-Tab Navigation Buttons -->
                <div class="mt-6 inline-flex p-1.5 rounded-2xl bg-slate-100 dark:bg-gray-900/90 border border-slate-200 dark:border-gray-700/80 max-w-sm w-full">
                    <button type="button" @click="switchTab('camera')" 
                            class="flex-1 py-2.5 px-4 rounded-xl text-xs font-extrabold transition-all duration-200 flex items-center justify-center gap-2"
                            :class="activeTab === 'camera' ? 'bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-400 shadow-md' : 'text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-gray-200'">
                        <i class="fas fa-camera text-xs"></i>
                        <span>Pemindai QR</span>
                    </button>
                    <button type="button" @click="switchTab('manual')" 
                            class="flex-1 py-2.5 px-4 rounded-xl text-xs font-extrabold transition-all duration-200 flex items-center justify-center gap-2"
                            :class="activeTab === 'manual' ? 'bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-400 shadow-md' : 'text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-gray-200'">
                        <i class="fas fa-magnifying-glass text-xs"></i>
                        <span>Cari Nomor</span>
                    </button>
                </div>
            </div>

            <!-- Tab 1: Camera & File Scanner -->
            <div x-show="activeTab === 'camera'" class="p-6 sm:p-8 space-y-6">
                
                <!-- Viewfinder Screen -->
                <div class="relative mx-auto border-2 border-teal-500/40 dark:border-teal-500/30 rounded-3xl overflow-hidden shadow-xl bg-slate-950 aspect-square max-w-sm w-full">
                    
                    <!-- Camera Feed Target -->
                    <div id="qr-reader" class="w-full h-full"></div>

                    <!-- Scanning Overlay (Visible When Camera Active) -->
                    <div id="scanner-overlay" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none hidden">
                        <!-- Frame Corner Brackets -->
                        <div class="w-56 h-56 relative flex items-center justify-center">
                            <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-teal-400 rounded-tl-lg shadow-[0_0_10px_#2dd4bf]"></div>
                            <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-teal-400 rounded-tr-lg shadow-[0_0_10px_#2dd4bf]"></div>
                            <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-teal-400 rounded-bl-lg shadow-[0_0_10px_#2dd4bf]"></div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-teal-400 rounded-br-lg shadow-[0_0_10px_#2dd4bf]"></div>
                            
                            <!-- Laser Line Animation -->
                            <div class="absolute w-full h-0.5 bg-gradient-to-r from-transparent via-teal-400 to-transparent left-0 laser-line shadow-[0_0_12px_#2dd4bf]"></div>
                        </div>
                        <span class="text-[10px] text-teal-300 bg-slate-950/85 backdrop-blur-md px-3.5 py-1.5 rounded-full mt-4 font-black tracking-widest uppercase border border-teal-500/30">
                            Memindai Kode QR...
                        </span>
                    </div>

                    <!-- Placeholder Screen (Camera Inactive) -->
                    <div id="scanner-placeholder" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900/90 backdrop-blur-sm text-slate-300 p-6 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-teal-500/20 border border-teal-500/30 text-teal-400 flex items-center justify-center mb-3 shadow-inner">
                            <i class="fas fa-camera text-2xl"></i>
                        </div>
                        <p class="font-bold text-white text-sm">Kamera Belum Aktif</p>
                        <p class="text-xs text-slate-400 mt-1 max-w-[240px]">Klik tombol <strong>Buka Kamera</strong> di bawah atau unggah berkas sertifikat Anda.</p>
                    </div>
                </div>

                <!-- Status & Action Buttons -->
                <div class="flex flex-col items-center gap-4 max-w-sm mx-auto w-full">
                    <div id="scanner-status" role="status" aria-live="polite" class="text-xs text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-gray-900 px-4 py-1.5 rounded-full font-bold border border-slate-200 dark:border-gray-700">
                        Status: <span class="text-slate-600 dark:text-slate-400 font-extrabold">Siap</span>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-2.5 w-full">
                        <button type="button" id="btn-start-scan" class="flex-1 bg-teal-600 hover:bg-teal-700 active:scale-98 text-white px-5 py-3.5 rounded-2xl text-xs uppercase tracking-wider font-extrabold transition shadow-md flex items-center justify-center gap-2">
                            <i class="fas fa-camera text-sm"></i>
                            <span>Buka Kamera</span>
                        </button>

                        <button type="button" id="btn-stop-scan" class="flex-1 bg-rose-600 hover:bg-rose-700 active:scale-98 text-white px-5 py-3.5 rounded-2xl text-xs uppercase tracking-wider font-extrabold transition shadow-md flex items-center justify-center gap-2 hidden">
                            <i class="fas fa-stop text-sm"></i>
                            <span>Hentikan Kamera</span>
                        </button>

                        <button type="button" id="btn-switch-cam" class="bg-slate-100 hover:bg-slate-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-slate-700 dark:text-gray-200 px-3.5 py-3.5 rounded-2xl text-xs font-bold transition shadow-xs hidden" title="Ganti Kamera">
                            <i class="fas fa-camera-rotate text-sm"></i>
                        </button>

                        <input type="file" id="qr-file-input" accept="image/*,application/pdf" class="hidden">
                        <button type="button" id="btn-upload-file" class="flex-1 bg-slate-100 hover:bg-slate-200 dark:bg-gray-900 dark:hover:bg-gray-800 text-slate-700 dark:text-gray-200 border border-slate-200 dark:border-gray-700 px-5 py-3.5 rounded-2xl text-xs uppercase tracking-wider font-extrabold transition flex items-center justify-center gap-2">
                            <i class="fas fa-file-arrow-up text-sm text-teal-600 dark:text-teal-400"></i>
                            <span>Unggah PDF / Gambar</span>
                        </button>
                    </div>

                    <!-- Feedback Alert Message Container -->
                    <div id="qr-reader-results" class="text-center font-bold text-xs sm:text-sm min-h-[1.5rem]"></div>
                </div>

                <!-- Troubleshooting & Camera Diagnostic Guide -->
                <div id="debug-panel" class="max-w-md mx-auto hidden bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/60 rounded-2xl p-5 text-left text-xs">
                    <h4 class="text-rose-800 dark:text-rose-300 font-bold flex items-center gap-2 mb-2">
                        <i class="fas fa-triangle-exclamation text-rose-500"></i>
                        <span>Kendala Akses Kamera Terdeteksi:</span>
                    </h4>
                    <p class="text-slate-600 dark:text-slate-300 mb-3 leading-relaxed">
                        Pastikan izin kamera telah diberikan pada browser Anda dan situs diakses menggunakan protokol aman (HTTPS).
                    </p>
                    <div class="bg-white dark:bg-gray-900 p-3 rounded-xl border border-rose-100 dark:border-rose-900/40 text-[11px] font-mono text-rose-700 dark:text-rose-300 space-y-1">
                        <div><strong>Protokol:</strong> <span id="debug-protocol"></span></div>
                        <div><strong>Secure Context:</strong> <span id="debug-secure"></span></div>
                        <div id="debug-error-msg" class="pt-1.5 border-t border-rose-100 dark:border-rose-900/40 text-rose-600 dark:text-rose-400 break-words"></div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Manual Certificate Number Search -->
            <div x-show="activeTab === 'manual'" x-cloak class="p-6 sm:p-10 max-w-md mx-auto space-y-6">
                <div class="text-center space-y-2">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-800/60 flex items-center justify-center mx-auto shadow-2xs">
                        <i class="fas fa-file-signature text-lg"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-black text-slate-800 dark:text-gray-100">
                        Cari Nomor Sertifikat / ID Card
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-gray-400 leading-relaxed font-medium">
                        Masukkan Nomor Sertifikat resmi atau Token Verifikasi ID Card untuk melihat data keabsahan.
                    </p>
                </div>

                <form action="{{ route('certificate.search') }}" method="POST" class="space-y-4" x-data="{ manualInput: '' }">
                    @csrf
                    <div>
                        <label for="nomor_sertifikat" class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                            Nomor Sertifikat / Token ID Card
                        </label>
                        <div class="relative">
                            <input type="text" id="nomor_sertifikat" name="nomor_sertifikat" x-model="manualInput" 
                                   placeholder="Contoh: SERT/DISKOMINFO/2026/001 atau token..." 
                                   required
                                   class="w-full px-4 py-3.5 pr-10 border border-slate-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm font-bold bg-slate-50 dark:bg-gray-900 text-slate-800 dark:text-gray-100 placeholder-slate-400 dark:placeholder-gray-500 shadow-2xs transition">
                            <button type="button" x-show="manualInput.length > 0" @click="manualInput = ''" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                                <i class="fas fa-times-circle text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 active:scale-98 text-white font-extrabold py-3.5 px-6 rounded-2xl shadow-md hover:shadow-lg transition flex items-center justify-center gap-2 text-xs uppercase tracking-wider">
                        <i class="fas fa-search text-xs"></i>
                        <span>Verifikasi Sekarang</span>
                    </button>
                </form>

                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-gray-900 border border-slate-200/70 dark:border-gray-800 text-xs text-slate-500 dark:text-gray-400 space-y-1.5">
                    <span class="font-bold text-slate-700 dark:text-slate-300 block mb-1">
                        <i class="fas fa-circle-info text-teal-600 dark:text-teal-400 mr-1"></i> Informasi Tambahan:
                    </span>
                    <p class="text-[11px] leading-relaxed">Nomor sertifikat tercantum di bagian atas sertifikat fisik, atau di bawah QR code sertifikat elektronik.</p>
                </div>
            </div>

            <!-- Footer Card Note -->
            <div class="px-6 sm:px-8 py-4 bg-slate-50 dark:bg-gray-900/60 border-t border-slate-100 dark:border-gray-700/80 text-center text-slate-400 dark:text-slate-500 text-[11px] font-medium flex items-center justify-center gap-2">
                <i class="fas fa-lock text-teal-600 dark:text-teal-400 text-xs"></i>
                <span>Sistem Verifikasi Digital Terenkripsi Pemerintah Kota Banjarmasin</span>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Load PDF.js for client-side PDF decoding -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    
    <script>
        if (window.pdfjsLib && window.pdfjsLib.GlobalWorkerOptions) {
            window.pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
        }

        function playBeepSound() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(880, ctx.currentTime); // A5 note
                gain.gain.setValueAtTime(0.15, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.15);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.15);
            } catch(e) {}
            
            if (navigator.vibrate) {
                try { navigator.vibrate([80, 40, 80]); } catch(e) {}
            }
        }

        function qrScannerManager() {
            return {
                activeTab: 'camera',
                switchTab(tab) {
                    this.activeTab = tab;
                    if (tab === 'manual') {
                        if (window.stopQrScanning) window.stopQrScanning();
                    }
                },
                init() {}
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            var resultContainer = document.getElementById('qr-reader-results');
            var btnStart = document.getElementById('btn-start-scan');
            var btnStop = document.getElementById('btn-stop-scan');
            var btnSwitch = document.getElementById('btn-switch-cam');
            var btnUpload = document.getElementById('btn-upload-file');
            var fileInput = document.getElementById('qr-file-input');
            
            var statusSpan = document.querySelector('#scanner-status span');
            var scannerOverlay = document.getElementById('scanner-overlay');
            var scannerPlaceholder = document.getElementById('scanner-placeholder');
            
            var debugPanel = document.getElementById('debug-panel');
            var debugProtocol = document.getElementById('debug-protocol');
            var debugSecure = document.getElementById('debug-secure');
            var debugErrorMsg = document.getElementById('debug-error-msg');

            debugProtocol.textContent = window.location.protocol;
            debugSecure.textContent = window.isSecureContext ? "YA" : "TIDAK (Wajib HTTPS)";

            var html5QrCode = null;
            var isStarting = false;
            var currentCameraIndex = 0;
            var availableCameras = [];

            function setStatus(message, className) {
                statusSpan.textContent = message;
                statusSpan.className = className;
            }

            function showDebug(error) {
                console.error("Camera error:", error);
                debugPanel.classList.remove('hidden');
                var errorName = error && error.name ? error.name : "CameraError";
                var errorMessage = error && error.message ? error.message : String(error || "Akses kamera ditolak.");
                debugErrorMsg.textContent = errorName + ": " + errorMessage;
                setStatus("Gagal", "text-rose-600 dark:text-rose-400 font-extrabold");
            }

            function onScanSuccess(decodedText) {
                decodedText = String(decodedText || '').trim();
                if (!decodedText) return;

                playBeepSound();
                resultContainer.innerHTML = "<span class='text-teal-600 dark:text-teal-400 flex items-center justify-center gap-1.5'><i class='fas fa-check-circle'></i> QR Berhasil Terdeteksi! Mengalihkan...</span>";
                
                stopScanning().then(() => {
                    if (decodedText.includes('/verify-id-card/')) {
                        window.location.href = decodedText;
                    } else if (decodedText.includes('/verify-certificate/')) {
                        window.location.href = decodedText;
                    } else {
                        const idCardMatch = decodedText.match(/verify-id-card\/([a-zA-Z0-9_\-]+)/);
                        if (idCardMatch) {
                            window.location.href = "{{ url('/verify-id-card') }}/" + idCardMatch[1];
                            return;
                        }

                        const certMatch = decodedText.match(/verify-certificate\/([a-zA-Z0-9_\-]+)/);
                        if (certMatch) {
                            window.location.href = "{{ url('/verify-certificate') }}/" + certMatch[1];
                            return;
                        }

                        // Default attempt ID Card verification
                        window.location.href = "{{ url('/verify-id-card') }}/" + encodeURIComponent(decodedText);
                    }
                });
            }

            async function checkCameras() {
                if (typeof window.Html5Qrcode !== 'undefined') {
                    try {
                        const devices = await window.Html5Qrcode.getCameras();
                        if (devices && devices.length > 1) {
                            availableCameras = devices;
                            btnSwitch.classList.remove('hidden');
                        }
                    } catch(e) {}
                }
            }

            function startScanning() {
                if (isStarting || (html5QrCode && html5QrCode.isScanning)) return;

                if (!window.isSecureContext || !navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    showDebug({ name: "NotSupportedError", message: "Browser membutuhkan protokol HTTPS untuk mengakses kamera." });
                    return;
                }

                if (typeof window.Html5Qrcode === 'undefined') {
                    showDebug({ name: "LibraryError", message: "Library pemindai belum siap. Muat ulang halaman." });
                    return;
                }

                debugPanel.classList.add('hidden');
                resultContainer.innerHTML = "";
                isStarting = true;
                setStatus("Memulai Kamera...", "text-amber-500 font-extrabold");
                
                if (!html5QrCode) {
                    html5QrCode = new window.Html5Qrcode("qr-reader");
                }

                const cameraIdOrConfig = availableCameras.length > 0 && availableCameras[currentCameraIndex] 
                    ? availableCameras[currentCameraIndex].id 
                    : { facingMode: "environment" };

                const config = { 
                    fps: 15, 
                    qrbox: { width: 260, height: 260 }
                };

                html5QrCode.start(
                    cameraIdOrConfig, 
                    config, 
                    onScanSuccess,
                    undefined
                ).then(() => {
                    setStatus("Kamera Aktif", "text-emerald-600 dark:text-emerald-400 font-extrabold");
                    btnStart.classList.add('hidden');
                    btnStop.classList.remove('hidden');
                    scannerOverlay.classList.remove('hidden');
                    scannerPlaceholder.classList.add('hidden');
                    checkCameras();
                }).catch(err => {
                    showDebug(err);
                    btnStart.classList.remove('hidden');
                    btnStop.classList.add('hidden');
                    scannerOverlay.classList.add('hidden');
                    scannerPlaceholder.classList.remove('hidden');
                    html5QrCode = null;
                }).finally(() => {
                    isStarting = false;
                });
            }

            async function stopScanning() {
                if (isStarting) return;

                var scanner = html5QrCode;
                html5QrCode = null;

                if (scanner) {
                    try {
                        if (scanner.isScanning) await scanner.stop();
                    } catch (err) {}
                    try {
                        await scanner.clear();
                    } catch (err) {}
                }

                setStatus("Siap", "text-slate-600 dark:text-slate-400 font-extrabold");
                btnStart.classList.remove('hidden');
                btnStop.classList.add('hidden');
                scannerOverlay.classList.add('hidden');
                scannerPlaceholder.classList.remove('hidden');
            }

            window.stopQrScanning = stopScanning;

            btnStart.addEventListener('click', startScanning);
            btnStop.addEventListener('click', stopScanning);

            btnSwitch.addEventListener('click', function() {
                if (availableCameras.length > 1) {
                    currentCameraIndex = (currentCameraIndex + 1) % availableCameras.length;
                    stopScanning().then(() => {
                        startScanning();
                    });
                }
            });

            // File Upload Integration
            btnUpload.addEventListener('click', function() {
                fileInput.click();
            });

            fileInput.addEventListener('change', function(e) {
                var file = e.target.files[0];
                if (!file) return;

                resultContainer.innerHTML = "";
                debugPanel.classList.add('hidden');
                
                stopScanning().then(() => {
                    setStatus("Membaca Berkas...", "text-amber-500 font-extrabold");
                    
                    if (file.type.startsWith('image/')) {
                        scanImageFile(file);
                    } else if (file.type === 'application/pdf') {
                        scanPdfFile(file);
                    } else {
                        resultContainer.innerHTML = "<span class='text-rose-500 font-bold'>Gunakan format Gambar (PNG/JPEG) atau PDF!</span>";
                        setStatus("Siap", "text-slate-600 dark:text-slate-400 font-extrabold");
                    }
                });
            });

            function scanImageFile(file) {
                if (typeof window.Html5Qrcode === 'undefined') {
                    showDebug({ name: "LibraryError", message: "Library pemindai belum siap." });
                    return;
                }

                if (!html5QrCode) {
                    html5QrCode = new window.Html5Qrcode("qr-reader");
                }
                
                html5QrCode.scanFile(file, false)
                    .then(decodedText => {
                        onScanSuccess(decodedText);
                    })
                    .catch(err => {
                        resultContainer.innerHTML = "<span class='text-rose-500 font-bold'>Kode QR tidak ditemukan pada berkas gambar ini.</span>";
                        setStatus("Gagal", "text-rose-600 font-extrabold");
                        fileInput.value = "";
                    });
            }

            function scanPdfFile(file) {
                if (!window.pdfjsLib) {
                    showDebug({ name: "PdfJsError", message: "Library PDF belum dimuat. Gunakan format gambar." });
                    return;
                }

                var fileReader = new FileReader();
                fileReader.onload = function() {
                    var typedarray = new Uint8Array(this.result);
                    
                    window.pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
                        return pdf.getPage(1);
                    }).then(function(page) {
                        var scale = 2.0;
                        var viewport = page.getViewport({ scale: scale });
                        
                        var canvas = document.createElement('canvas');
                        var context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        
                        return page.render({ canvasContext: context, viewport: viewport }).promise.then(function() {
                            return canvas;
                        });
                    }).then(function(canvas) {
                        canvas.toBlob(function(blob) {
                            if (!blob) throw new Error("Gagal konversi PDF ke canvas");
                            var convertedFile = new File([blob], "pdf_page.png", { type: "image/png" });
                            scanImageFile(convertedFile);
                        }, 'image/png');
                    }).catch(function(err) {
                        resultContainer.innerHTML = "<span class='text-rose-500 font-bold'>Gagal membaca dokumen PDF atau QR tidak ditemukan.</span>";
                        setStatus("Gagal", "text-rose-600 font-extrabold");
                        fileInput.value = "";
                    });
                };
                fileReader.readAsArrayBuffer(file);
            }

            window.addEventListener('pagehide', function() {
                stopScanning();
            });
        });
    </script>
    <style>
        @keyframes laser-scan {
            0% { top: 0%; }
            50% { top: 100%; }
            100% { top: 0%; }
        }
        .laser-line {
            animation: laser-scan 2.8s ease-in-out infinite;
        }
        #qr-reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            border-radius: 1.5rem !important;
        }
    </style>
    @endpush
</x-guest-layout>
