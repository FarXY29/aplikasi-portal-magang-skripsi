/**
 * Peserta dashboard client logic.
 *
 * Handles:
 *  - Automatic GPS detection + geofence status banner.
 *  - Attendance (clock in/out) submission with anti-fraud nonce + geolocation.
 *  - Dynamic QR scanner (html5-qrcode) for kiosk attendance.
 *
 * Server-generated values (challenge route) are supplied via the
 * #peserta-dashboard-config JSON blob emitted by the Blade view.
 */
(function () {
    'use strict';

    let config = { challengeUrl: '' };
    let latestGpsPosition = null;
    let latestGpsTimestamp = 0;

    // Singleton scanner state so it survives Turbo navigations without
    // leaking the camera MediaStream. See Batch 3 of the code review.
    window.__pesertaQr = window.__pesertaQr || {
        scanner: null,
        targetFormId: null,
        targetLatId: null,
        targetLngId: null,
        targetBtnId: null,
        challenge: null,
    };

    // ─────────────────────────────────────────────────────────────────────
    // GPS geofence banner
    // ─────────────────────────────────────────────────────────────────────
    function updateGpsStatusUI(lat, lng) {
        const banner = document.getElementById('gps-status-banner');
        if (!banner) return;

        const officeLat = parseFloat(banner.dataset.lat);
        const officeLng = parseFloat(banner.dataset.lng);
        const radius = parseInt(banner.dataset.radius) || 100;

        const iconWrapper = document.getElementById('gps-icon-wrapper');
        const title = document.getElementById('gps-title');
        const desc = document.getElementById('gps-desc');
        const badge = document.getElementById('gps-badge');

        const R = 6371000;
        const dLat = (officeLat - lat) * Math.PI / 180;
        const dLon = (officeLng - lng) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat * Math.PI / 180) * Math.cos(officeLat * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const distance = Math.round(R * c);

        if (distance <= radius) {
            banner.className = "px-4 py-3 sm:px-6 sm:py-4 bg-emerald-50/80 dark:bg-emerald-950/20 border-t border-emerald-200/60 dark:border-emerald-900/50 flex items-center justify-between flex-wrap gap-3 transition-all duration-300";
            if (iconWrapper) {
                iconWrapper.className = "w-11 h-11 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-lg shadow-md shadow-emerald-500/20 shrink-0";
                iconWrapper.innerHTML = '<i class="fas fa-map-marker-alt"></i>';
            }
            if (title) {
                title.className = "text-xs font-extrabold text-emerald-900 dark:text-emerald-300 uppercase tracking-wider";
                title.innerText = "Lokasi Terverifikasi (Dalam Radius)";
            }
            if (desc) {
                desc.className = "text-xs text-emerald-700 dark:text-emerald-400 font-medium";
                desc.innerText = `Jarak Anda: ${distance} meter dari kantor (Batas maksimal ${radius}m). Anda siap melakukan absensi!`;
            }
            if (badge) {
                badge.className = "min-h-[44px] px-3.5 py-2 sm:py-1.5 rounded-xl bg-white dark:bg-gray-800 text-emerald-700 dark:text-emerald-400 text-xs font-extrabold shadow-sm border border-emerald-200/60 dark:border-emerald-900/50 flex items-center gap-1.5 shrink-0";
                badge.innerHTML = '<i class="fas fa-check-circle text-emerald-500"></i> Siap Absen';
                badge.onclick = null;
            }
        } else {
            banner.className = "px-4 py-3 sm:px-6 sm:py-4 bg-amber-50/80 dark:bg-amber-950/20 border-t border-amber-200/60 dark:border-amber-900/50 flex items-center justify-between flex-wrap gap-3 transition-all duration-300";
            if (iconWrapper) {
                iconWrapper.className = "w-11 h-11 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-lg shadow-md shadow-amber-500/20 shrink-0";
                iconWrapper.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
            }
            if (title) {
                title.className = "text-xs font-extrabold text-amber-900 dark:text-amber-300 uppercase tracking-wider";
                title.innerText = "Di Luar Radius Kantor";
            }
            if (desc) {
                desc.className = "text-xs text-amber-700 dark:text-amber-400 font-medium";
                desc.innerText = `Jarak Anda: ${distance} meter dari kantor (Batas maksimal ${radius}m). Mendekatlah ke kantor untuk absen.`;
            }
            if (badge) {
                badge.className = "min-h-[44px] px-3.5 py-2 sm:py-1.5 rounded-xl bg-white dark:bg-gray-800 text-amber-700 dark:text-amber-400 text-xs font-extrabold shadow-sm border border-amber-200/60 dark:border-amber-900/50 flex items-center gap-1.5 shrink-0";
                badge.innerHTML = `<i class="fas fa-ruler-horizontal text-amber-500"></i> Jarak: ${distance}m`;
                badge.onclick = null;
            }
        }
    }

    function autoDetectGPS() {
        const banner = document.getElementById('gps-status-banner');
        if (!banner) return;

        const iconWrapper = document.getElementById('gps-icon-wrapper');
        const title = document.getElementById('gps-title');
        const desc = document.getElementById('gps-desc');
        const badge = document.getElementById('gps-badge');

        if (!navigator.geolocation) {
            banner.className = "px-4 py-3 sm:px-6 sm:py-4 bg-rose-50/60 dark:bg-rose-950/20 border-t border-rose-100 dark:border-rose-900/50 flex items-center justify-between flex-wrap gap-3 transition-all duration-300";
            if (iconWrapper) {
                iconWrapper.className = "w-11 h-11 rounded-2xl bg-rose-500 text-white flex items-center justify-center text-lg shadow-md shadow-rose-500/20 shrink-0";
                iconWrapper.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
            }
            if (title) {
                title.className = "text-xs font-extrabold text-rose-900 dark:text-rose-300 uppercase tracking-wider";
                title.innerText = "GPS Tidak Didukung";
            }
            if (desc) {
                desc.className = "text-xs text-rose-600 dark:text-rose-400 font-medium";
                desc.innerText = "Browser Anda tidak mendukung fitur geolokasi GPS.";
            }
            if (badge) {
                badge.className = "min-h-[44px] px-3.5 py-2 sm:py-1.5 rounded-xl bg-white dark:bg-gray-800 text-rose-700 dark:text-rose-400 text-xs font-extrabold shadow-sm border border-rose-200/60 dark:border-rose-900/50 flex items-center gap-1.5 shrink-0";
                badge.innerHTML = '<i class="fas fa-times"></i> Gagal';
                badge.onclick = null;
            }
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function (position) {
                latestGpsPosition = position;
                latestGpsTimestamp = Date.now();

                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                const latMasuk = document.getElementById('lat-masuk');
                const lngMasuk = document.getElementById('lng-masuk');
                const latPulang = document.getElementById('lat-pulang');
                const lngPulang = document.getElementById('lng-pulang');

                if (latMasuk && lngMasuk) {
                    latMasuk.value = lat.toFixed(6);
                    lngMasuk.value = lng.toFixed(6);
                }
                if (latPulang && lngPulang) {
                    latPulang.value = lat.toFixed(6);
                    lngPulang.value = lng.toFixed(6);
                }

                updateGpsStatusUI(lat, lng);
            },
            function (error) {
                banner.className = "px-4 py-3 sm:px-6 sm:py-4 bg-rose-50/80 dark:bg-rose-950/20 border-t border-rose-200/60 dark:border-rose-900/50 flex items-center justify-between flex-wrap gap-3 transition-all duration-300";
                if (iconWrapper) {
                    iconWrapper.className = "w-11 h-11 rounded-2xl bg-rose-500 text-white flex items-center justify-center text-lg shadow-md shadow-rose-500/20 shrink-0";
                    iconWrapper.innerHTML = '<i class="fas fa-map-pin"></i>';
                }
                if (title) {
                    title.className = "text-xs font-extrabold text-rose-900 dark:text-rose-300 uppercase tracking-wider";
                    title.innerText = "Izin Lokasi (GPS) Diperlukan";
                }

                let errorMsg = "Sistem gagal mengambil lokasi GPS Anda. Pastikan GPS aktif.";
                if (error.code === 1) errorMsg = "Akses lokasi ditolak! Silakan izinkan akses lokasi (GPS) pada browser/HP Anda.";
                else if (error.code === 2) errorMsg = "Sinyal GPS tidak ditemukan. Pastikan GPS HP/perangkat Anda aktif.";
                else if (error.code === 3) errorMsg = "Waktu permintaan lokasi habis (timeout). Silakan coba lagi.";

                if (desc) {
                    desc.className = "text-xs text-rose-700 dark:text-rose-400 font-medium";
                    desc.innerText = errorMsg;
                }
                if (badge) {
                    badge.className = "min-h-[44px] px-3.5 py-2 sm:py-1.5 rounded-xl bg-white dark:bg-gray-800 text-rose-700 dark:text-rose-400 text-xs font-extrabold shadow-sm border border-rose-200/60 dark:border-rose-900/50 flex items-center gap-1.5 cursor-pointer hover:bg-rose-50 dark:hover:bg-rose-950/20 shrink-0";
                    badge.innerHTML = '<i class="fas fa-sync-alt mr-1"></i> Coba Deteksi Ulang';
                    badge.onclick = autoDetectGPS;
                }
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Attendance submit (clock in / out)
    // ─────────────────────────────────────────────────────────────────────
    function addRipple(btn, event) {
        const circle = document.createElement('span');
        circle.classList.add('ripple-circle');
        const rect = btn.getBoundingClientRect();
        circle.style.left = (event.clientX - rect.left) + 'px';
        circle.style.top = (event.clientY - rect.top) + 'px';
        btn.appendChild(circle);
        setTimeout(() => circle.remove(), 650);
    }

    function handleAbsenClick(event, formId, latId, lngId, btnId) {
        event.preventDefault();
        const btn = document.getElementById(btnId);
        if (btn) addRipple(btn, event);
        const originalHtml = btn ? btn.innerHTML : '';
        const form = document.getElementById(formId);

        if (!form) return;

        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung fitur geolokasi GPS. Silakan gunakan browser/HP lain.');
            return;
        }

        if (btn) {
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> <span>Menyiapkan Sesi Aman...</span>';
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
        }

        function restoreButton() {
            if (btn) {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        }

        function submitWithPosition(position, challenge) {
            latestGpsPosition = position;
            latestGpsTimestamp = Date.now();
            fillAntiFraudFields(form, latId, lngId, position, challenge);
            updateGpsStatusUI(position.coords.latitude, position.coords.longitude);
            if (btn) {
                btn.innerHTML = '<i class="fas fa-check-circle"></i> <span>Lokasi Terkunci! Mengirim...</span>';
            }
            setTimeout(() => form.submit(), 300);
        }

        // Anti-fraud: minta challenge nonce dari server SEBELUM mengirim absensi.
        // Nonce single-use & berumur pendek → mencegah replay request absensi.
        fetch(config.challengeUrl, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (res) {
                if (!res.ok) throw new Error('challenge_failed_' + res.status);
                return res.json();
            })
            .then(function (challenge) {
                if (latestGpsPosition && (Date.now() - latestGpsTimestamp) < 20000) {
                    submitWithPosition(latestGpsPosition, challenge);
                    return;
                }

                if (btn) {
                    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> <span>Mendeteksi GPS...</span>';
                }

                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        submitWithPosition(position, challenge);
                    },
                    function (error) {
                        restoreButton();
                        let msg = 'Gagal mengambil lokasi GPS Anda.';
                        if (error.code === 1) msg = 'Akses Lokasi (GPS) ditolak! Izinkan akses lokasi pada browser Anda.';
                        else if (error.code === 2) msg = 'Sinyal GPS tidak ditemukan. Pastikan GPS aktif.';
                        else if (error.code === 3) msg = 'Waktu permintaan lokasi habis. Silakan coba lagi.';
                        alert(msg);
                    },
                    { enableHighAccuracy: true, timeout: 15000, maximumAge: 10000 }
                );
            })
            .catch(function () {
                restoreButton();
                alert('Gagal menyiapkan sesi keamanan absensi. Periksa koneksi internet Anda lalu coba lagi.');
            });
    }

    function formatNumericCoord(val) {
        if (val === null || val === undefined || isNaN(val)) return '';
        return String(Math.round(val));
    }

    function fillAntiFraudFields(form, latId, lngId, position, challenge) {
        const c = position.coords;
        if (typeof c.latitude === 'number' && !isNaN(c.latitude)) {
            const latEl = document.getElementById(latId);
            if (latEl) latEl.value = c.latitude.toFixed(6);
        }
        if (typeof c.longitude === 'number' && !isNaN(c.longitude)) {
            const lngEl = document.getElementById(lngId);
            if (lngEl) lngEl.value = c.longitude.toFixed(6);
        }

        setOrCreateHidden(form, 'nonce', challenge.nonce);
        setOrCreateHidden(form, 'idempotency_key',
            (window.crypto && crypto.randomUUID) ? crypto.randomUUID() : 'abs-' + Date.now() + '-' + Math.random().toString(36).slice(2));

        const ts = (position.timestamp && !isNaN(position.timestamp)) ? Math.round(position.timestamp) : Date.now();
        setOrCreateHidden(form, 'client_timestamp', String(ts));
        setOrCreateHidden(form, 'accuracy', formatNumericCoord(c.accuracy));
        setOrCreateHidden(form, 'altitude', formatNumericCoord(c.altitude));
        setOrCreateHidden(form, 'speed', formatNumericCoord(c.speed));
        setOrCreateHidden(form, 'heading', formatNumericCoord(c.heading));
    }

    function setOrCreateHidden(form, name, value) {
        let input = form.querySelector('input[name="' + name + '"]');
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            form.appendChild(input);
        }
        input.value = value;
    }

    // ─────────────────────────────────────────────────────────────────────
    // Dynamic QR scanner (html5-qrcode)
    // ─────────────────────────────────────────────────────────────────────
    function stopQrScanner() {
        const state = window.__pesertaQr;
        const scanner = state.scanner;
        state.scanner = null;
        if (!scanner) return Promise.resolve();
        return Promise.resolve()
            .then(() => scanner.stop())
            .catch(() => {})
            .then(() => {
                try {
                    if (typeof scanner.clear === 'function') scanner.clear();
                } catch (e) { /* ignore */ }
            });
    }

    function setGpsBadgeStatus(status, text) {
        const icon = document.getElementById('qr-gps-icon');
        const textEl = document.getElementById('qr-gps-text');
        if (textEl) textEl.innerText = text;
        if (icon) {
            if (status === 'loading') icon.className = 'fas fa-satellite-dish text-teal-600 animate-pulse';
            else if (status === 'success') icon.className = 'fas fa-check-circle text-emerald-600';
            else icon.className = 'fas fa-times-circle text-rose-600';
        }
    }

    function setSecBadgeStatus(status, text) {
        const icon = document.getElementById('qr-sec-icon');
        const textEl = document.getElementById('qr-sec-text');
        if (textEl) textEl.innerText = text;
        if (icon) {
            if (status === 'loading') icon.className = 'fas fa-shield-alt text-teal-600 animate-spin';
            else if (status === 'success') icon.className = 'fas fa-shield-halved text-emerald-600';
            else icon.className = 'fas fa-times-circle text-rose-600';
        }
    }

    function resetQrModalUi() {
        const modal = document.getElementById('modal-qr-scanner');
        if (modal) modal.classList.add('hidden');
        const overlayLoading = document.getElementById('qr-loading-overlay');
        const overlayError = document.getElementById('qr-error-overlay');
        if (overlayLoading) overlayLoading.classList.remove('hidden');
        if (overlayError) overlayError.classList.add('hidden');
        setGpsBadgeStatus('loading', 'Kunci GPS...');
        setSecBadgeStatus('loading', 'Sesi Keamanan...');
    }

    function ensureHtml5QrcodeLibrary() {
        return new Promise((resolve, reject) => {
            if (typeof window.Html5Qrcode !== 'undefined') {
                resolve();
                return;
            }
            const existing = document.querySelector('script[src*="html5-qrcode"]');
            if (existing) {
                existing.addEventListener('load', resolve);
                existing.addEventListener('error', reject);
                return;
            }
            const script = document.createElement('script');
            // Versi di-pin + SRI agar tidak mengeksekusi kode pihak ketiga
            // yang berubah tanpa sepengetahuan kita (supply-chain risk).
            script.src = 'https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js';
            script.integrity = 'sha384-c9d8RFSL+u3exBOJ4Yp3HUJXS4znl9f+z66d1y54ig+ea249SpqR+w1wyvXz/lk+';
            script.crossOrigin = 'anonymous';
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    function openQrScanner(event, formId, latId, lngId, btnId, actionLabel) {
        if (event) event.preventDefault();
        const state = window.__pesertaQr;
        state.targetFormId = formId;
        state.targetLatId = latId;
        state.targetLngId = lngId;
        state.targetBtnId = btnId;

        const modal = document.getElementById('modal-qr-scanner');
        const titleEl = document.getElementById('qr-modal-title');
        if (titleEl && actionLabel) {
            titleEl.innerText = 'Pindai Dynamic QR (' + actionLabel + ')';
        }
        if (modal) modal.classList.remove('hidden');

        // A11y: pindahkan fokus ke tombol tutup saat modal dibuka.
        const closeBtn = document.getElementById('qr-modal-close');
        if (closeBtn) closeBtn.focus();

        setGpsBadgeStatus('loading', 'Kunci GPS...');
        setSecBadgeStatus('loading', 'Sesi Keamanan...');

        fetch(config.challengeUrl, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => res.json())
            .then(data => {
                state.challenge = data;
                setSecBadgeStatus('success', 'Sesi Keamanan Siap');
            })
            .catch(() => {
                setSecBadgeStatus('error', 'Gagal Sesi');
            });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                pos => {
                    latestGpsPosition = pos;
                    latestGpsTimestamp = Date.now();
                    setGpsBadgeStatus('success', 'GPS Terkunci');
                },
                () => {
                    setGpsBadgeStatus('error', 'GPS Gagal');
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 5000 }
            );
        }

        startScanner();
    }

    async function startScanner() {
        const state = window.__pesertaQr;
        const overlayLoading = document.getElementById('qr-loading-overlay');
        const overlayError = document.getElementById('qr-error-overlay');
        if (overlayLoading) overlayLoading.classList.remove('hidden');
        if (overlayError) overlayError.classList.add('hidden');

        try {
            await ensureHtml5QrcodeLibrary();

            // Hentikan instance lama (mis. saat menekan "Coba Lagi") sebelum memulai yang baru.
            await stopQrScanner();

            const scanner = new window.Html5Qrcode('qr-reader');
            state.scanner = scanner;
            const qrConfig = { fps: 15, qrbox: { width: 250, height: 250 } };

            await scanner.start(
                { facingMode: 'environment' },
                qrConfig,
                onQrCodeScanned,
                () => {}
            );

            if (overlayLoading) overlayLoading.classList.add('hidden');
        } catch (err) {
            console.error('Scanner error:', err);
            if (overlayLoading) overlayLoading.classList.add('hidden');
            if (overlayError) overlayError.classList.remove('hidden');
        }
    }

    function onQrCodeScanned(decodedText) {
        if (!decodedText) return;

        if (navigator.vibrate) navigator.vibrate(100);

        const state = window.__pesertaQr;

        stopQrScanner();

        const modal = document.getElementById('modal-qr-scanner');
        if (modal) modal.classList.add('hidden');

        const form = document.getElementById(state.targetFormId);
        if (!form) return;

        setOrCreateHidden(form, 'qr_token', decodedText);

        const btn = document.getElementById(state.targetBtnId);
        if (btn) {
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> <span>Memproses Presensi...</span>';
            btn.disabled = true;
        }

        const ensureChallenge = state.challenge
            ? Promise.resolve(state.challenge)
            : fetch(config.challengeUrl, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            }).then(res => res.json());

        const ensureGps = (latestGpsPosition && (Date.now() - latestGpsTimestamp) < 20000)
            ? Promise.resolve(latestGpsPosition)
            : new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, { enableHighAccuracy: true, timeout: 10000, maximumAge: 5000 });
            });

        Promise.all([ensureChallenge, ensureGps])
            .then(([challenge, position]) => {
                fillAntiFraudFields(form, state.targetLatId, state.targetLngId, position, challenge);
                form.submit();
            })
            .catch(() => {
                alert('Gagal memvalidasi sesi keamanan atau lokasi GPS. Silakan coba scan ulang.');
                window.location.reload();
            });
    }

    function closeQrScannerModal() {
        stopQrScanner();
        const modal = document.getElementById('modal-qr-scanner');
        if (modal) modal.classList.add('hidden');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Global exposure (used by inline onclick/onchange attributes in markup)
    // ─────────────────────────────────────────────────────────────────────
    window.autoDetectGPS = autoDetectGPS;
    window.handleAbsenClick = handleAbsenClick;
    window.openQrScanner = openQrScanner;
    window.closeQrScannerModal = closeQrScannerModal;
    window.startScanner = startScanner;

    // ─────────────────────────────────────────────────────────────────────
    // Wiring (idempotent across Turbo navigations)
    // ─────────────────────────────────────────────────────────────────────
    function boot() {
        const el = document.getElementById('peserta-dashboard-config');
        if (el) {
            try { config = JSON.parse(el.textContent); } catch (e) { /* keep defaults */ }
        }
        autoDetectGPS();
    }

    if (!window.__pesertaDashboardGpsWired) {
        window.__pesertaDashboardGpsWired = true;
        window.addEventListener('turbo:load', boot);
    }

    if (!window.__pesertaQrLifecycleWired) {
        window.__pesertaQrLifecycleWired = true;
        document.addEventListener('turbo:before-cache', function () {
            stopQrScanner();
            resetQrModalUi();
        });
        document.addEventListener('turbo:before-visit', function () {
            stopQrScanner();
        });
        // A11y: tombol Escape menutup modal pemindai.
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            const modal = document.getElementById('modal-qr-scanner');
            if (modal && !modal.classList.contains('hidden')) {
                closeQrScannerModal();
            }
        });
    }

    boot();
})();
