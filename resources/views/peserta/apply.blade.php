<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
        @vite('resources/css/peserta.css')
    @endpush
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-file-signature text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Formulir Lamaran Magang') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('home') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Daftar Lowongan
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- Left Sidebar Detail Lowongan --}}
                <div class="lg:col-span-1 space-y-6 form-sticky-sidebar">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-br from-teal-600 to-teal-800 dark:from-teal-800 dark:to-teal-950 p-6 text-white">
                            <h3 class="font-bold text-sm leading-tight text-teal-100 uppercase tracking-wider">{{ $position->instansi->nama_dinas }}</h3>
                            <h2 class="font-black text-xl sm:text-2xl mt-1 leading-snug">{{ $position->judul_posisi }}</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 rounded-xl text-xs font-bold border border-teal-200 dark:border-teal-800/60 flex items-center">
                                    <i class="fas fa-graduation-cap mr-1.5"></i> {{ $position->required_major }}
                                </span>
                                <span class="px-3 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 rounded-xl text-xs font-bold border border-blue-200 dark:border-blue-800/60 flex items-center">
                                    <i class="fas fa-users mr-1.5"></i> Kuota: {{ $position->kuota }}
                                </span>
                            </div>

                            <div>
                                <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 pb-2 border-b border-gray-100 dark:border-gray-700">Detail Pekerjaan</h4>
                                <div class="prose prose-sm text-gray-600 dark:text-gray-300 text-xs sm:text-sm leading-relaxed whitespace-pre-line">
                                    {{ $position->deskripsi }}
                                    {!! \App\Services\HtmlSanitizer::clean($position->deskripsi) !!}
                                </div>
                            </div>

                            <div class="bg-amber-50/80 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/60 rounded-2xl p-4 flex gap-3 items-start">
                                <i class="fas fa-lightbulb text-amber-500 text-lg mt-0.5"></i>
                                <p class="text-xs text-amber-800 dark:text-amber-300 font-medium leading-relaxed">
                                    Pastikan tanggal magang yang Anda ajukan sesuai dengan ketentuan kampus dan ketersediaan kuota instansi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Form Container --}}
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            <h3 class="font-bold text-gray-800 dark:text-gray-100 text-base flex items-center gap-2">
                                <i class="fas fa-pen-alt text-teal-600 dark:text-teal-400"></i> Lengkapi Data Lamaran
                            </h3>
                        </div>

                        <div class="p-6 sm:p-8">
                            @if(session('error'))
                                <div class="mb-6 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800/60 text-rose-700 dark:text-rose-300 px-4 py-3 rounded-2xl flex items-start gap-3">
                                    <i class="fas fa-exclamation-circle mt-0.5 text-rose-500"></i>
                                    <div>
                                        <p class="font-bold text-xs uppercase tracking-wider">Gagal Mengirim Lamaran</p>
                                        <p class="text-xs mt-1 font-medium">{{ session('error') }}</p>
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('peserta.daftar', $position->id) }}" method="POST" enctype="multipart/form-data" id="applyForm">
                                @csrf
                                <input type="hidden" name="is_waiting_list" id="is_waiting_list" value="0">

                                <div class="mb-8">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">Upload Surat Pengantar <span class="text-rose-500">*</span></label>
                                    <label for="surat" class="upload-zone flex-col group border-2 border-dashed border-gray-300 dark:border-gray-700 hover:border-teal-500 dark:hover:border-teal-400 bg-gray-50/50 dark:bg-gray-900/50 rounded-3xl p-6 transition cursor-pointer flex items-center justify-center">
                                        <div id="upload-empty" class="flex flex-col items-center justify-center py-4">
                                            <div class="w-14 h-14 bg-white dark:bg-gray-800 rounded-2xl shadow-xs flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                                <i class="fas fa-file-pdf text-2xl text-teal-600 dark:text-teal-400 upload-icon"></i>
                                            </div>
                                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 text-center font-medium"><span class="font-bold text-teal-600 dark:text-teal-400">Klik untuk upload</span> atau drag & drop</p>
                                            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">Format PDF saja (Maksimal 2MB)</p>
                                        </div>
                                        <div id="upload-preview" class="hidden flex-col items-center py-4 gap-2">
                                            <i class="fas fa-file-pdf text-3xl text-teal-600 dark:text-teal-400"></i>
                                            <span id="file-name-chip" class="upload-success-chip px-3 py-1 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 rounded-full text-xs font-bold text-emerald-700 dark:text-emerald-300 flex items-center gap-1.5"><i class="fas fa-check"></i> <span id="file-name-text"></span></span>
                                            <span class="text-xs text-gray-400 dark:text-gray-500">Klik untuk mengganti file</span>
                                        </div>
                                        <input id="surat" name="surat" type="file" class="hidden" accept=".pdf" required />
                                    </label>
                                    @error('surat') <p class="text-rose-500 text-xs mt-2 font-bold flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p> @enderror
                                </div>

                                <div class="mb-6">
                                    <div class="flex items-center justify-between mb-3">
                                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Rencana Periode Magang
                                        </label>
                                        <span class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 hidden sm:inline">Pilih durasi cepat atau atur tanggal manual</span>
                                    </div>

                                    {{-- Quick Preset Duration Buttons --}}
                                    <div class="mb-4">
                                        <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                            <i class="fas fa-bolt text-amber-500"></i> Pilihan Cepat Periode
                                        </label>
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5" id="preset-buttons-group">
                                            <button type="button" data-months="1" class="preset-duration-btn group px-3.5 py-2.5 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-teal-500 dark:hover:border-teal-400 text-gray-700 dark:text-gray-300 font-bold text-xs transition flex items-center justify-between shadow-xs cursor-pointer">
                                                <span class="flex items-center gap-2">
                                                    <span class="w-6 h-6 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center text-[10px] font-black group-hover:scale-105 transition">1B</span>
                                                    <span>1 Bulan</span>
                                                </span>
                                                <i class="fas fa-check text-[10px] text-teal-600 dark:text-teal-400 opacity-0 group-[.active]:opacity-100 transition"></i>
                                            </button>
                                            <button type="button" data-months="2" class="preset-duration-btn group px-3.5 py-2.5 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-teal-500 dark:hover:border-teal-400 text-gray-700 dark:text-gray-300 font-bold text-xs transition flex items-center justify-between shadow-xs cursor-pointer">
                                                <span class="flex items-center gap-2">
                                                    <span class="w-6 h-6 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center text-[10px] font-black group-hover:scale-105 transition">2B</span>
                                                    <span>2 Bulan</span>
                                                </span>
                                                <i class="fas fa-check text-[10px] text-teal-600 dark:text-teal-400 opacity-0 group-[.active]:opacity-100 transition"></i>
                                            </button>
                                            <button type="button" data-months="3" class="preset-duration-btn group px-3.5 py-2.5 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-teal-500 dark:hover:border-teal-400 text-gray-700 dark:text-gray-300 font-bold text-xs transition flex items-center justify-between shadow-xs cursor-pointer">
                                                <span class="flex items-center gap-2">
                                                    <span class="w-6 h-6 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center text-[10px] font-black group-hover:scale-105 transition">3B</span>
                                                    <span>3 Bulan</span>
                                                </span>
                                                <i class="fas fa-check text-[10px] text-teal-600 dark:text-teal-400 opacity-0 group-[.active]:opacity-100 transition"></i>
                                            </button>
                                            <button type="button" data-months="6" class="preset-duration-btn group px-3.5 py-2.5 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-teal-500 dark:hover:border-teal-400 text-gray-700 dark:text-gray-300 font-bold text-xs transition flex items-center justify-between shadow-xs cursor-pointer">
                                                <span class="flex items-center gap-2">
                                                    <span class="w-6 h-6 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center text-[10px] font-black group-hover:scale-105 transition">6B</span>
                                                    <span>6 Bulan</span>
                                                </span>
                                                <i class="fas fa-check text-[10px] text-teal-600 dark:text-teal-400 opacity-0 group-[.active]:opacity-100 transition"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="date-timeline-wrapper bg-gray-50/50 dark:bg-gray-900/50 p-5 rounded-3xl border border-gray-200 dark:border-gray-700">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"><i class="fas fa-play-circle text-teal-600 dark:text-teal-400 mr-1"></i>Tanggal Mulai</label>
                                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" 
                                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 transition shadow-xs text-xs font-bold [color-scheme:dark]"
                                                    value="{{ old('tanggal_mulai') }}"
                                                    min="{{ date('Y-m-d') }}" required>
                                                @error('tanggal_mulai')
                                                    <p class="text-rose-500 text-xs mt-2 font-bold">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"><i class="fas fa-stop-circle text-rose-500 mr-1"></i>Tanggal Selesai</label>
                                                <input type="date" id="tanggal_selesai" name="tanggal_selesai" 
                                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 transition shadow-xs text-xs font-bold [color-scheme:dark]"
                                                    value="{{ old('tanggal_selesai') }}"
                                                    min="{{ date('Y-m-d') }}" required>
                                                @error('tanggal_selesai')
                                                    <p class="text-rose-500 text-xs mt-2 font-bold">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div id="duration-badge-wrap" class="mt-4 hidden flex items-center flex-wrap gap-2">
                                            <span id="duration-badge" class="px-3.5 py-1.5 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 rounded-full text-xs font-bold text-teal-700 dark:text-teal-300 inline-flex items-center gap-1.5 shadow-xs"><i class="fas fa-clock"></i> <span id="duration-text"></span></span>
                                            <span id="duration-preset-indicator" class="px-3 py-1 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full text-[11px] font-bold text-gray-600 dark:text-gray-400 hidden"></span>
                                        </div>
                                    </div>
                                </div>

                                <div id="availability-result" class="hidden mb-8">
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                                    <a href="{{ route('home') }}" class="px-6 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-bold hover:bg-gray-50 dark:hover:bg-gray-900 transition text-xs uppercase tracking-wider">
                                        Batal
                                    </a>
                                    <button type="submit" id="submitBtn" class="px-8 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold shadow-md transition active:scale-95 text-xs uppercase tracking-wider flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="fas fa-paper-plane"></i> Kirim Lamaran
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
    document.addEventListener('turbo:load', function() {
        const startInput = document.getElementById('tanggal_mulai');
        const endInput   = document.getElementById('tanggal_selesai');
        const resultDiv  = document.getElementById('availability-result');
        const submitBtn  = document.getElementById('submitBtn');
        const fileInput  = document.getElementById('surat');
        const uploadEmpty   = document.getElementById('upload-empty');
        const uploadPreview = document.getElementById('upload-preview');
        const fileNameText  = document.getElementById('file-name-text');
        const durationWrap  = document.getElementById('duration-badge-wrap');
        const durationText  = document.getElementById('duration-text');
        const presetIndicator = document.getElementById('duration-preset-indicator');
        const presetBtns    = document.querySelectorAll('.preset-duration-btn');
        const positionId = "{{ $position->id }}"; 

        if (!startInput || !endInput) return;

        // File Upload Preview
        fileInput.addEventListener('change', function(){
            if(this.files && this.files.length > 0){
                fileNameText.textContent = this.files[0].name;
                uploadEmpty.classList.add('hidden');
                uploadPreview.classList.remove('hidden');
                uploadPreview.classList.add('flex');
            }
        });

        // Drag & Drop
        const uploadZone = fileInput.closest('label');
        if (uploadZone) {
            uploadZone.addEventListener('dragover', e => { e.preventDefault(); uploadZone.classList.add('border-teal-500'); });
            uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('border-teal-500'));
            uploadZone.addEventListener('drop', e => {
                e.preventDefault();
                uploadZone.classList.remove('border-teal-500');
                if(e.dataTransfer.files[0]?.type === 'application/pdf') {
                    fileInput.files = e.dataTransfer.files;
                    fileNameText.textContent = e.dataTransfer.files[0].name;
                    uploadEmpty.classList.add('hidden');
                    uploadPreview.classList.remove('hidden');
                    uploadPreview.classList.add('flex');
                }
            });
        }

        // Helper hitung tanggal selesai berdasarkan penambahan bulan kalender (Mulai + X Bulan - 1 Hari)
        function calculateEndDateFromMonths(startDateStr, monthsCount) {
            const parts = startDateStr.split('-').map(Number);
            let year = parts[0];
            let month = parts[1] - 1 + monthsCount;
            let day = parts[2];

            year += Math.floor(month / 12);
            month = ((month % 12) + 12) % 12;

            const daysInTargetMonth = new Date(year, month + 1, 0).getDate();
            const clampedDay = Math.min(day, daysInTargetMonth);

            const targetDate = new Date(year, month, clampedDay);
            targetDate.setDate(targetDate.getDate() - 1);

            const pad = (n) => String(n).padStart(2, '0');
            return `${targetDate.getFullYear()}-${pad(targetDate.getMonth() + 1)}-${pad(targetDate.getDate())}`;
        }

        function setActivePresetButton(activeMonths) {
            presetBtns.forEach(btn => {
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

        // Preset Button Click Event
        presetBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const months = parseInt(this.getAttribute('data-months'), 10);
                
                // Jika tanggal mulai belum diisi, gunakan minimal tanggal hari ini
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

        // Duration Calculator & Preset Matching
        function updateDuration() {
            const s = startInput.value, e = endInput.value;
            if(startInput.value) endInput.min = startInput.value;
            if(s && e) {
                const days = Math.round((new Date(e) - new Date(s)) / 86400000) + 1;
                if(days > 0) {
                    const weeks = Math.round(days / 7);
                    const approxMonths = Math.round(days / 30.4);
                    
                    let durationSummary = `${days} hari`;
                    if (approxMonths >= 1) {
                        durationSummary += ` (~${approxMonths} bulan / ${weeks} minggu)`;
                    } else if (weeks >= 1) {
                        durationSummary += ` (~${weeks} minggu)`;
                    }

                    durationText.textContent = durationSummary;
                    durationWrap.classList.remove('hidden');

                    // Cek apakah rentang tanggal cocok dengan preset
                    let matchedPreset = null;
                    [1, 2, 3, 6].forEach(m => {
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
                    durationWrap.classList.add('hidden');
                    setActivePresetButton(null);
                }
            } else {
                durationWrap.classList.add('hidden');
                setActivePresetButton(null);
            }
        }

        function validateDates() {
            const startDate = startInput.value;
            const endDate = endInput.value;
            updateDuration();

            if(startDate) endInput.min = startDate;

            if (startDate && endDate) {
                if (new Date(endDate) < new Date(startDate)) {
                    showResult('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.', 'bg-rose-50 dark:bg-rose-950/60 border-rose-200 dark:border-rose-800/60 text-rose-700 dark:text-rose-300');
                    submitBtn.disabled = true;
                    return;
                }
                checkAvailability(startDate, endDate);
            } else {
                hideResult();
            }
        }

        function checkAvailability(start, end) {
            showResult('loading', 'Sedang memeriksa ketersediaan kuota...', 'bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300');
            submitBtn.disabled = true;

            fetch(`/magang/check-availability/${positionId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ start: start, end: end })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'available') {
                    showResult('success', data.message, data.class);
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    document.getElementById('is_waiting_list').value = "0";
                    submitBtn.className = "px-8 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold shadow-md transition active:scale-95 text-xs uppercase tracking-wider flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed";
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Lamaran';
                } else {
                    let errorMessage = data.message;
                    
                    if(data.suggestion_date) {
                        errorMessage += `
                            <div class="mt-3 text-xs text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                <p class="font-bold mb-2 uppercase tracking-wider">Opsi yang tersedia:</p>
                                <div class="flex flex-col gap-2">
                                    <button type="button" onclick="setWaitingList()" class="text-left w-full px-3 py-2 bg-amber-50 dark:bg-amber-950/60 hover:bg-amber-100 dark:hover:bg-amber-900/50 text-amber-800 dark:text-amber-300 rounded-xl text-xs font-bold border border-amber-200 dark:border-amber-800/60 transition">
                                        <i class="fas fa-clock mr-1"></i> Daftar & Masuk Daftar Tunggu (Otomatis diterima saat ada yang selesai)
                                    </button>
                                    <button type="button" onclick="setStartDate('${data.suggestion_date}')" class="text-left w-full px-3 py-2 bg-teal-50 dark:bg-teal-950/60 hover:bg-teal-100 dark:hover:bg-teal-900/50 text-teal-700 dark:text-teal-300 rounded-xl text-xs font-bold border border-teal-200 dark:border-teal-800/60 transition">
                                        <i class="fas fa-calendar-check mr-1"></i> Ganti Tanggal Mulai ke ${data.suggestion_text}
                                    </button>
                                </div>
                            </div>
                        `;
                    } else {
                        errorMessage += `
                            <div class="mt-3 text-xs text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                <button type="button" onclick="setWaitingList()" class="text-left w-full px-3 py-2 bg-amber-50 dark:bg-amber-950/60 hover:bg-amber-100 dark:hover:bg-amber-900/50 text-amber-800 dark:text-amber-300 rounded-xl text-xs font-bold border border-amber-200 dark:border-amber-800/60 transition">
                                    <i class="fas fa-clock mr-1"></i> Daftar & Masuk Daftar Tunggu (Otomatis diterima saat ada yang selesai)
                                </button>
                            </div>
                        `;
                    }
                    
                    showResult('error', errorMessage, data.class);
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showResult('error', 'Terjadi kesalahan sistem. Coba lagi nanti.', 'bg-rose-50 dark:bg-rose-950/60 border-rose-200 dark:border-rose-800/60 text-rose-700 dark:text-rose-300');
            });
        }

        // Helper: Tampilkan Alert
        function showResult(type, messageHtml, cssClass) {
            let icon = '';
            if(type === 'loading') icon = '<i class="fas fa-circle-notch fa-spin mr-2"></i>';
            else if(type === 'success') icon = '<i class="fas fa-check-circle mr-2 text-emerald-500"></i>';
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
            resultDiv.classList.add('hidden');
        }

        window.setStartDate = function(newDate) {
            const startInput = document.getElementById('tanggal_mulai');
            const endInput = document.getElementById('tanggal_selesai');
            
            startInput.value = newDate;
            endInput.value = '';
            endInput.min = newDate;
            endInput.focus();
            
            document.getElementById('availability-result').classList.add('hidden');
            document.getElementById('is_waiting_list').value = "0";
            
            submitBtn.className = "px-8 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold shadow-md transition active:scale-95 text-xs uppercase tracking-wider flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed";
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Lamaran';
        };

        window.setWaitingList = function() {
            document.getElementById('is_waiting_list').value = "1";
            submitBtn.disabled = false;
            submitBtn.className = "px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold shadow-md transition active:scale-95 text-xs uppercase tracking-wider flex items-center gap-2";
            submitBtn.innerHTML = '<i class="fas fa-clipboard-list"></i> Kirim sebagai Daftar Tunggu';
            
            showResult('success', 'Anda memilih untuk masuk Daftar Tunggu. Silakan klik tombol kirim di bawah.', 'bg-amber-50 dark:bg-amber-950/60 border-amber-200 dark:border-amber-800/60 text-amber-800 dark:text-amber-300');
        };

        document.getElementById('applyForm').addEventListener('submit', function(e) {
            if(!submitBtn.disabled) {
                submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Mengirim Lamaran...';
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            }
        });

        startInput.addEventListener('change', validateDates);
        endInput.addEventListener('change', validateDates);

        // Inisialisasi jika ada old values (misal validation error dari server)
        if (startInput.value && endInput.value) {
            validateDates();
        }
    });
    </script>
</x-app-layout>
