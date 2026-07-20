  <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 md:gap-6 mb-4 md:mb-5">
      {{-- Chart Demografi Kampus --}}
      <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700/60 p-6 transition-all duration-300 hover:shadow-md">
          <div class="flex items-center gap-3 mb-6">
              <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 flex items-center justify-center shadow-xs">
                  <i class="fas fa-university text-sm"></i>
              </div>
              <div>
                  <h3 class="text-sm md:text-base font-extrabold text-gray-800 dark:text-gray-200 tracking-tight">Demografi Kampus/Sekolah</h3>
                  <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Asal Instansi Pendidikan Peserta</p>
              </div>
          </div>
          <div class="relative flex items-center justify-center w-full" style="height: 260px;">
              <canvas id="kampusChart"
                  class="w-full h-full"
                  data-labels="{{ json_encode($kampusLabels) }}"
                  data-values="{{ json_encode($kampusData) }}">
              </canvas>
          </div>
      </div>

      {{-- Chart Penyerapan Kuota Instansi --}}
      <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700/60 p-6 transition-all duration-300 hover:shadow-md">
          <div class="flex items-center gap-3 mb-6">
              <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shadow-xs">
                  <i class="fas fa-chart-bar text-sm"></i>
              </div>
              <div>
                  <h3 class="text-sm md:text-base font-extrabold text-gray-800 dark:text-gray-200 tracking-tight">Top 10 Penyerapan Kuota Instansi</h3>
                  <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Instansi Teraktif dan Peminat Terbanyak</p>
              </div>
          </div>
          <div class="relative flex items-center justify-center w-full" style="height: 260px;">
              <canvas id="instansiChart"
                  class="w-full h-full"
                  data-labels="{{ json_encode($instansiChartLabels) }}"
                  data-values="{{ json_encode($instansiChartData) }}">
              </canvas>
          </div>
      </div>
  </div>
