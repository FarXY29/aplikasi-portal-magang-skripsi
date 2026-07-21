<!-- Floating Bento Statistics Cards -->
<section class="relative z-20 -mt-10 sm:-mt-16 px-4 w-full">
    <div class="max-w-6xl mx-auto w-full space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
             <!-- Instansi Card -->
             <div class="bg-white dark:bg-slate-800/95 backdrop-blur-md p-6 rounded-3xl shadow-sm hover:shadow-xl border border-slate-100 dark:border-slate-700/50 flex items-center gap-5 transition-layout group">
                 <div class="w-14 h-14 bg-teal-50 dark:bg-teal-950/40 rounded-2xl flex items-center justify-center text-teal-600 dark:text-teal-400 group-hover:bg-teal-600 group-hover:text-white transition-all duration-300 shadow-inner shrink-0">
                     <i class="fas fa-building text-xl"></i>
                 </div>
                 <div class="min-w-0">
                     <div class="text-3xl font-black text-slate-800 dark:text-white tracking-tight leading-none">{{ $totalInstansi }}</div>
                     <div class="text-slate-550 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mt-1.5 truncate">Instansi Pemerintah</div>
                 </div>
             </div>
             <!-- Lowongan Card -->
             <div class="bg-white dark:bg-slate-800/95 backdrop-blur-md p-6 rounded-3xl shadow-sm hover:shadow-xl border border-slate-100 dark:border-slate-700/50 flex items-center gap-5 transition-layout group">
                 <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-950/40 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 shadow-inner shrink-0">
                     <i class="fas fa-briefcase text-xl"></i>
                 </div>
                 <div class="min-w-0">
                     <div class="text-3xl font-black text-slate-800 dark:text-white tracking-tight leading-none">{{ $totalLowongan }}</div>
                     <div class="text-slate-550 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mt-1.5 truncate">Posisi Aktif</div>
                 </div>
             </div>
             <!-- Alumni Card -->
             <div class="bg-white dark:bg-slate-800/95 backdrop-blur-md p-6 rounded-3xl shadow-sm hover:shadow-xl border border-slate-100 dark:border-slate-700/50 flex items-center gap-5 transition-layout group">
                 <div class="w-14 h-14 bg-amber-50 dark:bg-amber-950/40 rounded-2xl flex items-center justify-center text-amber-500 dark:text-amber-400 group-hover:bg-amber-500 group-hover:text-white transition-all duration-300 shadow-inner shrink-0">
                     <i class="fas fa-user-graduate text-xl"></i>
                 </div>
                 <div class="min-w-0">
                     <div class="text-3xl font-black text-slate-800 dark:text-white tracking-tight leading-none">{{ $totalAlumni }}</div>
                     <div class="text-slate-550 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mt-1.5 truncate">Alumni Magang</div>
                 </div>
             </div>
        </div>
    </div>
</section>
