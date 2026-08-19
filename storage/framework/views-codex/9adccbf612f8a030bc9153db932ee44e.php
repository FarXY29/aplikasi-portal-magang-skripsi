<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <a href="<?php echo e(route('home')); ?>" class="mb-4 group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Halaman Utama
                </a>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                    <h2 class="text-2xl font-bold mb-4 text-teal-700">Scan QR Code Verifikasi</h2>
                    <p class="mb-6 text-gray-600 dark:text-gray-400 text-sm">Tekan tombol Kamera, izinkan akses kamera, lalu arahkan kamera belakang HP ke QR Code. Pastikan halaman dibuka melalui HTTPS.</p>
                    
                    <!-- QR Reader Wrapper -->
                    <div class="relative mx-auto border-2 border-teal-500/30 dark:border-teal-500/20 rounded-2xl overflow-hidden shadow-lg bg-slate-900 aspect-square" style="width:100%; max-width:400px;">
                        
                        <!-- Camera Feed Target Container -->
                        <div id="qr-reader" class="w-full h-full"></div>
                        
                        <!-- Scanning Scan Box Overlay (Only visible when active) -->
                        <div id="scanner-overlay" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none hidden">
                            <!-- Scanning Laser Box -->
                            <div class="w-48 h-48 border-2 border-teal-400/80 rounded-xl relative flex items-center justify-center">
                                <div class="absolute inset-0 border border-slate-900 m-[-2px] opacity-20"></div>
                                <!-- Laser Line -->
                                <div class="absolute w-full h-0.5 bg-teal-400 left-0 laser-line shadow-[0_0_8px_#2dd4bf]"></div>
                            </div>
                            <span class="text-[10px] text-teal-400 bg-slate-950/80 px-3 py-1 rounded-full mt-4 font-bold tracking-widest uppercase">MEMINDAI QR CODE...</span>
                        </div>

                        <!-- Placeholder / Inactive Screen -->
                        <div id="scanner-placeholder" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-500 p-6">
                            <div class="w-16 h-16 rounded-full bg-teal-50 dark:bg-teal-950/50 flex items-center justify-center text-teal-600 dark:text-teal-400 mb-4 shadow-sm">
                                <i class="fas fa-camera text-2xl"></i>
                            </div>
                            <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">Kamera Belum Aktif</p>
                            <p class="text-xs text-center mt-1 text-slate-400 dark:text-slate-500">Pilih opsi Kamera di bawah, atau unggah file PDF/Gambar.</p>
                        </div>
                    </div>

                    <!-- Controls & Status -->
                    <div class="mt-5 flex flex-col items-center gap-3">
                        <div id="scanner-status" role="status" aria-live="polite" class="text-xs text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800/80 px-4 py-1.5 rounded-full font-semibold border dark:border-slate-700">
                            Status: <span class="text-slate-600 dark:text-slate-400">Mati</span>
                        </div>

                        <div class="flex flex-wrap justify-center gap-3 w-full max-w-sm">
                            <button type="button" id="btn-start-scan" aria-controls="qr-reader" class="flex-1 bg-teal-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-teal-700 transition flex items-center justify-center gap-2 shadow-md shadow-teal-600/20 cursor-pointer">
                                <i class="fas fa-camera"></i> Kamera
                            </button>
                            <button type="button" id="btn-stop-scan" aria-controls="qr-reader" class="flex-1 bg-rose-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-rose-700 transition flex items-center justify-center gap-2 shadow-md shadow-rose-600/20 cursor-pointer hidden">
                                <i class="fas fa-stop"></i> Stop Kamera
                            </button>
                            
                            <input type="file" id="qr-file-input" accept="image/*,application/pdf" class="hidden">
                            <button type="button" id="btn-upload-file" class="flex-1 bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition flex items-center justify-center gap-2 shadow-sm cursor-pointer">
                                <i class="fas fa-file-upload"></i> Upload File
                            </button>
                        </div>
                    </div>

                    <!-- Error Log Panel (Debug) -->
                    <div id="debug-panel" class="mt-6 text-left max-w-sm mx-auto hidden bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/50 rounded-xl p-4">
                        <h4 class="text-rose-800 dark:text-rose-400 text-xs font-extrabold flex items-center gap-2 mb-2">
                            <i class="fas fa-exclamation-triangle"></i> Detail Masalah Kamera:
                        </h4>
                        <div class="text-[10px] text-rose-700 dark:text-rose-300 font-mono space-y-1 overflow-x-auto">
                            <div><strong>URL:</strong> <span id="debug-url" class="break-all"></span></div>
                            <div><strong>Protokol:</strong> <span id="debug-protocol"></span></div>
                            <div><strong>Secure Context:</strong> <span id="debug-secure"></span></div>
                            <div><strong>User Agent:</strong> <span id="debug-ua" class="break-all"></span></div>
                            <div><strong>MediaDevices:</strong> <span id="debug-media"></span></div>
                            <div class="pt-2 border-t border-rose-200/50 dark:border-rose-900/50"><strong>Error Log:</strong></div>
                            <div id="debug-error-msg" class="bg-white dark:bg-black/30 p-2 rounded border border-rose-100 dark:border-rose-950/50 text-rose-600 dark:text-rose-400 whitespace-pre-wrap break-words"></div>
                        </div>
                    </div>

                    <div id="qr-reader-results" class="mt-4 font-bold text-lg text-teal-600 h-8"></div>

                    <div class="mt-6 text-sm text-gray-500 dark:text-gray-400 pt-6 border-t border-gray-100 dark:border-gray-700">
                        <p>Atau gunakan fitur pencarian manual jika kamera tidak tersedia.</p>
                        <form action="<?php echo e(route('certificate.search')); ?>" method="POST" class="mt-3 flex justify-center gap-2 max-w-sm mx-auto">
                            <?php echo csrf_field(); ?>
                            <input type="text" name="nomor_sertifikat" placeholder="Masukkan Nomor Sertifikat" class="border rounded-l px-4 py-2 text-sm w-full focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-800 dark:border-gray-700" required>
                            <button type="submit" class="bg-teal-600 text-white px-5 py-2 rounded-r text-sm font-bold hover:bg-teal-700 transition">Cari</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <!-- Load pdf.js for client-side PDF decoding support -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        // PDF.js hanya diperlukan untuk upload PDF; kamera tetap harus berjalan
        // meskipun dependensi PDF gagal dimuat.
        if (window.pdfjsLib && window.pdfjsLib.GlobalWorkerOptions) {
            window.pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
        }

        function docReady(fn) {
            if (document.readyState === "complete" || document.readyState === "interactive") {
                setTimeout(fn, 1);
            } else {
                document.addEventListener("DOMContentLoaded", fn);
            }
        }

        docReady(function () {
            var resultContainer = document.getElementById('qr-reader-results');
            var lastResult, countResults = 0;
            
            var btnStart = document.getElementById('btn-start-scan');
            var btnStop = document.getElementById('btn-stop-scan');
            var btnUpload = document.getElementById('btn-upload-file');
            var fileInput = document.getElementById('qr-file-input');
            
            var statusSpan = document.querySelector('#scanner-status span');
            var scannerOverlay = document.getElementById('scanner-overlay');
            var scannerPlaceholder = document.getElementById('scanner-placeholder');
            
            var debugPanel = document.getElementById('debug-panel');
            var debugUrl = document.getElementById('debug-url');
            var debugProtocol = document.getElementById('debug-protocol');
            var debugSecure = document.getElementById('debug-secure');
            var debugUa = document.getElementById('debug-ua');
            var debugMedia = document.getElementById('debug-media');
            var debugErrorMsg = document.getElementById('debug-error-msg');

            // Set basic debug info
            debugUrl.textContent = window.location.href;
            debugProtocol.textContent = window.location.protocol;
            debugSecure.textContent = window.isSecureContext ? "YA" : "TIDAK (Wajib HTTPS)";
            debugUa.textContent = navigator.userAgent;
            debugMedia.textContent = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) ? "Didukung" : "Tidak Didukung oleh Browser";

            var html5QrCode = null;
            var isStarting = false;

            function setStatus(message, className) {
                statusSpan.textContent = message;
                statusSpan.className = className;
            }

            function showDebug(error) {
                console.error("Camera startup error:", error);
                debugPanel.classList.remove('hidden');
                var errorName = error && error.name ? error.name : "CameraError";
                var errorMessage = error && error.message ? error.message : String(error || "Kesalahan tidak diketahui");
                debugErrorMsg.textContent = errorName + ": " + errorMessage;
                
                setStatus("Gagal Aktif", "text-rose-600 dark:text-rose-400");
            }

            function showDependencyError(message) {
                debugPanel.classList.remove('hidden');
                debugErrorMsg.textContent = message;
                setStatus("Library Gagal", "text-rose-600 dark:text-rose-400");
            }

            function onScanSuccess(decodedText, decodedResult) {
                decodedText = String(decodedText || '').trim();
                if (!decodedText) return;

                if (decodedText !== lastResult) {
                    ++countResults;
                    lastResult = decodedText;
                    
                    resultContainer.innerHTML = "QR Code Ditemukan! Mengalihkan...";
                    
                    // Stop scanning
                    stopScanning().then(_ => {
                        if (decodedText.includes('/verify-certificate/')) {
                            window.location.href = decodedText;
                        } else {
                            resultContainer.innerHTML = "<span class='text-red-500'>QR Code bukan dari sistem Portal Magang!</span>";
                            setTimeout(() => { 
                                lastResult = null; 
                                resultContainer.innerHTML = "";
                            }, 3000);
                        }
                    });
                }
            }

            function startScanning() {
                if (isStarting || (html5QrCode && html5QrCode.isScanning)) return;

                if (!window.isSecureContext || !navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    showDependencyError("Kamera HP hanya dapat digunakan melalui HTTPS dan browser yang mendukung akses kamera.");
                    return;
                }

                if (typeof window.Html5Qrcode === 'undefined') {
                    showDependencyError("Library pemindai QR belum berhasil dimuat. Periksa koneksi internet lalu muat ulang halaman.");
                    return;
                }

                debugPanel.classList.add('hidden');
                resultContainer.innerHTML = "";
                isStarting = true;
                setStatus("Memulai Kamera...", "text-amber-500");
                resetPlaceholder();
                
                if (!html5QrCode) {
                    html5QrCode = new window.Html5Qrcode("qr-reader");
                }

                const config = { 
                    fps: 10, 
                    qrbox: { width: 250, height: 250 }
                };

                html5QrCode.start(
                    { facingMode: "environment" }, 
                    config, 
                    onScanSuccess,
                    undefined // No-op on scan frame error to prevent flooding console
                ).then(() => {
                    setStatus("Kamera Aktif", "text-teal-600 dark:text-teal-400");
                    
                    btnStart.classList.add('hidden');
                    btnStop.classList.remove('hidden');
                    scannerOverlay.classList.remove('hidden');
                    scannerPlaceholder.classList.add('hidden');
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
                    } catch (err) {
                        console.error("Failed to stop scan", err);
                    }

                    try {
                        await scanner.clear();
                    } catch (err) {
                        console.error("Failed to clear scanner", err);
                    }
                }

                setStatus("Mati", "text-slate-600 dark:text-slate-400");
                btnStart.classList.remove('hidden');
                btnStop.classList.add('hidden');
                scannerOverlay.classList.add('hidden');
                scannerPlaceholder.classList.remove('hidden');
                resetPlaceholder();
            }

            function resetPlaceholder() {
                scannerPlaceholder.innerHTML = `
                    <div class="w-16 h-16 rounded-full bg-teal-50 dark:bg-teal-950/50 flex items-center justify-center text-teal-600 dark:text-teal-400 mb-4 shadow-sm">
                        <i class="fas fa-camera text-2xl"></i>
                    </div>
                    <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">Kamera Belum Aktif</p>
                    <p class="text-xs text-center mt-1 text-slate-400 dark:text-slate-500">Pilih opsi Kamera di bawah, atau unggah file PDF/Gambar.</p>
                `;
            }

            // FILE UPLOAD INTEGRATION
            btnUpload.addEventListener('click', function() {
                fileInput.click();
            });

            fileInput.addEventListener('change', function(e) {
                var file = e.target.files[0];
                if (!file) return;

                // Reset results and hide errors
                resultContainer.innerHTML = "";
                debugPanel.classList.add('hidden');
                
                // Stop camera before processing file
                stopScanning().then(() => {
                    setStatus("Membaca File...", "text-amber-500");
                    
                    // Show custom loading placeholder with icon
                    scannerPlaceholder.innerHTML = `
                        <div class="w-16 h-16 rounded-full bg-teal-50 dark:bg-teal-950/50 flex items-center justify-center text-teal-600 dark:text-teal-400 mb-4 shadow-sm animate-pulse">
                            <i class="fas ${file.type === 'application/pdf' ? 'fa-file-pdf' : 'fa-file-image'} text-2xl"></i>
                        </div>
                        <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">Membaca ${file.type === 'application/pdf' ? 'PDF' : 'Gambar'}...</p>
                        <p class="text-[10px] text-center mt-1 text-slate-400 dark:text-slate-500 max-w-[200px] truncate">Mohon tunggu...</p>
                    `;

                    if (file.type.startsWith('image/')) {
                        scanImageFile(file);
                    } else if (file.type === 'application/pdf') {
                        scanPdfFile(file);
                    } else {
                        resultContainer.innerHTML = "<span class='text-red-500'>Format file tidak didukung! Gunakan gambar (PNG, JPEG) atau PDF.</span>";
                        setStatus("Mati", "text-slate-600 dark:text-slate-400");
                        resetPlaceholder();
                    }
                });
            });

            function scanImageFile(file) {
                if (typeof window.Html5Qrcode === 'undefined') {
                    showDependencyError("Library pemindai QR belum berhasil dimuat. Muat ulang halaman lalu coba lagi.");
                    return;
                }

                if (!html5QrCode) {
                    html5QrCode = new window.Html5Qrcode("qr-reader");
                }
                
                html5QrCode.scanFile(file, false)
                    .then(decodedText => {
                        onScanSuccess(decodedText, null);
                        setStatus("Mati", "text-slate-600 dark:text-slate-400");
                        resetPlaceholder();
                    })
                    .catch(err => {
                        console.error(err);
                        resultContainer.innerHTML = "<span class='text-red-500'>QR Code tidak ditemukan dalam gambar!</span>";
                        setStatus("Gagal", "text-rose-600 dark:text-rose-400");
                        fileInput.value = ""; // Clear input
                        resetPlaceholder();
                    });
            }

            function scanPdfFile(file) {
                if (!window.pdfjsLib) {
                    showDependencyError("Library pembaca PDF belum berhasil dimuat. Gunakan gambar QR atau muat ulang halaman.");
                    return;
                }

                var fileReader = new FileReader();
                fileReader.onload = function() {
                    var typedarray = new Uint8Array(this.result);
                    
                    window.pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
                        // Ambil halaman pertama
                        return pdf.getPage(1);
                    }).then(function(page) {
                        // Render ke canvas tersembunyi
                        var scale = 2.0; // Naikkan skala untuk meningkatkan resolusi pembacaan QR
                        var viewport = page.getViewport({ scale: scale });
                        
                        var canvas = document.createElement('canvas');
                        var context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        
                        var renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        
                        return page.render(renderContext).promise.then(function() {
                            return canvas;
                        });
                    }).then(function(canvas) {
                        // Ubah canvas ke Blob untuk di-scan oleh html5QrCode
                        canvas.toBlob(function(blob) {
                            if (!blob) {
                                throw new Error("Gagal membuat blob gambar dari PDF");
                            }
                            var convertedFile = new File([blob], "pdf_page.png", { type: "image/png" });
                            scanImageFile(convertedFile);
                        }, 'image/png');
                    }).catch(function(err) {
                        console.error("Gagal memproses PDF:", err);
                        resultContainer.innerHTML = "<span class='text-red-500'>Gagal membaca file PDF atau QR Code tidak ditemukan!</span>";
                        setStatus("Gagal", "text-rose-600 dark:text-rose-400");
                        fileInput.value = "";
                        resetPlaceholder();
                    });
                };
                fileReader.readAsArrayBuffer(file);
            }

            btnStart.addEventListener('click', startScanning);
            btnStop.addEventListener('click', stopScanning);

            if (typeof window.Html5Qrcode === 'undefined') {
                showDependencyError("Library pemindai QR belum berhasil dimuat. Muat ulang halaman atau periksa koneksi internet.");
            }

            // Clean up on unload
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
            animation: laser-scan 3s linear infinite;
        }
        /* html5-qrcode video container styles override to fit layout */
        #qr-reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }
    </style>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\public\certificate\scanner.blade.php ENDPATH**/ ?>