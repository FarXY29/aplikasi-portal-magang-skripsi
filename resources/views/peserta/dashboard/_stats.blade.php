<!-- Progress Card -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 flex flex-col justify-between stagger-1">
    <div>
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Progress Magang</h4>
            <div class="w-10 h-10 rounded-2xl bg-teal-50 dark:bg-teal-950/60 border border-teal-100 dark:border-teal-800/60 text-teal-600 dark:text-teal-400 flex items-center justify-center text-sm shadow-xs">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
        <div class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-gray-100 mb-1 font-mono animate-count-up">
            {{ $stats['elapsed_days'] }} <span class="text-xs font-normal text-gray-500 dark:text-gray-400 font-sans">dari {{ $stats['total_days'] }} Hari</span>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4 font-medium">Persentase penyelesaian periode magang Anda saat ini.</p>
    </div>
    <div>
        <div class="flex justify-between text-xs font-bold text-gray-700 dark:text-gray-300 mb-2">
            <span>Penyelesaian</span>
            <span class="text-teal-600 dark:text-teal-400 font-mono font-bold">{{ $stats['progress_percent'] }}%</span>
        </div>
        <div class="progress-bar-track">
            <div class="progress-bar-fill" style="--progress-width: {{ $stats['progress_percent'] }}%"></div>
        </div>
    </div>
</div>
