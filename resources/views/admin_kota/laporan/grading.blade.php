<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-chart-line text-teal-600 dark:text-teal-400"></i>
                {{ __('Analisis Kompetensi & Performa') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400 font-medium bg-white dark:bg-gray-800 px-4 py-1.5 rounded-full shadow-sm border border-gray-100 dark:border-gray-700">
                Total Terfilter: <span class="font-bold text-teal-600 dark:text-teal-400">{{ $stats['total'] }}</span> Peserta
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
                
                @if($stats['total'] > 0)
                <div class="flex gap-2">
                    <a href="{{ route('admin.laporan.grading.print', array_merge(request()->query(), ['format' => 'pdf'])) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-300 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-teal-950/30 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download PDF">
                        <i class="fas fa-file-pdf text-red-500"></i> <span class="hidden sm:inline">PDF</span>
                    </a>
                    <a href="{{ route('admin.laporan.grading.print', array_merge(request()->query(), ['format' => 'excel'])) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-300 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-teal-950/30 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download Excel">
                        <i class="fas fa-file-excel text-green-600"></i> <span class="hidden sm:inline">Excel</span>
                    </a>
                    <a href="{{ route('admin.laporan.grading.print', array_merge(request()->query(), ['format' => 'csv'])) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-300 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-teal-950/30 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download CSV">
                        <i class="fas fa-file-csv text-blue-600"></i> <span class="hidden sm:inline">CSV</span>
                    </a>
                </div>
                @endif
            </div>

            {{-- 6 Stats Cards Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-xl bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-3 border border-teal-100 dark:border-teal-900/40 shadow-xs">
                        <i class="fas fa-users text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight">{{ $stats['total'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Dinilai</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-xl bg-green-50 dark:bg-green-950/40 text-green-600 dark:text-green-400 flex items-center justify-center mx-auto mb-3 border border-green-100 dark:border-green-900/40 shadow-xs">
                        <i class="fas fa-check-circle text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-green-700 dark:text-green-400 tracking-tight">{{ $stats['sangat_baik'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Sangat Baik</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-3 border border-blue-100 dark:border-blue-900/40 shadow-xs">
                        <i class="fas fa-thumbs-up text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-blue-700 dark:text-blue-400 tracking-tight">{{ $stats['baik'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Baik</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-xl bg-yellow-50 dark:bg-yellow-950/40 text-yellow-600 dark:text-yellow-400 flex items-center justify-center mx-auto mb-3 border border-yellow-100 dark:border-yellow-900/40 shadow-xs">
                        <i class="fas fa-info-circle text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-yellow-600 dark:text-yellow-400 tracking-tight">{{ $stats['cukup'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Cukup</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-xl bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 flex items-center justify-center mx-auto mb-3 border border-red-100 dark:border-red-900/40 shadow-xs">
                        <i class="fas fa-times-circle text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-red-600 dark:text-red-400 tracking-tight">{{ $stats['kurang'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Kurang</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition bg-gradient-to-br from-teal-50/50 via-white to-indigo-50/30 dark:from-teal-950/20 dark:via-gray-800 dark:to-indigo-950/20">
                    <div class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center mx-auto mb-3 shadow-sm shadow-teal-500/20">
                        <i class="fas fa-star text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-teal-700 dark:text-teal-400 tracking-tight">{{ $stats['avg_nilai'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Rerata Nilai</p>
                </div>
            </div>

            {{-- 3-Component Rerata Mini Panel --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-laptop-code text-blue-500 dark:text-blue-400"></i> Rerata Teknis
                        </span>
                        <span class="text-sm font-black text-gray-800 dark:text-gray-200">{{ $statsGlobal['avg_teknis'] }}/100</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-900 h-2 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $statsGlobal['avg_teknis'] }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-calendar-check text-green-500 dark:text-green-400"></i> Rerata Disiplin
                        </span>
                        <span class="text-sm font-black text-gray-800 dark:text-gray-200">{{ $statsGlobal['avg_disiplin'] }}/100</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-900 h-2 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $statsGlobal['avg_disiplin'] }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-user-friends text-purple-500 dark:text-purple-400"></i> Rerata Perilaku
                        </span>
                        <span class="text-sm font-black text-gray-800 dark:text-gray-200">{{ $statsGlobal['avg_perilaku'] }}/100</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-900 h-2 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $statsGlobal['avg_perilaku'] }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Collapsible Top 3 Podium Leaderboard --}}
            @if($podium->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition-all duration-300" x-data="{ showTop3: false }">
                <div class="flex items-center justify-between cursor-pointer select-none" @click="showTop3 = !showTop3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-yellow-100 dark:bg-yellow-950/60 text-yellow-600 dark:text-yellow-400 flex items-center justify-center shadow-xs">
                            <i class="fas fa-trophy text-lg animate-pulse"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                TOP 3 PERFORMER TERBAIK KOTA
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Apresiasi khusus untuk peserta magang dengan pencapaian performa tertinggi se-Kota Banjarmasin.</p>
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
                                    {{ strtoupper(substr($p2['nama'], 0, 2)) }}
                                    <span class="absolute -top-3 -right-1 w-6 h-6 rounded-full bg-gray-400 text-white text-[10px] font-bold flex items-center justify-center border-2 border-white dark:border-gray-800 shadow">2</span>
                                </div>
                            </div>
                            <div class="text-center mb-2">
                                <p class="font-bold text-gray-800 dark:text-gray-200 text-sm truncate max-w-[180px]">{{ $p2['nama'] }}</p>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate max-w-[180px] font-semibold">{{ $p2['asal_instansi'] }}</p>
                                <p class="text-[9px] text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/50 border border-teal-100 dark:border-teal-900/40 px-2 py-0.5 rounded-full inline-block font-bold mt-1">{{ $p2['instansi'] }}</p>
                            </div>
                            <div class="w-full bg-gradient-to-t from-gray-100 to-gray-200/50 dark:from-gray-900 dark:to-gray-800/80 rounded-t-2xl pt-8 pb-4 text-center border-t border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-center items-center h-28">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">SKOR AKHIR</span>
                                <span class="text-2xl font-black text-gray-700 dark:text-gray-200 mt-1">{{ $p2['rata_rata'] }}</span>
                                <span class="text-[9px] font-extrabold text-gray-500 dark:text-gray-400 mt-1 uppercase">{{ $p2['predikat'] }}</span>
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
                                    {{ strtoupper(substr($p1['nama'], 0, 2)) }}
                                    <span class="absolute -top-1 -right-1 w-7 h-7 rounded-full bg-yellow-500 text-white text-[11px] font-black flex items-center justify-center border-2 border-white dark:border-gray-800 shadow">1</span>
                                </div>
                            </div>
                            <div class="text-center mb-2">
                                <p class="font-black text-gray-900 dark:text-gray-100 text-base truncate max-w-[200px]">{{ $p1['nama'] }}</p>
                                <p class="text-xs text-gray-700 dark:text-gray-300 truncate max-w-[200px] font-bold">{{ $p1['asal_instansi'] }}</p>
                                <p class="text-[10px] text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/50 border border-teal-200 dark:border-teal-900/40 px-2.5 py-0.5 rounded-full inline-block font-extrabold mt-1">{{ $p1['instansi'] }}</p>
                            </div>
                            <div class="w-full bg-gradient-to-t from-yellow-50 to-yellow-100 dark:from-yellow-950/40 dark:to-yellow-900/20 rounded-t-2xl pt-10 pb-6 text-center border-t-2 border-yellow-300 dark:border-yellow-700 shadow flex flex-col justify-center items-center h-36">
                                <span class="text-xs font-black text-yellow-800 dark:text-yellow-300 uppercase tracking-wider">SKOR AKHIR</span>
                                <span class="text-3xl font-black text-yellow-700 dark:text-yellow-400 mt-1">{{ $p1['rata_rata'] }}</span>
                                <span class="text-[10px] font-black text-yellow-600 dark:text-yellow-300 mt-1 uppercase">{{ $p1['predikat'] }}</span>
                            </div>
                        </div>
                        @endif

                        {{-- Juara 3 (Perunggu) --}}
                        @if($podium->count() > 2)
                        @php $p3 = $podium[2]; @endphp
                        <div class="w-full md:w-1/3 order-3 md:order-3 flex flex-col items-center">
                            <div class="relative mb-3 flex flex-col items-center">
                                <div class="h-16 w-16 rounded-full bg-gradient-to-br from-amber-600 to-amber-800/80 flex items-center justify-center text-white font-black border-4 border-white dark:border-gray-700 shadow-md text-lg relative">
                                    {{ strtoupper(substr($p3['nama'], 0, 2)) }}
                                    <span class="absolute -top-3 -right-1 w-6 h-6 rounded-full bg-amber-600 text-white text-[10px] font-bold flex items-center justify-center border-2 border-white dark:border-gray-800 shadow">3</span>
                                </div>
                            </div>
                            <div class="text-center mb-2">
                                <p class="font-bold text-gray-800 dark:text-gray-200 text-sm truncate max-w-[180px]">{{ $p3['nama'] }}</p>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate max-w-[180px] font-semibold">{{ $p3['asal_instansi'] }}</p>
                                <p class="text-[9px] text-teal-600 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/50 border border-teal-100 dark:border-teal-900/40 px-2 py-0.5 rounded-full inline-block font-bold mt-1">{{ $p3['instansi'] }}</p>
                            </div>
                            <div class="w-full bg-gradient-to-t from-orange-50/50 to-orange-100/30 dark:from-orange-950/40 dark:to-orange-900/20 rounded-t-2xl pt-8 pb-4 text-center border-t border-orange-200 dark:border-orange-900/40 shadow-sm flex flex-col justify-center items-center h-24">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">SKOR AKHIR</span>
                                <span class="text-xl font-black text-amber-700 dark:text-amber-400 mt-1">{{ $p3['rata_rata'] }}</span>
                                <span class="text-[9px] font-extrabold text-orange-700 dark:text-orange-400 mt-1 uppercase">{{ $p3['predikat'] }}</span>
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
                    <i class="fas fa-award"></i>
                </div>
                <div class="text-center sm:text-left flex-grow">
                    <p class="text-xs font-bold uppercase tracking-wider text-teal-100">Rekapitulasi Penilaian & Kompetensi Global</p>
                    <p class="text-xl font-black mt-0.5">Total {{ $stats['total'] }} Peserta Dinilai</p>
                    <p class="text-sm text-teal-50/90 font-medium">Data performa mencakup sebaran nilai berdasarkan parameter teknis, disiplin, dan soft skill.</p>
                </div>
            </div>

            {{-- Top Filter Laporan Card --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 print:hidden">
                <div class="mb-4 pb-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-gray-800 dark:text-gray-200 font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                        <i class="fas fa-filter text-teal-500 dark:text-teal-400"></i> Filter Laporan Analisis
                    </h3>
                    @if(request()->anyFilled(['q', 'instansi', 'instansi_id', 'predikat']))
                        <a href="{{ route('admin.laporan.grading') }}" class="text-xs text-red-500 dark:text-red-400 hover:text-red-700 font-bold flex items-center gap-1">
                            <i class="fas fa-undo text-[10px]"></i> Reset Filter
                        </a>
                    @endif
                </div>
                
                <form method="GET" action="{{ route('admin.laporan.grading') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Nama Peserta</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 pointer-events-none">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" name="q" value="{{ request('q') }}" 
                                placeholder="Cari nama peserta..."
                                class="w-full pl-9 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Asal Kampus / Sekolah</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 pointer-events-none">
                                <i class="fas fa-university text-xs"></i>
                            </span>
                            <select name="instansi" class="w-full pl-9 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 hover:bg-white dark:hover:bg-gray-800 transition">
                                <option value="">Semua Kampus</option>
                                @foreach($listCampus as $campus)
                                    <option value="{{ $campus }}" {{ request('instansi') == $campus ? 'selected' : '' }}>
                                        {{ Str::limit($campus, 25) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Lokasi Penempatan Dinas</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 pointer-events-none">
                                <i class="fas fa-building text-xs"></i>
                            </span>
                            <select name="instansi_id" class="w-full pl-9 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 hover:bg-white dark:hover:bg-gray-800 transition">
                                <option value="">Semua Lokasi Dinas</option>
                                @foreach($listDinas as $dinas)
                                    <option value="{{ $dinas->id }}" {{ request('instansi_id') == $dinas->id ? 'selected' : '' }}>
                                        {{ Str::limit($dinas->nama_dinas, 25) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Predikat Kelulusan</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 pointer-events-none">
                                <i class="fas fa-award text-xs"></i>
                            </span>
                            <select name="predikat" class="w-full pl-9 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 hover:bg-white dark:hover:bg-gray-800 transition">
                                <option value="">Semua Predikat</option>
                                <option value="Sangat Baik" {{ request('predikat') == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik (>= 86)</option>
                                <option value="Baik" {{ request('predikat') == 'Baik' ? 'selected' : '' }}>Baik (71 - 85)</option>
                                <option value="Cukup" {{ request('predikat') == 'Cukup' ? 'selected' : '' }}>Cukup (56 - 70)</option>
                                <option value="Kurang" {{ request('predikat') == 'Kurang' ? 'selected' : '' }}>Kurang (< 56)</option>
                            </select>
                        </div>
                    </div>

                    <div class="md:col-span-2 lg:col-span-4 flex justify-end gap-2 pt-2">
                        <button type="submit" class="bg-teal-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-teal-500/20 hover:bg-teal-700 transition transform active:scale-95 text-sm flex items-center gap-2">
                            <i class="fas fa-search"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- Main Table Card (100% Fluid Width without Horizontal Scroll) --}}
            <div class="w-full space-y-6" x-data="{ openRow: null }">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-gray-200">Daftar Analisis Kompetensi & Performa</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Daftar peringkat berdasarkan nilai rata-rata akhir peserta terfilter.</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto max-h-[650px] overflow-y-auto">
                        <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900 sticky top-0 z-20">
                                <tr>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">Rank</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Peserta & Asal Sekolah</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Penempatan Dinas & Posisi</th>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-24">Skor Akhir</th>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28">Predikat</th>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-14">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-50 dark:divide-gray-700/60 text-sm">
                                @forelse($gradedList as $index => $res)
                                <tr class="hover:bg-teal-50/15 dark:hover:bg-gray-900/60 transition group cursor-pointer" @click="openRow = (openRow === {{ $index }} ? null : {{ $index }})">
                                    <td class="px-3 py-3 text-center">
                                        @if($index == 0 && !request('q') && !request('instansi') && !request('instansi_id') && !request('predikat'))
                                            <div class="w-7 h-7 rounded-full bg-yellow-100 dark:bg-yellow-950/60 text-yellow-600 dark:text-yellow-400 flex items-center justify-center mx-auto shadow-sm ring-2 ring-yellow-200 dark:ring-yellow-800/50">
                                                <i class="fas fa-crown text-xs"></i>
                                            </div>
                                        @elseif($index == 1 && !request('q') && !request('instansi') && !request('instansi_id') && !request('predikat'))
                                            <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 flex items-center justify-center mx-auto border border-gray-300 dark:border-gray-700 font-bold text-xs">2</div>
                                        @elseif($index == 2 && !request('q') && !request('instansi') && !request('instansi_id') && !request('predikat'))
                                            <div class="w-7 h-7 rounded-full bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-400 flex items-center justify-center mx-auto border border-orange-200 dark:border-orange-800/50 font-bold text-xs">3</div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 font-bold text-xs">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-950 dark:to-teal-900 flex items-center justify-center text-teal-700 dark:text-teal-300 font-black border border-teal-200/50 dark:border-teal-800/50 text-xs flex-shrink-0">
                                                {{ strtoupper(substr($res['nama'], 0, 2)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-bold text-gray-900 dark:text-gray-100 truncate">{{ $res['nama'] }}</div>
                                                <div class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold truncate flex items-center mt-0.5">
                                                    <i class="fas fa-university mr-1.5 text-gray-400 dark:text-gray-500"></i> {{ $res['asal_instansi'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-col gap-0.5 min-w-0">
                                            <span class="font-bold text-gray-800 dark:text-gray-200 text-xs flex items-center gap-1.5 truncate">
                                                <i class="far fa-building text-gray-400 dark:text-gray-500"></i>
                                                {{ $res['instansi'] }}
                                            </span>
                                            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-medium truncate">
                                                Posisi: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $res['posisi'] }}</span>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="text-sm font-black text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/50 border border-teal-200 dark:border-teal-800/60 px-2.5 py-1 rounded-full inline-block">{{ $res['rata_rata'] }}</span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        @php
                                            $badgeColor = match($res['predikat']) {
                                                'Sangat Baik' => 'bg-green-100 dark:bg-green-950/60 text-green-700 dark:text-green-300 border-green-200 dark:border-green-800',
                                                'Baik' => 'bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                                'Cukup' => 'bg-yellow-100 dark:bg-yellow-950/60 text-yellow-700 dark:text-yellow-300 border-yellow-200 dark:border-yellow-800',
                                                default => 'bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700'
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded-full border {{ $badgeColor }}">
                                            {{ $res['predikat'] }}
                                        </span>
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
                                            <h4 class="text-[10px] font-black text-gray-400 dark:text-gray-400 uppercase tracking-wider flex items-center gap-2 border-b pb-2 border-gray-100 dark:border-gray-700">
                                                <i class="fas fa-award text-teal-500 dark:text-teal-400"></i> Rincian Penilaian Kompetensi & Performa
                                            </h4>
                                            
                                            @if($res['nilai_rata_rata'] !== null)
                                                {{-- New Grading System --}}
                                                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                                        <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Kerajinan</div>
                                                        <div class="text-xl font-black text-gray-800 dark:text-gray-200 mt-1">{{ $res['kerajinan'] }}</div>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                                        <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Kedisiplinan</div>
                                                        <div class="text-xl font-black text-gray-800 dark:text-gray-200 mt-1">{{ $res['disiplin'] }}</div>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                                        <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Adaptasi</div>
                                                        <div class="text-xl font-black text-gray-800 dark:text-gray-200 mt-1">{{ $res['adaptasi'] }}</div>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                                        <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Kreatifitas</div>
                                                        <div class="text-xl font-black text-gray-800 dark:text-gray-200 mt-1">{{ $res['kreatifitas'] }}</div>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700 col-span-2 md:col-span-1">
                                                        <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Skill & Pengetahuan</div>
                                                        <div class="text-xl font-black text-gray-800 dark:text-gray-200 mt-1">{{ $res['skill'] }}</div>
                                                    </div>
                                                </div>
                                                <div class="mt-3 flex items-center justify-between text-xs text-gray-400 dark:text-gray-500 italic">
                                                    <span>*Sistem Penilaian Baru (5 Aspek)</span>
                                                    <span>Rata-rata: <strong class="text-gray-700 dark:text-gray-300">{{ $res['rata_rata'] }}</strong></span>
                                                </div>
                                            @else
                                                {{-- Old Grading System --}}
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                                        <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Kompetensi Teknis</div>
                                                        <div class="text-xl font-black text-gray-800 dark:text-gray-200 mt-1">{{ $res['teknis'] }}</div>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                                        <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Kedisiplinan</div>
                                                        <div class="text-xl font-black text-gray-800 dark:text-gray-200 mt-1">{{ $res['disiplin'] }}</div>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                                        <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Sikap & Perilaku</div>
                                                        <div class="text-xl font-black text-gray-800 dark:text-gray-200 mt-1">{{ $res['perilaku'] }}</div>
                                                    </div>
                                                </div>
                                                <div class="mt-3 flex items-center justify-between text-xs text-gray-400 dark:text-gray-500 italic">
                                                    <span>*Sistem Penilaian Lama (3 Aspek)</span>
                                                    <span>Rata-rata: <strong class="text-gray-700 dark:text-gray-300">{{ $res['rata_rata'] }}</strong></span>
                                                </div>
                                            @endif
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