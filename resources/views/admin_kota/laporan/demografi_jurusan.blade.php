<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    @push('styles')
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    @endpush

    <x-slot name="header">
        <x-report.header
            icon="fas fa-graduation-cap"
            :title="__('Demografi Kualifikasi Jurusan')"
            countLabel="Total Kualifikasi:"
            :count="$jurusans->count()"
            countSuffix="Jurusan" />
    </x-slot>

    @include('admin_kota.laporan.partials.chart-loader')

    @php
        $totalLowongan = $jurusans->sum('total_lowongan');
        $totalKuota = $jurusans->sum('total_kuota');
        $topJurusan = $jurusans->first() ? $jurusans->first()->required_major : '-';
        $maxKuota = $jurusans->max('total_kuota') ?: 1;

        $chartTopJurusan = $jurusans
            ->sortByDesc('total_kuota')
            ->take(10)
            ->map(fn($row) => [
                'nama' => $row->required_major,
                'nilai' => (int) $row->total_kuota,
            ])
            ->values();
    @endphp

    <div class="font-[Inter] -mx-4 -mt-4 -mb-24 md:-mx-6 md:-mt-6 md:-mb-8 lg:-mx-8 lg:-mt-8 px-4 pt-4 pb-24 md:px-6 md:pt-6 md:pb-8 lg:px-8 lg:pt-8 min-h-full bg-gray-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-100"
        x-data="{ searchQuery: @js(request('q')) }">
        <div class="max-w-7xl mx-auto space-y-5 md:space-y-6">

            {{-- Navigation & Export Buttons --}}
            <x-report.toolbar
                :printRoute="$jurusans->count() > 0 ? route('admin.laporan.demografi_jurusan.print', request()->query()) : null" />

            {{-- Summary KPI Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                <x-report.stat-card
                    title="Total Kualifikasi"
                    :value="number_format($jurusans->count())"
                    icon="fas fa-graduation-cap"
                    color="teal"
                    tooltip="Jumlah kualifikasi atau jurusan yang memiliki data lowongan pada laporan." />
                <x-report.stat-card
                    title="Lowongan Terbuka"
                    :value="number_format($totalLowongan)"
                    icon="fas fa-briefcase"
                    color="blue"
                    tooltip="Jumlah akumulasi seluruh posisi lowongan magang yang dipublikasikan oleh instansi Pemerintah Kota Banjarmasin." />
                <x-report.stat-card
                    title="Total Kuota Kursi"
                    :value="number_format($totalKuota)"
                    icon="fas fa-chair"
                    color="indigo"
                    tooltip="Total kapasitas daya tampung kursi peserta magang yang disediakan dari seluruh formasi lowongan terbuka." />
                <x-report.stat-card
                    title="Jurusan Terfavorit"
                    :value="$topJurusan"
                    icon="fas fa-star"
                    color="teal"
                    :featured="true"
                    :textMode="true"
                    tooltip="Kualifikasi jurusan dengan alokasi total kuota penerimaan magang terbanyak di Kota Banjarmasin." />
            </div>

            {{-- Chart: Top 10 Jurusan by Kuota --}}
            @if($jurusans->count() > 0)
                <x-report.chart-card
                    icon="fas fa-ranking-star"
                    iconColor="indigo"
                    title="TOP 10 Jurusan dengan Kuota Terbanyak"
                    subtitle="Kualifikasi jurusan dengan alokasi kursi magang terbesar."
                    canvasId="chart-top-jurusan"
                    height="h-64 md:h-80" />
            @endif

            {{-- Main Table Card with Integrated Right-Side Filters --}}
            <div class="bg-white dark:bg-[#161f33] shadow-lg rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 overflow-hidden">

                {{-- Card Header: Title & Integrated Right-Side Filters --}}
                <div class="p-5 md:p-6 border-b border-slate-100 dark:border-slate-800/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/60 dark:bg-slate-900/40">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-teal-100 dark:bg-teal-500/15 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/25 flex items-center justify-center shrink-0">
                            <i class="fas fa-graduation-cap text-base"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 dark:text-white text-base md:text-lg leading-tight">
                                Daftar Pemeringkatan Jurusan Paling Dicari
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Kualifikasi jurusan terurut dari alokasi kuota terbanyak ke terendah.</p>
                        </div>
                    </div>

                    {{-- Integrated Right-Side Filter Form --}}
                    <form method="GET" action="{{ route('admin.laporan.demografi_jurusan') }}" class="flex flex-col sm:flex-row gap-2.5 items-stretch sm:items-center w-full sm:w-auto">
                        <div class="relative w-full sm:w-64">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-slate-500 pointer-events-none">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" name="q" value="{{ request('q') }}" x-model="searchQuery"
                                placeholder="Cari nama jurusan..."
                                class="w-full pl-9 py-2 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl text-xs font-bold focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                        </div>

                        <div class="flex gap-2 w-full sm:w-auto">
                            <button type="submit" class="flex-1 sm:flex-none px-4 py-2 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs font-bold shadow-md shadow-teal-500/20 transition active:scale-95 flex items-center justify-center gap-1.5">
                                <i class="fas fa-filter text-xs"></i> Filter
                            </button>
                            @if(request()->filled('q'))
                                <a href="{{ route('admin.laporan.demografi_jurusan') }}" class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl font-bold text-xs shadow-sm transition flex items-center justify-center">
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
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[220px] max-w-[320px]">Jurusan / Kualifikasi</th>
                                <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-40">Lowongan Terkait</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">Visual Proporsi Kuota</th>
                                <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-44">Total Kuota Kursi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-[#161f33] divide-y divide-slate-100 dark:divide-slate-800/60 text-sm">
                            @forelse($jurusans as $index => $jurusan)
                            <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-500/5 transition group"
                                x-show="!searchQuery || @js(strtolower((string) $jurusan->required_major)).includes(searchQuery.toLowerCase())">
                                <td class="px-4 py-4 text-center">
                                    <x-report.rank-badge :index="$index" class="mx-auto" />
                                </td>
                                <td class="px-6 py-4 min-w-[220px] max-w-[320px]">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-xl bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-500/25 text-xs shrink-0 flex items-center justify-center">
                                            <i class="fas fa-graduation-cap"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $jurusan->required_major }}">{{ $jurusan->required_major }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <x-report.pill color="blue"><strong class="font-mono">{{ $jurusan->total_lowongan }}</strong>&nbsp;Lowongan</x-report.pill>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $percentage = round(($jurusan->total_kuota / $maxKuota) * 100);
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        <x-report.progress :width="$percentage" barClass="bg-gradient-to-r from-teal-500 to-indigo-500" />
                                        <span class="text-[10px] font-bold font-mono text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $percentage }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <x-report.pill color="teal" :mono="true">{{ $jurusan->total_kuota }} Kursi</x-report.pill>
                                </td>
                            </tr>
                            @empty
                            <x-report.empty-state
                                :colspan="5"
                                title="Data jurusan tidak ditemukan" />
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($jurusans as $index => $jurusan)
                    <div class="p-4 space-y-3.5"
                        x-show="!searchQuery || @js(strtolower((string) $jurusan->required_major)).includes(searchQuery.toLowerCase())">
                        {{-- Header: Rank + Jurusan name --}}
                        <div class="flex items-center gap-3">
                            <x-report.rank-badge :index="$index" size="md" />
                            <div class="h-9 w-9 rounded-xl bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-500/25 text-xs shrink-0 flex items-center justify-center">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $jurusan->required_major }}">{{ $jurusan->required_major }}</div>
                            </div>
                        </div>

                        {{-- Detail box: counts + percentage + progress bar --}}
                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-3.5 space-y-3 border border-slate-100 dark:border-slate-800/60">
                            <div class="flex items-center justify-between gap-2">
                                <x-report.pill color="blue"><strong class="font-mono">{{ $jurusan->total_lowongan }}</strong>&nbsp;Lowongan</x-report.pill>
                                <x-report.pill color="teal" :mono="true">{{ $jurusan->total_kuota }} Kursi</x-report.pill>
                            </div>
                            @php
                                $percentage = round(($jurusan->total_kuota / $maxKuota) * 100);
                            @endphp
                            <div class="flex items-center gap-3">
                                <x-report.progress :width="$percentage" barClass="bg-gradient-to-r from-teal-500 to-indigo-500" />
                                <span class="text-[10px] font-bold font-mono text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $percentage }}%</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <x-report.empty-state
                        :mobile="true"
                        title="Data jurusan tidak ditemukan" />
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    (function () {
        const topJurusan = @json($chartTopJurusan);

        function init() {
            window.ReportCharts.render('chart-top-jurusan', function (p) {
                return {
                    type: 'bar',
                    data: {
                        labels: topJurusan.map(function (r) { return window.ReportCharts.truncate(r.nama); }),
                        datasets: [{
                            label: 'Total Kuota Kursi',
                            data: topJurusan.map(function (r) { return r.nilai; }),
                            backgroundColor: 'rgba(99, 102, 241, 0.85)',
                            hoverBackgroundColor: '#4f46e5',
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
