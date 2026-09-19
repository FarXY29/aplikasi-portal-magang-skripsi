{{-- Summary Stats Bar --}}
@php
    $totalRecords = $attendanceSummary['total'];
    $hadirCount   = $attendanceSummary['hadir'];
    $izinCount    = $attendanceSummary['izin'];
    $alpaCount    = $attendanceSummary['alpa'];
@endphp
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
        <div>
            <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Hari</p>
            <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono">{{ $totalRecords }}</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/60 border border-teal-100 dark:border-teal-800/60 flex items-center justify-center text-teal-600 dark:text-teal-400 text-lg shadow-xs">
            <i class="fas fa-calendar-day"></i>
        </div>
    </div>

    <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
        <div>
            <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Hadir</p>
            <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono">{{ $hadirCount }}</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-emerald-800/60 flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-lg shadow-xs">
            <i class="fas fa-user-check"></i>
        </div>
    </div>

    <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
        <div>
            <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Izin / Sakit</p>
            <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono">{{ $izinCount }}</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 border border-amber-100 dark:border-amber-800/60 flex items-center justify-center text-amber-600 dark:text-amber-400 text-lg shadow-xs">
            <i class="fas fa-clipboard-list"></i>
        </div>
    </div>

    <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
        <div>
            <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Alpha</p>
            <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono">{{ $alpaCount }}</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-800/60 flex items-center justify-center text-rose-600 dark:text-rose-400 text-lg shadow-xs">
            <i class="fas fa-user-times"></i>
        </div>
    </div>
</div>
