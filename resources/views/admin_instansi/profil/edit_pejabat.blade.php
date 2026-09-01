<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengaturan Tanda Tangan Sertifikat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-blue-50 dark:bg-blue-950/20 border-l-4 border-blue-500 p-4 mb-6 rounded-r shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            Data yang Anda isi di sini akan otomatis muncul pada bagian <strong>"Mengetahui" (Tanda Tangan Kiri)</strong> di Transkrip Nilai dan Sertifikat Magang.
                        </p>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" class="mb-6">
                    {{ session('success') }}
                </x-ui.alert>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    
                    <form action="{{ route('dinas.pejabat.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">
                                        Jabatan Penandatangan
                                    </label>
                                    <input type="text" name="jabatan_pejabat" 
                                           value="{{ old('jabatan_pejabat', $instansi->jabatan_pejabat) }}"
                                           placeholder="Contoh: Kepala Dinas Komunikasi dan Informatika"
                                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-gray-800 dark:text-gray-100 shadow-sm focus:border-teal-500 focus:ring-teal-500 transition text-sm" 
                                           required>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jabatan ini akan muncul di baris pertama tanda tangan.</p>
                                    @error('jabatan_pejabat') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">
                                        Nama Lengkap Pejabat
                                    </label>
                                    <input type="text" name="nama_pejabat" 
                                           value="{{ old('nama_pejabat', $instansi->nama_pejabat) }}"
                                           placeholder="Nama Lengkap beserta gelar"
                                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-gray-800 dark:text-gray-100 shadow-sm focus:border-teal-500 focus:ring-teal-500 transition text-sm" 
                                           required>
                                    @error('nama_pejabat') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">
                                        NIP (Nomor Induk Pegawai)
                                    </label>
                                    <input type="text" name="nip_pejabat" 
                                           value="{{ old('nip_pejabat', $instansi->nip_pejabat) }}"
                                           placeholder="19xxxxxxxx xxx x xxx"
                                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-gray-800 dark:text-gray-100 shadow-sm focus:border-teal-500 focus:ring-teal-500 transition text-sm font-mono" 
                                           required>
                                    @error('nip_pejabat') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">
                                    Scan Tanda Tangan (PNG Transparan)
                                </label>
                                
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900 h-40 flex items-center justify-center mb-3 relative overflow-hidden group">
                                    <img id="preview-ttd" src="{{ $instansi->ttd_kepala ? asset('storage/' . $instansi->ttd_kepala) : '' }}" class="h-28 object-contain z-10 {{ $instansi->ttd_kepala ? '' : 'hidden' }}">
                                    
                                    <div id="no-ttd-text" class="text-center text-gray-400 dark:text-gray-500 {{ $instansi->ttd_kepala ? 'hidden' : '' }}">
                                        <i class="fas fa-image text-3xl mb-1.5"></i>
                                        <p class="text-xs font-semibold">Belum ada tanda tangan</p>
                                    </div>

                                    <div id="ttd-hover-text" class="absolute inset-0 bg-white/90 dark:bg-gray-800/90 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 z-20 {{ $instansi->ttd_kepala ? '' : 'hidden' }}">
                                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Tanda tangan saat ini</span>
                                    </div>
                                </div>

                                <input type="file" id="ttd_kepala_input" name="ttd_kepala" accept="image/png" onchange="previewTtd(this)"
                                    class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 dark:file:bg-teal-950/60 file:text-teal-700 dark:file:text-teal-300 hover:file:bg-teal-100 cursor-pointer border border-gray-300 dark:border-gray-700 rounded-xl p-1 bg-white dark:bg-gray-900">
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1.5 ml-1">
                                    *Format wajib <strong>PNG Transparan</strong> (Maks 2MB) agar hasil cetak rapi.
                                </p>
                                @error('ttd_kepala') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-teal-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-teal-700 active:bg-teal-900 focus:outline-none focus:border-teal-900 focus:ring ring-teal-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-lg shadow-teal-500/20">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
            
            <div class="mt-8">
                <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-4">Preview Tanda Tangan:</h3>
                <div class="bg-gray-100 dark:bg-gray-800 p-8 rounded-2xl border border-gray-200 dark:border-gray-700 flex justify-center">
                    <div class="text-center bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-300 dark:border-gray-600 w-full sm:w-2/3 md:w-1/2 rounded-xl">
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Mengetahui,</p>
                        <p class="font-bold text-sm text-gray-800 dark:text-gray-100 mb-2">{{ $instansi->jabatan_pejabat ?? 'Nama Jabatan' }}</p>
                        
                        <div class="h-28 flex items-center justify-center my-2 relative">
                            <img id="doc-preview-ttd" src="{{ $instansi->ttd_kepala ? asset('storage/' . $instansi->ttd_kepala) : '' }}" class="h-24 object-contain {{ $instansi->ttd_kepala ? '' : 'hidden' }}">
                            <div id="doc-no-ttd" class="w-full h-full border-2 border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/60 flex items-center justify-center text-xs text-gray-400 dark:text-gray-500 italic rounded-xl {{ $instansi->ttd_kepala ? 'hidden' : '' }}">
                                Area Tanda Tangan
                            </div>
                        </div> 
                        
                        <p class="font-bold underline text-sm text-gray-900 dark:text-gray-100">{{ $instansi->nama_pejabat ?? 'Nama Pejabat' }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 font-mono mt-0.5">NIP. {{ $instansi->nip_pejabat ?? '....................' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function previewTtd(input) {
            const preview = document.getElementById('preview-ttd');
            const noTtdText = document.getElementById('no-ttd-text');
            const hoverText = document.getElementById('ttd-hover-text');
            
            // For the document preview box
            const docPreview = document.getElementById('doc-preview-ttd');
            const docNoTtd = document.getElementById('doc-no-ttd');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (preview) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    }
                    if (noTtdText) noTtdText.classList.add('hidden');
                    if (hoverText) {
                        hoverText.classList.remove('hidden');
                        const span = hoverText.querySelector('span');
                        if (span) span.innerText = 'Preview tanda tangan baru';
                    }

                    if (docPreview) {
                        docPreview.src = e.target.result;
                        docPreview.classList.remove('hidden');
                    }
                    if (docNoTtd) {
                        docNoTtd.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>