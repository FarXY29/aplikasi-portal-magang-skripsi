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
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

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
            <div class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center shadow-sm" style="background-color: #0d9488;">
                <i class="fas fa-shield-alt text-sm"></i>
            </div>
            <div>
                <h2 class="font-black text-xl text-gray-900 leading-tight">Super Admin Dashboard</h2>
                <p class="text-xs text-gray-500 font-medium hidden md:block">Pusat Kontrol & Monitoring Portal Magang</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-5 md:space-y-6 font-[Inter]">

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- ROW 1: HERO BANNER + SERTIFIKAT CARD (SOLID COLOR, NO GRADIENT) --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 md:gap-5">
            
            {{-- Hero Welcome Banner (Solid Teal #0f766e) --}}
            <div class="xl:col-span-2 relative overflow-hidden rounded-2xl md:rounded-3xl bg-teal-700 text-white shadow-lg min-h-[155px] md:min-h-[175px]" style="background-color: #0f766e;">
                {{-- Decorative circles --}}
                <div class="absolute top-0 right-0 -mt-14 -mr-14 w-48 h-48 bg-white/[0.06] rounded-full pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-36 h-36 bg-white/[0.06] rounded-full pointer-events-none"></div>

                <div class="relative z-10 p-5 md:p-7 flex items-center justify-between gap-4 h-full">
                    <div class="space-y-2.5 md:space-y-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/15 rounded-full text-[11px] font-bold border border-white/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            {{ now()->translatedFormat('l, d F Y') }}
                        </span>
                        <h1 class="text-xl md:text-2xl lg:text-3xl font-black leading-tight text-white">
                            Selamat Datang, <br class="md:hidden">Super Admin! 👋
                        </h1>
                        <p class="text-teal-100 text-xs md:text-sm font-medium leading-relaxed max-w-lg">
                            Pantau performa dan statistik dari 
                            <span class="font-black text-white bg-white/15 px-2 py-0.5 rounded-md">{{ $totalInstansi }}</span> 
                            instansi di lingkungan Pemerintah Kota Banjarmasin.
                        </p>
                    </div>
                    <div class="hidden md:block shrink-0">
                        <div class="w-20 h-20 lg:w-24 lg:h-24 bg-white/10 border border-white/20 rounded-2xl flex items-center justify-center text-4xl lg:text-5xl shadow-lg">
                            👑
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pusat Laporan Eksekutif Card (Solid Blue #1e40af) --}}
            <div class="xl:col-span-1 relative overflow-hidden rounded-2xl md:rounded-3xl bg-blue-800 text-white shadow-lg flex flex-col justify-center" style="background-color: #1e40af;">
                <div class="relative z-10 p-5 md:p-6 space-y-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-white/15 border border-white/20 flex items-center justify-center shrink-0">
                            <i class="fas fa-chart-pie text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black leading-tight text-white">Pusat Laporan Eksekutif</h3>
                            <p class="text-blue-100 text-[10px] font-medium">Rekap & Analisis Data Keseluruhan</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <a href="{{ route('admin.laporan.hub') }}" class="w-full py-2 px-3.5 bg-white text-blue-900 hover:bg-blue-50 font-black rounded-xl transition-all text-xs flex items-center justify-between shadow-xs active:scale-[0.98]">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-layer-group text-blue-600"></i>
                                <span>Buka Laporan Hub</span>
                            </span>
                            <i class="fas fa-arrow-right text-[10px] text-blue-400"></i>
                        </a>

                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('admin.laporan.peserta_global') }}" class="py-2 px-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition-all text-[11px] flex items-center justify-center gap-1.5 border border-white/15 active:scale-[0.98]">
                                <i class="fas fa-users text-[10px]"></i>
                                <span>Data Global</span>
                            </a>
                            <a href="{{ route('admin.laporan.penyerapan_kuota') }}" class="py-2 px-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition-all text-[11px] flex items-center justify-center gap-1.5 border border-white/15 active:scale-[0.98]">
                                <i class="fas fa-percentage text-[10px]"></i>
                                <span>Kuota Magang</span>
                            </a>
                        </div>
                    </div>
                </div>
                <i class="fas fa-file-invoice absolute -bottom-5 -right-3 text-7xl text-white opacity-[0.06] transform -rotate-12 pointer-events-none"></i>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- ROW 2: QUICK ACTION SHORTCUTS --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-200/60 p-1.5 md:p-2 overflow-x-auto custom-scrollbar">
            <div class="flex items-center min-w-max gap-1 md:gap-1.5 p-1 md:p-1.5">

                <a href="{{ route('admin.instansi.index') }}" class="shortcut-pill group flex items-center gap-2.5 md:gap-3 px-3.5 md:px-5 py-2.5 md:py-3 rounded-xl md:rounded-2xl hover:bg-teal-50 transition-all active:scale-[0.97]">
                    <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-building text-sm"></i>
                    </div>
                    <div>
                        <span class="block text-xs font-black text-gray-800">Instansi</span>
                        <span class="block text-[10px] text-gray-500 font-medium">Kelola Dinas</span>
                    </div>
                </a>
                <div class="w-px h-7 bg-gray-100 shrink-0"></div>

                <a href="{{ route('admin.users.index') }}" class="shortcut-pill group flex items-center gap-2.5 md:gap-3 px-3.5 md:px-5 py-2.5 md:py-3 rounded-xl md:rounded-2xl hover:bg-blue-50 transition-all active:scale-[0.97]">
                    <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-users-cog text-sm"></i>
                    </div>
                    <div>
                        <span class="block text-xs font-black text-gray-800">Pengguna</span>
                        <span class="block text-[10px] text-gray-500 font-medium">Akses Akun</span>
                    </div>
                </a>
                <div class="w-px h-7 bg-gray-100 shrink-0"></div>

                <a href="{{ route('admin.laporan') }}" class="shortcut-pill group flex items-center gap-2.5 md:gap-3 px-3.5 md:px-5 py-2.5 md:py-3 rounded-xl md:rounded-2xl hover:bg-orange-50 transition-all active:scale-[0.97]">
                    <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-orange-100 text-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-pie text-sm"></i>
                    </div>
                    <div>
                        <span class="block text-xs font-black text-gray-800">Laporan</span>
                        <span class="block text-[10px] text-gray-500 font-medium">Unduh PDF/Excel</span>
                    </div>
                </a>
                <div class="w-px h-7 bg-gray-100 shrink-0"></div>

                <a href="{{ route('admin.laporan.peserta_global') }}" class="shortcut-pill group flex items-center gap-2.5 md:gap-3 px-3.5 md:px-5 py-2.5 md:py-3 rounded-xl md:rounded-2xl hover:bg-pink-50 transition-all active:scale-[0.97]">
                    <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-pink-100 text-pink-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-globe-asia text-sm"></i>
                    </div>
                    <div>
                        <span class="block text-xs font-black text-gray-800">Data Global</span>
                        <span class="block text-[10px] text-gray-500 font-medium">Semua Pelamar</span>
                    </div>
                </a>
                <div class="w-px h-7 bg-gray-100 shrink-0"></div>

                <a href="{{ route('admin.users.logbooks') }}" class="shortcut-pill group flex items-center gap-2.5 md:gap-3 px-3.5 md:px-5 py-2.5 md:py-3 rounded-xl md:rounded-2xl hover:bg-purple-50 transition-all active:scale-[0.97]">
                    <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-book-open text-sm"></i>
                    </div>
                    <div>
                        <span class="block text-xs font-black text-gray-800">Logbook</span>
                        <span class="block text-[10px] text-gray-500 font-medium">Cek Harian</span>
                    </div>
                </a>
                <div class="w-px h-7 bg-gray-100 shrink-0"></div>

                <a href="{{ route('admin.settings.index') }}" class="shortcut-pill group flex items-center gap-2.5 md:gap-3 px-3.5 md:px-5 py-2.5 md:py-3 rounded-xl md:rounded-2xl hover:bg-gray-100 transition-all active:scale-[0.97]">
                    <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-gray-200 text-gray-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-cogs text-sm"></i>
                    </div>
                    <div>
                        <span class="block text-xs font-black text-gray-800">Sistem</span>
                        <span class="block text-[10px] text-gray-500 font-medium">Konfigurasi</span>
                    </div>
                </a>

            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- ROW 3: 6 METRIC STAT CARDS (STATIC TAILWIND CLASSES + SAFE FALLBACKS) --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4">

            {{-- 1. Instansi --}}
            <a href="{{ route('admin.instansi.index') }}" class="stat-card group relative bg-white rounded-2xl p-4 md:p-5 shadow-sm border border-gray-100 hover:border-teal-300 overflow-hidden">
                <div class="absolute -right-3 -top-3 w-16 h-16 bg-teal-50 rounded-full opacity-0 group-hover:opacity-60 group-hover:scale-150 transition-all duration-500"></div>
                <div class="flex items-center justify-between mb-3 relative z-10">
                    <div class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center shadow-sm" style="background-color: #0d9488;">
                        <i class="fas fa-building text-sm"></i>
                    </div>
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Instansi</span>
                </div>
                <h3 class="text-2xl md:text-3xl font-black text-gray-800 relative z-10">{{ number_format($totalInstansi) }}</h3>
                <div class="flex items-center gap-1 mt-1.5 relative z-10">
                    <span class="text-[10px] font-bold text-gray-400 group-hover:text-teal-600 transition-colors">Lihat Detail</span>
                    <i class="fas fa-arrow-right text-[8px] text-gray-300 group-hover:text-teal-600 group-hover:translate-x-1 transition-all"></i>
                </div>
            </a>

            {{-- 2. Pengguna --}}
            <a href="{{ route('admin.users.index') }}" class="stat-card group relative bg-white rounded-2xl p-4 md:p-5 shadow-sm border border-gray-100 hover:border-blue-300 overflow-hidden">
                <div class="absolute -right-3 -top-3 w-16 h-16 bg-blue-50 rounded-full opacity-0 group-hover:opacity-60 group-hover:scale-150 transition-all duration-500"></div>
                <div class="flex items-center justify-between mb-3 relative z-10">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm" style="background-color: #2563eb;">
                        <i class="fas fa-users text-sm"></i>
                    </div>
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Pengguna</span>
                </div>
                <h3 class="text-2xl md:text-3xl font-black text-gray-800 relative z-10">{{ number_format($totalUser) }}</h3>
                <div class="flex items-center gap-1 mt-1.5 relative z-10">
                    <span class="text-[10px] font-bold text-gray-400 group-hover:text-blue-600 transition-colors">Lihat Detail</span>
                    <i class="fas fa-arrow-right text-[8px] text-gray-300 group-hover:text-blue-600 group-hover:translate-x-1 transition-all"></i>
                </div>
            </a>

            {{-- 3. Pendaftar --}}
            <a href="{{ route('admin.laporan.peserta_global', ['status' => 'semua']) }}" class="stat-card group relative bg-white rounded-2xl p-4 md:p-5 shadow-sm border border-gray-100 hover:border-purple-300 overflow-hidden">
                <div class="absolute -right-3 -top-3 w-16 h-16 bg-purple-50 rounded-full opacity-0 group-hover:opacity-60 group-hover:scale-150 transition-all duration-500"></div>
                <div class="flex items-center justify-between mb-3 relative z-10">
                    <div class="w-9 h-9 rounded-xl bg-purple-600 text-white flex items-center justify-center shadow-sm" style="background-color: #9333ea;">
                        <i class="fas fa-file-signature text-sm"></i>
                    </div>
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Pendaftar</span>
                </div>
                <h3 class="text-2xl md:text-3xl font-black text-gray-800 relative z-10">{{ number_format($totalApplications) }}</h3>
                <div class="flex items-center gap-1 mt-1.5 relative z-10">
                    <span class="text-[10px] font-bold text-gray-400 group-hover:text-purple-600 transition-colors">Lihat Detail</span>
                    <i class="fas fa-arrow-right text-[8px] text-gray-300 group-hover:text-purple-600 group-hover:translate-x-1 transition-all"></i>
                </div>
            </a>

            {{-- 4. Aktif --}}
            <a href="{{ route('admin.laporan.peserta_global', ['status' => 'diterima']) }}" class="stat-card group relative bg-white rounded-2xl p-4 md:p-5 shadow-sm border border-gray-100 hover:border-green-300 overflow-hidden">
                <div class="absolute -right-3 -top-3 w-16 h-16 bg-green-50 rounded-full opacity-0 group-hover:opacity-60 group-hover:scale-150 transition-all duration-500"></div>
                <div class="flex items-center justify-between mb-3 relative z-10">
                    <div class="w-9 h-9 rounded-xl bg-green-600 text-white flex items-center justify-center shadow-sm" style="background-color: #16a34a;">
                        <i class="fas fa-user-clock text-sm"></i>
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md border bg-green-50 text-green-700 border-green-200">Aktif</span>
                </div>
                <h3 class="text-2xl md:text-3xl font-black text-gray-800 relative z-10">{{ number_format($activeInterns) }}</h3>
                <div class="flex items-center gap-1 mt-1.5 relative z-10">
                    <span class="text-[10px] font-bold text-gray-400 group-hover:text-green-600 transition-colors">Lihat Detail</span>
                    <i class="fas fa-arrow-right text-[8px] text-gray-300 group-hover:text-green-600 group-hover:translate-x-1 transition-all"></i>
                </div>
            </a>

            {{-- 5. Selesai --}}
            <a href="{{ route('admin.laporan.peserta_global', ['status' => 'selesai']) }}" class="stat-card group relative bg-white rounded-2xl p-4 md:p-5 shadow-sm border border-gray-100 hover:border-indigo-300 overflow-hidden">
                <div class="absolute -right-3 -top-3 w-16 h-16 bg-indigo-50 rounded-full opacity-0 group-hover:opacity-60 group-hover:scale-150 transition-all duration-500"></div>
                <div class="flex items-center justify-between mb-3 relative z-10">
                    <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-sm" style="background-color: #4f46e5;">
                        <i class="fas fa-graduation-cap text-sm"></i>
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md border bg-indigo-50 text-indigo-700 border-indigo-200">Selesai</span>
                </div>
                <h3 class="text-2xl md:text-3xl font-black text-gray-800 relative z-10">{{ number_format($completedInterns) }}</h3>
                <div class="flex items-center gap-1 mt-1.5 relative z-10">
                    <span class="text-[10px] font-bold text-gray-400 group-hover:text-indigo-600 transition-colors">Lihat Detail</span>
                    <i class="fas fa-arrow-right text-[8px] text-gray-300 group-hover:text-indigo-600 group-hover:translate-x-1 transition-all"></i>
                </div>
            </a>

            {{-- 6. Pending --}}
            <a href="{{ route('admin.laporan.peserta_global', ['status' => 'pending']) }}" class="stat-card group relative bg-white rounded-2xl p-4 md:p-5 shadow-sm border border-gray-100 hover:border-amber-300 overflow-hidden">
                <div class="absolute -right-3 -top-3 w-16 h-16 bg-amber-50 rounded-full opacity-0 group-hover:opacity-60 group-hover:scale-150 transition-all duration-500"></div>
                <div class="flex items-center justify-between mb-3 relative z-10">
                    <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow-sm" style="background-color: #f59e0b;">
                        <i class="fas fa-hourglass-half text-sm"></i>
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md border bg-amber-50 text-amber-700 border-amber-200">Pending</span>
                </div>
                <h3 class="text-2xl md:text-3xl font-black text-gray-800 relative z-10">{{ number_format($pendingApplications) }}</h3>
                <div class="flex items-center gap-1 mt-1.5 relative z-10">
                    <span class="text-[10px] font-bold text-gray-400 group-hover:text-amber-600 transition-colors">Lihat Detail</span>
                    <i class="fas fa-arrow-right text-[8px] text-gray-300 group-hover:text-amber-600 group-hover:translate-x-1 transition-all"></i>
                </div>
            </a>

        </div>

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- ROW 3.5: EXECUTIVE CHARTS (Demografi & Penyerapan) --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-5">
            {{-- Chart Demografi Kampus --}}
            <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 p-5 md:p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-xl bg-teal-500 text-white flex items-center justify-center shadow-sm" style="background-color: #14b8a6;">
                        <i class="fas fa-university text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm md:text-base font-black text-gray-800">Demografi Kampus/Sekolah</h3>
                        <p class="text-[10px] text-gray-500 font-medium">Asal instansi pendidikan peserta magang</p>
                    </div>
                </div>
                <div class="relative flex items-center justify-center w-full" style="height: 250px;">
                    <canvas id="kampusChart"
                        data-labels="{{ json_encode($kampusLabels) }}"
                        data-values="{{ json_encode($kampusData) }}">
                    </canvas>
                </div>
            </div>

            {{-- Chart Penyerapan Kuota Instansi --}}
            <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 p-5 md:p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-xl bg-indigo-500 text-white flex items-center justify-center shadow-sm" style="background-color: #6366f1;">
                        <i class="fas fa-chart-bar text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm md:text-base font-black text-gray-800">Top 10 Penyerapan Kuota Instansi</h3>
                        <p class="text-[10px] text-gray-500 font-medium">Instansi dengan jumlah pelamar terbanyak</p>
                    </div>
                </div>
                <div class="relative flex items-center justify-center w-full" style="height: 250px;">
                    <canvas id="instansiChart"
                        data-labels="{{ json_encode($instansiChartLabels) }}"
                        data-values="{{ json_encode($instansiChartData) }}">
                    </canvas>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- ROW 4: MAIN CONTENT (2/3 + 1/3) --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 md:gap-5">
            
            {{-- ═══ KOLOM KIRI (2/3): Tabel + Feed ═══ --}}
            <div class="xl:col-span-2 space-y-4 md:space-y-5">
                
                {{-- Tabel: Statistik Pelamar per Instansi --}}
                <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
                    <div class="p-4 md:p-5 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0 z-20">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center shadow-sm" style="background-color: #0d9488;">
                                <i class="fas fa-chart-bar text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-sm md:text-base font-black text-gray-800">Statistik Pelamar per Instansi</h3>
                                <p class="text-[10px] md:text-xs text-gray-500 font-medium mt-0.5">Distribusi peminat magang berdasarkan dinas</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.laporan.peserta_global') }}" class="hidden md:inline-flex items-center gap-1.5 text-xs text-teal-600 bg-teal-50 px-3 py-1.5 rounded-lg hover:bg-teal-100 font-bold transition border border-teal-100">
                            <i class="fas fa-external-link-alt text-[10px]"></i> Lihat Semua
                        </a>
                    </div>
                    
                    {{-- Desktop Table (md+) --}}
                    <div class="hidden md:block overflow-y-auto custom-scrollbar flex-1 relative">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/80 sticky top-0 z-10">
                                <tr>
                                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-500 uppercase tracking-wider w-14">No</th>
                                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-500 uppercase tracking-wider">Nama Instansi</th>
                                    <th class="px-5 py-3 text-right text-[10px] font-black text-gray-500 uppercase tracking-wider w-36">Total Pelamar</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                @forelse($instansiStats as $index => $instansi)
                                    @php
                                        $percentage = ($instansi->applications_count / $maxPelamar) * 100;
                                    @endphp
                                    <tr class="feed-item hover:bg-gray-50/60 group">
                                        <td class="px-5 py-3.5 whitespace-nowrap text-xs font-bold text-gray-400 group-hover:text-teal-600 transition-colors">
                                            {{ $instansiStats->firstItem() + $index }}
                                        </td>
                                        <td class="px-5 py-3.5 pr-8">
                                            <p class="text-sm font-bold text-gray-800 group-hover:text-teal-700 transition-colors">{{ $instansi->nama_dinas }}</p>
                                            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2 overflow-hidden">
                                                <div class="bg-teal-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%; background-color: #14b8a6;"></div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5 whitespace-nowrap text-right">
                                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg text-xs font-black bg-teal-50 text-teal-700 border border-teal-100 group-hover:bg-teal-600 group-hover:text-white transition-all duration-200 min-w-[56px]">
                                                {{ $instansi->applications_count }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center gap-2">
                                                <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center">
                                                    <i class="fas fa-building text-2xl text-gray-300"></i>
                                                </div>
                                                <p class="text-sm font-bold text-gray-500">Belum ada data instansi.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Card View (<md) --}}
                    <div class="md:hidden divide-y divide-gray-50 flex-1">
                        @forelse($instansiStats as $index => $instansi)
                            @php
                                $percentage = ($instansi->applications_count / $maxPelamar) * 100;
                            @endphp
                            <div class="p-4 space-y-2 feed-item hover:bg-gray-50/60 active:bg-gray-100/40">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <span class="w-7 h-7 rounded-lg bg-teal-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-sm" style="background-color: #0d9488;">
                                            {{ $instansiStats->firstItem() + $index }}
                                        </span>
                                        <p class="text-sm font-bold text-gray-800 line-clamp-2">{{ $instansi->nama_dinas }}</p>
                                    </div>
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-black bg-teal-50 text-teal-700 border border-teal-100 shrink-0">
                                        {{ $instansi->applications_count }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-teal-500 h-1.5 rounded-full" style="width: {{ $percentage }}%; background-color: #14b8a6;"></div>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-building text-xl text-gray-300"></i>
                                </div>
                                <p class="text-xs font-bold text-gray-400">Belum ada data instansi.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($instansiStats->hasPages())
                    <div class="p-3 md:p-4 border-t border-gray-100 bg-gray-50/30">
                        {{ $instansiStats->links() }}
                    </div>
                    @endif
                </div>

                {{-- Feed: Aktivitas Pendaftaran --}}
                <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 md:p-5 border-b border-gray-100 flex items-center justify-between bg-white">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-sm" style="background-color: #4f46e5;">
                                <i class="fas fa-broadcast-tower text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-sm md:text-base font-black text-gray-800">Aktivitas Pendaftaran</h3>
                                <p class="text-[10px] md:text-xs text-gray-500 font-medium mt-0.5">Feed pelamar magang terbaru</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-green-50 border border-green-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="text-[10px] font-black text-green-700">Live</span>
                        </div>
                    </div>
                    
                    <div class="divide-y divide-gray-50">
                        @forelse($recentApplications as $app)
                        <div class="p-4 md:p-5 flex items-center justify-between feed-item hover:bg-gray-50/60 gap-3 md:gap-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-sm shrink-0 border border-indigo-100">
                                    {{ strtoupper(substr($app->user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $app->user->name }}</p>
                                        <span class="text-[10px] text-gray-400 font-medium truncate hidden sm:inline">({{ $app->user->asal_instansi }})</span>
                                    </div>
                                    <p class="text-xs text-gray-500 truncate mt-0.5">
                                        <i class="fas fa-arrow-right text-[10px] text-gray-300 mr-1"></i> 
                                        <span class="font-bold text-gray-700">{{ $app->position->instansi->nama_dinas }}</span> 
                                        <span class="hidden sm:inline">&bull; <span class="italic text-gray-500">{{ $app->position->judul_posisi }}</span></span>
                                    </p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <x-ui.badge :status="$app->status" />
                                <span class="block text-[10px] text-gray-400 mt-1 font-medium">
                                    <i class="far fa-clock text-[9px]"></i> {{ $app->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="p-10 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-inbox text-2xl text-gray-300"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-500">Belum ada aktivitas lamaran</p>
                            <p class="text-xs text-gray-400 mt-1">Data akan muncul saat ada pendaftaran baru.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ═══ KOLOM KANAN (1/3): Chart + Instansi + Server ═══ --}}
            <div class="space-y-4 md:space-y-5">
                
                {{-- Donut Chart: Status Lamaran --}}
                <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 p-5 md:p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-orange-500 text-white flex items-center justify-center shadow-sm" style="background-color: #f97316;">
                                    <i class="fas fa-chart-pie text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm md:text-base font-black text-gray-800">Status Lamaran</h3>
                                    <p class="text-[10px] text-gray-500 font-medium">Distribusi status keseluruhan</p>
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
                    
                    <div class="grid grid-cols-2 gap-3 mt-5 pt-4 border-t border-gray-100">
                        <div class="bg-green-50 p-3 rounded-xl border border-green-100 text-center">
                            <span class="block text-[10px] text-gray-500 font-bold uppercase">Lolos</span>
                            <span class="text-lg font-black text-green-600">
                                @if($totalApplications > 0)
                                    {{ round((($activeInterns + $completedInterns) / $totalApplications) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </span>
                        </div>
                        <div class="bg-red-50 p-3 rounded-xl border border-red-100 text-center">
                            <span class="block text-[10px] text-gray-500 font-bold uppercase">Tolak</span>
                            <span class="text-lg font-black text-red-500">
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
                <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 md:p-5 border-b border-gray-100 flex items-center justify-between bg-white">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-teal-600 text-white flex items-center justify-center shadow-xs" style="background-color: #0d9488;">
                                <i class="fas fa-plus-circle text-xs"></i>
                            </div>
                            <h3 class="text-sm font-black text-gray-800">Instansi Terbaru</h3>
                        </div>
                        <a href="{{ route('admin.instansi.index') }}" class="text-[10px] text-teal-600 bg-teal-50 px-2.5 py-1 rounded-lg hover:bg-teal-100 font-black transition border border-teal-100">Semua</a>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($recentInstansis as $dinas)
                        <div class="p-3.5 md:p-4 flex items-center gap-3 feed-item hover:bg-gray-50/60">
                            <div class="w-9 h-9 rounded-xl bg-teal-50 border border-teal-100 text-teal-600 flex items-center justify-center font-bold text-xs shrink-0">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-800 truncate">{{ $dinas->nama_dinas }}</p>
                                <p class="text-[10px] text-gray-400 truncate mt-0.5 font-medium">
                                    <i class="far fa-clock text-[9px]"></i> {{ $dinas->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Info Server --}}
                <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 p-5 md:p-6">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center">
                            <i class="fas fa-server text-xs"></i>
                        </div>
                        <h3 class="text-sm font-black text-gray-800">Informasi Sistem</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs font-bold pb-3 border-b border-gray-50">
                            <span class="flex items-center gap-2 text-gray-500">
                                <i class="fas fa-code-branch text-gray-400 text-[10px] w-4 text-center"></i>
                                Framework
                            </span>
                            <span class="text-teal-700 bg-teal-50 px-2.5 py-1 rounded-lg border border-teal-100 font-black text-[11px]">v{{ app()->version() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold pb-3 border-b border-gray-50">
                            <span class="flex items-center gap-2 text-gray-500">
                                <i class="fas fa-code text-gray-400 text-[10px] w-4 text-center"></i>
                                PHP
                            </span>
                            <span class="text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100 font-black text-[11px]">{{ PHP_VERSION }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold pb-3 border-b border-gray-50">
                            <span class="flex items-center gap-2 text-gray-500">
                                <i class="fas fa-globe text-gray-400 text-[10px] w-4 text-center"></i>
                                Lingkungan
                            </span>
                            <span class="text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100 font-black text-[11px] uppercase">{{ app()->environment() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="flex items-center gap-2 text-gray-500">
                                <i class="fas fa-heartbeat text-gray-400 text-[10px] w-4 text-center"></i>
                                Scheduler
                            </span>
                            <span class="text-green-700 bg-green-50 px-2.5 py-1 rounded-lg border border-green-100 flex items-center gap-1.5 font-black text-[11px]">
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
                                borderColor: '#ffffff',
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
                                        color: '#6B7280'
                                    }
                                },
                                tooltip: {
                                    backgroundColor: '#111827',
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
                                backgroundColor: '#14b8a6',
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
                                y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                                x: { grid: { display: false }, ticks: { font: { size: 10 }, maxRotation: 45, minRotation: 45 } }
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
                                backgroundColor: '#6366f1',
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
                                x: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                                y: { grid: { display: false }, ticks: { font: { size: 10 } } }
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