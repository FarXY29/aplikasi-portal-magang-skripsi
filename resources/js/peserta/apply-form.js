/**
 * Shared form logic for the two "lamar magang" forms:
 *   - resources/views/peserta/apply.blade.php           (manual apply + availability check)
 *   - resources/views/peserta/apply_automatic.blade.php (automatic placement, no availability check)
 *
 * Exposes window.initPesertaApplyForm(config) so each Blade view can pass only
 * the values it needs (form id, availability URL, csrf token, flags). This
 * removes the large duplicated inline <script> previously copy-pasted between
 * the two views.
 *
 * config = {
 *   formId: 'applyForm',
 *   availabilityUrl: string|null,  // null disables the availability check (automatic form)
 *   csrfToken: string,
 *   enableAvailability: boolean,
 *   isWaitingListInput: boolean,   // whether the form has #is_waiting_list hidden input
 * }
 */
(function () {
    'use strict';

    function calculateEndDateFromMonths(startDateStr, monthsCount) {
        const parts = startDateStr.split('-').map(Number);
        let year = parts[0];
        let month = parts[1] - 1 + monthsCount;
        const day = parts[2];

        year += Math.floor(month / 12);
        month = ((month % 12) + 12) % 12;

        const daysInTargetMonth = new Date(year, month + 1, 0).getDate();
        const clampedDay = Math.min(day, daysInTargetMonth);

        const targetDate = new Date(year, month, clampedDay);
        targetDate.setDate(targetDate.getDate() - 1);

        const pad = (n) => String(n).padStart(2, '0');
        return `${targetDate.getFullYear()}-${pad(targetDate.getMonth() + 1)}-${pad(targetDate.getDate())}`;
    }

    function init(config) {
        const startInput = document.getElementById('tanggal_mulai');
        const endInput = document.getElementById('tanggal_selesai');
        const submitBtn = document.getElementById('submitBtn');
        const fileInput = document.getElementById('surat');
        const uploadEmpty = document.getElementById('upload-empty');
        const uploadPreview = document.getElementById('upload-preview');
        const fileNameText = document.getElementById('file-name-text');
        const durationWrap = document.getElementById('duration-badge-wrap');
        const durationText = document.getElementById('duration-text');
        const presetIndicator = document.getElementById('duration-preset-indicator');
        const presetBtns = document.querySelectorAll('.preset-duration-btn');
        const resultDiv = config.enableAvailability ? document.getElementById('availability-result') : null;
        const form = document.getElementById(config.formId);

        if (!startInput || !endInput || !form) return;

        // ── File upload preview ────────────────────────────────────────────
        if (fileInput) {
            fileInput.addEventListener('change', function () {
                if (this.files && this.files.length > 0) {
                    fileNameText.textContent = this.files[0].name;
                    uploadEmpty.classList.add('hidden');
                    uploadPreview.classList.remove('hidden');
                    uploadPreview.classList.add('flex');
                }
            });

            const uploadZone = fileInput.closest('label');
            if (uploadZone) {
                uploadZone.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.classList.add('border-teal-500'); });
                uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('border-teal-500'));
                uploadZone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    uploadZone.classList.remove('border-teal-500');
                    if (e.dataTransfer.files[0]?.type === 'application/pdf') {
                        fileInput.files = e.dataTransfer.files;
                        fileNameText.textContent = e.dataTransfer.files[0].name;
                        uploadEmpty.classList.add('hidden');
                        uploadPreview.classList.remove('hidden');
                        uploadPreview.classList.add('flex');
                    }
                });
            }
        }

        // ── Preset duration buttons ────────────────────────────────────────
        function setActivePresetButton(activeMonths) {
            presetBtns.forEach((btn) => {
                const m = parseInt(btn.getAttribute('data-months'), 10);
                if (m === activeMonths) {
                    btn.classList.add('active', 'border-teal-500', 'dark:border-teal-400', 'bg-teal-50/50', 'dark:bg-teal-950/40', 'ring-2', 'ring-teal-500/20');
                    btn.classList.remove('border-gray-200', 'dark:border-gray-700', 'bg-white', 'dark:bg-gray-800');
                } else {
                    btn.classList.remove('active', 'border-teal-500', 'dark:border-teal-400', 'bg-teal-50/50', 'dark:bg-teal-950/40', 'ring-2', 'ring-teal-500/20');
                    btn.classList.add('border-gray-200', 'dark:border-gray-700', 'bg-white', 'dark:bg-gray-800');
                }
            });
        }

        presetBtns.forEach((btn) => {
            btn.addEventListener('click', function () {
                const months = parseInt(this.getAttribute('data-months'), 10);

                if (!startInput.value) {
                    const today = new Date();
                    const pad = (n) => String(n).padStart(2, '0');
                    const todayStr = startInput.min || `${today.getFullYear()}-${pad(today.getMonth() + 1)}-${pad(today.getDate())}`;
                    startInput.value = todayStr;
                }

                const computedEnd = calculateEndDateFromMonths(startInput.value, months);
                endInput.value = computedEnd;
                setActivePresetButton(months);
                validateDates();
            });
        });

        // ── Duration calculator & preset matching ──────────────────────────
        function updateDuration() {
            const s = startInput.value, e = endInput.value;
            if (startInput.value) endInput.min = startInput.value;
            if (s && e) {
                const days = Math.round((new Date(e) - new Date(s)) / 86400000) + 1;
                if (days > 0) {
                    const weeks = Math.round(days / 7);
                    const approxMonths = Math.round(days / 30.4);

                    let durationSummary = `${days} hari`;
                    if (approxMonths >= 1) {
                        durationSummary += ` (~${approxMonths} bulan / ${weeks} minggu)`;
                    } else if (weeks >= 1) {
                        durationSummary += ` (~${weeks} minggu)`;
                    }

                    if (durationText) durationText.textContent = durationSummary;
                    if (durationWrap) durationWrap.classList.remove('hidden');

                    let matchedPreset = null;
                    [1, 2, 3, 6].forEach((m) => {
                        if (calculateEndDateFromMonths(s, m) === e) {
                            matchedPreset = m;
                        }
                    });

                    if (matchedPreset !== null) {
                        setActivePresetButton(matchedPreset);
                        if (presetIndicator) {
                            presetIndicator.textContent = `Preset: ${matchedPreset} Bulan`;
                            presetIndicator.classList.remove('hidden');
                        }
                    } else {
                        setActivePresetButton(null);
                        if (presetIndicator) {
                            presetIndicator.textContent = 'Durasi Kustom';
                            presetIndicator.classList.remove('hidden');
                        }
                    }
                } else {
                    if (durationWrap) durationWrap.classList.add('hidden');
                    setActivePresetButton(null);
                }
            } else {
                if (durationWrap) durationWrap.classList.add('hidden');
                setActivePresetButton(null);
            }
        }

        // ── Availability helpers (manual form only) ────────────────────────
        function showResult(type, messageHtml, cssClass) {
            if (!resultDiv) return;
            let icon = '';
            if (type === 'loading') icon = '<i class="fas fa-circle-notch fa-spin mr-2"></i>';
            else if (type === 'success') icon = '<i class="fas fa-check-circle mr-2 text-emerald-500"></i>';
            else icon = '<i class="fas fa-times-circle mr-2 text-rose-500"></i>';

            resultDiv.className = `p-4 rounded-2xl border availability-result-enter ${cssClass}`;
            resultDiv.innerHTML = `
                <div class="flex items-start">
                    <div class="mt-0.5">${icon}</div>
                    <div class="ml-2 w-full text-xs font-medium">${messageHtml}</div>
                </div>`;
            resultDiv.classList.remove('hidden');
        }

        function hideResult() {
            if (resultDiv) resultDiv.classList.add('hidden');
        }

        function restoreSubmitButton() {
            if (!submitBtn) return;
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            submitBtn.className = 'px-8 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold shadow-md transition active:scale-95 text-xs uppercase tracking-wider flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed';
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Lamaran';
        }

        function checkAvailability(start, end) {
            showResult('loading', 'Sedang memeriksa ketersediaan kuota...', 'bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300');
            if (submitBtn) submitBtn.disabled = true;

            fetch(config.availabilityUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': config.csrfToken,
                },
                body: JSON.stringify({ start: start, end: end }),
            })
                .then((response) => {
                    if (!response.ok) throw new Error('availability_http_' + response.status);
                    return response.json();
                })
                .then((data) => {
                    if (data.status === 'available') {
                        showResult('success', data.message, data.class);
                        restoreSubmitButton();
                        const wl = document.getElementById('is_waiting_list');
                        if (wl) wl.value = '0';
                    } else {
                        let errorMessage = data.message;

                        const waitingListBtn = `
                            <button type="button" onclick="setWaitingList()" class="text-left w-full px-3 py-2 bg-amber-50 dark:bg-amber-950/60 hover:bg-amber-100 dark:hover:bg-amber-900/50 text-amber-800 dark:text-amber-300 rounded-xl text-xs font-bold border border-amber-200 dark:border-amber-800/60 transition">
                                <i class="fas fa-clock mr-1"></i> Daftar & Masuk Daftar Tunggu (Otomatis diterima saat ada yang selesai)
                            </button>`;

                        if (data.suggestion_date) {
                            errorMessage += `
                                <div class="mt-3 text-xs text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                    <p class="font-bold mb-2 uppercase tracking-wider">Opsi yang tersedia:</p>
                                    <div class="flex flex-col gap-2">
                                        ${waitingListBtn}
                                        <button type="button" onclick="setStartDate('${data.suggestion_date}')" class="text-left w-full px-3 py-2 bg-teal-50 dark:bg-teal-950/60 hover:bg-teal-100 dark:hover:bg-teal-900/50 text-teal-700 dark:text-teal-300 rounded-xl text-xs font-bold border border-teal-200 dark:border-teal-800/60 transition">
                                            <i class="fas fa-calendar-check mr-1"></i> Ganti Tanggal Mulai ke ${data.suggestion_text}
                                        </button>
                                    </div>
                                </div>`;
                        } else {
                            errorMessage += `
                                <div class="mt-3 text-xs text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                    ${waitingListBtn}
                                </div>`;
                        }

                        showResult('error', errorMessage, data.class);
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        }
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    restoreSubmitButton();
                    const isRateLimited = String(error && error.message).indexOf('429') !== -1;
                    const msg = isRateLimited
                        ? 'Terlalu banyak permintaan pemeriksaan kuota. Mohon tunggu sebentar lalu coba lagi.'
                        : 'Terjadi kesalahan sistem. Coba lagi nanti.';
                    showResult('error', msg, 'bg-rose-50 dark:bg-rose-950/60 border-rose-200 dark:border-rose-800/60 text-rose-700 dark:text-rose-300');
                });
        }

        function validateDates() {
            const startDate = startInput.value;
            const endDate = endInput.value;
            updateDuration();

            if (startDate) endInput.min = startDate;

            if (!config.enableAvailability) {
                // Automatic form: only client-side ordering validation.
                if (startDate && endDate) {
                    if (new Date(endDate) < new Date(startDate)) {
                        alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        }
                    } else if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                }
                return;
            }

            if (startDate && endDate) {
                if (new Date(endDate) < new Date(startDate)) {
                    showResult('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.', 'bg-rose-50 dark:bg-rose-950/60 border-rose-200 dark:border-rose-800/60 text-rose-700 dark:text-rose-300');
                    if (submitBtn) submitBtn.disabled = true;
                    return;
                }
                checkAvailability(startDate, endDate);
            } else {
                hideResult();
            }
        }

        // ── Globals used by result buttons ─────────────────────────────────
        window.setStartDate = function (newDate) {
            startInput.value = newDate;
            endInput.value = '';
            endInput.min = newDate;
            endInput.focus();

            if (resultDiv) resultDiv.classList.add('hidden');
            const wl = document.getElementById('is_waiting_list');
            if (wl) wl.value = '0';

            restoreSubmitButton();
        };

        window.setWaitingList = function () {
            const wl = document.getElementById('is_waiting_list');
            if (wl) wl.value = '1';
            if (!submitBtn) return;
            submitBtn.disabled = false;
            submitBtn.className = 'px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold shadow-md transition active:scale-95 text-xs uppercase tracking-wider flex items-center gap-2';
            submitBtn.innerHTML = '<i class="fas fa-clipboard-list"></i> Kirim sebagai Daftar Tunggu';

            showResult('success', 'Anda memilih untuk masuk Daftar Tunggu. Silakan klik tombol kirim di bawah.', 'bg-amber-50 dark:bg-amber-950/60 border-amber-200 dark:border-amber-800/60 text-amber-800 dark:text-amber-300');
        };

        // ── Submit loading state ───────────────────────────────────────────
        form.addEventListener('submit', function () {
            if (submitBtn && !submitBtn.disabled) {
                submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Mengirim Lamaran...';
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            }
        });

        startInput.addEventListener('change', validateDates);
        endInput.addEventListener('change', validateDates);

        // Re-run when server returns old values (e.g. validation error).
        if (startInput.value && endInput.value) {
            validateDates();
        }
    }

    window.initPesertaApplyForm = init;

    // Wire up automatically: the config blob is emitted by the Blade view.
    // Module scripts execute after the DOM is parsed, and Turbo fires
    // 'turbo:load' on every navigation (including the first). We run init()
    // immediately (for the initial load) and on each subsequent turbo:load.
    function boot() {
        var el = document.getElementById('peserta-apply-config');
        if (el) {
            try {
                init(JSON.parse(el.textContent));
            } catch (e) {
                console.error('apply-form init failed', e);
            }
        }
    }

    if (!window.__pesertaApplyFormWired) {
        window.__pesertaApplyFormWired = true;
        document.addEventListener('turbo:load', boot);
    }
    boot();
})();
