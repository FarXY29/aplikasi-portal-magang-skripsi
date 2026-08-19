<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    @push('styles')
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    @endpush

    <x-slot name="header">
        <x-report.header icon="fas fa-chart-pie" :title="__('Laporan Statistik Instansi')" />
    </x-slot>

    @include('admin_kota.laporan.partials.chart-loader')

    @php
        $chartTopPelamar = collect($laporan)
            ->sortByDesc('total_pelamar')
            ->take(10)
            ->map(fn($row) => [
                'nama' => $row['nama_dinas'],
                'nilai' => (int) $row['total_pelamar'],
            ])
            ->values();

        $belumDiterima = max(0, (int) $stats['total_pelamar'] - (int) $stats['total_diterima']);
        $chartSeleksi = [
            'labels' => ['Diterima / Lulus', 'Belum Diterima'],
            'values' => [(int) $stats['total_diterima'], $belumDiterima],
            'colors' => ['#10b981', '#94a3b8'],
        ];
    @endphp

    <div class="font-[Inter] -mx-4 -mt-4 -mb-24 md:-mx-6 md:-mt-6 md:-mb-8 lg:-mx-8 lg:-mt-8 px-4 pt-4 pb-24 md:px-6 md:pt-6 md:pb-8 lg:px-8 lg:pt-8 min-h-full bg-gray-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-100">
        <div class="max-w-7xl mx-auto space-y-5 md:space-y-6">

            {{-- Navigation & Export Buttons --}}
            <x-report.toolbar
                :printRoute="$laporan->count() > 0 ? route('admin.laporan.print', request()->query()) : null" />

            {{-- Stats Cards Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4">
                <x-report.stat-card
                    title="Total Instansi"
                    :value="number_format($stats['total_instansi'])"
                    icon="fas fa-building"
                    color="teal"
                    tooltip="Total seluruh instansi/dinas Pemerintah Kota Banjarmasin yang terdaftar dalam sistem portal magang." />
                <x-report.stat-card
                    title="Lowongan Aktif"
                    :value="number_format($stats['total_lowongan'])"
                    icon="fas fa-briefcase"
                    color="blue"
                    tooltip="Jumlah posisi lowongan magang berstatus terbuka (buka) yang sedang ditawarkan seluruh instansi." />
                <x-report.stat-card
                    title="Total Pelamar"
                    :value="number_format($stats['total_pelamar'])"
                    icon="fas fa-users"
                    color="indigo"
                    tooltip="Total akumulasi berkas pendaftaran/lamaran peserta yang masuk ke seluruh instansi Pemko." />
                <x-report.stat-card
                    title="Diterima / Lulus"
                    :value="number_format($stats['total_diterima'])"
                    icon="fas fa-user-check"
                    color="emerald"
                    tooltip="Jumlah peserta magang yang telah diterima (status 'diterima') atau telah lulus/selesai (status 'selesai') secara akumulatif di seluruh instansi." />
                <x-report.stat-card
                    title="Seleksi Kota"
                    :value="$stats['avg_seleksi_rate'] . '%'"
                    icon="fas fa-percentage"
                    color="amber"
                    :featured="true"
                    tooltip="Rata-rata persentase kelulusan seleksi tingkat kota. Rumus: (Total Diterima / Total Pelamar) x 100%." />
                <x-report.stat-card
                    title="Instansi Favorit"
                    :value="$stats['fav_dinas']"
                    icon="fas fa-award"
                    color="rose"
                    :textMode="true"
                    tooltip="Instansi/dinas dengan jumlah pendaftar terbanyak se-Kota Banjarmasin." />
            </div>

            {{-- Highlight Banner --}}
            <div class="relative overflow-hidden bg-gradient-to-r from-teal-600 via-teal-600 to-emerald-600 rounded-2xl md:rounded-3xl p-6 text-white shadow-lg shadow-teal-500/20 border border-teal-400/30 flex flex-col sm:flex-row items-center gap-6">
                <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-3xl shrink-0 border border-white/20 shadow-md">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="text-center sm:text-left flex-grow space-y-1">
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-teal-100">Statistik Rekapitulasi Program Magang Kota</p>
                    <h2 class="text-xl font-extrabold mt-0.5 tracking-tight">Maju Bersama {{ $stats['total_instansi'] }} Instansi Pemerintahan</h2>
                    <p class="text-xs text-teal-50/90 font-medium">Tingkat seleksi kelulusan peserta kota berada pada kisaran {{ $stats['avg_seleksi_rate'] }}%.</p>
                </div>
            </div>

            {{-- Charts Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <div class="lg:col-span-2">
                    <x-report.chart-card
                        icon="fas fa-ranking-star"
                        iconColor="teal"
                        title="TOP 10 Instansi Paling Diminati"
                        subtitle="Instansi dengan total pelamar terbanyak se-Kota Banjarmasin."
                        canvasId="chart-top-pelamar"
                        height="h-64 md:h-80" />
                </div>
                <x-report.chart-card
                    icon="fas fa-chart-pie"
                    iconColor="emerald"
                    title="Komposisi Hasil Seleksi"
                    subtitle="Perbandingan pelamar diterima vs belum diterima."
                    canvasId="chart-seleksi"
                    height="h-64 md:h-80" />
            </div>

            {{-- Main Table Card with Integrated Header Filters --}}
            <div class="bg-white dark:bg-[#161f33] shadow-lg rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 overflow-hidden">

                {{-- Card Header: Title & Integrated Right-Side Filter --}}
                <div class="p-5 md:p-6 border-b border-slate-100 dark:border-slate-800/60 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-slate-50/60 dark:bg-slate-900/40">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-teal-100 dark:bg-teal-500/15 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/25 flex items-center justify-center shrink-0">
                            <i class="fas fa-building text-base"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 dark:text-white text-base md:text-lg leading-tight">
                                Penerimaan & Daya Serap per Instansi
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Rekapitulasi performa daya serap pelamar magang dan efektivitas seleksi untuk masing-masing instansi.</p>
                        </div>
                    </div>

                    {{-- Right-Side Filter Form --}}
                    <form action="{{ route('admin.laporan') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5 items-stretch sm:items-center w-full lg:w-auto">
                        <div class="relative w-full sm:w-60">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-slate-500 pointer-events-none">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama dinas..."
                                class="w-full pl-9 py-2 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl text-xs font-bold focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                        </div>

                        <div class="w-full sm:w-52">
                            <select name="sort" class="w-full py-2 px-3 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark]">
                                <option value="pelamar_desc" {{ request('sort') == 'pelamar_desc' ? 'selected' : '' }}>Peminat Terbanyak</option>
                                <option value="pelamar_asc" {{ request('sort') == 'pelamar_asc' ? 'selected' : '' }}>Peminat Tersedikit</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama Instansi (A - Z)</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Instansi (Z - A)</option>
                                <option value="lowongan_desc" {{ request('sort') == 'lowongan_desc' ? 'selected' : '' }}>Lowongan Terbanyak</option>
                                <option value="lowongan_asc" {{ request('sort') == 'lowongan_asc' ? 'selected' : '' }}>Lowongan Tersedikit</option>
                                <option value="seleksi_desc" {{ request('sort') == 'seleksi_desc' ? 'selected' : '' }}>Rasio Kelulusan Tertinggi</option>
                                <option value="seleksi_asc" {{ request('sort') == 'seleksi_asc' ? 'selected' : '' }}>Rasio Kelulusan Terendah</option>
                            </select>
                        </div>

                        <div class="flex gap-2 w-full sm:w-auto">
                            <button type="submit" class="flex-1 sm:flex-none px-4 py-2 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs font-bold shadow-md shadow-teal-500/20 transition active:scale-95 flex items-center justify-center gap-1.5">
                                <i class="fas fa-filter text-xs"></i> Filter
                            </button>
                            @if(request()->anyFilled(['search', 'sort']))
                                <a href="{{ route('admin.laporan') }}" class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl font-bold text-xs shadow-sm transition flex items-center justify-center">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Table Content (Desktop) --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider bg-slate-50 dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800/60">
                                <th class="px-6 py-4 w-12 text-center">No</th>
                                <th class="px-6 py-4">Nama Instansi</th>
                                <th class="px-6 py-4 text-center w-36">Lowongan Aktif</th>
                                <th class="px-6 py-4 text-center w-36">Total Pelamar</th>
                                <th class="px-6 py-4 text-center w-36">Diterima / Selesai</th>
                                <th class="px-6 py-4 text-center w-40">Tingkat Seleksi</th>
                                <th class="px-6 py-4 text-center w-44">Rasio Peminat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-sm">
                            @forelse($laporan as $index => $data)
                            <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-500/5 transition group">
                                <td class="px-6 py-4 text-center text-slate-400 dark:text-slate-500 font-bold text-xs">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/25 flex items-center justify-center text-xs shrink-0">
                                            <i class="far fa-building"></i>
                                        </div>
                                        <span class="font-bold text-slate-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition">{{ $data['nama_dinas'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <x-report.pill color="slate" :mono="true">{{ $data['lowongan_aktif'] }} Posisi</x-report.pill>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <x-report.pill color="blue" :mono="true">{{ $data['total_pelamar'] }} Orang</x-report.pill>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <x-report.pill color="emerald" :mono="true">{{ $data['total_magang'] }} Orang</x-report.pill>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <span class="font-black text-slate-900 dark:text-white font-mono">{{ $data['seleksi_rate'] }}%</span>
                                        <x-report.progress :width="$data['seleksi_rate']" height="h-1.5" class="w-24" />
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-slate-600 dark:text-slate-400 font-bold text-xs">
                                        {{ $data['avg_peminat'] }} <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">pelamar/posisi</span>
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <x-report.empty-state
                                :colspan="7"
                                title="Data instansi tidak ditemukan"
                                subtitle="Coba sesuaikan kata kunci pencarian Anda." />
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($laporan as $index => $data)
                    <div class="p-4 space-y-3.5">
                        {{-- Header: No + Instansi Name --}}
                        <div class="flex items-center gap-3">
                            <span class="text-slate-400 dark:text-slate-500 font-bold text-xs shrink-0">{{ $index + 1 }}</span>
                            <div class="w-9 h-9 rounded-xl bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/25 flex items-center justify-center text-xs shrink-0">
                                <i class="far fa-building"></i>
                            </div>
                            <span class="font-bold text-slate-900 dark:text-white text-sm leading-tight">{{ $data['nama_dinas'] }}</span>
                        </div>

                        {{-- Detail Grid --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-100 dark:border-slate-800/60">
                                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Lowongan Aktif</p>
                                <x-report.pill color="slate" :mono="true">{{ $data['lowongan_aktif'] }} Posisi</x-report.pill>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-100 dark:border-slate-800/60">
                                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Total Pelamar</p>
                                <x-report.pill color="blue" :mono="true">{{ $data['total_pelamar'] }} Orang</x-report.pill>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-100 dark:border-slate-800/60">
                                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Diterima</p>
                                <x-report.pill color="emerald" :mono="true">{{ $data['total_magang'] }} Orang</x-report.pill>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-100 dark:border-slate-800/60">
                                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Rasio Peminat</p>
                                <span class="text-slate-600 dark:text-slate-400 font-bold text-xs">
                                    {{ $data['avg_peminat'] }} <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">pelamar/posisi</span>
                                </span>
                            </div>
                        </div>

                        {{-- Tingkat Seleksi + Progress Bar --}}
                        <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-100 dark:border-slate-800/60">
                            <div class="flex items-center justify-between mb-1.5">
                                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Tingkat Seleksi</p>
                                <span class="font-black text-slate-900 dark:text-white font-mono text-sm">{{ $data['seleksi_rate'] }}%</span>
                            </div>
                            <x-report.progress :width="$data['seleksi_rate']" height="h-1.5" />
                        </div>
                    </div>
                    @empty
                    <x-report.empty-state
                        :mobile="true"
                        title="Data instansi tidak ditemukan"
                        subtitle="Coba sesuaikan kata kunci pencarian Anda." />
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    (function () {
        const topPelamar = @json($chartTopPelamar);
        const seleksi = @json($chartSeleksi);

        function init() {
            window.ReportCharts.render('chart-top-pelamar', function (p) {
                return {
                    type: 'bar',
                    data: {
                        labels: topPelamar.map(function (r) { return window.ReportCharts.truncate(r.nama); }),
                        datasets: [{
                            label: 'Total Pelamar',
                            data: topPelamar.map(function (r) { return r.nilai; }),
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

            window.ReportCharts.render('chart-seleksi', function (p) {
                return {
                    type: 'doughnut',
                    data: {
                        labels: seleksi.labels,
                        datasets: [{
                            data: seleksi.values,
                            backgroundColor: seleksi.colors,
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
