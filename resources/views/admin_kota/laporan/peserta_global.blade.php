<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">
                    <a href="{{ route('admin.laporan.hub') }}" class="hover:text-teal-600 transition flex items-center gap-1.5">
                        <i class="fas fa-arrow-left"></i> Pusat Laporan
                    </a>
                    <span>/</span>
                    <span class="text-teal-600">Global Peserta</span>
                </div>
                <h2 class="font-black text-2xl text-gray-900 leading-tight flex items-center gap-2.5">
                    <span class="w-10 h-10 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-700 text-white flex items-center justify-center text-lg shadow-md shadow-teal-600/20">
                        <i class="fas fa-globe-asia"></i>
                    </span>
                    {{ __('Rekapitulasi Global Peserta Magang') }}
                </h2>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                @if(request()->anyFilled(['instansi', 'instansi_id', 'status', 'start_date', 'end_date', 'posisi']))
                    <a href="{{ route('admin.laporan.peserta_global') }}" class="px-4 py-2.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl font-bold text-xs transition flex items-center gap-1.5 border border-rose-200/60 shadow-sm">
                        <i class="fas fa-redo-alt text-[10px]"></i> Reset Filter
                    </a>
                @endif

                @if($allInterns->count() > 0)
                    <div class="flex gap-2 w-full sm:w-auto">
                        <a href="{{ route('admin.laporan.peserta_global.print', array_merge(request()->query(), ['format' => 'pdf'])) }}" target="_blank" class="flex-1 sm:flex-none justify-center px-4 py-2.5 bg-white text-teal-700 border border-gray-200 hover:bg-teal-50 rounded-xl font-bold text-xs transition shadow-sm flex items-center gap-2" title="Download PDF">
                            <i class="fas fa-file-pdf text-red-500"></i> <span class="hidden sm:inline">PDF</span>
                        </a>
                        <a href="{{ route('admin.laporan.peserta_global.print', array_merge(request()->query(), ['format' => 'excel'])) }}" class="flex-1 sm:flex-none justify-center px-4 py-2.5 bg-white text-teal-700 border border-gray-200 hover:bg-teal-50 rounded-xl font-bold text-xs transition shadow-sm flex items-center gap-2" title="Download Excel">
                            <i class="fas fa-file-excel text-green-600"></i> <span class="hidden sm:inline">Excel</span>
                        </a>
                        <a href="{{ route('admin.laporan.peserta_global.print', array_merge(request()->query(), ['format' => 'csv'])) }}" class="flex-1 sm:flex-none justify-center px-4 py-2.5 bg-white text-teal-700 border border-gray-200 hover:bg-teal-50 rounded-xl font-bold text-xs transition shadow-sm flex items-center gap-2" title="Download CSV">
                            <i class="fas fa-file-csv text-blue-600"></i> <span class="hidden sm:inline">CSV</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/70 min-h-screen font-sans" x-data="{ quickSearch: '', showFilters: true }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- 1. EXECUTIVE KPI CARDS -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <!-- Total Pendaftar -->
                <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100/80 relative overflow-hidden group hover:shadow-md hover:border-teal-300/80 transition-all duration-300">
                    <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-teal-50/60 rounded-full group-hover:scale-125 transition-transform"></div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Total Pendaftar</span>
                            <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-sm border border-teal-100 shadow-sm group-hover:bg-teal-600 group-hover:text-white transition-colors">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <p class="text-2xl font-black text-gray-900 tracking-tight">{{ number_format($stats['total']) }}</p>
                        <p class="text-[11px] font-semibold text-teal-600 mt-1">Seluruh pelamar</p>
                    </div>
                </div>

                <!-- Status Aktif -->
                <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100/80 relative overflow-hidden group hover:shadow-md hover:border-emerald-300/80 transition-all duration-300">
                    <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-emerald-50/60 rounded-full group-hover:scale-125 transition-transform"></div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Aktif Magang</span>
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm border border-emerald-100 shadow-sm group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                <i class="fas fa-user-clock"></i>
                            </div>
                        </div>
                        <p class="text-2xl font-black text-emerald-600 tracking-tight">{{ number_format($stats['aktif']) }}</p>
                        <p class="text-[11px] font-semibold text-emerald-600 mt-1 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Sedang berjalan
                        </p>
                    </div>
                </div>

                <!-- Selesai / Lulus -->
                <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100/80 relative overflow-hidden group hover:shadow-md hover:border-blue-300/80 transition-all duration-300">
                    <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-blue-50/60 rounded-full group-hover:scale-125 transition-transform"></div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Selesai / Lulus</span>
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm border border-blue-100 shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        </div>
                        <p class="text-2xl font-black text-blue-600 tracking-tight">{{ number_format($stats['selesai']) }}</p>
                        <p class="text-[11px] font-semibold text-blue-600 mt-1">Alumni peserta</p>
                    </div>
                </div>

                <!-- Pending -->
                <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100/80 relative overflow-hidden group hover:shadow-md hover:border-amber-300/80 transition-all duration-300">
                    <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-amber-50/60 rounded-full group-hover:scale-125 transition-transform"></div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Proses Pending</span>
                            <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm border border-amber-100 shadow-sm group-hover:bg-amber-600 group-hover:text-white transition-colors">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                        </div>
                        <p class="text-2xl font-black text-amber-600 tracking-tight">{{ number_format($stats['pending']) }}</p>
                        <p class="text-[11px] font-semibold text-amber-600 mt-1">Menunggu seleksi</p>
                    </div>
                </div>

                <!-- Dinas Terlibat -->
                <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100/80 relative overflow-hidden group hover:shadow-md hover:border-indigo-300/80 transition-all duration-300">
                    <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-indigo-50/60 rounded-full group-hover:scale-125 transition-transform"></div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Dinas Terlibat</span>
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm border border-indigo-100 shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <i class="fas fa-building"></i>
                            </div>
                        </div>
                        <p class="text-2xl font-black text-indigo-600 tracking-tight">{{ number_format($stats['total_dinas']) }}</p>
                        <p class="text-[11px] font-semibold text-indigo-600 mt-1">Instansi penempatan</p>
                    </div>
                </div>

                <!-- Kampus Mitra -->
                <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100/80 relative overflow-hidden group hover:shadow-md hover:border-rose-300/80 transition-all duration-300">
                    <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-rose-50/60 rounded-full group-hover:scale-125 transition-transform"></div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Kampus Mitra</span>
                            <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-sm border border-rose-100 shadow-sm group-hover:bg-rose-600 group-hover:text-white transition-colors">
                                <i class="fas fa-university"></i>
                            </div>
                        </div>
                        <p class="text-2xl font-black text-rose-600 tracking-tight">{{ number_format($stats['total_kampus']) }}</p>
                        <p class="text-[11px] font-semibold text-rose-600 mt-1">Perguruan tinggi / SMK</p>
                    </div>
                </div>
            </div>

            <!-- 2. FILTER & SEARCH TOOLBAR CARD -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100/80">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 pb-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-base font-bold border border-teal-100">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-base text-gray-900">Filter Data & Kustomisasi Laporan</h3>
                            <p class="text-xs text-gray-500">Saring peserta berdasarkan kampus, dinas tujuan, posisi, status, dan periode waktu magang.</p>
                        </div>
                    </div>

                    <button type="button" @click="showFilters = !showFilters" 
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-gray-600 bg-gray-50 hover:bg-gray-100 transition border border-gray-200/80">
                        <i class="fas transition-transform duration-200" :class="showFilters ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        <span x-text="showFilters ? 'Sembunyikan Panel Filter' : 'Tampilkan Panel Filter'"></span>
                    </button>
                </div>

                <div x-show="showFilters" x-collapse x-cloak class="pt-6">
                    <form method="GET" action="{{ route('admin.laporan.peserta_global') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        
                        <!-- Filter Kampus -->
                        <div>
                            <label class="block text-xs font-extrabold text-gray-600 uppercase tracking-wider mb-1.5">Asal Kampus / Sekolah</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 pointer-events-none">
                                    <i class="fas fa-university text-xs"></i>
                                </span>
                                <select name="instansi" class="w-full pl-9 pr-8 py-2.5 bg-gray-50 border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 focus:bg-white shadow-sm transition">
                                    <option value="">Semua Kampus Mitra</option>
                                    @foreach($listInstansi as $instansi)
                                        <option value="{{ $instansi }}" {{ request('instansi') == $instansi ? 'selected' : '' }}>
                                            {{ Str::limit($instansi, 32) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Filter Dinas -->
                        <div>
                            <label class="block text-xs font-extrabold text-gray-600 uppercase tracking-wider mb-1.5">Dinas Penempatan</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 pointer-events-none">
                                    <i class="fas fa-building text-xs"></i>
                                </span>
                                <select name="instansi_id" class="w-full pl-9 pr-8 py-2.5 bg-gray-50 border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 focus:bg-white shadow-sm transition">
                                    <option value="">Semua Lokasi Dinas</option>
                                    @foreach($listDinas as $dinas)
                                        <option value="{{ $dinas->id }}" {{ request('instansi_id') == $dinas->id ? 'selected' : '' }}>
                                            {{ Str::limit($dinas->nama_dinas, 32) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Filter Posisi -->
                        <div>
                            <label class="block text-xs font-extrabold text-gray-600 uppercase tracking-wider mb-1.5">Kata Kunci Posisi</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 pointer-events-none">
                                    <i class="fas fa-briefcase text-xs"></i>
                                </span>
                                <input type="text" name="posisi" value="{{ request('posisi') }}" 
                                    placeholder="Contoh: Programmer, Staf..."
                                    class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 focus:bg-white shadow-sm transition">
                            </div>
                        </div>

                        <!-- Filter Status -->
                        <div>
                            <label class="block text-xs font-extrabold text-gray-600 uppercase tracking-wider mb-1.5">Status Magang</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 pointer-events-none">
                                    <i class="fas fa-check-circle text-xs"></i>
                                </span>
                                <select name="status" class="w-full pl-9 pr-8 py-2.5 bg-gray-50 border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 focus:bg-white shadow-sm transition">
                                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>🟢 Aktif (Sedang Magang)</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>🔵 Selesai / Lulus</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>🟡 Pending / Menunggu</option>
                                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>🔴 Ditolak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filter Periode Magang -->
                        <div>
                            <label class="block text-xs font-extrabold text-gray-600 uppercase tracking-wider mb-1.5">Rentang Periode</label>
                            <div class="grid grid-cols-2 gap-1.5">
                                <input type="date" name="start_date" value="{{ request('start_date') }}" title="Dari Tanggal Mulai"
                                    class="w-full px-2.5 py-2.5 bg-gray-50 border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 focus:bg-white shadow-sm transition">
                                <input type="date" name="end_date" value="{{ request('end_date') }}" title="Sampai Tanggal Selesai"
                                    class="w-full px-2.5 py-2.5 bg-gray-50 border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 focus:bg-white shadow-sm transition">
                            </div>
                        </div>

                        <!-- Tombol Terapkan & Reset -->
                        <div class="md:col-span-3 lg:col-span-5 flex flex-col sm:flex-row justify-end items-center gap-3 pt-3 border-t border-gray-100 mt-2">
                            @if(request()->anyFilled(['instansi', 'instansi_id', 'status', 'start_date', 'end_date', 'posisi']))
                                <a href="{{ route('admin.laporan.peserta_global') }}" class="w-full sm:w-auto px-5 py-2.5 text-xs font-bold text-gray-600 hover:text-rose-600 bg-gray-100 hover:bg-rose-50 rounded-xl transition text-center">
                                    <i class="fas fa-times mr-1"></i> Bersihkan Semua Filter
                                </a>
                            @endif
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-extrabold shadow-md shadow-teal-600/25 transition transform active:scale-95 flex items-center justify-center gap-2">
                                <i class="fas fa-filter text-xs"></i> Terapkan Filter Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 3. MAIN DATA TABLE CARD -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100/80 overflow-hidden">
                <!-- Table Header & Quick Client Search -->
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-gray-50/50">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h3 class="text-lg font-black text-gray-900">Daftar Rincian Peserta Magang</h3>
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-teal-100 text-teal-800 border border-teal-200/60">
                                {{ $allInterns->count() }} Peserta Terpilih
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Informasi lengkap peserta, instansi sekolah/kampus asal, dinas tujuan, posisi, serta durasi magang.</p>
                    </div>

                    <!-- Client-Side Quick Filter -->
                    <div class="w-full sm:w-72 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 pointer-events-none">
                            <i class="fas fa-search text-xs"></i>
                        </span>
                        <input type="text" x-model="quickSearch" 
                            placeholder="Cari nama / kampus / dinas..."
                            class="w-full pl-9 pr-8 py-2 bg-white border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 shadow-sm transition">
                        <button type="button" x-show="quickSearch !== ''" @click="quickSearch = ''"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times-circle text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 border-collapse">
                        <thead class="bg-gray-50/90 text-gray-500 text-[11px] uppercase tracking-wider font-extrabold sticky top-0 z-10 border-b border-gray-100">
                            <tr>
                                <th class="px-5 py-4 text-center w-12">No</th>
                                <th class="px-6 py-4 text-left">Peserta & Asal Kampus / Sekolah</th>
                                <th class="px-6 py-4 text-left">Penempatan Dinas & Posisi</th>
                                <th class="px-6 py-4 text-left">Periode & Durasi Magang</th>
                                <th class="px-6 py-4 text-center w-36">Status Magang</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100/80 text-sm">
                            @forelse($allInterns as $data)
                            <tr class="hover:bg-teal-50/30 transition duration-150 group"
                                x-show="quickSearch === '' || 
                                        '{{ strtolower($data->user->name ?? '') }}'.includes(quickSearch.toLowerCase()) || 
                                        '{{ strtolower($data->user->asal_instansi ?? '') }}'.includes(quickSearch.toLowerCase()) || 
                                        '{{ strtolower($data->position->instansi->nama_dinas ?? '') }}'.includes(quickSearch.toLowerCase()) ||
                                        '{{ strtolower($data->position->judul_posisi ?? '') }}'.includes(quickSearch.toLowerCase())">
                                
                                <td class="px-5 py-5 text-center text-gray-400 font-extrabold text-xs">
                                    {{ $loop->iteration }}
                                </td>

                                <!-- Peserta & Kampus -->
                                <td class="px-6 py-5">
                                    <div class="flex items-start gap-3.5">
                                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center text-white font-black text-xs shadow-md shadow-teal-600/15 flex-shrink-0 mt-0.5 group-hover:scale-105 transition-transform">
                                            {{ strtoupper(substr($data->user->name ?? 'P', 0, 2)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-extrabold text-gray-900 text-sm tracking-tight">{{ $data->user->name ?? 'User Tidak Ditemukan' }}</p>
                                            
                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-teal-50/80 text-teal-800 font-bold text-xs mt-1.5 border border-teal-100/80">
                                                <i class="fas fa-university text-teal-600 text-[10px]"></i>
                                                <span>{{ $data->user->asal_instansi ?? 'Tidak ada data kampus' }}</span>
                                            </div>

                                            <div class="flex items-center gap-3 text-[11px] text-gray-500 mt-2 font-medium">
                                                <span class="flex items-center gap-1">
                                                    <i class="far fa-envelope text-gray-400"></i> {{ $data->user->email ?? '-' }}
                                                </span>
                                                @if(!empty($data->user->phone))
                                                <span class="text-gray-300">•</span>
                                                <span class="flex items-center gap-1">
                                                    <i class="fas fa-phone-alt text-gray-400 text-[9px]"></i> {{ $data->user->phone }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Penempatan Dinas & Posisi -->
                                <td class="px-6 py-5">
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="font-extrabold text-gray-900 text-xs flex items-center gap-1.5">
                                            <i class="fas fa-building text-teal-600 text-xs"></i>
                                            {{ $data->position->instansi->nama_dinas ?? '-' }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-100 text-gray-700 font-bold text-xs mt-1">
                                            <i class="fas fa-briefcase text-gray-400 mr-1.5 text-[10px]"></i>
                                            {{ $data->position->judul_posisi ?? '-' }}
                                        </span>
                                        @if($data->is_automatic_placement)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black bg-indigo-50 text-indigo-700 border border-indigo-200/60 gap-1 mt-1.5 shadow-2xs">
                                                <i class="fas fa-magic text-[9px] text-indigo-600"></i> Penempatan Otomatis
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Periode & Durasi Magang -->
                                <td class="px-6 py-5">
                                    <div class="flex flex-col items-start gap-1.5">
                                        <div class="text-xs font-bold text-gray-800 flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-200/60">
                                            <i class="far fa-calendar-alt text-teal-600"></i>
                                            <span>{{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d M Y') }}</span>
                                            <span class="text-gray-400">➜</span>
                                            <span>{{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d M Y') }}</span>
                                        </div>
                                        @php
                                            $days = \Carbon\Carbon::parse($data->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($data->tanggal_selesai));
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-teal-50 text-teal-700 border border-teal-200/80">
                                            <i class="fas fa-history mr-1 text-[9px]"></i> Durasi: {{ $days }} Hari
                                        </span>
                                    </div>
                                </td>

                                <!-- Status Magang -->
                                <td class="px-6 py-5 text-center">
                                    @php
                                        $statusConfig = [
                                            'pending' => [
                                                'class' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                'icon' => 'fas fa-clock text-amber-500',
                                                'label' => 'Pending'
                                            ],
                                            'menunggu' => [
                                                'class' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                'icon' => 'fas fa-clock text-amber-500',
                                                'label' => 'Pending'
                                            ],
                                            'diterima' => [
                                                'class' => 'bg-emerald-50 text-emerald-800 border-emerald-200 shadow-sm',
                                                'icon' => 'dot-emerald',
                                                'label' => 'Aktif Magang'
                                            ],
                                            'selesai' => [
                                                'class' => 'bg-blue-50 text-blue-800 border-blue-200',
                                                'icon' => 'fas fa-check-circle text-blue-600',
                                                'label' => 'Selesai / Lulus'
                                            ],
                                            'ditolak' => [
                                                'class' => 'bg-rose-50 text-rose-800 border-rose-200',
                                                'icon' => 'fas fa-times-circle text-rose-600',
                                                'label' => 'Ditolak'
                                            ],
                                        ];
                                        $statusVal = $data->status instanceof \App\Enums\ApplicationStatus ? $data->status->value : $data->status;
                                        $s = $statusConfig[$statusVal] ?? [
                                            'class' => 'bg-gray-100 text-gray-800 border-gray-200',
                                            'icon' => 'fas fa-info-circle text-gray-500',
                                            'label' => ucfirst($statusVal)
                                        ];
                                    @endphp
                                    <span class="px-3.5 py-1.5 inline-flex items-center gap-1.5 text-xs font-black rounded-full border {{ $s['class'] }}">
                                        @if($s['icon'] === 'dot-emerald')
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        @else
                                            <i class="{{ $s['icon'] }} text-[11px]"></i>
                                        @endif
                                        <span>{{ $s['label'] }}</span>
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                        <div class="w-20 h-20 bg-teal-50 text-teal-600 rounded-3xl flex items-center justify-center mb-4 text-3xl shadow-sm border border-teal-100">
                                            <i class="fas fa-search"></i>
                                        </div>
                                        <h4 class="text-gray-900 font-black text-lg">Tidak Ada Data Peserta</h4>
                                        <p class="text-gray-500 text-xs mt-1 leading-relaxed">Belum ada data peserta magang yang sesuai dengan kriteria filter atau pencarian Anda saat ini.</p>
                                        @if(request()->anyFilled(['instansi', 'instansi_id', 'status', 'start_date', 'end_date', 'posisi']))
                                            <a href="{{ route('admin.laporan.peserta_global') }}" class="mt-5 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-extrabold shadow-md shadow-teal-600/20 transition">
                                                <i class="fas fa-redo-alt mr-1.5"></i> Reset Semua Filter
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer Summary -->
                @if($allInterns->count() > 0)
                <div class="p-4 bg-gray-50/80 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center text-xs font-bold text-gray-500 gap-2">
                    <div>
                        Menampilkan <span class="text-gray-900 font-black">{{ $allInterns->count() }}</span> data peserta berdasarkan filter aktif.
                    </div>
                    <div class="flex items-center gap-4 text-gray-400">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Aktif
                        </span>
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-check-circle text-blue-500 text-[10px]"></i> Selesai
                        </span>
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-clock text-amber-500 text-[10px]"></i> Pending
                        </span>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>