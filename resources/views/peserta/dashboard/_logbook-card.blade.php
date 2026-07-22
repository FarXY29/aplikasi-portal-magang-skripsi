<!-- Logbook & Pembimbing Lapangan Card -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 flex flex-col justify-between stagger-3">
    <div>
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Logbook Harian</h4>
            <a href="{{ route('peserta.logbook.index') }}" class="w-9 h-9 bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 rounded-2xl border border-blue-200 dark:border-blue-800/60 flex items-center justify-center text-xs hover:bg-blue-100 transition shadow-xs" title="Buka Logbook">
                <i class="fas fa-book-open"></i>
            </a>
        </div>
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-gray-50 dark:bg-gray-900 p-3.5 rounded-2xl border border-gray-200 dark:border-gray-700 text-center">
                <span class="block text-2xl font-black text-gray-800 dark:text-gray-100 font-mono animate-count-up">{{ $stats['logs_count'] }}</span>
                <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Entri</span>
            </div>
            <div class="bg-emerald-50 dark:bg-emerald-950/60 p-3.5 rounded-2xl border border-emerald-100 dark:border-emerald-800/60 text-center">
                <span class="block text-2xl font-black text-emerald-700 dark:text-emerald-300 font-mono animate-count-up">{{ $stats['logs_validated'] }}</span>
                <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Divalidasi</span>
            </div>
        </div>
    </div>
    
    <!-- Pembimbing Lapangan -->
    <div class="border-t border-gray-100 dark:border-gray-700 pt-3 flex items-center gap-3">
        <div class="w-9 h-9 rounded-2xl bg-teal-50 dark:bg-teal-950/60 flex items-center justify-center text-teal-600 dark:text-teal-400 font-bold border border-teal-200 dark:border-teal-800/60 text-xs shrink-0 shadow-xs">
            <i class="fas fa-user-tie"></i>
        </div>
        <div class="min-w-0 flex-1">
            <span class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider leading-none">Pembimbing Lapangan</span>
            <span class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate block mt-0.5">{{ $activeApp->pembimbing_lapangan->name ?? 'Belum Ditunjuk' }}</span>
        </div>
        <a href="{{ route('peserta.logbook.index') }}" class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300 shrink-0">
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>
