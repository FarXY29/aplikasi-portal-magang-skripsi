/**
 * Peserta logbook client logic.
 *
 * Handles:
 *  - Image upload preview & removal for daily journal entries.
 *  - Alpine.data('logbookData') state binding (status filtering, edit modal, date filters).
 *  - Geolocation tracking for journal submission.
 */
(function () {
    'use strict';

    function getLogbookConfig() {
        const configEl = document.getElementById('peserta-logbook-config');
        if (!configEl) return {};
        try {
            return JSON.parse(configEl.textContent || '{}');
        } catch (e) {
            console.error('Failed to parse peserta-logbook-config JSON:', e);
            return {};
        }
    }

    function previewImage(input) {
        const preview = document.getElementById('preview-img');
        const previewContainer = document.getElementById('image-preview');
        const emptyState = document.getElementById('empty-state');
        const removeBtn = document.getElementById('remove-btn');
        
        if (input && input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (preview) preview.src = e.target.result;
                if (previewContainer) previewContainer.classList.remove('hidden');
                if (emptyState) emptyState.classList.add('hidden');
                if (removeBtn) {
                    removeBtn.classList.remove('hidden');
                    removeBtn.classList.add('flex');
                }
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            removeImage();
        }
    }

    function removeImage() {
        const fileInput = document.getElementById('foto');
        if (fileInput) fileInput.value = '';
        const preview = document.getElementById('preview-img');
        if (preview) preview.src = '';
        const previewContainer = document.getElementById('image-preview');
        if (previewContainer) previewContainer.classList.add('hidden');
        const emptyState = document.getElementById('empty-state');
        if (emptyState) emptyState.classList.remove('hidden');
        const removeBtn = document.getElementById('remove-btn');
        if (removeBtn) {
            removeBtn.classList.add('hidden');
            removeBtn.classList.remove('flex');
        }
    }

    function getLogbookComponentData() {
        const cfg = getLogbookConfig();
        return {
            filter: cfg.filterStatus || 'semua',
            filterTanggal: cfg.filterDate || '',
            filterBulan: cfg.filterMonth || '',
            
            // Edit Modal State
            showEditModal: false,
            editLogId: null,
            editTanggal: '',
            editKegiatan: '',
            editStatusValidasi: '',
            
            init() {
                const oldLogId = cfg.oldLogId;
                if (oldLogId) {
                    this.editLogId = oldLogId;
                    this.editKegiatan = cfg.oldKegiatan || '';
                    this.editStatusValidasi = cfg.oldStatusValidasi || '';
                    this.editTanggal = cfg.oldTanggal || '';
                    this.showEditModal = true;
                }
            },
            openEditModal(id, tanggal, kegiatan, statusValidasi) {
                this.editLogId = id;
                this.editTanggal = tanggal;
                this.editKegiatan = kegiatan;
                this.editStatusValidasi = statusValidasi;
                this.showEditModal = true;
            },
            resetFilters() {
                window.location.href = cfg.resetUrl || window.location.pathname;
            }
        };
    }

    function registerAlpineData() {
        if (window.__pesertaLogbookAlpineWired) return;
        window.__pesertaLogbookAlpineWired = true;

        if (window.Alpine) {
            window.Alpine.data('logbookData', () => getLogbookComponentData());
        } else {
            document.addEventListener('alpine:init', () => {
                Alpine.data('logbookData', () => getLogbookComponentData());
            }, { once: true });
        }
    }

    function initLogbookLocation() {
        const statusDiv = document.getElementById('loc-status');
        const btnSubmit = document.getElementById('btn-submit');
        const coordsDisplay = document.getElementById('coords-display');
        const showLat = document.getElementById('show-lat');
        const showLng = document.getElementById('show-lng');

        if (!statusDiv || !btnSubmit) return;

        function requestLocation() {
            if (navigator.geolocation) {
                const options = {
                    enableHighAccuracy: false, 
                    timeout: 30000,            
                    maximumAge: 300000         
                };
                navigator.geolocation.getCurrentPosition(successLocation, errorLocation, options);
            } else {
                showErrorMsg('Browser tidak mendukung Geolocation.');
            }
        }

        function successLocation(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            const latInput = document.getElementById('lat');
            const lngInput = document.getElementById('lng');
            if (latInput) latInput.value = lat;
            if (lngInput) lngInput.value = lng;

            statusDiv.innerHTML = '<span class="text-emerald-600 dark:text-emerald-400 flex items-center font-bold"><i class="fas fa-check-circle mr-1.5 text-base"></i> Lokasi Terkunci</span>';
            if (showLat) showLat.innerText = lat.toFixed(6);
            if (showLng) showLng.innerText = lng.toFixed(6);
            if (coordsDisplay) coordsDisplay.classList.remove('hidden');
            
            btnSubmit.disabled = false;
        }

        function errorLocation(error) {
            let msg = 'Gagal mengambil lokasi.';
            let retryBtn = '';

            switch(error.code) {
                case error.PERMISSION_DENIED:
                    msg = 'Izin lokasi ditolak. Buka pengaturan browser.'; 
                    break;
                case error.POSITION_UNAVAILABLE:
                    msg = 'Sinyal lokasi tidak ditemukan. Pastikan GPS aktif.'; 
                    break;
                case error.TIMEOUT:
                    msg = 'Waktu habis. Sinyal lemah.';
                    retryBtn = `<button type="button" onclick="window.location.reload()" class="ml-2 underline text-teal-600 dark:text-teal-400">Coba Lagi</button>`;
                    break;
            }
            showErrorMsg(msg, retryBtn);
        }

        function showErrorMsg(msg, extraHtml = '') {
            statusDiv.innerHTML = `<div class="flex flex-col"><span class="text-rose-600 dark:text-rose-400 flex items-center font-bold text-xs"><i class="fas fa-exclamation-triangle mr-1.5"></i> ${msg}</span>${extraHtml}</div>`;
            btnSubmit.disabled = true;
        }

        requestLocation();
    }

    // Expose globals for inline markup hooks
    window.previewImage = previewImage;
    window.removeImage = removeImage;
    window.initLogbookLocation = initLogbookLocation;

    registerAlpineData();

    if (!window.__pesertaLogbookLocationWired) {
        window.__pesertaLogbookLocationWired = true;
        document.addEventListener('turbo:load', initLogbookLocation);
    }

    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        initLogbookLocation();
    }
})();
