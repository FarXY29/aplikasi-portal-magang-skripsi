<x-guest-layout :public-navigation="true">
    <div class="verification-shell" x-data="qrScannerManager()" x-init="init()">
        <div class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8 lg:py-16">
            <nav class="mb-8" aria-label="Navigasi verifikasi">
                <a href="{{ route('home') }}" class="verification-back"><i class="fas fa-arrow-left" aria-hidden="true"></i>Kembali ke beranda</a>
            </nav>

            <header class="mb-10 grid gap-6 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end">
                <div class="max-w-3xl">
                    <p class="verification-kicker mb-4">Layanan verifikasi publik</p>
                    <h1 class="font-display text-3xl font-bold leading-tight tracking-tight text-slate-950 dark:text-white sm:text-4xl lg:text-5xl">Pastikan dokumen Anda<br class="hidden sm:block"> asli dan terdaftar.</h1>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-600 dark:text-slate-400 sm:text-base">Verifikasi sertifikat dan ID Card magang melalui kamera, unggahan berkas, atau nomor dokumen.</p>
                </div>
                <div class="hidden items-center gap-2 rounded-full border border-teal-200 bg-teal-50 px-4 py-2 text-xs font-semibold text-teal-800 dark:border-teal-800 dark:bg-teal-950/40 dark:text-teal-300 lg:inline-flex">
                    <i class="fas fa-shield-halved" aria-hidden="true"></i>Terhubung ke data resmi
                </div>
            </header>

            <main class="grid gap-6 lg:grid-cols-[minmax(0,1.45fr)_minmax(19rem,0.75fr)] lg:items-start">
                <section aria-labelledby="camera-heading" class="verification-card p-4 sm:p-6">
                    <div class="mb-5 flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-teal-50 text-teal-700 dark:bg-teal-950 dark:text-teal-300"><i class="fas fa-qrcode" aria-hidden="true"></i></span>
                            <div>
                                <h2 id="camera-heading" class="font-display text-xl font-bold text-slate-900 dark:text-white">Pindai QR Code</h2>
                                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Gunakan kamera perangkat Anda</p>
                            </div>
                        </div>
                        <span id="scanner-status" role="status" aria-live="polite" class="inline-flex min-h-8 shrink-0 items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 text-[11px] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                            <span class="h-1.5 w-1.5 rounded-full" :class="isScanning ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                            <span :class="statusClass" x-text="statusText">Siap</span>
                        </span>
                    </div>

                    <div class="scanner-viewfinder relative mx-auto aspect-[4/3] w-full max-w-2xl overflow-hidden rounded-2xl bg-slate-950">
                        <div id="qr-reader" class="h-full w-full"></div>
                        <div id="scanner-overlay" x-show="isScanning" x-cloak class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                            <div class="scanner-frame relative h-52 w-52 sm:h-60 sm:w-60">
                                <span class="absolute left-0 top-0 h-9 w-9 rounded-tl-lg border-l-[3px] border-t-[3px] border-teal-300"></span>
                                <span class="absolute right-0 top-0 h-9 w-9 rounded-tr-lg border-r-[3px] border-t-[3px] border-teal-300"></span>
                                <span class="absolute bottom-0 left-0 h-9 w-9 rounded-bl-lg border-b-[3px] border-l-[3px] border-teal-300"></span>
                                <span class="absolute bottom-0 right-0 h-9 w-9 rounded-br-lg border-b-[3px] border-r-[3px] border-teal-300"></span>
                                <span class="scanner-laser"></span>
                            </div>
                            <span class="mt-5 rounded-full bg-slate-950/80 px-4 py-2 text-xs font-semibold text-white backdrop-blur">Posisikan QR di dalam bingkai</span>
                        </div>
                        <div id="scanner-placeholder" x-show="!isScanning" class="scanner-placeholder absolute inset-0 flex flex-col items-center justify-center p-6 text-center">
                            <span class="mb-5 flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-2xl text-teal-300"><i class="fas fa-camera" aria-hidden="true"></i></span>
                            <p class="font-display text-lg font-semibold text-white">Kamera belum aktif</p>
                            <p class="mt-2 max-w-xs text-xs leading-6 text-slate-400">Pilih “Buka kamera” atau unggah gambar dan PDF yang memuat QR Code.</p>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                        <button type="button" id="btn-start-scan" @click="startScanning()" x-show="!isScanning" :disabled="isStarting" class="verification-button flex-1 disabled:cursor-not-allowed disabled:opacity-60">
                            <i class="fas fa-camera" :class="{ 'fa-spin': isStarting }" aria-hidden="true"></i>
                            <span x-text="isStarting ? 'Menyiapkan kamera...' : 'Buka kamera'">Buka kamera</span>
                        </button>
                        <button type="button" id="btn-stop-scan" @click="stopScanning()" x-show="isScanning" x-cloak class="inline-flex min-h-11 flex-1 items-center justify-center gap-2 rounded-xl bg-rose-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-rose-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-600 focus-visible:ring-offset-2">
                            <i class="fas fa-stop" aria-hidden="true"></i>Hentikan kamera
                        </button>
                        <button type="button" id="btn-switch-cam" @click="switchCamera()" x-show="isScanning && availableCameras.length > 1" x-cloak class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-3.5 text-slate-700 transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-teal-600 dark:border-slate-600 dark:bg-slate-800 dark:text-white" title="Ganti kamera" aria-label="Ganti kamera">
                            <i class="fas fa-camera-rotate" aria-hidden="true"></i>
                        </button>
                        <input type="file" id="qr-file-input" x-ref="fileInput" @change="handleFileChange($event)" accept="image/*,application/pdf" class="hidden">
                        <button type="button" id="btn-upload-file" @click="triggerUpload()" class="verification-button-secondary flex-1">
                            <i class="fas fa-file-arrow-up text-teal-700 dark:text-teal-400" aria-hidden="true"></i>Unggah PDF / gambar
                        </button>
                    </div>
                    <div id="qr-reader-results" role="status" aria-live="polite" x-html="resultHtml" class="mt-3 min-h-6 text-center text-sm font-semibold text-slate-700 dark:text-slate-200"></div>

                    <div id="debug-panel" x-show="debugVisible" x-cloak class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-4 text-left text-sm dark:border-rose-900 dark:bg-rose-950/30">
                        <h3 class="mb-2 flex items-center gap-2 font-bold text-rose-800 dark:text-rose-300"><i class="fas fa-triangle-exclamation" aria-hidden="true"></i>Kamera tidak dapat diakses</h3>
                        <p class="mb-3 leading-6 text-slate-600 dark:text-slate-300">Izinkan akses kamera pada browser dan pastikan situs menggunakan koneksi aman (HTTPS).</p>
                        <div class="space-y-1 rounded-lg border border-rose-100 bg-white p-3 font-mono text-[11px] text-rose-700 dark:border-rose-900 dark:bg-slate-950 dark:text-rose-300">
                            <div><strong>Protokol:</strong> <span id="debug-protocol" x-text="debugProtocol"></span></div>
                            <div><strong>Koneksi aman:</strong> <span id="debug-secure" x-text="debugSecure"></span></div>
                            <div id="debug-error-msg" x-text="debugErrorMsg" class="break-words border-t border-rose-100 pt-1.5 dark:border-rose-900"></div>
                        </div>
                    </div>
                </section>

                <aside class="space-y-5">
                    <section aria-labelledby="manual-heading" class="verification-card p-5 sm:p-6">
                        <span class="mb-5 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300"><i class="fas fa-keyboard" aria-hidden="true"></i></span>
                        <h2 id="manual-heading" class="font-display text-xl font-bold text-slate-900 dark:text-white">Cari tanpa kamera</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">Masukkan nomor sertifikat atau token yang tertera pada ID Card.</p>
                        <form action="{{ route('certificate.search') }}" method="POST" class="mt-6 space-y-4" x-data="{ manualInput: '' }">
                            @csrf
                            <div>
                                <label for="nomor_sertifikat" class="mb-2 block text-xs font-bold text-slate-700 dark:text-slate-200">Nomor sertifikat / token ID Card</label>
                                <div class="relative">
                                    <input type="text" id="nomor_sertifikat" name="nomor_sertifikat" x-model="manualInput" placeholder="Masukkan nomor atau token" autocomplete="off" required class="min-h-12 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-11 text-sm text-slate-900 placeholder-slate-400 transition focus:border-teal-700 focus:outline-none focus:ring-4 focus:ring-teal-700/10 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-teal-400">
                                    <button type="button" x-show="manualInput.length > 0" x-cloak @click="manualInput = ''" class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-slate-400 hover:text-slate-700 dark:hover:text-white" aria-label="Hapus isian"><i class="fas fa-times-circle" aria-hidden="true"></i></button>
                                </div>
                            </div>
                            <button type="submit" class="verification-button w-full"><i class="fas fa-search" aria-hidden="true"></i>Periksa dokumen</button>
                        </form>
                        <p class="mt-5 border-t border-slate-100 pt-4 text-xs leading-6 text-slate-500 dark:border-slate-800 dark:text-slate-400"><i class="fas fa-circle-info mr-1.5 text-teal-700 dark:text-teal-400" aria-hidden="true"></i>Nomor sertifikat berada di bagian atas dokumen atau di bawah QR Code.</p>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5 dark:border-slate-800 dark:bg-slate-900/60" aria-labelledby="scan-guide-heading">
                        <h2 id="scan-guide-heading" class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Hasil pemindaian yang baik</h2>
                        <ul class="mt-4 space-y-3 text-xs leading-5 text-slate-600 dark:text-slate-400">
                            <li class="flex gap-3"><i class="fas fa-check mt-1 text-teal-700 dark:text-teal-400" aria-hidden="true"></i><span>Pastikan seluruh QR terlihat dan tidak terpotong.</span></li>
                            <li class="flex gap-3"><i class="fas fa-check mt-1 text-teal-700 dark:text-teal-400" aria-hidden="true"></i><span>Gunakan pencahayaan cukup dan hindari pantulan.</span></li>
                            <li class="flex gap-3"><i class="fas fa-check mt-1 text-teal-700 dark:text-teal-400" aria-hidden="true"></i><span>Berkas dapat berupa JPG, PNG, atau PDF.</span></li>
                        </ul>
                    </section>
                </aside>
            </main>

            <footer class="mt-8 flex flex-col gap-3 border-t border-slate-200 pt-5 text-xs leading-6 text-slate-500 dark:border-slate-800 dark:text-slate-400 sm:flex-row sm:items-center sm:justify-between">
                <p class="flex items-start gap-2"><i class="fas fa-lock mt-1.5 text-teal-700 dark:text-teal-400" aria-hidden="true"></i>Pemindaian berkas diproses langsung pada perangkat Anda.</p>
                <p>Kamera memerlukan izin browser dan koneksi HTTPS.</p>
            </footer>
        </div>
    </div>

    @push('scripts')
    <script>
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
                isScanning: false,
                isStarting: false,
                availableCameras: [],
                currentCameraIndex: 0,
                statusText: 'Siap',
                statusClass: 'text-slate-600 dark:text-slate-400 font-extrabold',
                resultHtml: '',
                debugVisible: false,
                debugProtocol: window.location.protocol,
                debugSecure: window.isSecureContext ? "YA" : "TIDAK (Wajib HTTPS)",
                debugErrorMsg: '',
                html5QrCode: null,

                init() {
                    window._activeQrScanner = this;
                    this.debugProtocol = window.location.protocol;
                    this.debugSecure = window.isSecureContext ? "YA" : "TIDAK (Wajib HTTPS)";

                    // Keep a stable reference so the listeners can be removed in
                    // destroy(). They must stay attached for the whole lifetime of
                    // the component (no { once: true }) so the camera is stopped on
                    // every navigation away, not just the first one.
                    this._cleanup = () => {
                        this.stopScanning();
                    };

                    document.addEventListener('turbo:before-cache', this._cleanup);
                    document.addEventListener('turbo:before-render', this._cleanup);
                    window.addEventListener('pagehide', this._cleanup);

                    this.ensureLibraries().catch(() => {});
                },

                // Alpine v3 invokes destroy() when Turbo detaches the element:
                // stop the camera and detach listeners to avoid leaks.
                destroy() {
                    this.stopScanning();
                    if (this._cleanup) {
                        document.removeEventListener('turbo:before-cache', this._cleanup);
                        document.removeEventListener('turbo:before-render', this._cleanup);
                        window.removeEventListener('pagehide', this._cleanup);
                        this._cleanup = null;
                    }
                },

                ensureLibraries() {
                    return Promise.all([
                        this.ensureHtml5Qrcode(),
                        this.ensurePdfJs()
                    ]);
                },

                ensureHtml5Qrcode() {
                    if (typeof window.Html5Qrcode !== 'undefined') {
                        return Promise.resolve(window.Html5Qrcode);
                    }
                    if (window.SimagangVendor && window.SimagangVendor.html5Qrcode) {
                        return window.SimagangVendor.html5Qrcode().then(lib => {
                            window.Html5Qrcode = lib;
                            return lib;
                        });
                    }
                    return Promise.reject(new Error("Gagal memuat library pemindai QR."));
                },

                ensurePdfJs() {
                    if (typeof window.pdfjsLib !== 'undefined') {
                        return Promise.resolve(window.pdfjsLib);
                    }
                    if (window.SimagangVendor && window.SimagangVendor.pdfjs) {
                        return window.SimagangVendor.pdfjs().then(lib => {
                            window.pdfjsLib = lib;
                            return lib;
                        });
                    }
                    return Promise.reject(new Error("Gagal memuat library PDF.js."));
                },

                setStatus(message, className) {
                    this.statusText = message;
                    this.statusClass = className;
                },

                showDebug(error) {
                    console.error("Camera error:", error);
                    this.debugVisible = true;
                    const errorName = error && error.name ? error.name : "CameraError";
                    const errorMessage = error && error.message ? error.message : String(error || "Akses kamera ditolak.");
                    this.debugErrorMsg = errorName + ": " + errorMessage;
                    this.setStatus("Gagal", "text-rose-600 dark:text-rose-400 font-extrabold");
                },

                async startScanning() {
                    if (this.isStarting || this.isScanning) return;

                    if (!window.isSecureContext || !navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        this.showDebug({ 
                            name: "NotSupportedError", 
                            message: "Browser membutuhkan protokol HTTPS untuk mengakses kamera." 
                        });
                        return;
                    }

                    this.debugVisible = false;
                    this.resultHtml = "";
                    this.isStarting = true;
                    this.setStatus("Memulai Kamera...", "text-amber-500 font-extrabold");

                    try {
                        await this.ensureHtml5Qrcode();
                        
                        if (typeof window.Html5Qrcode === 'undefined') {
                            throw new Error("Library pemindai belum siap. Muat ulang halaman.");
                        }

                        if (!this.html5QrCode) {
                            this.html5QrCode = new window.Html5Qrcode("qr-reader");
                        }

                        try {
                            const devices = await window.Html5Qrcode.getCameras();
                            if (devices && devices.length > 0) {
                                this.availableCameras = devices;
                            }
                        } catch(e) {}

                        const cameraIdOrConfig = this.availableCameras.length > 0 && this.availableCameras[this.currentCameraIndex] 
                            ? this.availableCameras[this.currentCameraIndex].id 
                            : { facingMode: "environment" };

                        const config = { 
                            fps: 15, 
                            qrbox: { width: 260, height: 260 }
                        };

                        await this.html5QrCode.start(
                            cameraIdOrConfig, 
                            config, 
                            (decodedText) => this.onScanSuccess(decodedText),
                            undefined
                        );

                        this.isScanning = true;
                        this.setStatus("Kamera Aktif", "text-emerald-600 dark:text-emerald-400 font-extrabold");
                    } catch(err) {
                        this.showDebug(err);
                        this.isScanning = false;
                        if (this.html5QrCode) {
                            try { await this.html5QrCode.clear(); } catch(e) {}
                            this.html5QrCode = null;
                        }
                    } finally {
                        this.isStarting = false;
                    }
                },

                async stopScanning() {
                    if (this.isStarting) return;

                    var scanner = this.html5QrCode;
                    this.html5QrCode = null;
                    this.isScanning = false;

                    if (scanner) {
                        try {
                            if (scanner.isScanning) {
                                await scanner.stop();
                            }
                        } catch (err) {}
                        try {
                            await scanner.clear();
                        } catch (err) {}
                    }

                    this.setStatus("Siap", "text-slate-600 dark:text-slate-400 font-extrabold");
                },

                async switchCamera() {
                    if (this.availableCameras.length > 1) {
                        this.currentCameraIndex = (this.currentCameraIndex + 1) % this.availableCameras.length;
                        await this.stopScanning();
                        await this.startScanning();
                    }
                },

                triggerUpload() {
                    if (this.$refs.fileInput) {
                        this.$refs.fileInput.click();
                    } else {
                        const fileInput = document.getElementById('qr-file-input');
                        if (fileInput) fileInput.click();
                    }
                },

                async handleFileChange(e) {
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;

                    this.resultHtml = "";
                    this.debugVisible = false;

                    await this.stopScanning();
                    this.setStatus("Membaca Berkas...", "text-amber-500 font-extrabold");

                    try {
                        if (file.type.startsWith('image/')) {
                            await this.scanImageFile(file);
                        } else if (file.type === 'application/pdf') {
                            await this.scanPdfFile(file);
                        } else {
                            this.resultHtml = "<span class='text-rose-500 font-bold'>Gunakan format Gambar (PNG/JPEG) atau PDF!</span>";
                            this.setStatus("Siap", "text-slate-600 dark:text-slate-400 font-extrabold");
                        }
                    } catch(err) {
                        this.resultHtml = "<span class='text-rose-500 font-bold'>Kode QR tidak ditemukan pada berkas ini.</span>";
                        this.setStatus("Gagal", "text-rose-600 font-extrabold");
                    } finally {
                        if (this.$refs.fileInput) this.$refs.fileInput.value = "";
                    }
                },

                async scanImageFile(file) {
                    await this.ensureHtml5Qrcode();

                    if (typeof window.Html5Qrcode === 'undefined') {
                        this.showDebug({ name: "LibraryError", message: "Library pemindai belum siap." });
                        return;
                    }

                    if (!this.html5QrCode) {
                        this.html5QrCode = new window.Html5Qrcode("qr-reader");
                    }

                    try {
                        const decodedText = await this.html5QrCode.scanFile(file, false);
                        this.onScanSuccess(decodedText);
                    } catch(err) {
                        this.resultHtml = "<span class='text-rose-500 font-bold'>Kode QR tidak ditemukan pada berkas gambar ini.</span>";
                        this.setStatus("Gagal", "text-rose-600 font-extrabold");
                    }
                },

                async scanPdfFile(file) {
                    await this.ensurePdfJs();

                    if (!window.pdfjsLib) {
                        this.showDebug({ name: "PdfJsError", message: "Library PDF belum dimuat. Gunakan format gambar." });
                        return;
                    }

                    return new Promise((resolve) => {
                        const fileReader = new FileReader();
                        fileReader.onload = async () => {
                            try {
                                const typedarray = new Uint8Array(fileReader.result);
                                const pdf = await window.pdfjsLib.getDocument(typedarray).promise;
                                const page = await pdf.getPage(1);
                                
                                const scale = 2.0;
                                const viewport = page.getViewport({ scale: scale });
                                
                                const canvas = document.createElement('canvas');
                                const context = canvas.getContext('2d');
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                
                                await page.render({ canvasContext: context, viewport: viewport }).promise;
                                
                                canvas.toBlob(async (blob) => {
                                    if (!blob) {
                                        this.resultHtml = "<span class='text-rose-500 font-bold'>Gagal konversi PDF ke gambar.</span>";
                                        this.setStatus("Gagal", "text-rose-600 font-extrabold");
                                        resolve();
                                        return;
                                    }
                                    const convertedFile = new File([blob], "pdf_page.png", { type: "image/png" });
                                    await this.scanImageFile(convertedFile);
                                    resolve();
                                }, 'image/png');
                            } catch(err) {
                                this.resultHtml = "<span class='text-rose-500 font-bold'>Gagal membaca dokumen PDF atau QR tidak ditemukan.</span>";
                                this.setStatus("Gagal", "text-rose-600 font-extrabold");
                                resolve();
                            }
                        };
                        fileReader.onerror = () => {
                            this.resultHtml = "<span class='text-rose-500 font-bold'>Gagal membaca file PDF.</span>";
                            this.setStatus("Gagal", "text-rose-600 font-extrabold");
                            resolve();
                        };
                        fileReader.readAsArrayBuffer(file);
                    });
                },

                onScanSuccess(decodedText) {
                    decodedText = String(decodedText || '').trim();
                    if (!decodedText) return;

                    playBeepSound();
                    this.resultHtml = "<span class='text-teal-600 dark:text-teal-400 flex items-center justify-center gap-1.5'><i class='fas fa-check-circle'></i> QR Berhasil Terdeteksi! Mengalihkan...</span>";
                    
                    this.stopScanning().then(() => {
                        const visit = (url) => window.Turbo ? window.Turbo.visit(url) : window.location.assign(url);

                        if (decodedText.includes('/verify-id-card/')) {
                            visit(decodedText);
                        } else if (decodedText.includes('/verify-certificate/')) {
                            visit(decodedText);
                        } else {
                            const idCardMatch = decodedText.match(/verify-id-card\/([a-zA-Z0-9_\-]+)/);
                            if (idCardMatch) {
                                visit("{{ url('/verify-id-card') }}/" + idCardMatch[1]);
                                return;
                            }

                            const certMatch = decodedText.match(/verify-certificate\/([a-zA-Z0-9_\-]+)/);
                            if (certMatch) {
                                visit("{{ url('/verify-certificate') }}/" + certMatch[1]);
                                return;
                            }

                            // Default attempt ID Card verification
                            visit("{{ url('/verify-id-card') }}/" + encodeURIComponent(decodedText));
                        }
                    });
                }
            };
        }

        window.qrScannerManager = qrScannerManager;
        window.stopQrScanning = function() {
            if (window._activeQrScanner) {
                window._activeQrScanner.stopScanning();
            }
        };
    </script>
    <style>
        #qr-reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            border-radius: 0.5rem !important;
        }
    </style>
    @endpush
</x-guest-layout>
