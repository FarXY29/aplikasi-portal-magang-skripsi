<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    @push('styles')
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
            .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
            .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }

            .stat-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
            .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px -5px rgba(0,0,0,0.08); }

            .shortcut-pill { transition: all 0.2s ease; }
            .shortcut-pill:hover { transform: translateY(-1px); }
            .shortcut-pill:active { transform: scale(0.97); }

            .feed-item { transition: background 0.15s ease; }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-teal-600 dark:bg-teal-500 text-white flex items-center justify-center shadow-sm">
                <i class="fas fa-shield-alt text-sm"></i>
            </div>
            <div>
                <h2 class="font-black text-xl text-gray-900 dark:text-gray-100 leading-tight">Super Admin Dashboard</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium hidden md:block">Pusat Kontrol & Monitoring Portal Magang</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-5 md:space-y-6 font-[Inter] py-4 bg-gray-50 dark:bg-gray-900 min-h-screen">

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- ROW 1: HERO BANNER + EXECUTIVE REPORTS CARD --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 md:gap-6">
            {{-- Hero Welcome Banner (Premium Teal/Emerald Gradient) --}}
            <div class="xl:col-span-2 relative overflow-hidden rounded-3xl bg-gradient-to-br from-teal-800 to-emerald-700 dark:from-teal-900 dark:to-emerald-900 text-white shadow-xl min-h-[175px] border border-teal-600/30 dark:border-teal-700/50">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-52 h-52 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-emerald-500/20 rounded-full blur-xl pointer-events-none"></div>
                <div class="relative z-10 p-6 md:p-8 flex items-center justify-between gap-6 h-full">
                    <div class="space-y-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 dark:bg-gray-800/40 backdrop-blur-md rounded-full text-[10px] font-extrabold uppercase tracking-widest border border-white/20 dark:border-gray-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            {{ now()->translatedFormat('l, d F Y') }}
                        </span>
                        <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold leading-tight text-white tracking-tight">
                            Selamat Datang, <br class="md:hidden">Super Admin! 👋
                        </h1>
                        <p class="text-teal-100 dark:text-teal-200 text-xs md:text-sm font-medium leading-relaxed max-w-xl">
                            Pantau performa dan statistik dari 
                            <span class="font-black text-white bg-white/20 dark:bg-gray-800/60 px-2 py-0.5 rounded-md">{{ $totalInstansi }}</span> 
                            instansi di lingkungan Pemerintah Kota Banjarmasin secara real-time.
                        </p>
                    </div>
                    <div class="hidden md:block shrink-0">
                        <div class="w-24 h-24 bg-white/10 dark:bg-gray-800/40 backdrop-blur-md border border-white/20 dark:border-gray-700 rounded-2xl flex items-center justify-center text-5xl shadow-lg transform rotate-3 hover:rotate-0 transition duration-300">
                            👑
                        </div>
                    </div>
                </div>
            </div>

            {{-- Executive Summary & Quick Navigation Card --}}
            <div class="xl:col-span-1 relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-900 to-indigo-800 dark:from-blue-950 dark:to-indigo-950 text-white shadow-xl flex flex-col justify-between border border-blue-700/30 dark:border-blue-900/50">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                <div class="relative z-10 p-6 space-y-4 flex-1 flex flex-col justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 dark:bg-gray-800/40 backdrop-blur-md border border-white/20 dark:border-gray-700 flex items-center justify-center shrink-0 shadow-sm">
                            <i class="fas fa-chart-pie text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black leading-tight text-white tracking-tight">Laporan Eksekutif</h3>
                            <p class="text-blue-200 dark:text-blue-300 text-[10px] font-bold uppercase tracking-wider">Rekap & Analisis Data Kota</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2 mt-4">
                        <a href="{{ route('admin.laporan.hub') }}" class="w-full py-2.5 px-4 bg-white dark:bg-gray-800 text-blue-900 dark:text-blue-100 hover:bg-blue-50 dark:hover:bg-gray-700 font-extrabold rounded-xl transition-all text-xs flex items-center justify-between shadow-md hover:shadow-lg active:scale-[0.98]">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-layer-group text-blue-600 dark:text-blue-400"></i>
                                <span>Buka Pusat Laporan</span>
                            </span>
                            <i class="fas fa-arrow-right text-[10px] text-blue-600 dark:text-blue-400"></i>
                        </a>

                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('admin.laporan.peserta_global') }}" class="py-2 px-3 bg-white/10 dark:bg-gray-800/60 hover:bg-white/20 dark:hover:bg-gray-700/80 text-white font-bold rounded-xl transition-all text-[11px] flex items-center justify-center gap-1.5 border border-white/15 dark:border-gray-700 active:scale-[0.98]">
                                <i class="fas fa-users text-[10px]"></i>
                                <span>Data Global</span>
                            </a>
                            <a href="{{ route('admin.laporan.penyerapan_kuota') }}" class="py-2 px-3 bg-white/10 dark:bg-gray-800/60 hover:bg-white/20 dark:hover:bg-gray-700/80 text-white font-bold rounded-xl transition-all text-[11px] flex items-center justify-center gap-1.5 border border-white/15 dark:border-gray-700 active:scale-[0.98]">
                                <i class="fas fa-percentage text-[10px]"></i>
                                <span>Kuota Magang</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════ --}}
        @include('admin_kota.dashboard._stats-grid')

        @include('admin_kota.dashboard._charts')

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- ROW 4: MAIN CONTENT (2/3 + 1/3) --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 md:gap-5">
            
            {{-- ═══ KOLOM KIRI (2/3): Tabel + Feed ═══ --}}
            <div class="xl:col-span-2 space-y-4 md:space-y-5">
                
                {{-- Tabel: Statistik Pelamar per Instansi --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col overflow-hidden">
                    <div class="p-4 md:p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800 sticky top-0 z-20">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-teal-600 dark:bg-teal-500 text-white flex items-center justify-center shadow-sm">
                                <i class="fas fa-chart-bar text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-sm md:text-base font-black text-gray-800 dark:text-gray-200">Statistik Pelamar per Instansi</h3>
                                <p class="text-[10px] md:text-xs text-gray-500 dark:text-gray-400 font-medium mt-0.5">Distribusi peminat magang berdasarkan dinas</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.laporan.peserta_global') }}" class="hidden md:inline-flex items-center gap-1.5 text-xs text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/60 px-3 py-1.5 rounded-xl hover:bg-teal-100 dark:hover:bg-teal-900/50 font-bold transition border border-teal-100 dark:border-teal-900/40">
                            <i class="fas fa-external-link-alt text-[10px]"></i> Lihat Semua
                        </a>
                    </div>
                    
                    {{-- Desktop Table (md+) --}}
                    <div class="hidden md:block overflow-y-auto custom-scrollbar flex-1 relative">
                        <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900 sticky top-0 z-10">
                                <tr>
                                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-14">No</th>
                                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Instansi</th>
                                    <th class="px-5 py-3 text-right text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-36">Total Pelamar</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60">
                                @forelse($instansiStats as $index => $instansi)
                                    @php
                                        $percentage = $maxPelamar > 0 ? ($instansi->applications_count / $maxPelamar) * 100 : 0;
                                    @endphp
                                    <tr class="feed-item hover:bg-gray-50 dark:hover:bg-gray-900/60 group transition">
                                        <td class="px-5 py-3.5 whitespace-nowrap text-xs font-bold text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                                            {{ $instansiStats->firstItem() + $index }}
                                        </td>
                                        <td class="px-5 py-3.5 pr-8">
                                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">{{ $instansi->nama_dinas }}</p>
                                            <div class="w-full bg-gray-100 dark:bg-gray-900 rounded-full h-1.5 mt-2 overflow-hidden border border-transparent dark:border-gray-700">
                                                <div class="bg-teal-500 dark:bg-teal-400 h-1.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5 whitespace-nowrap text-right">
                                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg text-xs font-black bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-100 dark:border-teal-900/50 group-hover:bg-teal-600 dark:group-hover:bg-teal-500 group-hover:text-white transition-all duration-200 min-w-[56px]">
                                                {{ $instansi->applications_count }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center gap-2">
                                                <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-900 flex items-center justify-center border border-gray-200 dark:border-gray-700">
                                                    <i class="fas fa-building text-2xl text-gray-400 dark:text-gray-500"></i>
                                                </div>
                                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Belum ada data instansi.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Card View (<md) --}}
                    <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700 flex-1">
                        @forelse($instansiStats as $index => $instansi)
                            @php
                                $percentage = $maxPelamar > 0 ? ($instansi->applications_count / $maxPelamar) * 100 : 0;
                            @endphp
                            <div class="p-4 space-y-2 feed-item hover:bg-gray-50 dark:hover:bg-gray-900/60 active:bg-gray-100 dark:active:bg-gray-800/40">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <span class="w-7 h-7 rounded-lg bg-teal-600 dark:bg-teal-500 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-sm">
                                            {{ $instansiStats->firstItem() + $index }}
                                        </span>
                                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200 line-clamp-2">{{ $instansi->nama_dinas }}</p>
                                    </div>
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-black bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-100 dark:border-teal-900/50 shrink-0">
                                        {{ $instansi->applications_count }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-gray-900 rounded-full h-1.5 overflow-hidden border border-transparent dark:border-gray-700">
                                    <div class="bg-teal-500 dark:bg-teal-400 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-900 flex items-center justify-center mx-auto mb-2 border border-gray-200 dark:border-gray-700">
                                    <i class="fas fa-building text-xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="text-xs font-bold text-gray-600 dark:text-gray-300">Belum ada data instansi.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($instansiStats->hasPages())
                    <div class="p-3 md:p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
                        {{ $instansiStats->links() }}
                    </div>
                    @endif
                </div>

                @include('admin_kota.dashboard._recent-activity')
            </div>

            {{-- ═══ KOLOM KANAN (1/3): Chart + Instansi + Server ═══ --}}
            <div class="space-y-4 md:space-y-5">
                
                {{-- Donut Chart: Status Lamaran --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 md:p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-orange-500 text-white flex items-center justify-center shadow-sm">
                                    <i class="fas fa-chart-pie text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm md:text-base font-black text-gray-800 dark:text-gray-200">Status Lamaran</h3>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">Distribusi status keseluruhan</p>
                                </div>
                            </div>
                        </div>
                        <div class="relative flex items-center justify-center" style="height: 200px;">
                            <canvas id="statusChart"
                                data-labels="{{ json_encode($statusLabels) }}"
                                data-values="{{ json_encode($statusData) }}">
                            </canvas>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="bg-green-50 dark:bg-green-950/40 p-3 rounded-xl border border-green-100 dark:border-green-900/40 text-center">
                            <span class="block text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase">Lolos</span>
                            <span class="text-lg font-black text-green-600 dark:text-green-400">
                                @if($totalApplications > 0)
                                    {{ round((($activeInterns + $completedInterns) / $totalApplications) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </span>
                        </div>
                        <div class="bg-red-50 dark:bg-red-950/40 p-3 rounded-xl border border-red-100 dark:border-red-900/40 text-center">
                            <span class="block text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase">Tolak</span>
                            <span class="text-lg font-black text-red-500 dark:text-red-400">
                                @if($totalApplications > 0)
                                    {{ round((($totalApplications - $activeInterns - $completedInterns - $pendingApplications) / $totalApplications) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Instansi Terbaru --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-4 md:p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-teal-600 dark:bg-teal-500 text-white flex items-center justify-center shadow-xs">
                                <i class="fas fa-plus-circle text-xs"></i>
                            </div>
                            <h3 class="text-sm font-black text-gray-800 dark:text-gray-200">Instansi Terbaru</h3>
                        </div>
                        <a href="{{ route('admin.instansi.index') }}" class="text-[10px] text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/60 px-2.5 py-1 rounded-lg hover:bg-teal-100 dark:hover:bg-teal-900/50 font-black transition border border-teal-100 dark:border-teal-900/40">Semua</a>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                        @foreach($recentInstansis as $dinas)
                        <div class="p-3.5 md:p-4 flex items-center gap-3 feed-item hover:bg-gray-50 dark:hover:bg-gray-900/60 transition">
                            <div class="w-9 h-9 rounded-xl bg-teal-50 dark:bg-teal-950/60 border border-teal-100 dark:border-teal-900/40 text-teal-600 dark:text-teal-300 flex items-center justify-center font-bold text-xs shrink-0">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate">{{ $dinas->nama_dinas }}</p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 truncate mt-0.5 font-medium">
                                    <i class="far fa-clock text-[9px]"></i> {{ $dinas->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Info Server --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 md:p-6">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-900 text-gray-500 dark:text-gray-400 flex items-center justify-center border border-transparent dark:border-gray-700">
                            <i class="fas fa-server text-xs"></i>
                        </div>
                        <h3 class="text-sm font-black text-gray-800 dark:text-gray-200">Informasi Sistem</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs font-bold pb-3 border-b border-gray-100 dark:border-gray-700/60">
                            <span class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-code-branch text-gray-400 dark:text-gray-500 text-[10px] w-4 text-center"></i>
                                Framework
                            </span>
                            <span class="text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 px-2.5 py-1 rounded-lg border border-teal-100 dark:border-teal-900/40 font-black text-[11px]">v{{ app()->version() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold pb-3 border-b border-gray-100 dark:border-gray-700/60">
                            <span class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-code text-gray-400 dark:text-gray-500 text-[10px] w-4 text-center"></i>
                                PHP
                            </span>
                            <span class="text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/60 px-2.5 py-1 rounded-lg border border-blue-100 dark:border-blue-900/40 font-black text-[11px]">{{ PHP_VERSION }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold pb-3 border-b border-gray-100 dark:border-gray-700/60">
                            <span class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-globe text-gray-400 dark:text-gray-500 text-[10px] w-4 text-center"></i>
                                Lingkungan
                            </span>
                            <span class="text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/60 px-2.5 py-1 rounded-lg border border-indigo-100 dark:border-indigo-900/40 font-black text-[11px] uppercase">{{ app()->environment() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-heartbeat text-gray-400 dark:text-gray-500 text-[10px] w-4 text-center"></i>
                                Scheduler
                            </span>
                            <span class="text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-950/60 px-2.5 py-1 rounded-lg border border-green-100 dark:border-green-900/40 flex items-center gap-1.5 font-black text-[11px]">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                </span>
                                Aktif
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function() {
            function initCharts() {
                if (typeof Chart === 'undefined') {
                    setTimeout(initCharts, 50);
                    return;
                }
                
                const isDarkMode = document.documentElement.classList.contains('dark');
                const textColor = isDarkMode ? '#9CA3AF' : '#6B7280';
                const gridColor = isDarkMode ? 'rgba(51, 65, 85, 0.5)' : '#F1F5F9';

                const canvasStatus = document.getElementById('statusChart');
                
                if (window.adminStatusChart) {
                    try { window.adminStatusChart.destroy(); } catch(e) {}
                    window.adminStatusChart = null;
                }
                
                if (canvasStatus) {
                    const labels = JSON.parse(canvasStatus.dataset.labels);
                    const dataValues = JSON.parse(canvasStatus.dataset.values);

                    window.adminStatusChart = new Chart(canvasStatus.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: dataValues,
                                backgroundColor: ['#F59E0B', '#10B981', '#6366F1', '#EF4444'],
                                borderWidth: 3,
                                borderColor: isDarkMode ? '#1F2937' : '#ffffff',
                                hoverOffset: 8,
                                borderRadius: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        boxWidth: 8,
                                        font: { family: 'Inter', size: 11, weight: '600' },
                                        padding: 16,
                                        color: textColor
                                    }
                                },
                                tooltip: {
                                    backgroundColor: isDarkMode ? '#111827' : '#1F2937',
                                    titleFont: { family: 'Inter', size: 13, weight: 'bold' },
                                    bodyFont: { family: 'Inter', size: 12 },
                                    padding: 14,
                                    cornerRadius: 12,
                                    displayColors: true,
                                    boxPadding: 6,
                                    callbacks: {
                                        label: (context) => ` ${context.label}: ${context.parsed} Orang`
                                    }
                                }
                            },
                            cutout: '68%',
                            animation: {
                                animateRotate: true,
                                duration: 1000,
                                easing: 'easeOutQuart'
                            }
                        }
                    });
                }
                
                const canvasKampus = document.getElementById('kampusChart');
                if (window.adminKampusChart) {
                    try { window.adminKampusChart.destroy(); } catch(e) {}
                    window.adminKampusChart = null;
                }
                
                if (canvasKampus) {
                    const kampusLabels = JSON.parse(canvasKampus.dataset.labels);
                    const kampusData = JSON.parse(canvasKampus.dataset.values);

                    window.adminKampusChart = new Chart(canvasKampus.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: kampusLabels,
                            datasets: [{
                                label: 'Peserta',
                                data: kampusData,
                                backgroundColor: isDarkMode ? '#2dd4bf' : '#14b8a6',
                                borderRadius: 6,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: { beginAtZero: true, grid: { color: gridColor, borderDash: [4, 4] }, ticks: { color: textColor } },
                                x: { grid: { display: false }, ticks: { color: textColor, font: { size: 10 }, maxRotation: 45, minRotation: 45 } }
                            }
                        }
                    });
                }
                
                const canvasInstansi = document.getElementById('instansiChart');
                if (window.adminInstansiChart) {
                    try { window.adminInstansiChart.destroy(); } catch(e) {}
                    window.adminInstansiChart = null;
                }
                
                if (canvasInstansi) {
                    const instansiLabels = JSON.parse(canvasInstansi.dataset.labels);
                    const instansiData = JSON.parse(canvasInstansi.dataset.values);

                    window.adminInstansiChart = new Chart(canvasInstansi.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: instansiLabels,
                            datasets: [{
                                label: 'Pelamar',
                                data: instansiData,
                                backgroundColor: isDarkMode ? '#818cf8' : '#6366f1',
                                borderRadius: 6,
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                x: { beginAtZero: true, grid: { color: gridColor, borderDash: [4, 4] }, ticks: { color: textColor } },
                                y: { grid: { display: false }, ticks: { color: textColor, font: { size: 10 } } }
                            }
                        }
                    });
                }
            }

            document.addEventListener('DOMContentLoaded', initCharts);
            document.addEventListener('turbo:load', initCharts);

            document.addEventListener('turbo:before-cache', function() {
                if (window.adminStatusChart) {
                    try { window.adminStatusChart.destroy(); } catch(e) {}
                    window.adminStatusChart = null;
                }
            });
        })();
    </script>
</x-app-layout>