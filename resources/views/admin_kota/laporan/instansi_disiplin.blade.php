<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-building text-teal-600 dark:text-teal-400"></i>
                {{ __('Analisis Kedisiplinan Instansi') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400 font-medium bg-white dark:bg-gray-800 px-4 py-1.5 rounded-full shadow-sm border border-gray-100 dark:border-gray-700">
                Total Terfilter: <span class="font-bold text-teal-600 dark:text-teal-400">{{ $stats['total_instansi'] }}</span> Instansi
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center print:hidden">
                <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-sm">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Pusat Laporan
                </a>
                
                @if($stats['total_instansi'] > 0)
                <div class="flex gap-2">
                    <a href="{{ route('admin.laporan.instansi_disiplin.print', array_merge(request()->query(), ['format' => 'pdf'])) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-300 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-teal-950/30 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download PDF">
                        <i class="fas fa-file-pdf text-red-500"></i> <span class="hidden sm:inline">PDF</span>
                    </a>
                    <a href="{{ route('admin.laporan.instansi_disiplin.print', array_merge(request()->query(), ['format' => 'excel'])) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-300 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-teal-950/30 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download Excel">
                        <i class="fas fa-file-excel text-green-600"></i> <span class="hidden sm:inline">Excel</span>
                    </a>
                    <a href="{{ route('admin.laporan.instansi_disiplin.print', array_merge(request()->query(), ['format' => 'csv'])) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-300 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-teal-950/30 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download CSV">
                        <i class="fas fa-file-csv text-blue-600"></i> <span class="hidden sm:inline">CSV</span>
                    </a>
                </div>
                @endif
            </div>

            {{-- 6 Stats Cards Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-8 h-8 rounded-full bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-400 flex items-center justify-center mx-auto mb-2 border border-teal-100 dark:border-teal-900/40">
                        <i class="fas fa-building text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-gray-800 dark:text-gray-200">{{ $stats['total_instansi'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Total Instansi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center bg-gradient-to-br from-teal-50/50 via-white to-indigo-50/30 dark:from-teal-950/20 dark:via-gray-800 dark:to-indigo-950/20">
                    <div class="w-8 h-8 rounded-full bg-teal-600 text-white flex items-center justify-center mx-auto mb-2 shadow-sm shadow-teal-500/20">
                        <i class="fas fa-percentage text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-teal-700 dark:text-teal-400">{{ $stats['avg_disiplin'] }}%</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Rerata Disiplin</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-2 border border-blue-100 dark:border-blue-900/40">
                        <i class="fas fa-calendar-check text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-blue-700 dark:text-blue-400">{{ $stats['total_kehadiran'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Total Absensi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-8 h-8 rounded-full bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-400 flex items-center justify-center mx-auto mb-2 border border-red-100 dark:border-red-900/40">
                        <i class="fas fa-exclamation-triangle text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-red-700 dark:text-red-400">{{ $stats['total_pelanggaran'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Total Pelanggaran</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-8 h-8 rounded-full bg-orange-50 dark:bg-orange-950/40 text-orange-600 dark:text-orange-400 flex items-center justify-center mx-auto mb-2 border border-orange-100 dark:border-orange-900/40">
                        <i class="fas fa-user-clock text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-orange-700 dark:text-orange-400">{{ $stats['total_terlambat'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Total Terlambat</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-8 h-8 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 flex items-center justify-center mx-auto mb-2 border border-amber-100 dark:border-amber-900/40">
                        <i class="fas fa-user-times text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-amber-800 dark:text-amber-400">{{ $stats['total_alpa'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Total Alpa</p>
                </div>
            </div>

            {{-- Collapsible Top 3 Podium Leaderboard --}}
            @if($podium->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition-all duration-300" x-data="{ showTop3: false }">
                <div class="flex items-center justify-between cursor-pointer select-none" @click="showTop3 = !showTop3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-yellow-100 dark:bg-yellow-950/60 text-yellow-600 dark:text-yellow-400 flex items-center justify-center shadow-xs">
                            <i class="fas fa-award text-lg animate-pulse"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                TOP 3 INSTANSI PALING DISIPLIN
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Daftar instansi aktif dengan persentase ketepatan waktu absensi peserta terbaik se-Banjarmasin.</p>
                        </div>
                    </div>
                    <button type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <span x-text="showTop3 ? 'Sembunyikan Podium' : 'Tampilkan TOP 3'"></span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300" :class="showTop3 ? 'rotate-180 text-teal-500' : ''"></i>
                    </button>
                </div>
                
                <div x-show="showTop3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak class="mt-6 border-t border-gray-100 dark:border-gray-700 pt-6">
                    <div class="flex flex-col md:flex-row items-end justify-center gap-6 md:gap-4 max-w-4xl mx-auto pt-4">
                        
                        {{-- Juara 2 (Perak) --}}
                        @if($podium->count() > 1)
                        @php $p2 = $podium[1]; @endphp
                        <div class="w-full md:w-1/3 order-2 md:order-1 flex flex-col items-center">
                            <div class="relative mb-3 flex flex-col items-center">
                                <div class="h-16 w-16 rounded-full bg-gradient-to-br from-gray-100 to-gray-300 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-100 font-black border-4 border-white dark:border-gray-700 shadow-md text-lg relative">
                                    {{ strtoupper(substr($p2->nama_dinas, 0, 2)) }}
                                    <span class="absolute -top-3 -right-1 w-6 h-6 rounded-full bg-gray-400 text-white text-[10px] font-bold flex items-center justify-center border-2 border-white dark:border-gray-800 shadow">2</span>
                                </div>
                            </div>
                            <div class="text-center mb-2">
                                <p class="font-bold text-gray-800 dark:text-gray-200 text-sm truncate max-w-[200px]" title="{{ $p2->nama_dinas }}">{{ $p2->nama_dinas }}</p>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold">{{ $p2->total_attendances }} Kehadiran</p>
                                <p class="text-[9px] text-red-500 dark:text-red-400 bg-red-50 dark:bg-red-950/40 px-2 py-0.5 rounded-full inline-block font-bold mt-1 border border-transparent dark:border-red-900/40">{{ $p2->total_pelanggaran }} Pelanggaran</p>
                            </div>
                            <div class="w-full bg-gradient-to-t from-gray-100 to-gray-200/50 dark:from-gray-900 dark:to-gray-800/80 rounded-t-2xl pt-8 pb-4 text-center border-t border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-center items-center h-28">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">KEDISIPLINAN</span>
                                <span class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1">{{ number_format($p2->tingkat_disiplin, 1) }}%</span>
                            </div>
                        </div>
                        @endif

                        {{-- Juara 1 (Emas) --}}
                        @if($podium->count() > 0)
                        @php $p1 = $podium[0]; @endphp
                        <div class="w-full md:w-1/3 order-1 md:order-2 flex flex-col items-center transform md:-translate-y-4">
                            <div class="relative mb-3 flex flex-col items-center">
                                <div class="absolute -top-7 text-yellow-500 text-2xl drop-shadow animate-pulse">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <div class="h-20 w-20 rounded-full bg-gradient-to-br from-yellow-300 to-yellow-500 flex items-center justify-center text-white font-black border-4 border-white dark:border-gray-700 shadow-lg text-xl relative">
                                    {{ strtoupper(substr($p1->nama_dinas, 0, 2)) }}
                                    <span class="absolute -top-1 -right-1 w-7 h-7 rounded-full bg-yellow-500 text-white text-[11px] font-black flex items-center justify-center border-2 border-white dark:border-gray-800 shadow">1</span>
                                </div>
                            </div>
                            <div class="text-center mb-2">
                                <p class="font-black text-gray-900 dark:text-gray-100 text-base truncate max-w-[220px]" title="{{ $p1->nama_dinas }}">{{ $p1->nama_dinas }}</p>
                                <p class="text-xs text-gray-700 dark:text-gray-300 font-bold">{{ $p1->total_attendances }} Kehadiran</p>
                                <p class="text-[10px] text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950/40 px-2.5 py-0.5 rounded-full inline-block font-extrabold mt-1 border border-red-200 dark:border-red-900/50">{{ $p1->total_pelanggaran }} Pelanggaran</p>
                            </div>
                            <div class="w-full bg-gradient-to-t from-yellow-50 to-yellow-100 dark:from-yellow-950/40 dark:to-yellow-900/20 rounded-t-2xl pt-10 pb-6 text-center border-t-2 border-yellow-300 dark:border-yellow-700 shadow flex flex-col justify-center items-center h-36">
                                <span class="text-xs font-black text-yellow-800 dark:text-yellow-300 uppercase tracking-wider">KEDISIPLINAN</span>
                                <span class="text-3xl font-black text-yellow-700 dark:text-yellow-400 mt-1">{{ number_format($p1->tingkat_disiplin, 1) }}%</span>
                            </div>
                        </div>
                        @endif

                        {{-- Juara 3 (Perunggu) --}}
                        @if($podium->count() > 2)
                        @php $p3 = $podium[2]; @endphp
                        <div class="w-full md:w-1/3 order-3 md:order-3 flex flex-col items-center">
                            <div class="relative mb-3 flex flex-col items-center">
                                <div class="h-16 w-16 rounded-full bg-gradient-to-br from-amber-600 to-amber-800/80 flex items-center justify-center text-white font-black border-4 border-white dark:border-gray-700 shadow-md text-lg relative">
                                    {{ strtoupper(substr($p3->nama_dinas, 0, 2)) }}
                                    <span class="absolute -top-3 -right-1 w-6 h-6 rounded-full bg-amber-600 text-white text-[10px] font-bold flex items-center justify-center border-2 border-white dark:border-gray-800 shadow">3</span>
                                </div>
                            </div>
                            <div class="text-center mb-2">
                                <p class="font-bold text-gray-800 dark:text-gray-200 text-sm truncate max-w-[200px]" title="{{ $p3->nama_dinas }}">{{ $p3->nama_dinas }}</p>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold">{{ $p3->total_attendances }} Kehadiran</p>
                                <p class="text-[9px] text-red-500 dark:text-red-400 bg-red-50 dark:bg-red-950/40 px-2 py-0.5 rounded-full inline-block font-bold mt-1 border border-transparent dark:border-red-900/40">{{ $p3->total_pelanggaran }} Pelanggaran</p>
                            </div>
                            <div class="w-full bg-gradient-to-t from-orange-50/50 to-orange-100/30 dark:from-orange-950/40 dark:to-orange-900/20 rounded-t-2xl pt-8 pb-4 text-center border-t border-orange-200 dark:border-orange-900/40 shadow-sm flex flex-col justify-center items-center h-24">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">KEDISIPLINAN</span>
                                <span class="text-xl font-black text-amber-700 dark:text-amber-400 mt-1">{{ number_format($p3->tingkat_disiplin, 1) }}%</span>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
            @endif

            {{-- Highlight Banner --}}
            <div class="bg-gradient-to-r from-teal-700 to-indigo-700 dark:from-teal-900 dark:to-indigo-950 rounded-3xl p-6 text-white shadow-lg shadow-teal-700/20 flex flex-col sm:flex-row items-center gap-4 border border-teal-600/30">
                <div class="w-14 h-14 rounded-2xl bg-white/10 dark:bg-gray-800/40 backdrop-blur-sm flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="text-center sm:text-left flex-grow">
                    <p class="text-xs font-bold uppercase tracking-wider text-teal-100">Laporan Kedisiplinan & Kepatuhan Absensi</p>
                    <p class="text-xl font-black mt-0.5">Total {{ $stats['total_instansi'] }} Dinas Instansi</p>
                    <p class="text-sm text-teal-50/90 font-medium">Berdasarkan rasio keterlambatan (melebihi jam masuk masing-masing dinas) dan ketidakhadiran (alpa).</p>
                </div>
            </div>

            {{-- Filter Laporan (Dipindah ke atas Card Pemeringkatan Kepatuhan Instansi) --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 print:hidden">
                <div class="mb-4 pb-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-gray-800 dark:text-gray-200 font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                        <i class="fas fa-filter text-teal-500 dark:text-teal-400"></i> Filter Laporan Kedisiplinan
                    </h3>
                    @if(request()->anyFilled(['q', 'disiplin_range']))
                        <a href="{{ route('admin.laporan.instansi_disiplin') }}" class="text-xs text-red-500 dark:text-red-400 hover:text-red-700 font-bold flex items-center gap-1">
                            <i class="fas fa-undo text-[10px]"></i> Reset Filter
                        </a>
                    @endif
                </div>
                
                <form method="GET" action="{{ route('admin.laporan.instansi_disiplin') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Nama Instansi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 pointer-events-none">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" name="q" value="{{ request('q') }}" 
                                placeholder="Cari nama dinas / badan..."
                                class="w-full pl-9 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Kategori Kedisiplinan</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 pointer-events-none">
                                <i class="fas fa-percentage text-xs"></i>
                            </span>
                            <select name="disiplin_range" class="w-full pl-9 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 hover:bg-white dark:hover:bg-gray-800 transition">
                                <option value="">Semua Kategori</option>
                                <option value="sangat" {{ request('disiplin_range') == 'sangat' ? 'selected' : '' }}>Sangat Disiplin (>= 90%)</option>
                                <option value="cukup" {{ request('disiplin_range') == 'cukup' ? 'selected' : '' }}>Cukup Disiplin (70% - 89%)</option>
                                <option value="kurang" {{ request('disiplin_range') == 'kurang' ? 'selected' : '' }}>Kurang Disiplin (< 70%)</option>
                            </select>
                        </div>
                    </div>

                    <div class="md:col-span-2 flex justify-end gap-2 pt-2">
                        <button type="submit" class="bg-teal-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-teal-500/20 hover:bg-teal-700 transition transform active:scale-95 text-sm flex items-center gap-2">
                            <i class="fas fa-search"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- Main Table Card (Daftar Pemeringkatan Kepatuhan Instansi - Fully Fits Screen) --}}
            <div class="w-full space-y-6" x-data="{ openRow: null }">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-gray-200 text-lg">Daftar Pemeringkatan Kepatuhan Instansi</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Instansi dengan tingkat kepatuhan terurut dari tertinggi ke terendah.</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto max-h-[650px] overflow-y-auto">
                        <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900 sticky top-0 z-20">
                                <tr>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">Rank</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Instansi</th>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28">Total Absensi</th>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">Pelanggaran</th>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-36">Tingkat Disiplin</th>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-14">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-50 dark:divide-gray-700/60 text-sm">
                                @forelse($instansis as $index => $res)
                                <tr class="hover:bg-teal-50/15 dark:hover:bg-gray-900/60 transition group cursor-pointer" @click="openRow = (openRow === {{ $index }} ? null : {{ $index }})">
                                    <td class="px-3 py-3 text-center">
                                        @if($index == 0 && !request('q') && !request('disiplin_range') && $res->total_attendances > 0)
                                            <div class="w-7 h-7 rounded-full bg-yellow-100 dark:bg-yellow-950/60 text-yellow-600 dark:text-yellow-400 flex items-center justify-center mx-auto shadow-sm ring-2 ring-yellow-200 dark:ring-yellow-800/50">
                                                <i class="fas fa-crown text-xs"></i>
                                            </div>
                                        @elseif($index == 1 && !request('q') && !request('disiplin_range') && $res->total_attendances > 0)
                                            <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 flex items-center justify-center mx-auto border border-gray-300 dark:border-gray-700 font-bold text-xs">2</div>
                                        @elseif($index == 2 && !request('q') && !request('disiplin_range') && $res->total_attendances > 0)
                                            <div class="w-7 h-7 rounded-full bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-400 flex items-center justify-center mx-auto border border-orange-200 dark:border-orange-800/50 font-bold text-xs">3</div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 font-bold text-xs">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-950 dark:to-teal-900 flex items-center justify-center text-teal-700 dark:text-teal-300 font-black border border-teal-200/50 dark:border-teal-800/50 text-xs flex-shrink-0">
                                                {{ strtoupper(substr($res->nama_dinas, 0, 2)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-bold text-gray-900 dark:text-gray-100 truncate" title="{{ $res->nama_dinas }}">{{ $res->nama_dinas }}</div>
                                                <div class="text-[10px] text-gray-500 dark:text-gray-400 font-bold mt-0.5 flex items-center gap-1">
                                                    <i class="far fa-clock text-gray-400"></i> Jam Masuk: <span class="text-gray-600 dark:text-gray-300">{{ $res->jam_mulai_masuk ?: '08:00:00' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-full font-bold text-xs inline-block">
                                            <strong>{{ $res->total_attendances }}</strong> <span class="text-[10px] text-gray-400 dark:text-gray-500 font-normal">Entri</span>
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        @if($res->total_pelanggaran > 0)
                                            <div class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 dark:bg-red-950/40 border border-red-100 dark:border-red-900/40 rounded-full">
                                                <span class="text-red-600 dark:text-red-400 font-bold text-xs">{{ $res->total_pelanggaran }}</span>
                                                <span class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">({{ $res->total_terlambat }} Telat, {{ $res->total_alpa }} Alpa)</span>
                                            </div>
                                        @else
                                            <span class="px-2.5 py-1 bg-green-50 dark:bg-green-950/40 border border-green-100 dark:border-green-900/40 text-green-600 dark:text-green-400 font-bold rounded-full inline-flex items-center justify-center gap-1 text-xs">
                                                <i class="fas fa-check-circle"></i> Nihil
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="flex flex-col gap-1 items-center">
                                            @php
                                                $barColor = 'bg-red-500';
                                                $textColor = 'text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-900/40';
                                                if ($res->tingkat_disiplin >= 90) {
                                                    $barColor = 'bg-green-500';
                                                    $textColor = 'text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-950/50 border border-green-200 dark:border-green-900/40';
                                                } elseif ($res->tingkat_disiplin >= 70) {
                                                    $barColor = 'bg-blue-500';
                                                    $textColor = 'text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-900/40';
                                                }
                                            @endphp
                                            <div class="w-20 bg-gray-100 dark:bg-gray-900 h-2 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                                                <div class="{{ $barColor }} h-2 rounded-full" style="width: {{ $res->tingkat_disiplin }}%"></div>
                                            </div>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-black {{ $textColor }}">
                                                {{ number_format($res->tingkat_disiplin, 1) }}%
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <div class="w-8 h-8 rounded-full bg-gray-50 dark:bg-gray-900 flex items-center justify-center mx-auto border border-gray-100 dark:border-gray-700">
                                            <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 text-xs transition-transform duration-200" :class="openRow === {{ $index }} ? 'rotate-180 text-teal-500 dark:text-teal-400' : ''"></i>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Expanded detail row --}}
                                <tr x-show="openRow === {{ $index }}" x-transition.opacity x-cloak>
                                    <td colspan="6" class="px-4 py-4 bg-gray-50 dark:bg-gray-900/50 border-y border-gray-100 dark:border-gray-700">
                                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 sm:p-5 shadow-sm space-y-4">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b pb-2 border-gray-100 dark:border-gray-700 gap-2">
                                                <h4 class="text-[10px] font-black text-gray-400 dark:text-gray-400 uppercase tracking-wider flex items-center gap-2">
                                                    <i class="fas fa-user-clock text-teal-500 dark:text-teal-400"></i> Detail Absensi Dinas & Pelanggaran
                                                </h4>
                                                <span class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase bg-gray-50 dark:bg-gray-900 px-3 py-1 rounded-full border border-gray-200 dark:border-gray-700">
                                                    Hadir: {{ $res->total_hadir }} | Sakit: {{ $res->total_sakit }} | Izin: {{ $res->total_izin }}
                                                </span>
                                            </div>
                                            
                                            <div class="space-y-2">
                                                <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Peserta dengan Pelanggaran Kehadiran:</div>
                                                
                                                @if(count($res->pelanggar_list) > 0)
                                                    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                                                        <table class="w-full divide-y divide-gray-100 dark:divide-gray-700 text-xs text-left">
                                                            <thead class="bg-gray-50 dark:bg-gray-900">
                                                                <tr>
                                                                    <th class="px-4 py-2.5 font-bold text-gray-500 dark:text-gray-400 uppercase">Nama Peserta / Asal</th>
                                                                    <th class="px-4 py-2.5 font-bold text-gray-500 dark:text-gray-400 uppercase">Posisi</th>
                                                                    <th class="px-4 py-2.5 text-center font-bold text-gray-500 dark:text-gray-400 uppercase w-24">Terlambat</th>
                                                                    <th class="px-4 py-2.5 text-center font-bold text-gray-500 dark:text-gray-400 uppercase w-24">Alpa</th>
                                                                    <th class="px-4 py-2.5 text-center font-bold text-gray-500 dark:text-gray-400 uppercase w-32">Total Pelanggaran</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-50 dark:divide-gray-700/60 bg-white dark:bg-gray-800">
                                                                @foreach($res->pelanggar_list as $p)
                                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                                                    <td class="px-4 py-2.5 font-bold text-gray-800 dark:text-gray-200">
                                                                        {{ $p['nama'] }}
                                                                        <div class="text-[9px] text-gray-500 dark:text-gray-400 font-normal mt-0.5">{{ $p['kampus'] }}</div>
                                                                    </td>
                                                                    <td class="px-4 py-2.5 text-gray-600 dark:text-gray-400 font-medium">{{ $p['posisi'] }}</td>
                                                                    <td class="px-4 py-2.5 text-center text-orange-600 dark:text-orange-400 font-bold">{{ $p['terlambat'] }}x</td>
                                                                    <td class="px-4 py-2.5 text-center text-red-500 dark:text-red-400 font-bold">{{ $p['alpa'] }}x</td>
                                                                    <td class="px-4 py-2.5 text-center font-black text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-900/50">
                                                                        {{ $p['terlambat'] + $p['alpa'] }}x
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="text-center py-6 text-gray-500 flex flex-col items-center justify-center">
                                                        <i class="fas fa-check-circle text-green-500 dark:text-green-400 text-3xl mb-2"></i>
                                                        <p class="font-bold text-gray-800 dark:text-gray-200">Tidak Ada Pelanggaran Kehadiran</p>
                                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Semua entri absensi masuk tepat waktu dan nihil alpa.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 text-gray-300 dark:text-gray-600 border border-transparent dark:border-gray-700">
                                                <i class="fas fa-search text-2xl"></i>
                                            </div>
                                            <p class="text-gray-900 dark:text-gray-100 font-bold">Data tidak ditemukan</p>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Coba sesuaikan filter pencarian Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
