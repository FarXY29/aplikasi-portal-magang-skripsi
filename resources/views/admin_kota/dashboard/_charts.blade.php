        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- ROW 3.5: EXECUTIVE CHARTS (Demografi & Penyerapan) --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-5">
            {{-- Chart Demografi Kampus --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 md:p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-xl bg-teal-500 text-white flex items-center justify-center shadow-sm" style="background-color: #14b8a6;">
                        <i class="fas fa-university text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm md:text-base font-black text-gray-800 dark:text-gray-200">Demografi Kampus/Sekolah</h3>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">Asal instansi pendidikan peserta magang</p>
                    </div>
                </div>
                <div class="relative flex items-center justify-center w-full" style="height: 250px;">
                    <canvas id="kampusChart"
                        data-labels="{{ json_encode($kampusLabels) }}"
                        data-values="{{ json_encode($kampusData) }}">
                    </canvas>
                </div>
            </div>

            {{-- Chart Penyerapan Kuota Instansi --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 md:p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-xl bg-indigo-500 text-white flex items-center justify-center shadow-sm" style="background-color: #6366f1;">
                        <i class="fas fa-chart-bar text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm md:text-base font-black text-gray-800 dark:text-gray-200">Top 10 Penyerapan Kuota Instansi</h3>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">Instansi dengan jumlah pelamar terbanyak</p>
                    </div>
                </div>
                <div class="relative flex items-center justify-center w-full" style="height: 250px;">
                    <canvas id="instansiChart"
                        data-labels="{{ json_encode($instansiChartLabels) }}"
                        data-values="{{ json_encode($instansiChartData) }}">
                    </canvas>
                </div>
            </div>
        </div>
