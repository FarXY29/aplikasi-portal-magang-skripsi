<!-- Attendance Stats Card -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 flex flex-col justify-between stagger-2">
    <div>
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Statistik Absensi</h4>
            <div class="w-10 h-10 rounded-2xl bg-purple-50 dark:bg-purple-950/60 border border-purple-100 dark:border-purple-800/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-sm shadow-xs">
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
        <div class="flex items-center gap-4 mb-3">
            <div class="relative w-20 h-20 flex-shrink-0">
                <svg viewBox="0 0 80 80" class="w-20 h-20 -rotate-90">
                    <circle cx="40" cy="40" r="32" fill="none" stroke="#f3f4f6" stroke-width="8" class="dark:stroke-gray-700"/>
                    <circle cx="40" cy="40" r="32" fill="none" stroke="#14b8a6" stroke-width="8"
                        class="donut-ring"
                        stroke-dasharray="{{ $circumference }}"
                        stroke-dashoffset="{{ $hadirOffset }}"
                        style="transition: stroke-dashoffset 1.4s cubic-bezier(0.22,1,0.36,1);"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center rotate-90">
                    <span class="text-lg font-black text-teal-600 dark:text-teal-400 font-mono leading-none">{{ $stats['attendance']['hadir'] }}</span>
                    <span class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Hadir</span>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-1.5 flex-1 text-xs">
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-1.5 text-gray-700 dark:text-gray-300 font-medium"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Hadir</span>
                    <span class="font-black text-emerald-600 dark:text-emerald-400 font-mono">{{ $stats['attendance']['hadir'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-1.5 text-gray-700 dark:text-gray-300 font-medium"><span class="w-2 h-2 rounded-full bg-amber-400"></span> Izin/Sakit</span>
                    <span class="font-black text-amber-600 dark:text-amber-400 font-mono">{{ $stats['attendance']['izin'] + ($stats['attendance']['sakit'] ?? 0) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-1.5 text-gray-700 dark:text-gray-300 font-medium"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Alpa</span>
                    <span class="font-black text-rose-600 dark:text-rose-400 font-mono">{{ $stats['attendance']['alpa'] }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-2 pt-3 border-t border-gray-100 dark:border-gray-700 text-center">
        <a href="{{ route('peserta.absensi.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-600 hover:text-teal-800 dark:text-teal-400 dark:hover:text-teal-300 transition">
            <i class="fas fa-history"></i> Lihat Riwayat Absen &rarr;
        </a>
    </div>
</div>
