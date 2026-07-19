<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-building text-teal-600"></i>
                {{ __('Analisis Kedisiplinan Instansi') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400 font-medium bg-white dark:bg-gray-800 px-4 py-1.5 rounded-full shadow-sm border border-gray-100 dark:border-gray-700">
                Total Terfilter: <span class="font-bold text-teal-600">{{ $stats['total_instansi'] }}</span> Instansi
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center print:hidden">
                <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-650 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Pusat Laporan
                </a>
                
                @if($stats['total_instansi'] > 0)
                <div class="flex gap-2">
                    <a href="{{ route('admin.laporan.instansi_disiplin.print', array_merge(request()->query(), ['format' => 'pdf'])) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download PDF">
                        <i class="fas fa-file-pdf text-red-500"></i> <span class="hidden sm:inline">PDF</span>
                    </a>
                    <a href="{{ route('admin.laporan.instansi_disiplin.print', array_merge(request()->query(), ['format' => 'excel'])) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download Excel">
                        <i class="fas fa-file-excel text-green-600"></i> <span class="hidden sm:inline">Excel</span>
                    </a>
                    <a href="{{ route('admin.laporan.instansi_disiplin.print', array_merge(request()->query(), ['format' => 'csv'])) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download CSV">
                        <i class="fas fa-file-csv text-blue-600"></i> <span class="hidden sm:inline">CSV</span>
                    </a>
                </div>
                @endif
            </div>

            {{-- 6 Stats Cards Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-8 h-8 rounded-full bg-teal-50 text-teal-650 flex items-center justify-center mx-auto mb-2 border border-teal-100">
                        <i class="fas fa-building text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-gray-800 dark:text-gray-200">{{ $stats['total_instansi'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Total Instansi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center bg-gradient-to-br from-teal-50/50 to-indigo-50/30">
                    <div class="w-8 h-8 rounded-full bg-teal-600 text-white flex items-center justify-center mx-auto mb-2 shadow-sm shadow-teal-200">
                        <i class="fas fa-percentage text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-teal-700">{{ $stats['avg_disiplin'] }}%</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Rerata Disiplin</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-2 border border-blue-100">
                        <i class="fas fa-calendar-check text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-blue-700">{{ $stats['total_kehadiran'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Total Absensi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-8 h-8 rounded-full bg-red-50 text-red-650 flex items-center justify-center mx-auto mb-2 border border-red-100">
                        <i class="fas fa-exclamation-triangle text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-red-700">{{ $stats['total_pelanggaran'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Total Pelanggaran</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-8 h-8 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center mx-auto mb-2 border border-orange-100">
                        <i class="fas fa-user-clock text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-orange-700">{{ $stats['total_terlambat'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Total Terlambat</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-700 flex items-center justify-center mx-auto mb-2 border border-amber-100">
                        <i class="fas fa-user-times text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-amber-800">{{ $stats['total_alpa'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Total Alpa</p>
                </div>
            </div>

            {{-- Top 3 Podium Leaderboard --}}
            @if($podium->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="text-center mb-6">
                    <h3 class="text-lg font-black text-gray-800 dark:text-gray-200 flex items-center justify-center gap-2">
                        <i class="fas fa-award text-yellow-500 animate-bounce"></i> TOP 3 INSTANSI PALING DISIPLIN
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Daftar instansi aktif dengan persentase ketepatan waktu absensi peserta terbaik se-Banjarmasin.</p>
                </div>
                
                <div class="flex flex-col md:flex-row items-end justify-center gap-6 md:gap-4 max-w-4xl mx-auto pt-6">
                    
                    {{-- Juara 2 (Perak) --}}
                    @if($podium->count() > 1)
                    @php $p2 = $podium[1]; @endphp
                    <div class="w-full md:w-1/3 order-2 md:order-1 flex flex-col items-center">
                        <div class="relative mb-3 flex flex-col items-center">
                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-gray-100 to-gray-300 flex items-center justify-center text-gray-700 dark:text-gray-300 font-black border-4 border-white shadow-md text-lg relative">
                                {{ strtoupper(substr($p2->nama_dinas, 0, 2)) }}
                                <span class="absolute -top-3 -right-1 w-6 h-6 rounded-full bg-gray-400 text-white text-[10px] font-bold flex items-center justify-center border-2 border-white shadow">2</span>
                            </div>
                        </div>
                        <div class="text-center mb-2">
                            <p class="font-bold text-gray-800 dark:text-gray-200 text-sm truncate max-w-[200px]" title="{{ $p2->nama_dinas }}">{{ $p2->nama_dinas }}</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold">{{ $p2->total_attendances }} Kehadiran</p>
                            <p class="text-[9px] text-red-500 bg-red-50 px-2 py-0.5 rounded-full inline-block font-bold mt-1">{{ $p2->total_pelanggaran }} Pelanggaran</p>
                        </div>
                        <div class="w-full bg-gradient-to-t from-gray-100 to-gray-200/50 rounded-t-2xl pt-8 pb-4 text-center border-t border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-center items-center h-28">
                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">KEDISIPLINAN</span>
                            <span class="text-2xl font-black text-gray-750 mt-1">{{ number_format($p2->tingkat_disiplin, 1) }}%</span>
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
                            <div class="h-20 w-20 rounded-full bg-gradient-to-br from-yellow-300 to-yellow-500 flex items-center justify-center text-white font-black border-4 border-white shadow-lg text-xl relative">
                                {{ strtoupper(substr($p1->nama_dinas, 0, 2)) }}
                                <span class="absolute -top-1 -right-1 w-7 h-7 rounded-full bg-yellow-500 text-white text-[11px] font-black flex items-center justify-center border-2 border-white shadow">1</span>
                            </div>
                        </div>
                        <div class="text-center mb-2">
                            <p class="font-black text-gray-950 text-base truncate max-w-[220px]" title="{{ $p1->nama_dinas }}">{{ $p1->nama_dinas }}</p>
                            <p class="text-xs text-gray-650 font-bold">{{ $p1->total_attendances }} Kehadiran</p>
                            <p class="text-[10px] text-red-650 bg-red-50 px-2.5 py-0.5 rounded-full inline-block font-extrabold mt-1 border border-red-150">{{ $p1->total_pelanggaran }} Pelanggaran</p>
                        </div>
                        <div class="w-full bg-gradient-to-t from-yellow-50 to-yellow-100 rounded-t-2xl pt-10 pb-6 text-center border-t-2 border-yellow-300 shadow flex flex-col justify-center items-center h-36">
                            <span class="text-xs font-black text-yellow-750 uppercase tracking-wider">KEDISIPLINAN</span>
                            <span class="text-3xl font-black text-yellow-700 mt-1">{{ number_format($p1->tingkat_disiplin, 1) }}%</span>
                        </div>
                    </div>
                    @endif

                    {{-- Juara 3 (Perunggu) --}}
                    @if($podium->count() > 2)
                    @php $p3 = $podium[2]; @endphp
                    <div class="w-full md:w-1/3 order-3 md:order-3 flex flex-col items-center">
                        <div class="relative mb-3 flex flex-col items-center">
                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-amber-600 to-amber-800/80 flex items-center justify-center text-white font-black border-4 border-white shadow-md text-lg relative">
                                {{ strtoupper(substr($p3->nama_dinas, 0, 2)) }}
                                <span class="absolute -top-3 -right-1 w-6 h-6 rounded-full bg-amber-600 text-white text-[10px] font-bold flex items-center justify-center border-2 border-white shadow">3</span>
                            </div>
                        </div>
                        <div class="text-center mb-2">
                            <p class="font-bold text-gray-800 dark:text-gray-200 text-sm truncate max-w-[200px]" title="{{ $p3->nama_dinas }}">{{ $p3->nama_dinas }}</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold">{{ $p3->total_attendances }} Kehadiran</p>
                            <p class="text-[9px] text-red-500 bg-red-50 px-2 py-0.5 rounded-full inline-block font-bold mt-1">{{ $p3->total_pelanggaran }} Pelanggaran</p>
                        </div>
                        <div class="w-full bg-gradient-to-t from-orange-50/50 to-orange-100/30 rounded-t-2xl pt-8 pb-4 text-center border-t border-orange-200 shadow-sm flex flex-col justify-center items-center h-24">
                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">KEDISIPLINAN</span>
                            <span class="text-xl font-black text-amber-700 mt-1">{{ number_format($p3->tingkat_disiplin, 1) }}%</span>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            @endif

            {{-- Highlight Banner --}}
            <div class="bg-gradient-to-r from-teal-700 to-indigo-700 rounded-3xl p-6 text-white shadow-lg shadow-teal-700/20 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white dark:bg-gray-800/20 backdrop-blur-sm flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="text-center sm:text-left flex-grow">
                    <p class="text-xs font-bold uppercase tracking-wider text-teal-100">Laporan Kedisiplinan & Kepatuhan Absensi</p>
                    <p class="text-xl font-black mt-0.5">Total {{ $stats['total_instansi'] }} Dinas Instansi</p>
                    <p class="text-sm text-teal-50 font-medium">Berdasarkan rasio keterlambatan (melebihi jam masuk masing-masing dinas) dan ketidakhadiran (alpa).</p>
                </div>
                @if($instansis->count() > 0)
                <div class="sm:ml-auto flex-shrink-0">
                    <a href="{{ route('admin.laporan.instansi_disiplin.print', request()->query()) }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-white dark:bg-gray-800 text-teal-700 rounded-xl hover:bg-teal-50 transition text-sm font-bold shadow-md">
                        <i class="fas fa-file-pdf mr-2"></i> Download PDF
                    </a>
                </div>
                @endif
            </div>

            <div class="flex flex-col lg:flex-row gap-6 items-start">
                
                {{-- Left Side: Filters --}}
                <div class="w-full lg:w-1/4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 print:hidden lg:sticky lg:top-8">
                    <div class="mb-5 border-b border-gray-100 dark:border-gray-700 pb-3 flex items-center justify-between">
                        <h3 class="text-gray-800 dark:text-gray-200 font-bold text-sm uppercase tracking-wide flex items-center">
                            <i class="fas fa-filter mr-2 text-teal-500"></i> Filter Laporan
                        </h3>
                        @if(request()->anyFilled(['q', 'disiplin_range']))
                            <a href="{{ route('admin.laporan.instansi_disiplin') }}" class="text-xs text-red-500 hover:text-red-700 font-bold">Reset</a>
                        @endif
                    </div>
                    
                    <form method="GET" action="{{ route('admin.laporan.instansi_disiplin') }}" class="flex flex-col gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Nama Instansi</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                    <i class="fas fa-search text-xs"></i>
                                </span>
                                <input type="text" name="q" value="{{ request('q') }}" 
                                    placeholder="Cari dinas/badan..."
                                    class="w-full pl-9 border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Kategori Kedisiplinan</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                    <i class="fas fa-percentage text-xs"></i>
                                </span>
                                <select name="disiplin_range" class="w-full pl-9 border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-white dark:hover:bg-gray-800 transition">
                                    <option value="">Semua Kategori</option>
                                    <option value="sangat" {{ request('disiplin_range') == 'sangat' ? 'selected' : '' }}>Sangat Disiplin (>= 90%)</option>
                                    <option value="cukup" {{ request('disiplin_range') == 'cukup' ? 'selected' : '' }}>Cukup Disiplin (70% - 89%)</option>
                                    <option value="kurang" {{ request('disiplin_range') == 'kurang' ? 'selected' : '' }}>Kurang Disiplin (< 70%)</option>
                                </select>
                            </div>
                        </div>

                        <x-primary-button class="w-full mt-2 justify-center">
                            <i class="fas fa-search mr-2"></i> Terapkan Filter
                        </x-primary-button>
                    </form>
                </div>

                {{-- Right Side: Table --}}
                <div class="w-full lg:w-3/4 space-y-6" x-data="{ openRow: null }">
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                            <div>
                                <h3 class="font-bold text-gray-800 dark:text-gray-200">Daftar Pemeringkatan Kepatuhan Instansi</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Instansi dengan tingkat kepatuhan terurut dari tertinggi ke terendah.</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-100 border-collapse">
                                <thead class="bg-gray-50 dark:bg-gray-900 sticky top-0 z-20 shadow-[inset_0_-1px_0_rgba(229,231,235,1)]">
                                    <tr>
                                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16 whitespace-nowrap">Rank</th>
                                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap min-w-[200px]">Instansi</th>
                                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28 whitespace-nowrap min-w-[150px]">Total Kehadiran</th>
                                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-36 whitespace-nowrap min-w-[200px]">Pelanggaran (Telat/Alpa)</th>
                                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-36 whitespace-nowrap min-w-[150px]">Tingkat Disiplin</th>
                                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12 whitespace-nowrap">Detail</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-50 text-sm">
                                    @forelse($instansis as $index => $res)
                                    <tr class="hover:bg-teal-50/15 transition group cursor-pointer" @click="openRow = (openRow === {{ $index }} ? null : {{ $index }})">
                                        <td class="px-5 py-4 text-center">
                                            @if($index == 0 && !request('q') && !request('disiplin_range') && $res->total_attendances > 0)
                                                <div class="w-7 h-7 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mx-auto shadow-sm ring-2 ring-yellow-200">
                                                    <i class="fas fa-crown text-xs"></i>
                                                </div>
                                            @elseif($index == 1 && !request('q') && !request('disiplin_range') && $res->total_attendances > 0)
                                                <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 flex items-center justify-center mx-auto border border-gray-300 dark:border-gray-600 font-bold text-xs">2</div>
                                            @elseif($index == 2 && !request('q') && !request('disiplin_range') && $res->total_attendances > 0)
                                                <div class="w-7 h-7 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center mx-auto border border-orange-200 font-bold text-xs">3</div>
                                            @else
                                                <span class="text-gray-400 font-bold text-xs">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-teal-50 to-teal-100 flex items-center justify-center text-teal-700 font-black border border-teal-200/50 text-xs flex-shrink-0">
                                                    {{ strtoupper(substr($res->nama_dinas, 0, 2)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="font-bold text-gray-900 dark:text-gray-100 truncate" title="{{ $res->nama_dinas }}">{{ $res->nama_dinas }}</div>
                                                    <div class="text-[9px] text-gray-405 font-bold mt-0.5">
                                                        Jam Masuk: <span class="text-gray-600 dark:text-gray-400">{{ $res->jam_mulai_masuk ?: '08:00:00' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-center whitespace-nowrap text-gray-700 dark:text-gray-300">
                                            <strong>{{ $res->total_attendances }}</strong> <span class="text-[10px] text-gray-400">Entri</span>
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            @if($res->total_pelanggaran > 0)
                                                <span class="text-red-600 font-bold">{{ $res->total_pelanggaran }}</span>
                                                <span class="text-[10px] text-gray-400">({{ $res->total_terlambat }} Telat, {{ $res->total_alpa }} Alpa)</span>
                                            @else
                                                <span class="text-green-600 font-bold flex items-center justify-center gap-1 text-xs">
                                                    <i class="fas fa-check-circle"></i> Nihil
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex flex-col gap-1 items-center">
                                                @php
                                                    $barColor = 'bg-red-500';
                                                    $textColor = 'text-red-700 bg-red-50';
                                                    if ($res->tingkat_disiplin >= 90) {
                                                        $barColor = 'bg-green-500';
                                                        $textColor = 'text-green-700 bg-green-50';
                                                    } elseif ($res->tingkat_disiplin >= 70) {
                                                        $barColor = 'bg-blue-500';
                                                        $textColor = 'text-blue-700 bg-blue-50';
                                                    }
                                                @endphp
                                                <div class="w-24 bg-gray-100 dark:bg-gray-800 h-2 rounded-full overflow-hidden">
                                                    <div class="{{ $barColor }} h-2 rounded-full" style="width: {{ $res->tingkat_disiplin }}%"></div>
                                                </div>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-black {{ $textColor }}">
                                                    {{ number_format($res->tingkat_disiplin, 1) }}%
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" :class="openRow === {{ $index }} ? 'rotate-180 text-teal-500' : ''"></i>
                                        </td>
                                    </tr>

                                    {{-- Expanded detail row --}}
                                    <tr x-show="openRow === {{ $index }}" x-transition.opacity x-cloak>
                                        <td colspan="6" class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-y border-gray-100 dark:border-gray-700">
                                            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-250/60 p-5 shadow-sm space-y-4">
                                                <div class="flex justify-between items-center border-b pb-2 border-gray-150">
                                                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider flex items-center gap-2">
                                                        <i class="fas fa-user-clock text-teal-500"></i> Detail Absensi Dinas & Pelanggaran
                                                    </h4>
                                                    <span class="text-[10px] text-gray-400 font-bold uppercase">
                                                        Hadir: {{ $res->total_hadir }} | Sakit: {{ $res->total_sakit }} | Izin: {{ $res->total_izin }}
                                                    </span>
                                                </div>
                                                
                                                <div class="space-y-2">
                                                    <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Peserta dengan Pelanggaran Kehadiran:</div>
                                                    
                                                    @if(count($res->pelanggar_list) > 0)
                                                        <div class="overflow-x-auto rounded-xl border border-gray-150">
                                                            <table class="min-w-full divide-y divide-gray-100 text-xs text-left">
                                                                <thead class="bg-gray-50 dark:bg-gray-900">
                                                                    <tr>
                                                                        <th class="px-4 py-2 font-bold text-gray-500 dark:text-gray-400 uppercase whitespace-nowrap min-w-[150px]">Nama Peserta / Asal</th>
                                                                        <th class="px-4 py-2 font-bold text-gray-500 dark:text-gray-400 uppercase whitespace-nowrap min-w-[150px]">Posisi</th>
                                                                        <th class="px-4 py-2 text-center font-bold text-gray-500 dark:text-gray-400 uppercase w-20 whitespace-nowrap">Terlambat</th>
                                                                        <th class="px-4 py-2 text-center font-bold text-gray-500 dark:text-gray-400 uppercase w-20 whitespace-nowrap">Alpa</th>
                                                                        <th class="px-4 py-2 text-center font-bold text-gray-500 dark:text-gray-400 uppercase w-28 whitespace-nowrap min-w-[150px]">Total Pelanggaran</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="divide-y divide-gray-50 bg-white dark:bg-gray-800">
                                                                    @foreach($res->pelanggar_list as $p)
                                                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                                                        <td class="px-4 py-2.5 font-bold text-gray-800 dark:text-gray-200">
                                                                            {{ $p['nama'] }}
                                                                            <div class="text-[9px] text-gray-450 font-normal mt-0.5">{{ $p['kampus'] }}</div>
                                                                        </td>
                                                                        <td class="px-4 py-2.5 text-gray-600 dark:text-gray-400 font-medium">{{ $p['posisi'] }}</td>
                                                                        <td class="px-4 py-2.5 text-center text-orange-600 font-bold">{{ $p['terlambat'] }}x</td>
                                                                        <td class="px-4 py-2.5 text-center text-red-500 font-bold">{{ $p['alpa'] }}x</td>
                                                                        <td class="px-4 py-2.5 text-center font-black text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-900/50">
                                                                            {{ $p['terlambat'] + $p['alpa'] }}x
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @else
                                                        <div class="text-center py-6 text-gray-450 flex flex-col items-center justify-center">
                                                            <i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i>
                                                            <p class="font-bold text-gray-800 dark:text-gray-200">Tidak Ada Pelanggaran Kehadiran</p>
                                                            <p class="text-[10px] text-gray-400 mt-0.5">Semua entri absensi masuk tepat waktu dan nihil alpa.</p>
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
                                                <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 text-gray-300">
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
    </div>
</x-app-layout>
