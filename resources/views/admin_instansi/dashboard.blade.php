<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-chart-pie text-teal-600 dark:text-teal-400"></i>
                {{ __('Dashboard Statistik Instansi') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Pengumuman Kota --}}
            @php
                $globalAnnouncement = \App\Models\Setting::where('key', 'announcement')->value('value');
            @endphp
            @if(!empty($globalAnnouncement))
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-950/30 dark:to-orange-950/30 border-l-4 border-amber-500 p-6 rounded-r-2xl shadow-sm border border-amber-100 dark:border-amber-900/50 flex gap-4 items-start relative overflow-hidden mb-6">
                    <div class="absolute right-0 top-0 translate-x-4 -translate-y-4 opacity-5 text-amber-500 pointer-events-none">
                        <i class="fas fa-bullhorn text-9xl"></i>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0 shadow-inner">
                        <i class="fas fa-bullhorn text-lg"></i>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-xs font-extrabold text-amber-800 dark:text-amber-400 uppercase tracking-wider mb-1">Pengumuman Kota Banjarmasin</h4>
                        <div class="text-sm text-amber-950 dark:text-amber-200 font-medium leading-relaxed max-w-none">
                            {!! nl2br(e($globalAnnouncement)) !!}
                        </div>
                    </div>
                </div>
            @endif

            {{-- Welcome Hero Banner --}}
            <div class="relative bg-gradient-to-r from-teal-700 to-teal-900 dark:from-teal-900 dark:to-teal-950 rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-xl mb-6 sm:mb-8 overflow-hidden text-white border border-teal-600/30 dark:border-teal-700/50">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/10 dark:bg-gray-800/10 opacity-10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-48 h-48 bg-teal-400 opacity-20 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 sm:gap-6">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black mb-2 tracking-tight">Selamat Datang, Admin Instansi! 👋</h1>
                        <p class="text-teal-100 dark:text-teal-200 text-sm sm:text-base max-w-xl font-medium leading-relaxed">
                            Kelola peserta magang di <span class="font-bold text-white bg-white/20 dark:bg-gray-800/60 px-2 py-0.5 rounded-md">{{ $instansi->nama_dinas }}</span> dengan mudah dan efisien.
                        </p>
                    </div>
                    <div class="hidden md:block bg-white/10 dark:bg-gray-800/40 p-4 rounded-2xl backdrop-blur-md border border-white/20 dark:border-gray-700 shadow-lg">
                        <i class="fas fa-building text-4xl text-white"></i>
                    </div>
                </div>
            </div>

            @php
                $instansiId = Auth::user()->instansi_id;
                
                // Data Chart 12 Bulan Terakhir
                $chartLabels = [];
                $chartData = [];
                
                for ($i = 11; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $chartLabels[] = $date->format('M Y');
                    
                    $count = \App\Models\Application::whereHas('position', fn($q) => $q->where('instansi_id', $instansiId))
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
                    
                    $chartData[] = $count;
                }

                // Growth Logic
                $currentMonth = end($chartData);
                $lastMonth = prev($chartData); 
                reset($chartData); 
                
                $selisih = $currentMonth - $lastMonth;
                
                if ($lastMonth > 0) {
                    $growth = round(($selisih / $lastMonth) * 100, 1);
                    $trendIcon = $growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                    $trendColor = $growth >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
                    $trendBg = $growth >= 0 ? 'bg-green-50 dark:bg-green-950/50 border-green-100 dark:border-green-900/40' : 'bg-red-50 dark:bg-red-950/50 border-red-100 dark:border-red-900/40';
                } else {
                    $growth = $currentMonth > 0 ? 100 : 0;
                    $trendIcon = 'fa-minus';
                    $trendColor = 'text-gray-500 dark:text-gray-400';
                    $trendBg = 'bg-gray-50 dark:bg-gray-900 border-gray-100 dark:border-gray-700';
                }
            @endphp

            {{-- 3 KPI Stat Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                <x-ui.stat-card 
                    href="{{ route('dinas.pelamar') }}"
                    title="Pelamar Pending" 
                    value="{{ $widget['pending'] }}" 
                    icon="fas fa-inbox" 
                    color="amber" 
                    subtitle="Menunggu konfirmasi" 
                />

                <x-ui.stat-card 
                    href="{{ route('dinas.peserta.index') }}"
                    title="Peserta Aktif" 
                    value="{{ $widget['active'] }}" 
                    icon="fas fa-user-check" 
                    color="emerald" 
                    subtitle="Sedang magang aktif" 
                />

                <x-ui.stat-card 
                    title="Total Alumni" 
                    value="{{ $widget['completed'] }}" 
                    icon="fas fa-graduation-cap" 
                    color="blue" 
                    subtitle="Peserta selesai magang" 
                />
            </div>

            {{-- Main Chart & Asal Peserta Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Left Chart Area --}}
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 md:p-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                <i class="fas fa-chart-line text-teal-600 dark:text-teal-400"></i> Trend Peminat Magang
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Statistik jumlah pelamar baru masuk ke instansi Anda.</p>
                        </div>
                        
                        <div class="bg-gray-100 dark:bg-gray-900 p-1 rounded-xl flex text-xs font-bold w-full sm:w-auto justify-between sm:justify-start border border-transparent dark:border-gray-700">
                            <button onclick="updateChart(3)" class="flex-1 sm:flex-initial px-3.5 py-1.5 rounded-lg transition hover:bg-white dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400" id="btn-3">3 Bln</button>
                            <button onclick="updateChart(6)" class="flex-1 sm:flex-initial px-3.5 py-1.5 rounded-lg transition hover:bg-white dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400" id="btn-6">6 Bln</button>
                            <button onclick="updateChart(12)" class="flex-1 sm:flex-initial px-3.5 py-1.5 rounded-lg bg-white dark:bg-gray-800 shadow-sm text-teal-700 dark:text-teal-400 font-extrabold" id="btn-12">1 Thn</button>
                        </div>
                    </div>

                    <div class="mb-6 flex items-center gap-3 p-3 rounded-xl {{ $trendBg }} border w-fit">
                        <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-xs {{ $trendColor }}">
                            <i class="fas {{ $trendIcon }}"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase">Perubahan Bulan Ini</p>
                            <p class="text-sm font-black {{ $trendColor }}">
                                {{ $growth }}% <span class="text-gray-400 dark:text-gray-500 font-normal">vs bulan lalu</span>
                            </p>
                        </div>
                    </div>

                    <div class="relative h-64 w-full">
                        <canvas id="peminatChart"></canvas>
                    </div>
                </div>

                {{-- Right Top Asal Peserta Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col justify-between">
                    <div class="mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Demografi Asal Peserta</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Sekolah / Perguruan Tinggi pengirim terbanyak.</p>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto custom-scrollbar pr-2 max-h-[350px]">
                        @if(count($topInstansi) > 0)
                            <div class="space-y-4">
                                @foreach($topInstansi as $index => $inst)
                                <div class="flex items-center justify-between group">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-8 h-8 rounded-xl bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-gray-500 dark:text-gray-400 font-bold text-xs border border-gray-100 dark:border-gray-700 shrink-0">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate w-32 md:w-40 cursor-help hover:text-teal-600 dark:hover:text-teal-400 transition" 
                                               title="{{ $inst->asal_instansi }}">
                                                {{ $inst->asal_instansi }}
                                            </p>
                                            <div class="w-full bg-gray-100 dark:bg-gray-900 rounded-full h-1.5 mt-1.5 overflow-hidden border border-transparent dark:border-gray-700">
                                                <div class="bg-indigo-500 dark:bg-indigo-400 h-1.5 rounded-full" style="width: {{ min(($inst->total_peserta / $topInstansi[0]->total_peserta) * 100, 100) }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-black text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/60 px-2.5 py-1 rounded-lg border border-indigo-100 dark:border-indigo-900/40 shrink-0">
                                        {{ $inst->total_peserta }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10 text-gray-400 dark:text-gray-500">
                                <div class="w-12 h-12 rounded-xl bg-gray-50 dark:bg-gray-900 flex items-center justify-center mx-auto mb-2 border border-gray-200 dark:border-gray-700">
                                    <i class="fas fa-users-slash text-xl text-gray-400"></i>
                                </div>
                                <p class="text-xs font-bold text-gray-600 dark:text-gray-300">Belum ada data pendaftar.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("turbo:load", function() {
            const canvas = document.getElementById('peminatChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? '#9CA3AF' : '#6B7280';
            const gridColor = isDarkMode ? 'rgba(51, 65, 85, 0.5)' : '#F3F4F6';
            
            const allLabels = {!! json_encode($chartLabels) !!};
            const allData = {!! json_encode($chartData) !!};

            let chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: allLabels,
                    datasets: [{
                        label: 'Pelamar',
                        data: allData,
                        borderColor: isDarkMode ? '#2dd4bf' : '#0D9488',
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                            gradient.addColorStop(0, isDarkMode ? 'rgba(45, 212, 191, 0.25)' : 'rgba(13, 148, 136, 0.2)');
                            gradient.addColorStop(1, 'rgba(13, 148, 136, 0)');
                            return gradient;
                        },
                        borderWidth: 3,
                        pointBackgroundColor: isDarkMode ? '#1F2937' : '#fff',
                        pointBorderColor: isDarkMode ? '#2dd4bf' : '#0D9488',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { borderDash: [2, 4], color: gridColor },
                            ticks: { stepSize: 1, font: { size: 10 }, color: textColor }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { font: { size: 10 }, color: textColor }
                        }
                    }
                }
            });

            window.updateChart = function(range) {
                document.querySelectorAll('[id^="btn-"]').forEach(btn => {
                    btn.className = "flex-1 sm:flex-initial px-3.5 py-1.5 rounded-lg transition hover:bg-white dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400";
                });
                document.getElementById('btn-' + range).className = "flex-1 sm:flex-initial px-3.5 py-1.5 rounded-lg bg-white dark:bg-gray-800 shadow-sm text-teal-700 dark:text-teal-400 font-extrabold";

                const newLabels = allLabels.slice(-range);
                const newData = allData.slice(-range);

                chart.data.labels = newLabels;
                chart.data.datasets[0].data = newData;
                chart.update();
            };
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    </style>
</x-app-layout>