<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-chart-line text-teal-600"></i>
                {{ __('Analisis Kompetensi & Performa') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400 font-medium bg-white dark:bg-gray-800 px-4 py-1.5 rounded-full shadow-sm border border-gray-100 dark:border-gray-700">
                Total Terfilter: <span class="font-bold text-teal-600">{{ $stats['total'] }}</span> Peserta
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center print:hidden">
                <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-700 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Pusat Laporan
                </a>
                
                @if($stats['total'] > 0)
                <div class="flex gap-2">
                    <a href="{{ route('admin.laporan.grading.print', array_merge(request()->query(), ['format' => 'pdf'])) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download PDF">
                        <i class="fas fa-file-pdf text-red-500"></i> <span class="hidden sm:inline">PDF</span>
                    </a>
                    <a href="{{ route('admin.laporan.grading.print', array_merge(request()->query(), ['format' => 'excel'])) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download Excel">
                        <i class="fas fa-file-excel text-green-600"></i> <span class="hidden sm:inline">Excel</span>
                    </a>
                    <a href="{{ route('admin.laporan.grading.print', array_merge(request()->query(), ['format' => 'csv'])) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download CSV">
                        <i class="fas fa-file-csv text-blue-600"></i> <span class="hidden sm:inline">CSV</span>
                    </a>
                </div>
                @endif
            </div>

            {{-- 6 Stats Cards Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-8 h-8 rounded-full bg-teal-50 text-teal-700 flex items-center justify-center mx-auto mb-2 border border-teal-100">
                        <i class="fas fa-users text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-gray-800 dark:text-gray-200">{{ $stats['total'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Total Dinilai</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-8 h-8 rounded-full bg-green-50 text-green-600 flex items-center justify-center mx-auto mb-2 border border-green-100">
                        <i class="fas fa-check-circle text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-green-800">{{ $stats['sangat_baik'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Sangat Baik</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-2 border border-blue-100">
                        <i class="fas fa-thumbs-up text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-blue-800">{{ $stats['baik'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Baik</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-8 h-8 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center mx-auto mb-2 border border-yellow-100">
                        <i class="fas fa-info-circle text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-yellow-700" style="color: #d97706;">{{ $stats['cukup'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Cukup</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-8 h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-2 border border-red-100">
                        <i class="fas fa-times-circle text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-red-800">{{ $stats['kurang'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Kurang</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center bg-gradient-to-br from-teal-50/50 to-indigo-50/30">
                    <div class="w-8 h-8 rounded-full bg-teal-600 text-white flex items-center justify-center mx-auto mb-2 shadow-sm shadow-teal-200">
                        <i class="fas fa-star text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-teal-700">{{ $stats['avg_nilai'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Rerata Nilai</p>
                </div>
            </div>

            {{-- 3-Component Rerata Mini Panel --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-laptop-code text-blue-500"></i> Rerata Teknis
                        </span>
                        <span class="text-sm font-black text-gray-800 dark:text-gray-200">{{ $statsGlobal['avg_teknis'] }}/100</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $statsGlobal['avg_teknis'] }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-clock text-purple-500"></i> Rerata Kedisiplinan
                        </span>
                        <span class="text-sm font-black text-gray-800 dark:text-gray-200">{{ $statsGlobal['avg_disiplin'] }}/100</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $statsGlobal['avg_disiplin'] }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-user-check text-emerald-500"></i> Rerata Perilaku / Soft Skill
                        </span>
                        <span class="text-sm font-black text-gray-800 dark:text-gray-200">{{ $statsGlobal['avg_perilaku'] }}/100</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $statsGlobal['avg_perilaku'] }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Top 3 Podium Leaderboard --}}
            @if($podium->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="text-center mb-6">
                    <h3 class="text-lg font-black text-gray-800 dark:text-gray-200 flex items-center justify-center gap-2">
                        <i class="fas fa-trophy text-yellow-500 animate-bounce"></i> TOP 3 PERFORMER TERBAIK KOTA
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Apresiasi khusus untuk peserta magang dengan pencapaian performa tertinggi se-Kota Banjarmasin.</p>
                </div>
                
                <div class="flex flex-col md:flex-row items-end justify-center gap-6 md:gap-4 max-w-4xl mx-auto pt-6">
                    
                    {{-- Juara 2 (Perak) --}}
                    @if($podium->count() > 1)
                    @php $p2 = $podium[1]; @endphp
                    <div class="w-full md:w-1/3 order-2 md:order-1 flex flex-col items-center">
                        <div class="relative mb-3 flex flex-col items-center">
                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-gray-100 to-gray-300 flex items-center justify-center text-gray-700 dark:text-gray-300 font-black border-4 border-white shadow-md text-lg relative">
                                {{ strtoupper(substr($p2['nama'], 0, 2)) }}
                                <span class="absolute -top-3 -right-1 w-6 h-6 rounded-full bg-gray-400 text-white text-[10px] font-bold flex items-center justify-center border-2 border-white shadow">2</span>
                            </div>
                        </div>
                        <div class="text-center mb-2">
                            <p class="font-bold text-gray-800 dark:text-gray-200 text-sm truncate max-w-[180px]">{{ $p2['nama'] }}</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate max-w-[180px] font-semibold">{{ $p2['asal_instansi'] }}</p>
                            <p class="text-[9px] text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full inline-block font-bold mt-1">{{ $p2['instansi'] }}</p>
                        </div>
                        <div class="w-full bg-gradient-to-t from-gray-100 to-gray-200/50 rounded-t-2xl pt-8 pb-4 text-center border-t border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-center items-center h-28">
                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">SKOR AKHIR</span>
                            <span class="text-2xl font-black text-gray-700 dark:text-gray-300 mt-1">{{ $p2['rata_rata'] }}</span>
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
                            <div class="h-20 w-20 rounded-full bg-gradient-to-br from-yellow-300 to-yellow-500 flex items-center justify-center text-white font-black border-4 border-white shadow-lg text-xl relative">
                                {{ strtoupper(substr($p1['nama'], 0, 2)) }}
                                <span class="absolute -top-1 -right-1 w-7 h-7 rounded-full bg-yellow-500 text-white text-[11px] font-black flex items-center justify-center border-2 border-white shadow">1</span>
                            </div>
                        </div>
                        <div class="text-center mb-2">
                            <p class="font-black text-gray-950 text-base truncate max-w-[200px]">{{ $p1['nama'] }}</p>
                            <p class="text-xs text-gray-700 truncate max-w-[200px] font-bold">{{ $p1['asal_instansi'] }}</p>
                            <p class="text-[10px] text-teal-700 bg-teal-50 px-2.5 py-0.5 rounded-full inline-block font-extrabold mt-1 border border-teal-200">{{ $p1['instansi'] }}</p>
                        </div>
                        <div class="w-full bg-gradient-to-t from-yellow-50 to-yellow-100 rounded-t-2xl pt-10 pb-6 text-center border-t-2 border-yellow-300 shadow flex flex-col justify-center items-center h-36">
                            <span class="text-xs font-black text-yellow-800 uppercase tracking-wider">SKOR AKHIR</span>
                            <span class="text-3xl font-black text-yellow-700 mt-1">{{ $p1['rata_rata'] }}</span>
                            <span class="text-[10px] font-black text-yellow-600 mt-1 uppercase">{{ $p1['predikat'] }}</span>
                        </div>
                    </div>
                    @endif

                    {{-- Juara 3 (Perunggu) --}}
                    @if($podium->count() > 2)
                    @php $p3 = $podium[2]; @endphp
                    <div class="w-full md:w-1/3 order-3 md:order-3 flex flex-col items-center">
                        <div class="relative mb-3 flex flex-col items-center">
                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-amber-600 to-amber-800/80 flex items-center justify-center text-white font-black border-4 border-white shadow-md text-lg relative">
                                {{ strtoupper(substr($p3['nama'], 0, 2)) }}
                                <span class="absolute -top-3 -right-1 w-6 h-6 rounded-full bg-amber-600 text-white text-[10px] font-bold flex items-center justify-center border-2 border-white shadow">3</span>
                            </div>
                        </div>
                        <div class="text-center mb-2">
                            <p class="font-bold text-gray-800 dark:text-gray-200 text-sm truncate max-w-[180px]">{{ $p3['nama'] }}</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate max-w-[180px] font-semibold">{{ $p3['asal_instansi'] }}</p>
                            <p class="text-[9px] text-teal-600 bg-teal-50 px-2 py-0.5 rounded-full inline-block font-bold mt-1">{{ $p3['instansi'] }}</p>
                        </div>
                        <div class="w-full bg-gradient-to-t from-orange-50/50 to-orange-100/30 rounded-t-2xl pt-8 pb-4 text-center border-t border-orange-200 shadow-sm flex flex-col justify-center items-center h-24">
                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">SKOR AKHIR</span>
                            <span class="text-xl font-black text-amber-700 mt-1">{{ $p3['rata_rata'] }}</span>
                            <span class="text-[9px] font-extrabold text-orange-700 mt-1 uppercase">{{ $p3['predikat'] }}</span>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            @endif

            {{-- Highlight Banner --}}
            <div class="bg-gradient-to-r from-teal-700 to-indigo-700 rounded-3xl p-6 text-white shadow-lg shadow-teal-700/20 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white dark:bg-gray-800/20 backdrop-blur-sm flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fas fa-award"></i>
                </div>
                <div class="text-center sm:text-left flex-grow">
                    <p class="text-xs font-bold uppercase tracking-wider text-teal-100">Rekapitulasi Penilaian & Kompetensi Global</p>
                    <p class="text-xl font-black mt-0.5">Total {{ $stats['total'] }} Peserta Dinilai</p>
                    <p class="text-sm text-teal-50 font-medium">Data performa mencakup sebaran nilai berdasarkan parameter teknis, disiplin, dan soft skill.</p>
                </div>
                @if($gradedList->count() > 0)
                <div class="sm:ml-auto flex-shrink-0">
                    <a href="{{ route('admin.laporan.grading.print', request()->query()) }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-white dark:bg-gray-800 text-teal-700 rounded-xl hover:bg-teal-50 transition text-sm font-bold shadow-md">
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
                        @if(request()->anyFilled(['q', 'instansi', 'instansi_id', 'predikat']))
                            <a href="{{ route('admin.laporan.grading') }}" class="text-xs text-red-500 hover:text-red-700 font-bold">Reset</a>
                        @endif
                    </div>
                    
                    <form method="GET" action="{{ route('admin.laporan.grading') }}" class="flex flex-col gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Nama Peserta</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                    <i class="fas fa-search text-xs"></i>
                                </span>
                                <input type="text" name="q" value="{{ request('q') }}" 
                                    placeholder="Cari nama peserta..."
                                    class="w-full pl-9 border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Asal Kampus / Sekolah</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                    <i class="fas fa-university text-xs"></i>
                                </span>
                                <select name="instansi" class="w-full pl-9 border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-white dark:hover:bg-gray-800 transition">
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
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                    <i class="fas fa-building text-xs"></i>
                                </span>
                                <select name="instansi_id" class="w-full pl-9 border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-white dark:hover:bg-gray-800 transition">
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
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                    <i class="fas fa-award text-xs"></i>
                                </span>
                                <select name="predikat" class="w-full pl-9 border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-white dark:hover:bg-gray-800 transition">
                                    <option value="">Semua Predikat</option>
                                    <option value="Sangat Baik" {{ request('predikat') == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik (>= 86)</option>
                                    <option value="Baik" {{ request('predikat') == 'Baik' ? 'selected' : '' }}>Baik (71 - 85)</option>
                                    <option value="Cukup" {{ request('predikat') == 'Cukup' ? 'selected' : '' }}>Cukup (56 - 70)</option>
                                    <option value="Kurang" {{ request('predikat') == 'Kurang' ? 'selected' : '' }}>Kurang (< 56)</option>
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
                                <h3 class="font-bold text-gray-800 dark:text-gray-200">Daftar Analisis Kompetensi & Performa</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Daftar peringkat berdasarkan nilai rata-rata akhir peserta terfilter.</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-100 border-collapse">
                                <thead class="bg-gray-50 dark:bg-gray-900 sticky top-0 z-20 shadow-[inset_0_-1px_0_rgba(229,231,235,1)]">
                                    <tr>
                                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16 whitespace-nowrap">Rank</th>
                                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap min-w-[200px]">Peserta & Asal Kampus</th>
                                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap min-w-[200px]">Penempatan Dinas & Posisi</th>
                                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-24 whitespace-nowrap min-w-[100px]">Skor Akhir</th>
                                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28 whitespace-nowrap min-w-[120px]">Predikat</th>
                                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12 whitespace-nowrap">Detail</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-50 text-sm">
                                    @forelse($gradedList as $index => $res)
                                    <tr class="hover:bg-teal-50/15 transition group cursor-pointer" @click="openRow = (openRow === {{ $index }} ? null : {{ $index }})">
                                        <td class="px-5 py-4 text-center">
                                            @if($index == 0 && !request('q') && !request('instansi') && !request('instansi_id') && !request('predikat'))
                                                <div class="w-7 h-7 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mx-auto shadow-sm ring-2 ring-yellow-200">
                                                    <i class="fas fa-crown text-xs"></i>
                                                </div>
                                            @elseif($index == 1 && !request('q') && !request('instansi') && !request('instansi_id') && !request('predikat'))
                                                <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 flex items-center justify-center mx-auto border border-gray-300 dark:border-gray-600 font-bold text-xs">2</div>
                                            @elseif($index == 2 && !request('q') && !request('instansi') && !request('instansi_id') && !request('predikat'))
                                                <div class="w-7 h-7 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center mx-auto border border-orange-200 font-bold text-xs">3</div>
                                            @else
                                                <span class="text-gray-400 font-bold text-xs">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-teal-50 to-teal-100 flex items-center justify-center text-teal-700 font-black border border-teal-200/50 text-xs flex-shrink-0">
                                                    {{ strtoupper(substr($res['nama'], 0, 2)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="font-bold text-gray-900 dark:text-gray-100 truncate">{{ $res['nama'] }}</div>
                                                    <div class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold truncate flex items-center mt-0.5">
                                                        <i class="fas fa-university mr-1.5 text-gray-300"></i> {{ $res['asal_instansi'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex flex-col gap-0.5">
                                                <span class="font-bold text-gray-800 dark:text-gray-200 text-xs flex items-center gap-1.5">
                                                    <i class="far fa-building text-gray-400"></i>
                                                    {{ $res['instansi'] }}
                                                </span>
                                                <span class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">
                                                    Posisi: <span class="font-semibold text-gray-600 dark:text-gray-400">{{ $res['posisi'] }}</span>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            <span class="text-base font-black text-teal-700 bg-teal-50 px-2.5 py-0.5 rounded-full">{{ $res['rata_rata'] }}</span>
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            @php
                                                $badgeColor = match($res['predikat']) {
                                                    'Sangat Baik' => 'bg-green-100 text-green-700 border-green-200',
                                                    'Baik' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                    'Cukup' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                    default => 'bg-gray-100 dark:bg-gray-800 text-gray-700 border-gray-200 dark:border-gray-700'
                                                };
                                            @endphp
                                            <span class="px-2.5 py-1 text-[9px] font-black uppercase rounded-full border {{ $badgeColor }}">
                                                {{ $res['predikat'] }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" :class="openRow === {{ $index }} ? 'rotate-180 text-teal-500' : ''"></i>
                                        </td>
                                    </tr>

                                    {{-- Expanded detail row --}}
                                    <tr x-show="openRow === {{ $index }}" x-transition.opacity x-cloak>
                                        <td colspan="6" class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-y border-gray-100 dark:border-gray-700">
                                            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-300/60 p-5 shadow-sm">
                                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider flex items-center gap-2 border-b pb-2 border-gray-200 mb-4">
                                                    <i class="fas fa-award text-teal-500"></i> Rincian Penilaian Kompetensi & Performa
                                                </h4>
                                                
                                                @if($res['nilai_rata_rata'] !== null)
                                                    {{-- New Grading System --}}
                                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                                        <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200">
                                                            <div class="text-[9px] font-bold text-gray-400 uppercase">Kerajinan</div>
                                                            <div class="text-xl font-black text-gray-800 dark:text-gray-200 mt-1">{{ $res['kerajinan'] }}</div>
                                                        </div>
                                                        <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200">
                                                            <div class="text-[9px] font-bold text-gray-400 uppercase">Kedisiplinan</div>
                                                            <div class="text-xl font-black text-gray-800 dark:text-gray-200 mt-1">{{ $res['disiplin'] }}</div>
                                                        </div>
                                                        <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200">
                                                            <div class="text-[9px] font-bold text-gray-400 uppercase">Adaptasi</div>
                                                            <div class="text-xl font-black text-gray-800 dark:text-gray-200 mt-1">{{ $res['adaptasi'] }}</div>
                                                        </div>
                                                        <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200">
                                                            <div class="text-[9px] font-bold text-gray-400 uppercase">Kreatifitas</div>
                                                            <div class="text-xl font-black text-gray-800 dark:text-gray-200 mt-1">{{ $res['kreatifitas'] }}</div>
                                                        </div>
                                                        <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 col-span-2 md:col-span-1">
                                                            <div class="text-[9px] font-bold text-gray-400 uppercase">Skill & Pengetahuan</div>
                                                            <div class="text-xl font-black text-gray-800 dark:text-gray-200 mt-1">{{ $res['skill'] }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 flex items-center justify-between text-xs text-gray-400 italic">
                                                        <span>*Sistem Penilaian Baru (5 Aspek)</span>
                                                        <span>Rata-rata: <strong>{{ $res['rata_rata'] }}</strong></span>
                                                    </div>
                                                @else
                                                    {{-- Old Grading System --}}
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                        <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200">
                                                            <div class="text-[9px] font-bold text-gray-400 uppercase">Kompetensi Teknis</div>
                                                            <div class="text-xl font-black text-gray-800 dark:text-gray-200 mt-1">{{ $res['teknis'] }}</div>
                                                        </div>
                                                        <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200">
                                                            <div class="text-[9px] font-bold text-gray-400 uppercase">Kedisiplinan</div>
                                                            <div class="text-xl font-black text-gray-800 dark:text-gray-200 mt-1">{{ $res['disiplin'] }}</div>
                                                        </div>
                                                        <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200">
                                                            <div class="text-[9px] font-bold text-gray-400 uppercase">Sikap & Perilaku</div>
                                                            <div class="text-xl font-black text-gray-800 dark:text-gray-200 mt-1">{{ $res['perilaku'] }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 flex items-center justify-between text-xs text-gray-400 italic">
                                                        <span>*Sistem Penilaian Lama (3 Aspek)</span>
                                                        <span>Rata-rata: <strong>{{ $res['rata_rata'] }}</strong></span>
                                                    </div>
                                                @endif
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