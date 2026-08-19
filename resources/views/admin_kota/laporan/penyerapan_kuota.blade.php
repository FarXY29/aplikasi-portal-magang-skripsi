<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    @push('styles')
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    @endpush

    <x-slot name="header">
        <x-report.header
            icon="fas fa-chart-bar"
            :title="__('Laporan Penyerapan Kuota (City-Wide)')"
            countLabel="Total Terfilter:"
            :count="$penyerapan->count()"
            countSuffix="Instansi" />
    </x-slot>

    @include('admin_kota.laporan.partials.chart-loader')

    @php
        $totalKuotaKota = $penyerapan->sum('total_kuota');
        $totalTerserapKota = $penyerapan->sum('total_terserap');
        $avgPenyerapanKota = $totalKuotaKota > 0 ? round(($totalTerserapKota / $totalKuotaKota) * 100, 1) : 0;

        $chartPenyerapan = $penyerapan
            ->sortByDesc('persentase_penyerapan')
            ->take(10)
            ->map(fn($i) => [
                'nama' => $i->nama_dinas,
                'nilai' => round($i->persentase_penyerapan, 1),
            ])
            ->values();

        $optimalCount = $penyerapan->filter(fn($i) => $i->persentase_penyerapan >= 80)->count();
        $cukupCount = $penyerapan->filter(fn($i) => $i->persentase_penyerapan >= 50 && $i->persentase_penyerapan < 80)->count();
        $rendahCount = $penyerapan->filter(fn($i) => $i->persentase_penyerapan < 50)->count();
        $chartStatusKuota = [
            'labels' => ['Optimal (≥ 80%)', 'Cukup (50-79%)', 'Rendah (< 50%)'],
            'values' => [$optimalCount, $cukupCount, $rendahCount],
            'colors' => ['#10b981', '#3b82f6', '#f43f5e'],
        ];
    @endphp

    <div class="font-[Inter] -mx-4 -mt-4 -mb-24 md:-mx-6 md:-mt-6 md:-mb-8 lg:-mx-8 lg:-mt-8 px-4 pt-4 pb-24 md:px-6 md:pt-6 md:pb-8 lg:px-8 lg:pt-8 min-h-full bg-gray-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-100"
        x-data="{ searchQuery: @js(request('q')) }">
        <div class="max-w-7xl mx-auto space-y-5 md:space-y-6">

            {{-- Navigation & Export Buttons --}}
            <x-report.toolbar
                :printRoute="$penyerapan->count() > 0 ? route('admin.laporan.penyerapan_kuota.print', request()->query()) : null" />

            {{-- Summary KPI Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                <x-report.stat-card
                    title="Total Instansi"
                    :value="number_format($penyerapan->count())"
                    icon="fas fa-building"
                    color="teal"
                    tooltip="Total jumlah instansi/dinas Pemerintah Kota Banjarmasin yang membuka formasi kuota penerimaan magang." />
                <x-report.stat-card
                    title="Total Kuota Disediakan"
                    :value="number_format($totalKuotaKota)"
                    icon="fas fa-chair"
                    color="blue"
                    tooltip="Total akumulasi daya tampung kursi magang yang disediakan oleh seluruh instansi Pemko." />
                <x-report.stat-card
                    title="Total Peserta Terserap"
                    :value="number_format($totalTerserapKota)"
                    icon="fas fa-user-check"
                    color="emerald"
                    tooltip="Total peserta magang (diterima & selesai) yang telah menempati dan mengisi kuota magang di dinas." />
                <x-report.stat-card
                    title="Rerata Penyerapan Kota"
                    :value="$avgPenyerapanKota . '%'"
                    icon="fas fa-chart-pie"
                    color="teal"
                    :featured="true"
                    tooltip="Persentase keterisian total kuota magang se-Kota Banjarmasin. Rumus: (Total Peserta Terserap / Total Kuota Disediakan) x 100%." />
            </div>

            {{-- Charts Row --}}
            @if($penyerapan->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    <div class="lg:col-span-2">
                        <x-report.chart-card
                            icon="fas fa-ranking-star"
                            iconColor="emerald"
                            title="TOP 10 Instansi dengan Penyerapan Tertinggi"
                            subtitle="Persentase keterisian kuota magang per instansi."
                            canvasId="chart-top-penyerapan"
                            height="h-64 md:h-80" />
                    </div>
                    <x-report.chart-card
                        icon="fas fa-chart-pie"
                        iconColor="blue"
                        title="Distribusi Status Penyerapan"
                        subtitle="Jumlah instansi per kategori keterisian kuota."
                        canvasId="chart-status-kuota"
                        height="h-64 md:h-80" />
                </div>
            @endif

            {{-- Main Table Card with Integrated Header Filters --}}
            <div class="bg-white dark:bg-[#161f33] shadow-lg rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 overflow-hidden">

                {{-- Card Header: Title & Integrated Right-Side Filters --}}
                <div class="p-5 md:p-6 border-b border-slate-100 dark:border-slate-800/60 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-slate-50/60 dark:bg-slate-900/40">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-teal-100 dark:bg-teal-500/15 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/25 flex items-center justify-center shrink-0">
                            <i class="fas fa-chart-bar text-base"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 dark:text-white text-base md:text-lg leading-tight">
                                Daftar Penyerapan Kuota per Instansi
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Daftar tingkat penyerapan kuota terurut dari persentase tertinggi ke terendah.</p>
                        </div>
                    </div>

                    {{-- Integrated Right-Side Filter Form --}}
                    <form method="GET" action="{{ route('admin.laporan.penyerapan_kuota') }}" class="flex flex-col sm:flex-row gap-2.5 items-stretch sm:items-center w-full lg:w-auto">
                        <div class="relative w-full sm:w-56">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-slate-500 pointer-events-none">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" name="q" value="{{ request('q') }}" x-model="searchQuery"
                                placeholder="Cari nama dinas..."
                                class="w-full pl-9 py-2 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl text-xs font-bold focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                        </div>

                        <div class="w-full sm:w-48">
                            <select name="status_keterisian" class="w-full py-2 px-3 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark]">
                                <option value="">Semua Status</option>
                                <option value="optimal" {{ request('status_keterisian') == 'optimal' ? 'selected' : '' }}>Optimal (>= 80%)</option>
                                <option value="cukup" {{ request('status_keterisian') == 'cukup' ? 'selected' : '' }}>Cukup (50% - 79%)</option>
                                <option value="rendah" {{ request('status_keterisian') == 'rendah' ? 'selected' : '' }}>Rendah (< 50%)</option>
                            </select>
                        </div>

                        <div class="flex gap-2 w-full sm:w-auto">
                            <button type="submit" class="flex-1 sm:flex-none px-4 py-2 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs font-bold shadow-md shadow-teal-500/20 transition active:scale-95 flex items-center justify-center gap-1.5">
                                <i class="fas fa-filter text-xs"></i> Filter
                            </button>
                            @if(request()->anyFilled(['q', 'status_keterisian']))
                                <a href="{{ route('admin.laporan.penyerapan_kuota') }}" class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl font-bold text-xs shadow-sm transition flex items-center justify-center">
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
                                <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-16">Rank</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[220px] max-w-[320px]">Nama Instansi</th>
                                <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-40">Kuota Disediakan</th>
                                <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-36">Total Terserap</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tingkat Penyerapan</th>
                                <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-32">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-[#161f33] divide-y divide-slate-100 dark:divide-slate-800/60 text-sm">
                            @forelse($penyerapan as $index => $instansi)
                            <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-500/5 transition group"
                                x-show="!searchQuery || @js(strtolower($instansi->nama_dinas)).includes(searchQuery.toLowerCase())">
                                <td class="px-4 py-4 text-center">
                                    <x-report.rank-badge :index="$index" :eligible="$instansi->persentase_penyerapan > 0" class="mx-auto" />
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
                                    <x-report.pill color="slate" :mono="true">{{ $instansi->total_kuota }} Kursi</x-report.pill>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <x-report.pill color="emerald" :mono="true">{{ $instansi->total_terserap }} Orang</x-report.pill>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $rate = min(100, round($instansi->persentase_penyerapan, 1));
                                        $barBg = 'from-rose-500 to-orange-500';
                                        if ($rate >= 80) {
                                            $barBg = 'from-teal-500 to-emerald-500';
                                        } elseif ($rate >= 50) {
                                            $barBg = 'from-blue-500 to-indigo-500';
                                        }
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        <x-report.progress :width="$rate" barClass="bg-gradient-to-r {{ $barBg }}" />
                                        <span class="text-xs font-black font-mono text-slate-800 dark:text-white min-w-[45px] text-right">{{ $rate }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($instansi->persentase_penyerapan >= 80)
                                        <x-report.pill color="emerald" class="uppercase text-[10px] font-black">Optimal</x-report.pill>
                                    @elseif($instansi->persentase_penyerapan >= 50)
                                        <x-report.pill color="blue" class="uppercase text-[10px] font-black">Cukup</x-report.pill>
                                    @else
                                        <x-report.pill color="rose" class="uppercase text-[10px] font-black">Rendah</x-report.pill>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <x-report.empty-state
                                :colspan="6"
                                title="Data penyerapan tidak ditemukan" />
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($penyerapan as $index => $instansi)
                        @php
                            $rate = min(100, round($instansi->persentase_penyerapan, 1));
                            $barBg = 'from-rose-500 to-orange-500';
                            if ($rate >= 80) {
                                $barBg = 'from-teal-500 to-emerald-500';
                            } elseif ($rate >= 50) {
                                $barBg = 'from-blue-500 to-indigo-500';
                            }
                        @endphp
                        <div class="p-4 space-y-3.5" x-show="!searchQuery || @js(strtolower($instansi->nama_dinas)).includes(searchQuery.toLowerCase())">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-xl bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-300 font-black border border-teal-200 dark:border-teal-500/25 text-xs shrink-0 flex items-center justify-center">
                                    {{ $index + 1 }}
                                </div>
                                <div class="h-9 w-9 rounded-xl bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-300 font-black border border-teal-200 dark:border-teal-500/25 text-xs shrink-0 flex items-center justify-center">
                                    {{ strtoupper(substr($instansi->nama_dinas, 0, 2)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $instansi->nama_dinas }}">{{ $instansi->nama_dinas }}</div>
                                </div>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-2xl border border-slate-100 dark:border-slate-800/60 grid grid-cols-3 gap-3">
                                <div class="text-center">
                                    <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Kuota</p>
                                    <span class="font-mono font-black text-slate-800 dark:text-white text-sm">{{ $instansi->total_kuota }}</span>
                                </div>
                                <div class="text-center border-x border-slate-200 dark:border-slate-700/60">
                                    <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Terisi</p>
                                    <span class="font-mono font-black text-emerald-600 dark:text-emerald-400 text-sm">{{ $instansi->total_terserap }}</span>
                                </div>
                                <div class="text-center">
                                    <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Rate</p>
                                    <span class="font-mono font-black text-slate-800 dark:text-white text-sm">{{ $rate }}%</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <x-report.progress :width="$rate" barClass="bg-gradient-to-r {{ $barBg }}" />
                                @if($instansi->persentase_penyerapan >= 80)
                                    <x-report.pill color="emerald" class="uppercase text-[10px] font-black whitespace-nowrap">Optimal</x-report.pill>
                                @elseif($instansi->persentase_penyerapan >= 50)
                                    <x-report.pill color="blue" class="uppercase text-[10px] font-black whitespace-nowrap">Cukup</x-report.pill>
                                @else
                                    <x-report.pill color="rose" class="uppercase text-[10px] font-black whitespace-nowrap">Rendah</x-report.pill>
                                @endif
                            </div>
                        </div>
                    @empty
                    <x-report.empty-state
                        :mobile="true"
                        title="Data penyerapan tidak ditemukan" />
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    (function () {
        const topPenyerapan = @json($chartPenyerapan);
        const statusKuota = @json($chartStatusKuota);

        function barColor(rate) {
            if (rate >= 80) return 'rgba(16, 185, 129, 0.85)';
            if (rate >= 50) return 'rgba(59, 130, 246, 0.85)';
            return 'rgba(244, 63, 94, 0.85)';
        }

        function init() {
            window.ReportCharts.render('chart-top-penyerapan', function (p) {
                return {
                    type: 'bar',
                    data: {
                        labels: topPenyerapan.map(function (r) { return window.ReportCharts.truncate(r.nama); }),
                        datasets: [{
                            label: 'Penyerapan (%)',
                            data: topPenyerapan.map(function (r) { return r.nilai; }),
                            backgroundColor: topPenyerapan.map(function (r) { return barColor(r.nilai); }),
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
                            x: { min: 0, max: 100, grid: { color: p.grid }, ticks: { color: p.ticks, font: { size: 10, weight: 'bold' }, callback: function (v) { return v + '%'; } } },
                            y: { grid: { display: false }, ticks: { color: p.ticks, font: { size: 10, weight: 'bold' } } },
                        }
                    }
                };
            });

            window.ReportCharts.render('chart-status-kuota', function (p) {
                return {
                    type: 'doughnut',
                    data: {
                        labels: statusKuota.labels,
                        datasets: [{
                            data: statusKuota.values,
                            backgroundColor: statusKuota.colors,
                            hoverOffset: 6,
                            borderWidth: 2,
                            borderColor: window.ReportCharts.isDark() ? '#161f33' : '#ffffff',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '62%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { color: p.legend, usePointStyle: true, boxWidth: 8, boxHeight: 8, padding: 16, font: { size: 10, weight: 'bold' } },
                            },
                            tooltip: window.ReportCharts.tooltip(p),
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
