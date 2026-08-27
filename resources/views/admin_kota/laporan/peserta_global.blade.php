<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    @push('styles')
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    @endpush

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 min-w-0">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-teal-500 text-white flex items-center justify-center shadow-md shadow-teal-500/25 shrink-0">
                    <i class="fas fa-users-cog text-sm md:text-base"></i>
                </div>
                <h2 class="font-black text-base md:text-xl text-slate-900 dark:text-white leading-tight truncate">
                    {{ __('Rekapitulasi Global Peserta Magang') }}
                </h2>
            </div>
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                @if(request()->anyFilled(['instansi', 'instansi_id', 'status', 'posisi', 'q', 'start_date', 'end_date']))
                    <a href="{{ route('admin.laporan.peserta_global') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30 hover:bg-rose-100 dark:hover:bg-rose-500/20 rounded-xl font-bold text-xs transition shadow-sm">
                        <i class="fas fa-redo-alt text-[10px]"></i> Reset Filter
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    @include('admin_kota.laporan.partials.chart-loader')

    @php
        $sumKnownStatus = (int) $stats['aktif'] + (int) $stats['selesai'] + (int) $stats['pending'];
        $otherStatus = max(0, (int) $stats['total'] - $sumKnownStatus);

        $chartStatusLabels = ['Aktif Magang', 'Selesai Magang', 'Pending / Menunggu'];
        $chartStatusValues = [(int) $stats['aktif'], (int) $stats['selesai'], (int) $stats['pending']];
        $chartStatusColors = ['#10b981', '#3b82f6', '#f59e0b'];
        if ($otherStatus > 0) {
            $chartStatusLabels[] = 'Lainnya';
            $chartStatusValues[] = $otherStatus;
            $chartStatusColors[] = '#94a3b8';
        }
        $chartStatusGlobal = [
            'labels' => $chartStatusLabels,
            'values' => $chartStatusValues,
            'colors' => $chartStatusColors,
        ];
    @endphp

    <div class="font-[Inter] -mx-4 -mt-4 -mb-24 md:-mx-6 md:-mt-6 md:-mb-8 lg:-mx-8 lg:-mt-8 px-4 pt-4 pb-24 md:px-6 md:pt-6 md:pb-8 lg:px-8 lg:pt-8 min-h-full bg-gray-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-100"
        x-data="{ quickSearch: '' }">
        <div class="max-w-7xl mx-auto space-y-5 md:space-y-6">

            {{-- Back Navigation & Export Buttons --}}
            <x-report.toolbar
                :printRoute="$stats['total'] > 0 ? route('admin.laporan.peserta_global.print', array_merge(request()->query(), ['format' => 'pdf'])) : null" />

            {{-- Ringkasan Statistik Utama --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                <x-report.stat-card
                    title="Total Peserta"
                    :value="number_format($stats['total'])"
                    icon="fas fa-users"
                    color="teal"
                    tooltip="Jumlah seluruh peserta yang sesuai dengan filter laporan saat ini." />
                <x-report.stat-card
                    title="Aktif Magang"
                    :value="number_format($stats['aktif'])"
                    icon="fas fa-user-check"
                    color="emerald"
                    tooltip="Jumlah peserta magang dengan status 'diterima' yang saat ini sedang aktif menjalani kegiatan magang di instansi Pemko." />
                <x-report.stat-card
                    title="Selesai Magang"
                    :value="number_format($stats['selesai'])"
                    icon="fas fa-graduation-cap"
                    color="blue"
                    tooltip="Jumlah alumni peserta magang yang telah menyelesaikan seluruh program magang secara tuntas (status 'selesai')." />
                <x-report.stat-card
                    title="Pending / Menunggu"
                    :value="number_format($stats['pending'])"
                    icon="fas fa-clock"
                    color="amber"
                    tooltip="Jumlah pendaftaran magang yang masih dalam proses verifikasi seleksi / pending (status 'pending'/'menunggu')." />
            </div>

            {{-- Chart: Distribusi Status --}}
            @if($stats['total'] > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    <x-report.chart-card
                        icon="fas fa-chart-pie"
                        iconColor="teal"
                        title="Distribusi Status Peserta"
                        subtitle="Komposisi status magang peserta terfilter."
                        canvasId="chart-status-global"
                        height="h-64 md:h-72" />
                    <div class="lg:col-span-2 bg-white dark:bg-[#161f33] rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 shadow-lg p-5 md:p-6 flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-2xl bg-teal-100 dark:bg-teal-500/15 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/25 flex items-center justify-center shrink-0">
                                <i class="fas fa-circle-info text-base"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-sm md:text-base font-black text-slate-900 dark:text-white truncate">Ringkasan Rekapitulasi</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">Gambaran umum data peserta pada laporan ini.</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-800/60 p-4 text-center">
                                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Aktif</p>
                                <p class="text-2xl font-extrabold font-mono text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($stats['aktif']) }}</p>
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mt-0.5">{{ $stats['total'] > 0 ? round($stats['aktif'] / $stats['total'] * 100, 1) : 0 }}% dari total</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-800/60 p-4 text-center">
                                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Selesai</p>
                                <p class="text-2xl font-extrabold font-mono text-blue-600 dark:text-blue-400 mt-1">{{ number_format($stats['selesai']) }}</p>
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mt-0.5">{{ $stats['total'] > 0 ? round($stats['selesai'] / $stats['total'] * 100, 1) : 0 }}% dari total</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-800/60 p-4 text-center">
                                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Pending</p>
                                <p class="text-2xl font-extrabold font-mono text-amber-600 dark:text-amber-400 mt-1">{{ number_format($stats['pending']) }}</p>
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mt-0.5">{{ $stats['total'] > 0 ? round($stats['pending'] / $stats['total'] * 100, 1) : 0 }}% dari total</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Card Filter Rekapitulasi Modern 2 Baris --}}
            @php
                $activeFilterCount = collect([
                    request('instansi'),
                    request('instansi_id'),
                    (request('status') && request('status') !== 'semua' ? request('status') : null),
                    request('start_date'),
                    request('end_date'),
                    request('posisi'),
                    request('q')
                ])->filter(fn($v) => !empty($v))->count();
            @endphp

            <div class="bg-white dark:bg-[#161f33] rounded-2xl md:rounded-3xl p-5 md:p-6 shadow-lg border border-slate-200 dark:border-slate-800/40 space-y-5">
                
                {{-- Card Header: Title, Subtitle, Active Filter Badge & Reset Button --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-slate-800/60">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-teal-100 dark:bg-teal-500/15 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/25 flex items-center justify-center shrink-0">
                            <i class="fas fa-sliders-h text-sm"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm md:text-base font-black text-slate-900 dark:text-white">Filter & Parameter Rekapitulasi</h3>
                                @if($activeFilterCount > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-teal-500/15 text-teal-600 dark:text-teal-400 border border-teal-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span>
                                        {{ $activeFilterCount }} Filter Aktif
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">Saring data peserta berdasarkan asal sekolah/kampus, unit dinas, status, rentang waktu, dan formasi.</p>
                        </div>
                    </div>

                    @if($activeFilterCount > 0)
                        <a href="{{ route('admin.laporan.peserta_global') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30 hover:bg-rose-100 dark:hover:bg-rose-500/20 rounded-xl font-bold text-xs transition shadow-sm self-start sm:self-center">
                            <i class="fas fa-rotate-left text-[10px]"></i> Reset Semua
                        </a>
                    @endif
                </div>

                <form method="GET" action="{{ route('admin.laporan.peserta_global') }}" class="space-y-4">
                    @if(request()->filled('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    {{-- Baris 1: Kategori Utama (Kampus, Dinas, Status) --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Filter Kampus --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-1 flex items-center gap-1.5">
                                <i class="fas fa-university text-teal-500 text-[11px]"></i> Asal Sekolah / Kampus
                            </label>
                            <div class="relative">
                                <select name="instansi" class="w-full py-2.5 px-3.5 bg-slate-50/70 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark] transition">
                                    <option value="">Semua Sekolah / Kampus</option>
                                    @foreach($listInstansi as $instansi)
                                        <option value="{{ $instansi }}" {{ request('instansi') == $instansi ? 'selected' : '' }}>
                                            {{ $instansi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Filter Dinas --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-1 flex items-center gap-1.5">
                                <i class="fas fa-building text-teal-500 text-[11px]"></i> Dinas Penempatan
                            </label>
                            <div class="relative">
                                <select name="instansi_id" class="w-full py-2.5 px-3.5 bg-slate-50/70 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark] transition">
                                    <option value="">Semua Dinas Penempatan</option>
                                    @foreach($listDinas as $dinas)
                                        <option value="{{ $dinas->id }}" {{ request('instansi_id') == $dinas->id ? 'selected' : '' }}>
                                            {{ $dinas->nama_dinas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Filter Status --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-1 flex items-center gap-1.5">
                                <i class="fas fa-user-check text-teal-500 text-[11px]"></i> Status Magang
                            </label>
                            <div class="relative">
                                <select name="status" class="w-full py-2.5 px-3.5 bg-slate-50/70 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark] transition">
                                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Aktif Magang</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai Magang</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending / Menunggu</option>
                                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Baris 2: Waktu, Posisi & Tombol Aksi --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end pt-1">
                        {{-- Dari Tanggal --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-1 flex items-center gap-1.5">
                                <i class="far fa-calendar-alt text-teal-500 text-[11px]"></i> Dari Tanggal (Mulai)
                            </label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="w-full py-2.5 px-3 bg-slate-50/70 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark] transition">
                        </div>

                        {{-- Sampai Tanggal --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-1 flex items-center gap-1.5">
                                <i class="far fa-calendar-check text-teal-500 text-[11px]"></i> Sampai Tanggal (Selesai)
                            </label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="w-full py-2.5 px-3 bg-slate-50/70 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark] transition">
                        </div>

                        {{-- Posisi / Formasi Magang --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-1 flex items-center gap-1.5">
                                <i class="fas fa-briefcase text-teal-500 text-[11px]"></i> Posisi / Formasi
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-slate-500 pointer-events-none">
                                    <i class="fas fa-search text-xs"></i>
                                </span>
                                <input type="text" name="posisi" value="{{ request('posisi') }}" placeholder="Cari posisi / divisi..."
                                    class="w-full pl-9 pr-3 py-2.5 bg-slate-50/70 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm transition">
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center gap-2">
                            @if($activeFilterCount > 0)
                                <a href="{{ route('admin.laporan.peserta_global') }}" title="Bersihkan Filter"
                                    class="px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800/80 hover:bg-rose-50 dark:hover:bg-rose-500/10 text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs font-bold transition flex items-center justify-center shadow-sm">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                            <button type="submit" class="w-full py-2.5 px-4 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs font-black shadow-md shadow-teal-600/20 hover:shadow-teal-500/30 transition uppercase tracking-wider flex items-center justify-center gap-2 active:scale-95">
                                <i class="fas fa-filter text-xs"></i> Terapkan Filter
                            </button>
                        </div>
                    </div>

                    {{-- Active Search Query Tag if present --}}
                    @if(request()->filled('q'))
                        <div class="flex items-center justify-between gap-3 px-4 py-2.5 bg-teal-50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/25 rounded-xl mt-2">
                            <span class="text-xs font-bold text-teal-700 dark:text-teal-300 truncate">
                                <i class="fas fa-search mr-1.5 text-[10px]"></i>Pencarian teks tabel: &ldquo;{{ request('q') }}&rdquo;
                            </span>
                            <a href="{{ route('admin.laporan.peserta_global', request()->except('q', 'page')) }}" class="text-[10px] font-black text-teal-600 dark:text-teal-400 hover:text-rose-600 dark:hover:text-rose-400 uppercase tracking-wider shrink-0 transition">
                                Hapus <i class="fas fa-times ml-0.5"></i>
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            {{-- Tabel Utama Data Peserta --}}
            <div class="bg-white dark:bg-[#161f33] rounded-2xl md:rounded-3xl shadow-lg border border-slate-200 dark:border-slate-800/40 overflow-hidden">
                <div class="p-5 md:p-6 border-b border-slate-100 dark:border-slate-800/60 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/60 dark:bg-slate-900/40">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-teal-100 dark:bg-teal-500/15 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/25 flex items-center justify-center shrink-0">
                            <i class="fas fa-list text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-sm md:text-base font-black text-slate-900 dark:text-white">Daftar Peserta Magang</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Rekapitulasi informasi peserta magang secara menyeluruh.</p>
                        </div>
                    </div>

                    {{-- Client-Side Quick Search Bar --}}
                    <div class="w-full sm:w-64 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-slate-500 pointer-events-none">
                            <i class="fas fa-search text-xs"></i>
                        </span>
                        <input type="text" x-model="quickSearch" placeholder="Cari nama/sekolah..."
                            class="w-full pl-9 pr-8 py-2 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-teal-500 focus:border-teal-500 shadow-sm transition">
                        <button type="button" x-show="quickSearch !== ''" @click="quickSearch = ''" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                            <i class="fas fa-times-circle text-xs"></i>
                        </button>
                    </div>
                </div>

                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full divide-y divide-slate-100 dark:divide-slate-800/60">
                        <thead class="bg-slate-50 dark:bg-slate-900">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-12">No</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[180px]">Nama Peserta</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[220px]">Asal Sekolah / Kampus</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[220px]">Dinas & Posisi</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[190px]">Periode Magang</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-28">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-[#161f33] divide-y divide-slate-100 dark:divide-slate-800/60 text-sm">
                            @forelse($allInterns as $data)
                            <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-500/5 transition duration-150"
                                x-show="quickSearch === '' ||
                                        @js(strtolower($data->user->name ?? '')).includes(quickSearch.toLowerCase()) ||
                                        @js(strtolower($data->user->asal_instansi ?? '')).includes(quickSearch.toLowerCase()) ||
                                        @js(strtolower($data->position->instansi->nama_dinas ?? '')).includes(quickSearch.toLowerCase()) ||
                                        @js(strtolower($data->position->judul_posisi ?? '')).includes(quickSearch.toLowerCase())">

                                <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-400 dark:text-slate-500">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4 min-w-[180px] max-w-[240px]">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $data->user->name ?? '-' }}">{{ $data->user->name ?? '-' }}</div>
                                    <div class="text-xs text-slate-400 dark:text-slate-500 font-medium truncate mt-0.5">{{ $data->user->email ?? '-' }}</div>
                                </td>

                                <td class="px-6 py-4 min-w-[220px] max-w-[280px]">
                                    <div class="inline-flex items-start gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-500/25 leading-snug">
                                        <i class="fas fa-university text-[10px] mt-0.5 shrink-0"></i>
                                        <span class="line-clamp-2" title="{{ $data->user->asal_instansi ?? '-' }}">{{ $data->user->asal_instansi ?? '-' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 min-w-[220px] max-w-[280px]">
                                    <div class="text-xs font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $data->position->instansi->nama_dinas ?? '-' }}">{{ $data->position->instansi->nama_dinas ?? '-' }}</div>
                                    <div class="text-xs text-teal-600 dark:text-teal-400 font-medium mt-1 leading-snug line-clamp-2" title="{{ $data->position->judul_posisi ?? '-' }}">{{ $data->position->judul_posisi ?? '-' }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-bold text-slate-700 dark:text-slate-300">
                                    @if($data->tanggal_mulai && $data->tanggal_selesai)
                                        <span class="px-3 py-1 bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/60 rounded-xl font-mono inline-block">
                                            {{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 dark:text-slate-500 italic">Tanggal belum ditentukan</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @include('admin_kota.laporan.partials.application-status-badge', ['status' => $data->status])
                                </td>
                            </tr>
                            @empty
                            <x-report.empty-state
                                :colspan="6"
                                icon="fas fa-user-circle"
                                title="Tidak Ada Data Peserta"
                                subtitle="Belum ada data peserta yang sesuai dengan filter pilihan Anda." />
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View (<md) --}}
                <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($allInterns as $data)
                    <div class="p-4 space-y-3.5 hover:bg-slate-50 dark:hover:bg-slate-900/40 transition"
                        x-show="quickSearch === '' ||
                                @js(strtolower($data->user->name ?? '')).includes(quickSearch.toLowerCase()) ||
                                @js(strtolower($data->user->asal_instansi ?? '')).includes(quickSearch.toLowerCase()) ||
                                @js(strtolower($data->position->instansi->nama_dinas ?? '')).includes(quickSearch.toLowerCase()) ||
                                @js(strtolower($data->position->judul_posisi ?? '')).includes(quickSearch.toLowerCase())">

                        {{-- header row: iteration + name + status badge --}}
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3 min-w-0">
                                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 shrink-0 pt-0.5">{{ $loop->iteration }}</span>
                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $data->user->name ?? '-' }}">{{ $data->user->name ?? '-' }}</div>
                                    <div class="text-xs text-slate-400 dark:text-slate-500 font-medium truncate mt-0.5">{{ $data->user->email ?? '-' }}</div>
                                </div>
                            </div>
                            @include('admin_kota.laporan.partials.application-status-badge', ['status' => $data->status, 'extraClass' => 'shrink-0'])
                        </div>

                        {{-- detail block: key-value mini grid --}}
                        <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-2xl border border-slate-100 dark:border-slate-800/60 space-y-2.5">
                            <div class="flex items-start gap-2">
                                <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-24 shrink-0 pt-0.5">Sekolah</span>
                                <div class="inline-flex items-start gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-500/25 leading-snug">
                                    <i class="fas fa-university text-[10px] mt-0.5 shrink-0"></i>
                                    <span class="line-clamp-2" title="{{ $data->user->asal_instansi ?? '-' }}">{{ $data->user->asal_instansi ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-24 shrink-0 pt-0.5">Dinas</span>
                                <span class="text-xs font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $data->position->instansi->nama_dinas ?? '-' }}">{{ $data->position->instansi->nama_dinas ?? '-' }}</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-24 shrink-0 pt-0.5">Posisi</span>
                                <span class="text-xs text-teal-600 dark:text-teal-400 font-medium leading-snug line-clamp-2" title="{{ $data->position->judul_posisi ?? '-' }}">{{ $data->position->judul_posisi ?? '-' }}</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-24 shrink-0 pt-0.5">Periode</span>
                                @if($data->tanggal_mulai && $data->tanggal_selesai)
                                    <span class="px-2.5 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg font-mono text-[11px] inline-block font-bold text-slate-700 dark:text-slate-300">
                                        {{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 dark:text-slate-500 italic">Tanggal belum ditentukan</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <x-report.empty-state
                        :mobile="true"
                        icon="fas fa-user-circle"
                        title="Tidak Ada Data Peserta"
                        subtitle="Belum ada data peserta yang sesuai dengan filter pilihan Anda." />
                    @endforelse
                </div>

                @if($allInterns instanceof \Illuminate\Pagination\LengthAwarePaginator && $allInterns->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-800/60 bg-slate-50 dark:bg-slate-900/40">
                        {{ $allInterns->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    (function () {
        const statusData = @json($chartStatusGlobal);

        function init() {
            window.ReportCharts.render('chart-status-global', function (p) {
                return {
                    type: 'doughnut',
                    data: {
                        labels: statusData.labels,
                        datasets: [{
                            data: statusData.values,
                            backgroundColor: statusData.colors,
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
