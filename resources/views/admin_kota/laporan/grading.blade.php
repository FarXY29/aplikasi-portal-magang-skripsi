<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    @push('styles')
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    @endpush

    <x-slot name="header">
        <x-report.header
            icon="fas fa-chart-line"
            :title="__('Analisis Kompetensi & Performa')"
            countLabel="Total Terfilter:"
            :count="$stats['total']"
            countSuffix="Peserta" />
    </x-slot>

    @include('admin_kota.laporan.partials.chart-loader')

    @php
        $isUnfiltered = !request('q') && !request('instansi') && !request('instansi_id') && !request('predikat');

        $chartPredikat = [
            'labels' => ['Sangat Baik', 'Baik', 'Cukup', 'Kurang'],
            'values' => [
                (int) $stats['sangat_baik'],
                (int) $stats['baik'],
                (int) $stats['cukup'],
                (int) $stats['kurang'],
            ],
            'colors' => ['#10b981', '#3b82f6', '#f59e0b', '#f43f5e'],
        ];
    @endphp

    <div class="font-[Inter] -mx-4 -mt-4 -mb-24 md:-mx-6 md:-mt-6 md:-mb-8 lg:-mx-8 lg:-mt-8 px-4 pt-4 pb-24 md:px-6 md:pt-6 md:pb-8 lg:px-8 lg:pt-8 min-h-full bg-gray-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-100">
        <div class="max-w-7xl mx-auto space-y-5 md:space-y-6">

            {{-- Back Navigation & Download Buttons --}}
            <x-report.toolbar
                :printRoute="$stats['total'] > 0 ? route('admin.laporan.grading.print', array_merge(request()->query(), ['format' => 'pdf'])) : null" />

            {{-- Stats Cards Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4">
                <x-report.stat-card
                    title="Total Dinilai"
                    :value="number_format($stats['total'])"
                    icon="fas fa-users"
                    color="teal"
                    tooltip="Total seluruh peserta magang se-Kota Banjarmasin yang telah memiliki penilaian evaluasi akhir." />
                <x-report.stat-card
                    title="Sangat Baik"
                    :value="number_format($stats['sangat_baik'])"
                    icon="fas fa-check-circle"
                    color="emerald"
                    tooltip="Jumlah peserta dengan predikat 'Sangat Baik'. Kriteria Nilai Rata-Rata: 86,00 s.d. 100,00." />
                <x-report.stat-card
                    title="Baik"
                    :value="number_format($stats['baik'])"
                    icon="fas fa-thumbs-up"
                    color="blue"
                    tooltip="Jumlah peserta dengan predikat 'Baik'. Kriteria Nilai Rata-Rata: 71,00 s.d. 85,99." />
                <x-report.stat-card
                    title="Cukup"
                    :value="number_format($stats['cukup'])"
                    icon="fas fa-info-circle"
                    color="amber"
                    tooltip="Jumlah peserta dengan predikat 'Cukup'. Kriteria Nilai Rata-Rata: 56,00 s.d. 70,99." />
                <x-report.stat-card
                    title="Kurang"
                    :value="number_format($stats['kurang'])"
                    icon="fas fa-times-circle"
                    color="rose"
                    tooltip="Jumlah peserta dengan predikat 'Kurang'. Kriteria Nilai Rata-Rata: 0,00 s.d. 55,99." />
                <x-report.stat-card
                    title="Rerata Nilai"
                    :value="$stats['avg_nilai']"
                    icon="fas fa-star"
                    color="teal"
                    :featured="true"
                    tooltip="Nilai rata-rata evaluasi akhir akumulasi seluruh peserta yang telah dinilai di Kota Banjarmasin. Rumus: (Total Nilai Rata-Rata Seluruh Peserta / Jumlah Peserta Dinilai)." />
            </div>

            {{-- Charts Row: Predikat Distribution + 3-Component Rerata Panel --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <x-report.chart-card
                    icon="fas fa-chart-pie"
                    iconColor="emerald"
                    title="Distribusi Predikat"
                    subtitle="Sebaran predikat evaluasi akhir peserta."
                    canvasId="chart-predikat"
                    height="h-64 md:h-72" />

                {{-- 3-Component Rerata Mini Panel --}}
                <div class="lg:col-span-2 bg-white dark:bg-[#161f33] rounded-2xl md:rounded-3xl p-5 md:p-6 shadow-lg border border-slate-200 dark:border-slate-800/40 grid grid-cols-1 md:grid-cols-3 gap-6 cursor-help content-center"
                    title="Perbandingan nilai rata-rata 3 aspek utama: Teknis, Disiplin, dan Perilaku peserta se-Kota Banjarmasin.">
                    <div class="cursor-help" title="Rata-rata aspek keahlian teknis peserta. Rumus: (Total Nilai Teknis / Jumlah Peserta Dinilai). Nilai Teknis = Skill Pengetahuan.">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fas fa-laptop-code text-blue-500 dark:text-blue-400"></i> Rerata Teknis
                            </span>
                            <span class="text-sm font-black text-slate-800 dark:text-slate-200 font-mono">{{ $statsGlobal['avg_teknis'] }}/100</span>
                        </div>
                        <x-report.progress :width="$statsGlobal['avg_teknis']" barClass="bg-blue-500" />
                    </div>
                    <div class="cursor-help" title="Rata-rata aspek kedisiplinan dan ketepatan waktu peserta. Rumus: (Total Nilai Disiplin / Jumlah Peserta Dinilai).">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fas fa-calendar-check text-emerald-500 dark:text-emerald-400"></i> Rerata Disiplin
                            </span>
                            <span class="text-sm font-black text-slate-800 dark:text-slate-200 font-mono">{{ $statsGlobal['avg_disiplin'] }}/100</span>
                        </div>
                        <x-report.progress :width="$statsGlobal['avg_disiplin']" barClass="bg-emerald-500" />
                    </div>
                    <div class="cursor-help" title="Rata-rata aspek etika, keaktifan, dan komunikasi peserta. Rumus: (Total Nilai Perilaku / Jumlah Peserta). Nilai Perilaku = (Adaptasi + Kreativitas + Kerajinan) / 3.">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fas fa-user-friends text-purple-500 dark:text-purple-400"></i> Rerata Perilaku
                            </span>
                            <span class="text-sm font-black text-slate-800 dark:text-slate-200 font-mono">{{ $statsGlobal['avg_perilaku'] }}/100</span>
                        </div>
                        <x-report.progress :width="$statsGlobal['avg_perilaku']" barClass="bg-purple-500" />
                    </div>
                </div>
            </div>

            {{-- Collapsible Top 3 Podium Leaderboard --}}
            @if($podium->count() > 0)
            <div class="bg-white dark:bg-[#161f33] rounded-2xl md:rounded-3xl p-5 md:p-6 shadow-lg border border-slate-200 dark:border-slate-800/40 transition-all duration-300 cursor-help" x-data="{ showTop3: false }"
                title="Peringkat 3 alumni peserta magang dengan pencapaian nilai akhir evaluasi tertinggi se-Kota Banjarmasin.">
                <div class="flex items-center justify-between cursor-pointer select-none" @click="showTop3 = !showTop3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 md:w-11 md:h-11 rounded-2xl bg-gradient-to-br from-amber-300 to-amber-500 text-white flex items-center justify-center shadow-md shadow-amber-500/30 shrink-0">
                            <i class="fas fa-trophy text-base md:text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-sm md:text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
                                TOP 3 PERFORMER TERBAIK KOTA
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Apresiasi khusus untuk peserta magang dengan pencapaian performa tertinggi se-Kota Banjarmasin.</p>
                        </div>
                    </div>
                    <button type="button" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition shrink-0">
                        <span x-text="showTop3 ? 'Sembunyikan Podium' : 'Tampilkan TOP 3'"></span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300" :class="showTop3 ? 'rotate-180 text-teal-500' : ''"></i>
                    </button>
                </div>

                <div x-show="showTop3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak class="mt-6 border-t border-slate-100 dark:border-slate-800/60 pt-6">
                    <div class="flex flex-col md:flex-row items-end justify-center gap-6 md:gap-4 max-w-4xl mx-auto pt-4">

                        {{-- Juara 2 (Perak) --}}
                        @if($podium->count() > 1)
                        @php $p2 = $podium[1]; @endphp
                        <div class="w-full md:w-1/3 order-2 md:order-1 flex flex-col items-center">
                            <div class="relative mb-3 flex flex-col items-center">
                                <div class="h-16 w-16 rounded-full bg-gradient-to-br from-slate-200 to-slate-400 flex items-center justify-center text-slate-700 font-black border-4 border-white dark:border-slate-700 shadow-lg text-lg relative">
                                    {{ strtoupper(substr($p2['nama'], 0, 2)) }}
                                    <span class="absolute -top-2 -right-1 w-6 h-6 rounded-full bg-slate-400 text-white text-[10px] font-bold flex items-center justify-center border-2 border-white dark:border-slate-800 shadow">2</span>
                                </div>
                            </div>
                            <div class="text-center mb-2">
                                <p class="font-bold text-slate-800 dark:text-slate-200 text-sm truncate max-w-[180px]">{{ $p2['nama'] }}</p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate max-w-[180px] font-semibold">{{ $p2['asal_instansi'] }}</p>
                                <p class="text-[9px] text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/25 px-2 py-0.5 rounded-full inline-block font-bold mt-1">{{ $p2['instansi'] }}</p>
                            </div>
                            <div class="w-full bg-gradient-to-t from-slate-100 to-slate-200/60 dark:from-slate-900 dark:to-slate-800/60 rounded-t-2xl pt-8 pb-4 text-center border-t border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-center items-center h-28">
                                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">SKOR AKHIR</span>
                                <span class="text-2xl font-black text-slate-700 dark:text-slate-200 font-mono mt-1">{{ $p2['rata_rata'] }}</span>
                                <span class="text-[9px] font-extrabold text-slate-500 dark:text-slate-400 mt-1 uppercase">{{ $p2['predikat'] }}</span>
                            </div>
                        </div>
                        @endif

                        {{-- Juara 1 (Emas) --}}
                        @if($podium->count() > 0)
                        @php $p1 = $podium[0]; @endphp
                        <div class="w-full md:w-1/3 order-1 md:order-2 flex flex-col items-center transform md:-translate-y-4">
                            <div class="relative mb-3 flex flex-col items-center">
                                <div class="absolute -top-7 text-amber-500 text-2xl drop-shadow">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <div class="h-20 w-20 rounded-full bg-gradient-to-br from-amber-300 to-amber-500 flex items-center justify-center text-white font-black border-4 border-white dark:border-slate-700 shadow-lg shadow-amber-500/40 text-xl relative">
                                    {{ strtoupper(substr($p1['nama'], 0, 2)) }}
                                    <span class="absolute -top-1 -right-1 w-7 h-7 rounded-full bg-amber-500 text-white text-[11px] font-black flex items-center justify-center border-2 border-white dark:border-slate-800 shadow">1</span>
                                </div>
                            </div>
                            <div class="text-center mb-2">
                                <p class="font-black text-slate-900 dark:text-white text-base truncate max-w-[200px]">{{ $p1['nama'] }}</p>
                                <p class="text-xs text-slate-700 dark:text-slate-300 truncate max-w-[200px] font-bold">{{ $p1['asal_instansi'] }}</p>
                                <p class="text-[10px] text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/25 px-2.5 py-0.5 rounded-full inline-block font-extrabold mt-1">{{ $p1['instansi'] }}</p>
                            </div>
                            <div class="w-full bg-gradient-to-t from-amber-50 to-amber-100 dark:from-amber-950/40 dark:to-amber-900/20 rounded-t-2xl pt-10 pb-6 text-center border-t-2 border-amber-300 dark:border-amber-700 shadow flex flex-col justify-center items-center h-36">
                                <span class="text-xs font-black text-amber-800 dark:text-amber-300 uppercase tracking-wider">SKOR AKHIR</span>
                                <span class="text-3xl font-black text-amber-700 dark:text-amber-400 font-mono mt-1">{{ $p1['rata_rata'] }}</span>
                                <span class="text-[10px] font-black text-amber-600 dark:text-amber-300 mt-1 uppercase">{{ $p1['predikat'] }}</span>
                            </div>
                        </div>
                        @endif

                        {{-- Juara 3 (Perunggu) --}}
                        @if($podium->count() > 2)
                        @php $p3 = $podium[2]; @endphp
                        <div class="w-full md:w-1/3 order-3 md:order-3 flex flex-col items-center">
                            <div class="relative mb-3 flex flex-col items-center">
                                <div class="h-16 w-16 rounded-full bg-gradient-to-br from-orange-300 to-amber-600 flex items-center justify-center text-white font-black border-4 border-white dark:border-slate-700 shadow-lg text-lg relative">
                                    {{ strtoupper(substr($p3['nama'], 0, 2)) }}
                                    <span class="absolute -top-2 -right-1 w-6 h-6 rounded-full bg-amber-600 text-white text-[10px] font-bold flex items-center justify-center border-2 border-white dark:border-slate-800 shadow">3</span>
                                </div>
                            </div>
                            <div class="text-center mb-2">
                                <p class="font-bold text-slate-800 dark:text-slate-200 text-sm truncate max-w-[180px]">{{ $p3['nama'] }}</p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate max-w-[180px] font-semibold">{{ $p3['asal_instansi'] }}</p>
                                <p class="text-[9px] text-teal-600 dark:text-teal-300 bg-teal-50 dark:bg-teal-500/10 border border-teal-100 dark:border-teal-500/25 px-2 py-0.5 rounded-full inline-block font-bold mt-1">{{ $p3['instansi'] }}</p>
                            </div>
                            <div class="w-full bg-gradient-to-t from-orange-50/60 to-orange-100/40 dark:from-orange-950/40 dark:to-orange-900/20 rounded-t-2xl pt-8 pb-4 text-center border-t border-orange-200 dark:border-orange-900/40 shadow-sm flex flex-col justify-center items-center h-24">
                                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">SKOR AKHIR</span>
                                <span class="text-xl font-black text-amber-700 dark:text-amber-400 font-mono mt-1">{{ $p3['rata_rata'] }}</span>
                                <span class="text-[9px] font-extrabold text-orange-700 dark:text-orange-400 mt-1 uppercase">{{ $p3['predikat'] }}</span>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
            @endif

            {{-- Main Table Card with Integrated Header Filters --}}
            <div class="w-full space-y-6" x-data="{ openRow: null }">
                <div class="bg-white dark:bg-[#161f33] shadow-lg rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 overflow-hidden">

                    {{-- Card Header: Title & Integrated Right-Side Filters --}}
                    <div class="p-5 md:p-6 border-b border-slate-100 dark:border-slate-800/60 flex flex-col xl:flex-row xl:items-center justify-between gap-4 bg-slate-50/60 dark:bg-slate-900/40">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-teal-100 dark:bg-teal-500/15 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/25 flex items-center justify-center shrink-0">
                                <i class="fas fa-chart-line text-base"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-slate-900 dark:text-white text-base md:text-lg leading-tight">
                                    Daftar Analisis Kompetensi & Performa
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Daftar peringkat berdasarkan nilai rata-rata akhir peserta terfilter.</p>
                            </div>
                        </div>

                        {{-- Integrated Right-Side Filter Form --}}
                        <form method="GET" action="{{ route('admin.laporan.grading') }}" class="flex flex-wrap sm:flex-nowrap lg:flex-row gap-2.5 items-stretch sm:items-center w-full xl:w-auto">
                            {{-- Search Name --}}
                            <div class="relative w-full sm:w-44">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-slate-500 pointer-events-none">
                                    <i class="fas fa-search text-xs"></i>
                                </span>
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama..."
                                    class="w-full pl-9 py-2 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl text-xs font-bold focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                            </div>

                            {{-- Filter Kampus --}}
                            <div class="w-full sm:w-40">
                                <select name="instansi" class="w-full py-2 px-3 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark]">
                                    <option value="">Semua Kampus</option>
                                    @foreach($listCampus as $campus)
                                        <option value="{{ $campus }}" {{ request('instansi') == $campus ? 'selected' : '' }}>
                                            {{ Str::limit($campus, 25) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Dinas --}}
                            <div class="w-full sm:w-40">
                                <select name="instansi_id" class="w-full py-2 px-3 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark]">
                                    <option value="">Semua Dinas</option>
                                    @foreach($listDinas as $dinas)
                                        <option value="{{ $dinas->id }}" {{ request('instansi_id') == $dinas->id ? 'selected' : '' }}>
                                            {{ Str::limit($dinas->nama_dinas, 25) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Predikat --}}
                            <div class="w-full sm:w-36">
                                <select name="predikat" class="w-full py-2 px-3 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark]">
                                    <option value="">Semua Predikat</option>
                                    <option value="Sangat Baik" {{ request('predikat') == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                                    <option value="Baik" {{ request('predikat') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Cukup" {{ request('predikat') == 'Cukup' ? 'selected' : '' }}>Cukup</option>
                                    <option value="Kurang" {{ request('predikat') == 'Kurang' ? 'selected' : '' }}>Kurang</option>
                                </select>
                            </div>

                            <div class="flex gap-2 w-full sm:w-auto">
                                <button type="submit" class="flex-1 sm:flex-none px-4 py-2 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs font-bold shadow-md shadow-teal-500/20 transition active:scale-95 flex items-center justify-center gap-1.5">
                                    <i class="fas fa-filter text-xs"></i> Filter
                                </button>
                                @if(request()->anyFilled(['q', 'instansi', 'instansi_id', 'predikat']))
                                    <a href="{{ route('admin.laporan.grading') }}" class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl font-bold text-xs shadow-sm transition flex items-center justify-center">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Desktop Table View (>=md) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full divide-y divide-slate-100 dark:divide-slate-800/60 border-collapse">
                            <thead class="bg-slate-50 dark:bg-slate-900">
                                <tr>
                                    <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-16">Rank</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[200px] max-w-[260px]">Peserta & Asal Sekolah</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[220px] max-w-[280px]">Penempatan Dinas & Posisi</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-28">Skor Akhir</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-32">Predikat</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-16">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-[#161f33] divide-y divide-slate-100 dark:divide-slate-800/60 text-sm">
                                @forelse($gradedList as $index => $res)
                                <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-500/5 transition group cursor-pointer" @click="openRow = (openRow === {{ $index }} ? null : {{ $index }})">
                                    <td class="px-4 py-4 text-center">
                                        <x-report.rank-badge :index="$index" :eligible="$isUnfiltered" class="mx-auto" />
                                    </td>
                                    <td class="px-6 py-4 min-w-[200px] max-w-[260px]">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-xl bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-300 font-black border border-teal-200 dark:border-teal-500/25 text-xs shrink-0 flex items-center justify-center">
                                                {{ strtoupper(substr($res['nama'], 0, 2)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $res['nama'] }}">{{ $res['nama'] }}</div>
                                                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold leading-snug line-clamp-2 mt-0.5" title="{{ $res['asal_instansi'] }}">
                                                    <i class="fas fa-university mr-1 text-slate-400 dark:text-slate-500"></i> {{ $res['asal_instansi'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 min-w-[220px] max-w-[280px]">
                                        <div class="flex flex-col gap-0.5">
                                            <span class="font-bold text-slate-900 dark:text-white text-xs leading-snug line-clamp-2" title="{{ $res['instansi'] }}">
                                                <i class="far fa-building text-slate-400 dark:text-slate-500 mr-1"></i>
                                                {{ $res['instansi'] }}
                                            </span>
                                            <span class="text-[11px] text-teal-600 dark:text-teal-400 font-medium leading-snug line-clamp-2 mt-0.5" title="{{ $res['posisi'] }}">
                                                Posisi: {{ $res['posisi'] }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="text-sm font-black text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/25 px-2.5 py-1 rounded-full inline-block font-mono">{{ $res['rata_rata'] }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        @php
                                            $predikatColor = match($res['predikat']) {
                                                'Sangat Baik' => 'emerald',
                                                'Baik' => 'blue',
                                                'Cukup' => 'amber',
                                                default => 'rose',
                                            };
                                        @endphp
                                        <x-report.pill :color="$predikatColor" class="uppercase text-[10px] font-black">{{ $res['predikat'] }}</x-report.pill>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-slate-900/60 flex items-center justify-center mx-auto border border-slate-200 dark:border-slate-700">
                                            <i class="fas fa-chevron-down text-slate-400 dark:text-slate-500 text-xs transition-transform duration-200" :class="openRow === {{ $index }} ? 'rotate-180 text-teal-600 dark:text-teal-400' : ''"></i>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Expanded detail row --}}
                                <tr x-show="openRow === {{ $index }}" x-transition.opacity x-cloak>
                                    <td colspan="6" class="px-4 py-4 bg-slate-50/80 dark:bg-slate-900/40 border-y border-slate-100 dark:border-slate-800/60">
                                        <div class="bg-white dark:bg-[#161f33] rounded-2xl border border-slate-200 dark:border-slate-800/60 p-4 sm:p-5 shadow-sm space-y-4">
                                            <h4 class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-2 border-b pb-2 border-slate-100 dark:border-slate-800/60">
                                                <i class="fas fa-award text-teal-600 dark:text-teal-400"></i> Rincian Penilaian Kompetensi & Performa Peserta
                                            </h4>

                                            @if($res['nilai_rata_rata'] !== null)
                                                {{-- New Grading System --}}
                                                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                                        <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Kerajinan</div>
                                                        <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ $res['kerajinan'] }}</div>
                                                    </div>
                                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                                        <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Kedisiplinan</div>
                                                        <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ $res['disiplin'] }}</div>
                                                    </div>
                                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                                        <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Adaptasi</div>
                                                        <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ $res['adaptasi'] }}</div>
                                                    </div>
                                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                                        <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Kreatifitas</div>
                                                        <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ $res['kreatifitas'] }}</div>
                                                    </div>
                                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60 col-span-2 md:col-span-1">
                                                        <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Skill & Pengetahuan</div>
                                                        <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ $res['skill'] }}</div>
                                                    </div>
                                                </div>
                                                <div class="mt-3 flex items-center justify-between text-xs text-slate-400 dark:text-slate-500 italic">
                                                    <span>*Sistem Penilaian Utama (5 Aspek)</span>
                                                    <span>Rata-rata: <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $res['rata_rata'] }}</strong></span>
                                                </div>
                                            @else
                                                {{-- Old Grading System --}}
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                                        <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Kompetensi Teknis</div>
                                                        <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ $res['teknis'] }}</div>
                                                    </div>
                                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                                        <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Kedisiplinan</div>
                                                        <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ $res['disiplin'] }}</div>
                                                    </div>
                                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                                        <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Sikap & Perilaku</div>
                                                        <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ $res['perilaku'] }}</div>
                                                    </div>
                                                </div>
                                                <div class="mt-3 flex items-center justify-between text-xs text-slate-400 dark:text-slate-500 italic">
                                                    <span>*Sistem Penilaian Tambahan (3 Aspek)</span>
                                                    <span>Rata-rata: <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $res['rata_rata'] }}</strong></span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <x-report.empty-state
                                    :colspan="6"
                                    title="Data tidak ditemukan"
                                    subtitle="Coba sesuaikan filter pencarian Anda." />
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Card View (<md) --}}
                    <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($gradedList as $index => $res)
                        <div class="p-4 space-y-3.5 hover:bg-slate-50 dark:hover:bg-slate-900/40 transition" x-data="{ open: false }">
                            {{-- Header: rank icon + peserta + predikat badge --}}
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="shrink-0">
                                        <x-report.rank-badge :index="$index" :eligible="$isUnfiltered" size="md" />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $res['nama'] }}">{{ $res['nama'] }}</h4>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold leading-snug line-clamp-2 mt-0.5" title="{{ $res['asal_instansi'] }}">
                                            <i class="fas fa-university mr-1 text-slate-400 dark:text-slate-500"></i>{{ $res['asal_instansi'] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    @php
                                        $predikatColorMobile = match($res['predikat']) {
                                            'Sangat Baik' => 'emerald',
                                            'Baik' => 'blue',
                                            'Cukup' => 'amber',
                                            default => 'rose',
                                        };
                                    @endphp
                                    <x-report.pill :color="$predikatColorMobile" class="uppercase text-[10px] font-black">{{ $res['predikat'] }}</x-report.pill>
                                </div>
                            </div>

                            {{-- Detail: dinas, posisi, skor akhir --}}
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-2xl border border-slate-100 dark:border-slate-800/60 space-y-2.5">
                                <div class="flex items-start gap-2 text-xs">
                                    <i class="far fa-building text-slate-400 dark:text-slate-500 mt-0.5"></i>
                                    <span class="font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $res['instansi'] }}">{{ $res['instansi'] }}</span>
                                </div>
                                <div class="flex items-start gap-2 text-[11px]">
                                    <i class="fas fa-briefcase text-teal-500 dark:text-teal-400 mt-0.5"></i>
                                    <span class="text-teal-600 dark:text-teal-400 font-medium leading-snug line-clamp-2" title="{{ $res['posisi'] }}">{{ $res['posisi'] }}</span>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-slate-200 dark:border-slate-700/60">
                                    <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">Skor Akhir</span>
                                    <span class="text-sm font-black text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/25 px-2.5 py-1 rounded-full inline-block font-mono">{{ $res['rata_rata'] }}</span>
                                </div>
                            </div>

                            {{-- Expandable detail (rincian penilaian) --}}
                            <button type="button" @click="open = !open" class="w-full flex items-center justify-center gap-1.5 py-2 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                <span x-text="open ? 'Sembunyikan Rincian' : 'Lihat Rincian'"></span>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180 text-teal-600 dark:text-teal-400' : ''"></i>
                            </button>
                            <div x-show="open" x-transition.opacity x-cloak class="bg-white dark:bg-slate-900/40 rounded-2xl border border-slate-200 dark:border-slate-700/60 p-4 shadow-sm space-y-3">
                                <h4 class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-2 border-b pb-2 border-slate-100 dark:border-slate-800/60">
                                    <i class="fas fa-award text-teal-600 dark:text-teal-400"></i> Rincian Penilaian
                                </h4>
                                @if($res['nilai_rata_rata'] !== null)
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Kerajinan</div>
                                            <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res['kerajinan'] }}</div>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Kedisiplinan</div>
                                            <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res['disiplin'] }}</div>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Adaptasi</div>
                                            <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res['adaptasi'] }}</div>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Kreatifitas</div>
                                            <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res['kreatifitas'] }}</div>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60 col-span-2">
                                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Skill & Pengetahuan</div>
                                            <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res['skill'] }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-slate-400 dark:text-slate-500 italic">
                                        <span>*Sistem Penilaian Utama (5 Aspek)</span>
                                        <span>Rata-rata: <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $res['rata_rata'] }}</strong></span>
                                    </div>
                                @else
                                    <div class="grid grid-cols-3 gap-2">
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Teknis</div>
                                            <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res['teknis'] }}</div>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Disiplin</div>
                                            <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res['disiplin'] }}</div>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Perilaku</div>
                                            <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res['perilaku'] }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-slate-400 dark:text-slate-500 italic">
                                        <span>*Sistem Penilaian Tambahan (3 Aspek)</span>
                                        <span>Rata-rata: <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $res['rata_rata'] }}</strong></span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <x-report.empty-state
                            :mobile="true"
                            title="Data tidak ditemukan"
                            subtitle="Coba sesuaikan filter pencarian Anda." />
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    (function () {
        const predikat = @json($chartPredikat);

        function init() {
            window.ReportCharts.render('chart-predikat', function (p) {
                return {
                    type: 'doughnut',
                    data: {
                        labels: predikat.labels,
                        datasets: [{
                            data: predikat.values,
                            backgroundColor: predikat.colors,
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
