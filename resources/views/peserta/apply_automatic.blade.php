<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
        @vite('resources/css/peserta.css')
    @endpush
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-magic text-teal-600"></i>
                {{ __('Pendaftaran Penempatan Otomatis') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('home') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Halaman Utama
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Kolom Kiri: Informasi Penempatan Otomatis -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-br from-teal-600 to-teal-800 p-6 text-white">
                            <span class="bg-teal-500/30 border border-teal-400 text-teal-100 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider block w-fit mb-3">
                                Fitur Cerdas
                            </span>
                            <h2 class="font-extrabold text-2xl leading-snug">Jalur Distribusi Merata</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="feature-step-item stagger-1">
                                <div class="feature-step-number">1</div>
                                <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/30 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">Sesuai Jurusan</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Sistem hanya akan menempatkan Anda pada posisi yang sesuai dengan jurusan Anda saat ini: <strong>{{ Auth::user()->major ?? '-' }}</strong>.</p>
                                </div>
                            </div>

                            <div class="feature-step-item stagger-2">
                                <div class="feature-step-number">2</div>
                                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                    <i class="fas fa-balance-scale"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">Beban Merata</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Algoritma penyeimbangan akan memprioritaskan dinas/instansi yang memiliki jumlah peserta magang paling sedikit saat ini.</p>
                                </div>
                            </div>

                            <div class="feature-step-item stagger-3">
                                <div class="feature-step-number">3</div>
                                <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                                    <i class="fas fa-check-double"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">Kapasitas Aman</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Sistem akan secara cerdas memvalidasi bahwa instansi target memiliki sisa kuota yang cukup pada tanggal pelaksanaan pilihan Anda.</p>
                                </div>
                            </div>

                            <div class="bg-teal-50 dark:bg-teal-950/20 border border-teal-100 dark:border-teal-900/50 rounded-xl p-4 flex gap-3 mt-4">
                                <i class="fas fa-info-circle text-teal-600 mt-0.5"></i>
                                <p class="text-[11px] text-teal-800 dark:text-teal-400 leading-relaxed">
                                    Jalur ini membantu dinas yang sedang kekurangan tenaga magang dan menjamin peluang diterimanya pendaftaran Anda lebih besar karena didistribusikan ke dinas dengan kuota longgar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Pendaftaran -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-6 border-b border-gray-50 bg-gray-50 dark:bg-gray-900/30">
                            <h3 class="font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                <i class="fas fa-file-signature text-teal-500"></i> Lengkapi Berkas Penempatan
                            </h3>
                        </div>

                        <div class="p-8">
                            @if(session('error'))
                                <div class="mb-6 bg-red-50 dark:bg-red-950/20 border border-red-100 dark:border-red-900/50 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl flex items-start gap-3">
                                    <i class="fas fa-exclamation-circle mt-0.5"></i>
                                    <div>
                                        <p class="font-bold text-sm">Gagal Melakukan Pendaftaran</p>
                                        <p class="text-xs mt-1">{{ session('error') }}</p>
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('peserta.apply_automatic.store') }}" method="POST" enctype="multipart/form-data" id="applyForm">
                                @csrf

                                <div class="mb-8">
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Upload Surat Pengantar <span class="text-red-500">*</span></label>
                                    <label for="surat" class="upload-zone flex-col group">
                                        <div id="upload-empty" class="flex flex-col items-center justify-center py-6">
                                            <div class="w-14 h-14 bg-white dark:bg-gray-800 rounded-2xl shadow-sm flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                                <i class="fas fa-file-pdf text-2xl text-teal-500 upload-icon"></i>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center"><span class="font-bold text-teal-600">Klik untuk upload</span> atau drag & drop</p>
                                            <p class="text-xs text-gray-400 mt-1">PDF saja (Maks. 2MB)</p>
                                        </div>
                                        <div id="upload-preview" class="hidden flex-col items-center py-4 gap-2">
                                            <i class="fas fa-file-pdf text-3xl text-teal-600"></i>
                                            <span class="upload-success-chip"><i class="fas fa-check"></i> <span id="file-name-text"></span></span>
                                            <span class="text-xs text-gray-400">Klik untuk ganti file</span>
                                        </div>
                                        <input id="surat" name="surat" type="file" class="hidden" accept=".pdf" required />
                                    </label>
                                    @error('surat') <p class="text-red-500 text-xs mt-2 font-bold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Rencana Periode Magang</label>
                                    <div class="date-timeline-wrapper">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5"><i class="fas fa-play-circle text-teal-500 mr-1"></i>Tanggal Mulai</label>
                                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" 
                                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm text-sm"
                                                    min="{{ date('Y-m-d') }}" required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5"><i class="fas fa-stop-circle text-red-500 mr-1"></i>Tanggal Selesai</label>
                                                <input type="date" id="tanggal_selesai" name="tanggal_selesai" 
                                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm text-sm"
                                                    min="{{ date('Y-m-d') }}" required>
                                            </div>
                                        </div>
                                        <div id="duration-badge-wrap" class="mt-3 hidden">
                                            <span class="date-duration-badge"><i class="fas fa-clock"></i> <span id="duration-text"></span></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                                    <a href="{{ route('home') }}" class="px-6 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 font-bold hover:bg-gray-50 dark:hover:bg-gray-900 transition text-sm">
                                        Batal
                                    </a>
                                    <button type="submit" id="submitBtn" class="btn-ripple px-8 py-3 bg-gradient-to-r from-teal-500 to-teal-700 hover:from-teal-600 hover:to-teal-800 text-white rounded-xl font-bold shadow-lg shadow-teal-200 dark:shadow-teal-900/30 transition transform active:scale-95 text-sm flex items-center gap-2">
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
        const submitBtn  = document.getElementById('submitBtn');
        const fileInput  = document.getElementById('surat');
        const uploadEmpty   = document.getElementById('upload-empty');
        const uploadPreview = document.getElementById('upload-preview');
        const fileNameText  = document.getElementById('file-name-text');
        const durationWrap  = document.getElementById('duration-badge-wrap');
        const durationText  = document.getElementById('duration-text');
        
        // Premium File Upload Preview
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
        uploadZone.addEventListener('dragover', e => { e.preventDefault(); uploadZone.classList.add('drag-over'); });
        uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('drag-over'));
        uploadZone.addEventListener('drop', e => {
            e.preventDefault();
            uploadZone.classList.remove('drag-over');
            if(e.dataTransfer.files[0]?.type === 'application/pdf') {
                fileInput.files = e.dataTransfer.files;
                fileNameText.textContent = e.dataTransfer.files[0].name;
                uploadEmpty.classList.add('hidden');
                uploadPreview.classList.remove('hidden');
                uploadPreview.classList.add('flex');
            }
        });

        // Duration Calculator
        function updateDuration() {
            const s = startInput.value, e = endInput.value;
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
                    alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
        }

        // Submit loading state
        document.getElementById('applyForm').addEventListener('submit', function() {
            if(!submitBtn.disabled) {
                submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Mengirim...';
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            }
        });

        startInput.addEventListener('change', validateDates);
        endInput.addEventListener('change', validateDates);
    });
    </script>
</x-app-layout>
