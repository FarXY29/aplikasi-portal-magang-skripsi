{{-- Card Input Saran / Evaluasi --}}
@if($activeApp && $activeApp->status?->value === 'selesai' && empty($activeApp->saran_peserta))
    <div id="saran-evaluasi" class="scroll-mt-24 bg-indigo-50/60 dark:bg-indigo-950/40 rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 border border-indigo-200/80 dark:border-indigo-800/60 shadow-2xs mb-6">
        <div class="flex flex-col md:flex-row gap-4 sm:gap-6 items-start justify-between">
            <div class="flex gap-3 sm:gap-4 items-start">
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 border border-indigo-200/80 dark:border-indigo-800/60 shadow-inner">
                    <i class="fas fa-comment-alt text-lg sm:text-xl"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-slate-900 dark:text-gray-100 font-extrabold text-base sm:text-lg md:text-xl tracking-tight flex flex-wrap items-center gap-2">
                        Isi Evaluasi & Saran Magang
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-indigo-700 text-white shadow-2xs">
                            WAJIB
                        </span>
                    </h3>
                    <p class="text-slate-600 dark:text-gray-400 text-xs md:text-sm font-medium leading-relaxed max-w-3xl">
                        Selamat! Masa magang Anda telah selesai. Mohon berikan saran dan evaluasi konstruktif untuk <strong>{{ $activeApp->position->instansi->nama_dinas }}</strong>. Evaluasi Anda bersifat <strong>anonim</strong> dan wajib diisi sebelum mengunduh Sertifikat & Transkrip Nilai Anda.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('peserta.saran.store', $activeApp->id) }}" method="POST" class="mt-4 sm:mt-6 space-y-4">
            @csrf
            <div>
                <textarea name="saran_peserta" rows="4" required class="w-full border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100 rounded-2xl shadow-2xs focus:border-indigo-600 focus:ring-indigo-600 text-xs sm:text-sm font-medium" placeholder="Tuliskan evaluasi, kritik, atau saran perbaikan untuk instansi tempat magang Anda..."></textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="w-full sm:w-auto min-h-[44px] px-5 sm:px-6 py-3 bg-indigo-700 hover:bg-indigo-800 text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-2xs active:scale-95 transition flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i> Kirim Evaluasi & Buka Sertifikat
                </button>
            </div>
        </form>
    </div>
@endif
