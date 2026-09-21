{{-- SECTION 3: DATA PENANDATANGAN DOKUMEN & PREVIEW TAMPILAN SIDE-BY-SIDE --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    
    {{-- CARD 3: DATA PENANDATANGAN DOKUMEN --}}
    <div class="lg:col-span-7 bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/60 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl border border-teal-100 dark:border-teal-900/50 flex-shrink-0">
                <i class="fas fa-file-signature"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Data Penandatangan Dokumen</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Digunakan untuk legalisasi otomatis pada Sertifikat dan Transkrip Nilai.</p>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            <div class="bg-teal-50/60 dark:bg-teal-950/40 border border-teal-200 dark:border-teal-800/60 rounded-2xl p-4 mb-6 flex items-start gap-3">
                <i class="fas fa-info-circle text-teal-600 dark:text-teal-400 mt-0.5 text-base flex-shrink-0"></i>
                <p class="text-xs text-teal-800 dark:text-teal-300 leading-relaxed font-medium">
                    Data pejabat di bawah ini akan otomatis tercetak pada bagian tanda tangan dokumen resmi (Sertifikat & Transkrip). Pastikan data sudah sesuai.
                </p>
            </div>

            <form action="{{ route('dinas.pejabat.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Jabatan</label>
                            <input type="text" name="jabatan_pejabat" value="{{ old('jabatan_pejabat', $instansi->jabatan_pejabat) }}"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-xs font-bold shadow-xs"
                                placeholder="Contoh: Kepala Dinas Kominfo">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Nama</label>
                            <input type="text" name="nama_pejabat" value="{{ old('nama_pejabat', $instansi->nama_pejabat) }}"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-xs font-bold shadow-xs"
                                placeholder="Nama Lengkap beserta gelar">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">NIP</label>
                            <input type="text" name="nip_pejabat" value="{{ old('nip_pejabat', $instansi->nip_pejabat) }}"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-xs font-mono font-bold shadow-xs"
                                placeholder="19xxxxxxxx xxx x xxx">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Singkatan Instansi</label>
                            <input type="text" name="singkatan" value="{{ old('singkatan', $instansi->singkatan) }}"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-xs font-bold shadow-xs uppercase"
                                placeholder="Contoh: DISKOMINFO, BPKPAD">
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">Digunakan untuk penomoran LoA & surat resmi.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Email Resmi Instansi (Kop Surat)</label>
                            <input type="email" name="email" value="{{ old('email', $instansi->email) }}"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-xs font-bold shadow-xs"
                                placeholder="Contoh: diskominfo@banjarmasinkota.go.id">
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">Dicantumkan pada kop surat dan dokumen resmi cetak.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Scan Tanda Tangan (PNG Transparan)</label>
                        
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900 h-36 flex items-center justify-center mb-3 relative overflow-hidden group">
                            <img id="preview-ttd" src="{{ $instansi->ttd_kepala ? asset('storage/' . $instansi->ttd_kepala) : '' }}" class="h-24 object-contain z-10 {{ $instansi->ttd_kepala ? '' : 'hidden' }}">
                            
                            <div id="no-ttd-text" class="text-center text-gray-400 dark:text-gray-500 {{ $instansi->ttd_kepala ? 'hidden' : '' }}">
                                <i class="fas fa-image text-2xl mb-1"></i>
                                <p class="text-xs font-semibold">Belum ada tanda tangan</p>
                            </div>

                            <div id="ttd-hover-text" class="absolute inset-0 bg-white/90 dark:bg-gray-800/90 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 z-20 {{ $instansi->ttd_kepala ? '' : 'hidden' }}">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Tanda tangan saat ini</span>
                            </div>
                        </div>

                        <input type="file" id="ttd_kepala_input" name="ttd_kepala" accept="image/png" onchange="previewTtd(this)"
                            class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 dark:file:bg-teal-950/60 file:text-teal-700 dark:file:text-teal-300 hover:file:bg-teal-100 cursor-pointer border border-gray-300 dark:border-gray-700 rounded-xl p-1 bg-white dark:bg-gray-900">
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1.5 ml-1">
                            *Format wajib <strong>PNG Transparan</strong> agar hasil cetak rapi.
                        </p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-md transition flex items-center active:scale-95">
                        <i class="fas fa-check-circle mr-2 text-sm"></i> Simpan Data 
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- PREVIEW TAMPILAN DOKUMEN (SEBELAH KANAN - DIPERBESAR) --}}
    <div class="lg:col-span-5 bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-8">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 flex items-center justify-center text-amber-600 dark:text-amber-400 text-xl border border-amber-100 dark:border-amber-900/50 flex-shrink-0">
                <i class="fas fa-eye"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Preview Tampilan Dokumen</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Hasil cetak tanda tangan pada berkas resmi.</p>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            <div class="bg-gray-50 dark:bg-gray-900 p-6 sm:p-8 rounded-2xl border border-gray-200 dark:border-gray-700 text-left shadow-inner space-y-2">
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Mengetahui,</p>
                    <p class="text-base font-bold text-gray-800 dark:text-gray-100 mt-0.5">{{ $instansi->jabatan_pejabat ?? '[Jabatan Kosong]' }}</p>
                </div>
                
                <div class="h-32 sm:h-36 flex items-center justify-start my-3 relative">
                    <img id="doc-preview-ttd" src="{{ $instansi->ttd_kepala ? asset('storage/' . $instansi->ttd_kepala) : '' }}" class="h-28 sm:h-32 object-contain {{ $instansi->ttd_kepala ? '' : 'hidden' }}">
                    <div id="doc-no-ttd" class="w-full h-full border-2 border-dashed border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800/60 flex items-center justify-center text-sm text-gray-400 dark:text-gray-500 italic rounded-2xl {{ $instansi->ttd_kepala ? 'hidden' : '' }}">
                        Area Tanda Tangan
                    </div>
                </div>

                <div>
                    <p class="text-base font-bold text-gray-900 dark:text-gray-100 border-b-2 border-gray-900 dark:border-gray-200 inline-block pb-0.5 mb-1">
                        {{ $instansi->nama_pejabat ?? '[Nama Pejabat Kosong]' }}
                    </p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold">NIP. {{ $instansi->nip_pejabat ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

</div>
