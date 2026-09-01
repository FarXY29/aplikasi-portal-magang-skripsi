<!-- Attendance Stats Card -->
<div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-2xs border border-slate-200/80 dark:border-gray-700 flex flex-col justify-between stagger-2">
    <div>
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Statistik Absensi</h4>
            <div class="w-10 h-10 rounded-2xl bg-teal-50 dark:bg-teal-950/60 border border-teal-200/60 dark:border-teal-800/60 text-teal-700 dark:text-teal-400 flex items-center justify-center text-sm shadow-2xs">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>

        {{-- Mini Donut Chart SVG --}}
        @php
            $totalDays = max(1, $stats['attendance']['hadir'] + $stats['attendance']['izin'] + $stats['attendance']['alpa'] + ($stats['attendance']['sakit'] ?? 0));
            $circumference = 2 * 3.14159 * 32;
            $hadirPct = ($stats['attendance']['hadir'] / $totalDays);
            $hadirOffset = $circumference * (1 - $hadirPct);
        @endphp
        <div class="flex items-center gap-3 sm:gap-4 mb-3">
            <div class="relative w-16 h-16 sm:w-20 sm:h-20 flex-shrink-0">
                <svg viewBox="0 0 80 80" class="w-16 h-16 sm:w-20 sm:h-20 -rotate-90">
                    <circle cx="40" cy="40" r="32" fill="none" stroke="#e2e8f0" stroke-width="8" class="dark:stroke-gray-700"/>
                    <circle cx="40" cy="40" r="32" fill="none" stroke="#0f766e" stroke-width="8"
                        class="donut-ring dark:stroke-teal-400"
                        stroke-dasharray="{{ $circumference }}"
                        stroke-dashoffset="{{ $hadirOffset }}"
                        style="transition: stroke-dashoffset 1.4s cubic-bezier(0.22,1,0.36,1);"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center rotate-90">
                    <span class="text-base sm:text-lg font-black text-teal-700 dark:text-teal-400 font-mono leading-none">{{ $stats['attendance']['hadir'] }}</span>
                    <span class="text-[9px] font-bold text-slate-400 dark:text-gray-500 uppercase">Hadir</span>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-2 sm:gap-1.5 flex-1 text-xs">
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-1.5 text-slate-700 dark:text-gray-300 font-medium"><span class="w-2 h-2 rounded-full bg-emerald-600"></span> Hadir</span>
                    <span class="font-black text-emerald-700 dark:text-emerald-400 font-mono">{{ $stats['attendance']['hadir'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-1.5 text-slate-700 dark:text-gray-300 font-medium"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Izin/Sakit</span>
                    <span class="font-black text-amber-700 dark:text-amber-400 font-mono">{{ $stats['attendance']['izin'] + ($stats['attendance']['sakit'] ?? 0) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-1.5 text-slate-700 dark:text-gray-300 font-medium"><span class="w-2 h-2 rounded-full bg-rose-600"></span> Alpa</span>
                    <span class="font-black text-rose-700 dark:text-rose-400 font-mono">{{ $stats['attendance']['alpa'] }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-2 pt-3 border-t border-slate-100 dark:border-gray-700 text-center">
        <a href="{{ route('peserta.absensi.index') }}" class="inline-flex items-center gap-1.5 min-h-[44px] text-xs font-bold text-teal-700 hover:text-teal-800 dark:text-teal-400 dark:hover:text-teal-300 transition">
            <i class="fas fa-history"></i> Lihat Riwayat Absen &rarr;
        </a>
    </div>
</div>
