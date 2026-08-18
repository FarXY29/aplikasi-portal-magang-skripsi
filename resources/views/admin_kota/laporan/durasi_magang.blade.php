<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    @push('styles')
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    @endpush

    <x-slot name="header">
        <x-report.header
            icon="fas fa-business-time"
            :title="__('Rata-Rata Durasi Magang Instansi')"
            countLabel="Total Terfilter:"
            :count="$instansis->count()"
            countSuffix="Instansi" />
    </x-slot>

    @include('admin_kota.laporan.partials.chart-loader')

    @php
        $activeInstansis = $instansis->filter(fn($i) => $i->avg_durasi_hari > 0);
        $avgHariKota = $activeInstansis->count() > 0 ? round($activeInstansis->avg('avg_durasi_hari')) : 0;
        $avgBulanKota = $activeInstansis->count() > 0 ? round($activeInstansis->avg('avg_durasi_bulan'), 1) : 0;
        $totalPesertaEvaluasi = $instansis->sum(fn($i) => $i->applications->count());

        $chartTopDurasi = $instansis
            ->sortByDesc('avg_durasi_hari')
            ->take(10)
            ->map(fn($i) => [
                'nama' => $i->nama_dinas,
                'nilai' => (int) $i->avg_durasi_hari,
            ])
            ->values();
    @endphp

    <div class="font-[Inter] -mx-4 -mt-4 -mb-24 md:-mx-6 md:-mt-6 md:-mb-8 lg:-mx-8 lg:-mt-8 px-4 pt-4 pb-24 md:px-6 md:pt-6 md:pb-8 lg:px-8 lg:pt-8 min-h-full bg-gray-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-100"
        x-data="{ searchQuery: @js(request('q')) }">
        <div class="max-w-7xl mx-auto space-y-5 md:space-y-6">

            {{-- Navigation & Export Buttons --}}
            <x-report.toolbar
                :printRoute="$instansis->count() > 0 ? route('admin.laporan.durasi_magang.print', request()->query()) : null" />

            {{-- Summary KPI Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                <x-report.stat-card
                    title="Total Instansi"
                    :value="number_format($instansis->count())"
                    icon="fas fa-building"
                    color="teal"
                    tooltip="Total jumlah instansi/dinas Pemerintah Kota Banjarmasin yang terdaftar pada laporan durasi magang." />
                <x-report.stat-card
                    title="Peserta Terdokumentasi"
                    :value="number_format($totalPesertaEvaluasi)"
                    icon="fas fa-users"
                    color="blue"
                    tooltip="Jumlah peserta magang aktif & alumni yang memiliki data tanggal mulai dan selesai magang secara valid." />
                <x-report.stat-card
                    title="Rerata Kota (Hari)"
                    :value="$avgHariKota . ' Hari'"
                    icon="fas fa-calendar-day"
                    color="indigo"
                    tooltip="Rata-rata durasi magang instansi dalam satuan hari. Rumus per instansi: Total Selisih Hari (Tanggal Selesai - Tanggal Mulai) / Jumlah Peserta Magang." />
                <x-report.stat-card
                    title="Rerata Kota (Bulan)"
                    :value="$avgBulanKota . ' Bulan'"
                    icon="fas fa-clock"
                    color="teal"
                    :featured="true"
                    tooltip="Rata-rata durasi magang instansi dalam satuan bulan (asumsi 1 bulan = 30 hari). Rumus: Rata-Rata Durasi Hari / 30 Hari." />
            </div>

            {{-- Chart: Top 10 Durasi Terpanjang --}}
            @if($instansis->count() > 0)
                <x-report.chart-card
                    icon="fas fa-hourglass-half"
                    iconColor="teal"
                    title="TOP 10 Instansi dengan Durasi Magang Terpanjang"
                    subtitle="Rata-rata durasi magang dalam hari per instansi."
                    canvasId="chart-top-durasi"
                    height="h-64 md:h-80" />
            @endif

            {{-- Main Table Card with Integrated Header Filters --}}
            <div class="bg-white dark:bg-[#161f33] shadow-lg rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 overflow-hidden">

                {{-- Card Header: Title & Integrated Right-Side Filters --}}
                <div class="p-5 md:p-6 border-b border-slate-100 dark:border-slate-800/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/60 dark:bg-slate-900/40">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-teal-100 dark:bg-teal-500/15 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/25 flex items-center justify-center shrink-0">
                            <i class="fas fa-business-time text-base"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 dark:text-white text-base md:text-lg leading-tight">
                                Daftar Rata-Rata Durasi Magang per Instansi
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Daftar durasi rata-rata terurut dari durasi terpanjang ke terpendek.</p>
                        </div>
                    </div>

                    {{-- Integrated Right-Side Filter Form --}}
                    <form method="GET" action="{{ route('admin.laporan.durasi_magang') }}" class="flex flex-col sm:flex-row gap-2.5 items-stretch sm:items-center w-full sm:w-auto">
                        <div class="relative w-full sm:w-64">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-slate-500 pointer-events-none">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" name="q" value="{{ request('q') }}" x-model="searchQuery"
                                placeholder="Cari nama dinas..."
                                class="w-full pl-9 py-2 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl text-xs font-bold focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                        </div>

                        <div class="flex gap-2 w-full sm:w-auto">
                            <button type="submit" class="flex-1 sm:flex-none px-4 py-2 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs font-bold shadow-md shadow-teal-500/20 transition active:scale-95 flex items-center justify-center gap-1.5">
                                <i class="fas fa-filter text-xs"></i> Filter
                            </button>
                            @if(request()->filled('q'))
                                <a href="{{ route('admin.laporan.durasi_magang') }}" class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl font-bold text-xs shadow-sm transition flex items-center justify-center">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Table Content (Desktop) --}}
                <div class="hidden md:block overflow-x-auto max-h-[650px] overflow-y-auto custom-scrollbar">
                    <table class="w-full divide-y divide-slate-100 dark:divide-slate-800/60 border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-900 sticky top-0 z-20">
                            <tr>
                                <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-14">No</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[220px] max-w-[320px]">Nama Instansi</th>
                                <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-36">Peserta Magang</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">Visual Rasio Durasi</th>
                                <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-36">Rata-Rata (Hari)</th>
                                <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-36">Rata-Rata (Bulan)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-[#161f33] divide-y divide-slate-100 dark:divide-slate-800/60 text-sm">
                            @forelse($instansis as $index => $instansi)
                            <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-500/5 transition group"
                                x-show="!searchQuery || @js(strtolower($instansi->nama_dinas)).includes(searchQuery.toLowerCase())">
                                <td class="px-4 py-4 text-center text-slate-400 dark:text-slate-500 font-bold text-xs">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 min-w-[220px] max-w-[320px]">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-xl bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-300 font-black border border-teal-200 dark:border-teal-500/25 text-xs shrink-0 flex items-center justify-center">
                                            {{ strtoupper(substr($instansi->nama_dinas, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $instansi->nama_dinas }}">{{ $instansi->nama_dinas }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <x-report.pill color="slate"><strong class="font-mono">{{ $instansi->applications->count() }}</strong>&nbsp;<span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">Orang</span></x-report.pill>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $percentage = min(100, round(($instansi->avg_durasi_hari / 180) * 100));
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        <x-report.progress :width="$percentage" barClass="bg-gradient-to-r from-teal-500 to-emerald-500" />
                                        <span class="text-[10px] font-bold font-mono text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $percentage }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <x-report.pill color="slate" :mono="true">{{ $instansi->avg_durasi_hari }} Hari</x-report.pill>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <x-report.pill color="teal" :mono="true">{{ number_format($instansi->avg_durasi_bulan, 1) }} Bulan</x-report.pill>
                                </td>
                            </tr>
                            @empty
                            <x-report.empty-state
                                :colspan="6"
                                title="Data instansi tidak ditemukan" />
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($instansis as $index => $instansi)
                    <div class="p-4 space-y-3.5"
                        x-show="!searchQuery || @js(strtolower($instansi->nama_dinas)).includes(searchQuery.toLowerCase())">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-300 font-black border border-teal-200 dark:border-teal-500/25 text-xs shrink-0 flex items-center justify-center">
                                {{ strtoupper(substr($instansi->nama_dinas, 0, 2)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $instansi->nama_dinas }}">{{ $instansi->nama_dinas }}</div>
                                <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-0.5">No. {{ $index + 1 }}</div>
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-2xl border border-slate-100 dark:border-slate-800/60 grid grid-cols-3 gap-3 text-center">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Peserta</p>
                                <p class="font-black text-slate-800 dark:text-white font-mono text-sm">{{ $instansi->applications->count() }}</p>
                            </div>
                            <div class="border-x border-slate-200 dark:border-slate-700/60">
                                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Hari</p>
                                <p class="font-black text-slate-800 dark:text-white font-mono text-sm">{{ $instansi->avg_durasi_hari }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Bulan</p>
                                <p class="font-black text-teal-600 dark:text-teal-400 font-mono text-sm">{{ number_format($instansi->avg_durasi_bulan, 1) }}</p>
                            </div>
                        </div>
                        @php
                            $percentageMobile = min(100, round(($instansi->avg_durasi_hari / 180) * 100));
                        @endphp
                        <div class="flex items-center gap-3">
                            <x-report.progress :width="$percentageMobile" barClass="bg-gradient-to-r from-teal-500 to-emerald-500" />
                            <span class="text-[10px] font-bold font-mono text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $percentageMobile }}%</span>
                        </div>
                    </div>
                    @empty
                    <x-report.empty-state
                        :mobile="true"
                        title="Data instansi tidak ditemukan" />
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    (function () {
        const topDurasi = @json($chartTopDurasi);

        function init() {
            window.ReportCharts.render('chart-top-durasi', function (p) {
                return {
                    type: 'bar',
                    data: {
                        labels: topDurasi.map(function (r) { return window.ReportCharts.truncate(r.nama); }),
                        datasets: [{
                            label: 'Rata-Rata Durasi (Hari)',
                            data: topDurasi.map(function (r) { return r.nilai; }),
                            backgroundColor: 'rgba(20, 184, 166, 0.85)',
                            hoverBackgroundColor: '#0d9488',
                            borderRadius: 8,
                            borderSkipped: false,
                            maxBarThickness: 18,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: window.ReportCharts.tooltip(p),
                        },
                        scales: {
                            x: { beginAtZero: true, grid: { color: p.grid }, ticks: { color: p.ticks, font: { size: 10, weight: 'bold' }, precision: 0 } },
                            y: { grid: { display: false }, ticks: { color: p.ticks, font: { size: 10, weight: 'bold' } } },
                        }
                    }
                };
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
    </script>
    @endpush
</x-app-layout>
