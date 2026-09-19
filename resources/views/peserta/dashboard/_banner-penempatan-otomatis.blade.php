{{-- Banner Penempatan Otomatis --}}
<div class="bg-gradient-to-r from-teal-800 via-teal-900 to-emerald-900 rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 text-white shadow-xl shadow-teal-950/20 mb-6 overflow-hidden relative border border-teal-700/50">
    <div class="absolute right-0 top-0 translate-x-6 -translate-y-6 opacity-10 text-white pointer-events-none">
        <i class="fas fa-magic text-7xl sm:text-9xl"></i>
    </div>
    <div class="flex flex-col md:flex-row gap-4 sm:gap-6 items-center justify-between relative z-10">
        <div class="flex gap-3 sm:gap-4 items-start">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-teal-500/20 text-teal-300 flex items-center justify-center flex-shrink-0 shadow-inner border border-teal-500/30">
                <i class="fas fa-magic text-lg sm:text-xl"></i>
            </div>
            <div>
                <h3 class="text-white font-extrabold text-base sm:text-lg md:text-xl leading-snug">Bingung Memilih Instansi Magang?</h3>
                <p class="text-teal-100/90 text-xs sm:text-sm mt-1 max-w-2xl font-medium leading-relaxed">
                    Gunakan fitur <strong>Penempatan Otomatis</strong>! Sistem akan menyalurkan Anda secara otomatis ke instansi yang masih kuota tersedia sesuai dengan kualifikasi jurusan Anda ({{ Auth::user()->major ?? '-' }}).
                </p>
            </div>
        </div>
        <a href="{{ route('peserta.apply_automatic.form') }}" class="shrink-0 w-full sm:w-auto min-h-[44px] text-center bg-white dark:bg-gray-800 text-teal-800 dark:text-teal-300 hover:bg-teal-50 px-5 sm:px-6 py-3 rounded-xl font-bold shadow-2xs hover:shadow-xs transition transform hover:-translate-y-0.5 active:scale-95 text-xs uppercase tracking-wider flex items-center justify-center gap-2">
            <i class="fas fa-magic"></i> Daftar Penempatan Otomatis
        </a>
    </div>
</div>
