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
                                <div class="prose prose-sm text-gray-600 dark:text-gray-300 text-xs sm:text-sm leading-relaxed">
                                    {!! $position->deskripsi !!}
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
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">Rencana Periode Magang</label>
                                    <div class="date-timeline-wrapper bg-gray-50/50 dark:bg-gray-900/50 p-5 rounded-3xl border border-gray-200 dark:border-gray-700">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"><i class="fas fa-play-circle text-teal-600 dark:text-teal-400 mr-1"></i>Tanggal Mulai</label>
                                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" 
                                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 transition shadow-xs text-xs font-bold [color-scheme:dark]"
                                                    min="{{ date('Y-m-d') }}" required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"><i class="fas fa-stop-circle text-rose-500 mr-1"></i>Tanggal Selesai</label>
                                                <input type="date" id="tanggal_selesai" name="tanggal_selesai" 
                                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 transition shadow-xs text-xs font-bold [color-scheme:dark]"
                                                    min="{{ date('Y-m-d') }}" required>
                                            </div>
                                        </div>
                                        <div id="duration-badge-wrap" class="mt-4 hidden">
                                            <span id="duration-badge" class="px-3.5 py-1.5 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 rounded-full text-xs font-bold text-teal-700 dark:text-teal-300 inline-flex items-center gap-1.5 shadow-xs"><i class="fas fa-clock"></i> <span id="duration-text"></span></span>
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

        // Duration Calculator
        function updateDuration() {
            const s = startInput.value, e = endInput.value;
            if(startInput.value) endInput.min = startInput.value;
            if(s && e) {
                const days = Math.round((new Date(e) - new Date(s)) / 86400000) + 1;
                if(days > 0) {
                    durationText.textContent = days + ' hari (' + Math.round(days/7) + ' minggu)';
                    durationWrap.classList.remove('hidden');
                } else {
                    durationWrap.classList.add('hidden');
                }
            } else {
                durationWrap.classList.add('hidden');
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
    });
    </script>
</x-app-layout>