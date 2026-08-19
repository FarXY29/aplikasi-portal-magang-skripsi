<!-- Floating Bento Statistics Cards (4 Metriks Layout) -->
<section class="relative z-20 -mt-10 sm:-mt-16 px-4 w-full">
    <div class="max-w-6xl mx-auto w-full">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-6 w-full">
            <!-- Metriks 1: Instansi Card -->
            <div class="reveal bg-white dark:bg-gray-800/95 backdrop-blur-md p-4 sm:p-6 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100/80 dark:border-gray-700/50 flex flex-col justify-between transform hover:-translate-y-1.5 transition-all duration-300 group relative overflow-hidden" style="--reveal-delay: 0ms" x-intersect.once="$el.classList.add('revealed')">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-teal-50 dark:bg-teal-950/40 rounded-xl flex items-center justify-center text-teal-600 dark:text-teal-400 group-hover:bg-teal-600 group-hover:text-white dark:group-hover:bg-teal-600 dark:group-hover:text-white transition-all duration-500 shadow-inner shrink-0">
                        <i class="fas fa-building text-base sm:text-lg"></i>
                    </div>
                    <span class="text-[10px] font-mono font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/80 px-2 py-0.5 rounded-md border border-teal-200/60 dark:border-teal-900/60">
                        SKPD
                    </span>
                </div>
                <div class="min-w-0">
                    <div class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white tracking-tight truncate leading-none font-display mb-1" x-data="countUp({{ $totalInstansi }})" x-intersect.once="start()" x-text="display">{{ $totalInstansi }}</div>
                    <div class="text-slate-500 dark:text-slate-400 text-[10px] sm:text-xs font-bold uppercase tracking-wider truncate">Instansi Aktif</div>
                    <div class="text-[10px] text-slate-400 dark:text-slate-500 truncate mt-1">Dinas & Kecamatan</div>
                </div>
            </div>

            <!-- Metriks 2: Lowongan Card -->
            <div class="reveal bg-white dark:bg-gray-800/95 backdrop-blur-md p-4 sm:p-6 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100/80 dark:border-gray-700/50 flex flex-col justify-between transform hover:-translate-y-1.5 transition-all duration-300 group relative overflow-hidden" style="--reveal-delay: 100ms" x-intersect.once="$el.classList.add('revealed')">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-50 dark:bg-emerald-950/40 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-600 group-hover:text-white dark:group-hover:bg-emerald-600 dark:group-hover:text-white transition-all duration-500 shadow-inner shrink-0">
                        <i class="fas fa-briefcase text-base sm:text-lg"></i>
                    </div>
                    <span class="text-[10px] font-mono font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/80 px-2 py-0.5 rounded-md border border-emerald-200/60 dark:border-emerald-900/60">
                        OPEN
                    </span>
                </div>
                <div class="min-w-0">
                    <div class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white tracking-tight truncate leading-none font-display mb-1" x-data="countUp({{ $totalLowongan }})" x-intersect.once="start()" x-text="display">{{ $totalLowongan }}</div>
                    <div class="text-slate-500 dark:text-slate-400 text-[10px] sm:text-xs font-bold uppercase tracking-wider truncate">Posisi Aktif</div>
                    <div class="text-[10px] text-slate-400 dark:text-slate-500 truncate mt-1">Kuota Magang</div>
                </div>
            </div>

            <!-- Metriks 3: Alumni Card -->
            <div class="reveal bg-white dark:bg-gray-800/95 backdrop-blur-md p-4 sm:p-6 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100/80 dark:border-gray-700/50 flex flex-col justify-between transform hover:-translate-y-1.5 transition-all duration-300 group relative overflow-hidden" style="--reveal-delay: 200ms" x-intersect.once="$el.classList.add('revealed')">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-50 dark:bg-amber-950/40 rounded-xl flex items-center justify-center text-amber-500 dark:text-amber-400 group-hover:bg-amber-500 group-hover:text-white dark:group-hover:bg-amber-500 dark:group-hover:text-white transition-all duration-500 shadow-inner shrink-0">
                        <i class="fas fa-user-graduate text-base sm:text-lg"></i>
                    </div>
                    <span class="text-[10px] font-mono font-bold text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/80 px-2 py-0.5 rounded-md border border-amber-200/60 dark:border-amber-900/60">
                        LULUS
                    </span>
                </div>
                <div class="min-w-0">
                    <div class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white tracking-tight truncate leading-none font-display mb-1" x-data="countUp({{ $totalAlumni }})" x-intersect.once="start()" x-text="display">{{ $totalAlumni }}</div>
                    <div class="text-slate-500 dark:text-slate-400 text-[10px] sm:text-xs font-bold uppercase tracking-wider truncate">Alumni Magang</div>
                    <div class="text-[10px] text-slate-400 dark:text-slate-500 truncate mt-1">Siswa & Mahasiswa</div>
                </div>
            </div>

            <!-- Metriks 4: Sertifikat QR Digital Card -->
            <div class="reveal bg-white dark:bg-gray-800/95 backdrop-blur-md p-4 sm:p-6 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100/80 dark:border-gray-700/50 flex flex-col justify-between transform hover:-translate-y-1.5 transition-all duration-300 group relative overflow-hidden" style="--reveal-delay: 300ms" x-intersect.once="$el.classList.add('revealed')">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-50 dark:bg-indigo-950/40 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white dark:group-hover:bg-indigo-600 dark:group-hover:text-white transition-all duration-500 shadow-inner shrink-0">
                        <i class="fas fa-qrcode text-base sm:text-lg"></i>
                    </div>
                    <span class="text-[10px] font-mono font-bold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/80 px-2 py-0.5 rounded-md border border-indigo-200/60 dark:border-indigo-900/60">
                        VERIFIED
                    </span>
                </div>
                <div class="min-w-0">
                    <div class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white tracking-tight truncate leading-none font-display mb-1" x-data="countUp(100, 1600, '%')" x-intersect.once="start()" x-text="display">100%</div>
                    <div class="text-slate-500 dark:text-slate-400 text-[10px] sm:text-xs font-bold uppercase tracking-wider truncate">Sertifikat Digital</div>
                    <div class="text-[10px] text-slate-400 dark:text-slate-500 truncate mt-1">Validasi QR Code</div>
                </div>
            </div>
        </div>
    </div>
</section>
