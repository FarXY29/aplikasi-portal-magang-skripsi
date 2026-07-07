<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-chart-pie text-teal-600"></i>
                {{ __('Dashboard Statistik Instansi') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @php
                $globalAnnouncement = \App\Models\Setting::where('key', 'announcement')->value('value');
            @endphp
            @if(!empty($globalAnnouncement))
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 p-6 rounded-r-2xl shadow-sm border border-amber-100 flex gap-4 items-start relative overflow-hidden mb-6">
                    <div class="absolute right-0 top-0 translate-x-4 -translate-y-4 opacity-5 text-amber-500 pointer-events-none">
                        <i class="fas fa-bullhorn text-9xl"></i>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 shadow-inner">
                        <i class="fas fa-bullhorn text-lg"></i>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-sm font-extrabold text-amber-800 uppercase tracking-wider mb-1">Pengumuman Kota Banjarmasin</h4>
                        <div class="text-sm text-amber-950 font-semibold leading-relaxed prose prose-amber max-w-none">
                            {!! nl2br(e($globalAnnouncement)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <div class="relative bg-gradient-to-r from-teal-600 to-teal-800 rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-xl shadow-teal-100 mb-6 sm:mb-10 overflow-hidden text-white">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-48 h-48 bg-teal-400 opacity-20 rounded-full blur-2xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 sm:gap-6">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold mb-2">Selamat Datang, Admin!</h1>
                        <p class="text-teal-100 text-sm sm:text-lg max-w-xl font-light leading-relaxed">
                            Kelola peserta magang di <span class="font-bold text-white bg-white/20 px-2 rounded">{{ $instansi->nama_dinas }}</span> dengan mudah dan efisien.
                        </p>
                    </div>
                    <div class="hidden md:block bg-white/20 p-4 rounded-2xl backdrop-blur-sm">
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
                    $trendColor = $growth >= 0 ? 'text-green-500' : 'text-red-500';
                    $trendBg = $growth >= 0 ? 'bg-green-50' : 'bg-red-50';
                } else {
                    $growth = $currentMonth > 0 ? 100 : 0;
                    $trendIcon = 'fa-minus';
                    $trendColor = 'text-gray-500';
                    $trendBg = 'bg-gray-50';
                }
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('dinas.pelamar') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-yellow-200 transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Pelamar Pending</p>
                            <h3 class="text-3xl font-black text-gray-800 group-hover:text-yellow-500 transition">{{ $widget['pending'] }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center shadow-sm group-hover:scale-110 transition">
                            <i class="fas fa-inbox text-lg"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-3 flex items-center gap-1">
                        <i class="fas fa-clock"></i> Menunggu konfirmasi
                    </p>
                </a>

                <a href="{{ route('dinas.peserta.index') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-teal-200 transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Peserta Aktif</p>
                            <h3 class="text-3xl font-black text-gray-800 group-hover:text-teal-600 transition">{{ $widget['active'] }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm group-hover:scale-110 transition">
                            <i class="fas fa-user-check text-lg"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-3 flex items-center gap-1">
                        <i class="fas fa-circle text-[8px] text-green-500"></i> Sedang magang
                    </p>
                </a>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Alumni</p>
                            <h3 class="text-3xl font-black text-gray-800">{{ $widget['completed'] }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm">
                            <i class="fas fa-graduation-cap text-lg"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-3">Peserta selesai magang</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-chart-line text-teal-500"></i> Trend Peminat
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">Statistik jumlah pelamar baru.</p>
                        </div>
                        
                        <div class="bg-gray-100 p-1 rounded-xl flex text-xs font-bold w-full sm:w-auto justify-between sm:justify-start">
                            <button onclick="updateChart(3)" class="flex-1 sm:flex-initial px-3 py-2 rounded-lg transition hover:bg-white hover:shadow-sm" id="btn-3">3 Bln</button>
                            <button onclick="updateChart(6)" class="flex-1 sm:flex-initial px-3 py-2 rounded-lg transition hover:bg-white hover:shadow-sm" id="btn-6">6 Bln</button>
                            <button onclick="updateChart(12)" class="flex-1 sm:flex-initial px-3 py-2 rounded-lg bg-white shadow-sm text-teal-600" id="btn-12">1 Thn</button>
                        </div>
                    </div>

                    <div class="mb-6 flex items-center gap-3 p-3 rounded-xl {{ $trendBg }} border border-transparent w-fit">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm {{ $trendColor }}">
                            <i class="fas {{ $trendIcon }}"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase">Bulan Ini</p>
                            <p class="text-sm font-bold {{ $trendColor }}">
                                {{ $growth }}% <span class="text-gray-400 font-normal">vs bulan lalu</span>
                            </p>
                        </div>
                    </div>

                    <div class="relative h-64 w-full">
                        <canvas id="peminatChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col">
                    <div class="mb-4 pb-4 border-b border-gray-50">
                        <h3 class="text-lg font-bold text-gray-800">Asal Peserta</h3>
                        <p class="text-xs text-gray-500 mt-1">Institusi pengirim terbanyak.</p>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto custom-scrollbar pr-2 max-h-[350px]">
                        @if(count($topInstansi) > 0)
                            <div class="space-y-4">
                                @foreach($topInstansi as $index => $inst)
                                <div class="flex items-center justify-between group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-500 font-bold text-xs border border-gray-100">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            {{-- MODIFIKASI: Menambahkan 'title' dan 'cursor-help' untuk popup native browser --}}
                                            <p class="text-sm font-bold text-gray-700 truncate w-32 md:w-40 cursor-help hover:text-teal-600 transition" 
                                               title="{{ $inst->asal_instansi }}">
                                                {{ $inst->asal_instansi }}
                                            </p>
                                            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1.5">
                                                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ min(($inst->total_peserta / $topInstansi[0]->total_peserta) * 100, 100) }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md">
                                        {{ $inst->total_peserta }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-users-slash text-3xl mb-2"></i>
                                <p class="text-xs">Belum ada data.</p>
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
            const ctx = document.getElementById('peminatChart').getContext('2d');
            
            const allLabels = {!! json_encode($chartLabels) !!};
            const allData = {!! json_encode($chartData) !!};

            let chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: allLabels,
                    datasets: [{
                        label: 'Pelamar',
                        data: allData,
                        borderColor: '#0D9488', // Teal 600
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                            gradient.addColorStop(0, 'rgba(13, 148, 136, 0.2)');
                            gradient.addColorStop(1, 'rgba(13, 148, 136, 0)');
                            return gradient;
                        },
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#0D9488',
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
                            grid: { borderDash: [2, 4], color: '#f3f4f6' },
                            ticks: { stepSize: 1, font: { size: 10 } }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });

            window.updateChart = function(range) {
                document.querySelectorAll('[id^="btn-"]').forEach(btn => {
                    btn.className = "px-3 py-1.5 rounded-md transition hover:bg-white hover:shadow-sm";
                });
                document.getElementById('btn-' + range).className = "px-3 py-1.5 rounded-md bg-white shadow-sm text-teal-600 font-bold";

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
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    </style>
</x-app-layout>