<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-magic text-teal-600"></i>
                {{ __('Pendaftaran Penempatan Otomatis') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('home') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Halaman Utama
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Kolom Kiri: Informasi Penempatan Otomatis -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-br from-teal-600 to-teal-800 p-6 text-white">
                            <span class="bg-teal-500/30 border border-teal-400 text-teal-100 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider block w-fit mb-3">
                                Fitur Cerdas
                            </span>
                            <h2 class="font-extrabold text-2xl leading-snug">Jalur Distribusi Merata</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800">Sesuai Jurusan</h4>
                                    <p class="text-xs text-gray-500 mt-1">Sistem hanya akan menempatkan Anda pada posisi yang sesuai dengan jurusan Anda saat ini: <strong>{{ Auth::user()->major ?? '-' }}</strong>.</p>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <i class="fas fa-balance-scale"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800">Beban Merata</h4>
                                    <p class="text-xs text-gray-500 mt-1">Algoritma penyeimbangan akan memprioritaskan dinas/instansi yang memiliki jumlah peserta magang paling sedikit saat ini.</p>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                    <i class="fas fa-check-double"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800">Kapasitas Aman</h4>
                                    <p class="text-xs text-gray-500 mt-1">Sistem akan secara cerdas memvalidasi bahwa instansi target memiliki sisa kuota yang cukup pada tanggal pelaksanaan pilihan Anda.</p>
                                </div>
                            </div>

                            <div class="bg-teal-50 border border-teal-100 rounded-xl p-4 flex gap-3 mt-4">
                                <i class="fas fa-info-circle text-teal-600 mt-0.5"></i>
                                <p class="text-[11px] text-teal-800 leading-relaxed">
                                    Jalur ini membantu dinas yang sedang kekurangan tenaga magang dan menjamin peluang diterimanya pendaftaran Anda lebih besar karena didistribusikan ke dinas dengan kuota longgar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Pendaftaran -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50 bg-gray-50/30">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-file-signature text-teal-500"></i> Lengkapi Berkas Penempatan
                            </h3>
                        </div>

                        <div class="p-8">
                            @if(session('error'))
                                <div class="mb-6 bg-red-50 border border-red-100 text-red-700 px-4 py-3 rounded-xl flex items-start gap-3">
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
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Upload Surat Pengantar (PDF)</label>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="surat" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition group">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 group-hover:text-teal-500 transition mb-2"></i>
                                                <p class="mb-1 text-sm text-gray-500"><span class="font-semibold text-teal-600">Klik untuk upload</span> atau drag & drop</p>
                                                <p class="text-xs text-gray-400">PDF (Maks. 2MB)</p>
                                            </div>
                                            <input id="surat" name="surat" type="file" class="hidden" accept=".pdf" required />
                                        </label>
                                    </div>
                                    <p id="file-name" class="text-xs text-teal-600 mt-2 font-bold hidden"></p> 
                                    @error('surat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-bold text-gray-700 mb-3">Rencana Periode Magang</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-5 rounded-xl border border-gray-200">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Mulai</label>
                                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" 
                                                class="w-full rounded-lg border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm text-sm"
                                                min="{{ date('Y-m-d') }}" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Selesai</label>
                                            <input type="date" id="tanggal_selesai" name="tanggal_selesai" 
                                                class="w-full rounded-lg border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm text-sm"
                                                min="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                                    <a href="{{ route('home') }}" class="px-6 py-3 rounded-xl border border-gray-300 text-gray-600 font-bold hover:bg-gray-50 transition text-sm">
                                        Batal
                                    </a>
                                    <button type="submit" id="submitBtn" class="px-8 py-3 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 shadow-lg shadow-teal-200 transition transform active:scale-95 text-sm flex items-center gap-2">
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
        const endInput = document.getElementById('tanggal_selesai');
        const submitBtn = document.getElementById('submitBtn');
        const fileInput = document.getElementById('surat');
        const fileNameDisplay = document.getElementById('file-name');
        
        // File Upload Preview
        fileInput.addEventListener('change', function(){
            if(this.files && this.files.length > 0){
                fileNameDisplay.textContent = 'File terpilih: ' + this.files[0].name;
                fileNameDisplay.classList.remove('hidden');
            }
        });

        function validateDates() {
            const startDate = startInput.value;
            const endDate = endInput.value;

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

        startInput.addEventListener('change', validateDates);
        endInput.addEventListener('change', validateDates);
    });
    </script>
</x-app-layout>
