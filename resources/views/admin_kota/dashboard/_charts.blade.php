<section aria-labelledby="analitik-dashboard" class="space-y-3">
    <div>
        <h3 id="analitik-dashboard" class="text-sm font-black text-gray-800 dark:text-gray-100">Analitik Peserta</h3>
        <p class="mt-0.5 text-xs font-medium text-gray-500 dark:text-gray-400">Perbandingan asal pendidikan dan minat per instansi.</p>
    </div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
    {{-- Chart Demografi Kampus --}}
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 md:p-6 transition-all duration-300 hover:shadow-md">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center shadow-xs border border-teal-100 dark:border-teal-900/40">
                <i class="fas fa-university text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm md:text-base font-extrabold text-gray-800 dark:text-gray-200 tracking-tight">Demografi Kampus / Sekolah</h3>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Asal Instansi Pendidikan Peserta</p>
            </div>
        </div>
        @if(count($kampusData))
            <div class="relative flex items-center justify-center w-full min-h-[200px] sm:min-h-[260px]" style="height: 260px;">
                <canvas id="kampusChart"
                    class="w-full h-full"
                    data-labels="{{ json_encode($kampusLabels) }}"
                    data-values="{{ json_encode($kampusData) }}">
                </canvas>
            </div>
        @else
            <div class="flex h-[260px] flex-col items-center justify-center text-center">
                <i class="fas fa-university text-2xl text-gray-300 dark:text-gray-600 mb-3"></i>
                <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Belum ada data asal pendidikan</p>
                <p class="mt-1 text-xs font-medium text-gray-400 dark:text-gray-500">Data muncul saat peserta melengkapi profil.</p>
            </div>
        @endif
    </div>

    {{-- Chart Penyerapan Kuota Instansi --}}
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 md:p-6 transition-all duration-300 hover:shadow-md">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shadow-xs border border-indigo-100 dark:border-indigo-900/40">
                <i class="fas fa-chart-bar text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm md:text-base font-extrabold text-gray-800 dark:text-gray-200 tracking-tight">Top 10 Penyerapan Kuota Instansi</h3>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Instansi Teraktif dan Peminat Terbanyak</p>
            </div>
        </div>
        @if(count($instansiChartData))
            <div class="relative flex items-center justify-center w-full min-h-[200px] sm:min-h-[260px]" style="height: 260px;">
                <canvas id="instansiChart"
                    class="w-full h-full"
                    data-labels="{{ json_encode($instansiChartLabels) }}"
                    data-values="{{ json_encode($instansiChartData) }}">
                </canvas>
            </div>
        @else
            <div class="flex h-[260px] flex-col items-center justify-center text-center">
                <i class="fas fa-chart-bar text-2xl text-gray-300 dark:text-gray-600 mb-3"></i>
                <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Belum ada data pelamar</p>
                <p class="mt-1 text-xs font-medium text-gray-400 dark:text-gray-500">Data muncul saat ada pendaftaran baru.</p>
            </div>
        @endif
    </div>
</div>
</section>
