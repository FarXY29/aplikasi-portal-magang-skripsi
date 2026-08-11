<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-building text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Analisis Kedisiplinan Instansi') }}
            </h2>
            <div class="text-xs font-bold text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-4 py-2 rounded-2xl shadow-xs border border-gray-200 dark:border-gray-700">
                Total Terfilter: <span class="font-black text-teal-600 dark:text-teal-400 font-mono">{{ $stats['total_instansi'] }}</span> Instansi
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Back Navigation & Export Buttons --}}
            <div class="flex justify-between items-center print:hidden">
                <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Pusat Laporan
                </a>
                
                @if($stats['total_instansi'] > 0)
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.laporan.instansi_disiplin.print', array_merge(request()->query(), ['format' => 'pdf'])) }}" target="_blank" class="flex-1 sm:flex-none px-3.5 py-1.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-gray-700 rounded-xl font-bold text-xs transition shadow-xs flex items-center justify-center gap-1.5" title="Download PDF">
                        <i class="fas fa-file-pdf text-rose-500"></i> PDF
                    </a>
                    <a href="{{ route('admin.laporan.instansi_disiplin.print', array_merge(request()->query(), ['format' => 'excel'])) }}" class="flex-1 sm:flex-none px-3.5 py-1.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-gray-700 rounded-xl font-bold text-xs transition shadow-xs flex items-center justify-center gap-1.5" title="Download Excel">
                        <i class="fas fa-file-excel text-emerald-500"></i> Excel
                    </a>
                    <a href="{{ route('admin.laporan.instansi_disiplin.print', array_merge(request()->query(), ['format' => 'csv'])) }}" class="flex-1 sm:flex-none px-3.5 py-1.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-gray-700 rounded-xl font-bold text-xs transition shadow-xs flex items-center justify-center gap-1.5" title="Download CSV">
                        <i class="fas fa-file-csv text-blue-500"></i> CSV
                    </a>
                </div>
                @endif
            </div>

            {{-- 6 Stats Cards Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Total jumlah instansi/dinas Pemerintah Kota Banjarmasin yang masuk dalam evaluasi kedisiplinan presensi.">
                    <div class="w-9 h-9 rounded-2xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-3 border border-teal-100 dark:border-teal-800/60 shadow-xs">
                        <i class="fas fa-building text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100 font-mono tracking-tight">{{ number_format($stats['total_instansi']) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Instansi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help bg-gradient-to-br from-teal-50/50 via-white to-indigo-50/30 dark:from-teal-950/20 dark:via-gray-800 dark:to-indigo-950/20" title="Rata-rata persentase kedisiplinan instansi se-Kota Banjarmasin. Rumus Disiplin Instansi: 100% - ((Total Pelanggaran / Total Absensi) x 100%). Pelanggaran = Terlambat + Alpa.">
                    <div class="w-9 h-9 rounded-2xl bg-teal-600 text-white flex items-center justify-center mx-auto mb-3 shadow-xs">
                        <i class="fas fa-percentage text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-teal-600 dark:text-teal-400 font-mono tracking-tight">{{ $stats['avg_disiplin'] }}%</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Rerata Disiplin</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Total seluruh log entri presensi/kehadiran peserta magang pada instansi yang dinilai.">
                    <div class="w-9 h-9 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-3 border border-blue-100 dark:border-blue-800/60 shadow-xs">
                        <i class="fas fa-calendar-check text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-blue-600 dark:text-blue-400 font-mono tracking-tight">{{ number_format($stats['total_kehadiran']) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Absensi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Total akumulasi kasus pelanggaran presensi peserta magang. Rumus: Total Terlambat + Total Alpa.">
                    <div class="w-9 h-9 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-3 border border-rose-100 dark:border-rose-800/60 shadow-xs">
                        <i class="fas fa-exclamation-triangle text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-rose-600 dark:text-rose-400 font-mono tracking-tight">{{ number_format($stats['total_pelanggaran']) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Pelanggaran</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Total kehadiran peserta yang waktu masuknya melewati jam kerja resmi instansi (clock_in > jam_mulai_masuk dinas).">
                    <div class="w-9 h-9 rounded-2xl bg-orange-50 dark:bg-orange-950/60 text-orange-600 dark:text-orange-400 flex items-center justify-center mx-auto mb-3 border border-orange-100 dark:border-orange-800/60 shadow-xs">
                        <i class="fas fa-user-clock text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-orange-600 dark:text-orange-400 font-mono tracking-tight">{{ number_format($stats['total_terlambat']) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Terlambat</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Total ketidakhadiran peserta tanpa keterangan atau tanpa dokumen izin/sakit yang sah.">
                    <div class="w-9 h-9 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto mb-3 border border-amber-100 dark:border-amber-800/60 shadow-xs">
                        <i class="fas fa-user-times text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-amber-600 dark:text-amber-400 font-mono tracking-tight">{{ number_format($stats['total_alpa']) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Alpa</p>
                </div>
            </div>

            {{-- Collapsible Top 3 Podium Leaderboard --}}
            @if($podium->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 transition-all duration-300" x-data="{ showTop3: false }">
                <div class="flex items-center justify-between cursor-pointer select-none" @click="showTop3 = !showTop3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shadow-xs">
                            <i class="fas fa-award text-lg"></i>
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
                                <p class="text-[9px] text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 px-2 py-0.5 rounded-full inline-block font-bold mt-1 border border-rose-200 dark:border-rose-900/40">{{ $p2->total_pelanggaran }} Pelanggaran</p>
                            </div>
                            <div class="w-full bg-gradient-to-t from-gray-100 to-gray-200/50 dark:from-gray-900 dark:to-gray-800/80 rounded-t-2xl pt-8 pb-4 text-center border-t border-gray-200 dark:border-gray-700 shadow-xs flex flex-col justify-center items-center h-28">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">KEDISIPLINAN</span>
                                <span class="text-2xl font-black text-gray-800 dark:text-gray-100 font-mono mt-1">{{ number_format($p2->tingkat_disiplin, 1) }}%</span>
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
                                <div class="h-20 w-20 rounded-full bg-gradient-to-br from-amber-300 to-amber-500 flex items-center justify-center text-white font-black border-4 border-white dark:border-gray-700 shadow-lg text-xl relative">
                                    {{ strtoupper(substr($p1->nama_dinas, 0, 2)) }}
                                    <span class="absolute -top-1 -right-1 w-7 h-7 rounded-full bg-amber-500 text-white text-[11px] font-black flex items-center justify-center border-2 border-white dark:border-gray-800 shadow">1</span>
                                </div>
                            </div>
                            <div class="text-center mb-2">
                                <p class="font-black text-gray-900 dark:text-gray-100 text-base truncate max-w-[220px]" title="{{ $p1->nama_dinas }}">{{ $p1->nama_dinas }}</p>
                                <p class="text-xs text-gray-700 dark:text-gray-300 font-bold">{{ $p1->total_attendances }} Kehadiran</p>
                                <p class="text-[10px] text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 px-2.5 py-0.5 rounded-full inline-block font-extrabold mt-1 border border-rose-200 dark:border-rose-900/50">{{ $p1->total_pelanggaran }} Pelanggaran</p>
                            </div>
                            <div class="w-full bg-gradient-to-t from-amber-50 to-amber-100 dark:from-amber-950/40 dark:to-amber-900/20 rounded-t-2xl pt-10 pb-6 text-center border-t-2 border-amber-300 dark:border-amber-700 shadow flex flex-col justify-center items-center h-36">
                                <span class="text-xs font-black text-amber-800 dark:text-amber-300 uppercase tracking-wider">KEDISIPLINAN</span>
                                <span class="text-3xl font-black text-amber-700 dark:text-amber-400 font-mono mt-1">{{ number_format($p1->tingkat_disiplin, 1) }}%</span>
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
                                <p class="text-[9px] text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 px-2 py-0.5 rounded-full inline-block font-bold mt-1 border border-rose-200 dark:border-rose-900/40">{{ $p3->total_pelanggaran }} Pelanggaran</p>
                            </div>
                            <div class="w-full bg-gradient-to-t from-orange-50/50 to-orange-100/30 dark:from-orange-950/40 dark:to-orange-900/20 rounded-t-2xl pt-8 pb-4 text-center border-t border-orange-200 dark:border-orange-900/40 shadow-xs flex flex-col justify-center items-center h-24">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">KEDISIPLINAN</span>
                                <span class="text-xl font-black text-amber-700 dark:text-amber-400 font-mono mt-1">{{ number_format($p3->tingkat_disiplin, 1) }}%</span>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
            @endif

            {{-- Main Table Card (Daftar Pemeringkatan Kepatuhan Instansi) with Integrated Filters --}}
            <div class="w-full space-y-6" x-data="{ openRow: null }">
                <div class="bg-white dark:bg-gray-800 shadow-xs sm:rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    
                    {{-- Card Header: Title & Right-Side Filter Form --}}
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-gray-50/50 dark:bg-gray-900/50">
                        <div>
                            <h3 class="font-extrabold text-gray-900 dark:text-gray-100 text-lg flex items-center gap-2.5">
                                <i class="fas fa-building text-teal-600 dark:text-teal-400"></i>
                                Daftar Pemeringkatan Kepatuhan Instansi
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Instansi dengan tingkat kepatuhan terurut dari tertinggi ke terendah.</p>
                        </div>

                        {{-- Integrated Right-Side Filter Form --}}
                        <form method="GET" action="{{ route('admin.laporan.instansi_disiplin') }}" class="flex flex-col sm:flex-row gap-2.5 items-center w-full lg:w-auto">
                            <div class="relative w-full sm:w-56">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 pointer-events-none">
                                    <i class="fas fa-search text-xs"></i>
                                </span>
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama dinas..."
                                    class="w-full pl-9 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-xl text-xs font-bold focus:ring-teal-500 focus:border-teal-500 shadow-xs">
                            </div>

                            <div class="w-full sm:w-48">
                                <select name="disiplin_range" class="w-full py-2 px-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-100 focus:ring-teal-500 focus:border-teal-500 shadow-xs cursor-pointer [color-scheme:dark]">
                                    <option value="">Semua Kategori</option>
                                    <option value="sangat" {{ request('disiplin_range') == 'sangat' ? 'selected' : '' }}>Sangat Disiplin (>= 90%)</option>
                                    <option value="cukup" {{ request('disiplin_range') == 'cukup' ? 'selected' : '' }}>Cukup Disiplin (70-89%)</option>
                                    <option value="kurang" {{ request('disiplin_range') == 'kurang' ? 'selected' : '' }}>Kurang Disiplin (< 70%)</option>
                                </select>
                            </div>

                            <div class="flex gap-2 w-full sm:w-auto">
                                <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 w-full sm:w-auto">
                                    <i class="fas fa-filter text-xs"></i> Filter
                                </button>
                                @if(request()->anyFilled(['q', 'disiplin_range']))
                                    <a href="{{ route('admin.laporan.instansi_disiplin') }}" class="px-3 py-2 border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl font-bold text-xs shadow-xs transition flex items-center justify-center">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Table Content --}}
                    <div class="hidden md:block overflow-x-auto max-h-[650px] overflow-y-auto">
                        <table class="w-full divide-y divide-gray-100 dark:divide-gray-700 border-collapse">
                            <thead class="bg-gray-50 dark:bg-gray-900 sticky top-0 z-20">
                                <tr>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-14">Rank</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-[220px] max-w-[320px]">Instansi</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">Total Absensi</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-44">Pelanggaran</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">Tingkat Disiplin</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                                @forelse($instansis as $index => $res)
                                <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-950/20 transition group cursor-pointer" @click="openRow = (openRow === {{ $index }} ? null : {{ $index }})">
                                    <td class="px-4 py-4 text-center">
                                        @if($index == 0 && !request('q') && !request('disiplin_range') && $res->total_attendances > 0)
                                            <div class="w-7 h-7 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto shadow-xs border border-amber-200 dark:border-amber-800/50">
                                                <i class="fas fa-crown text-xs"></i>
                                            </div>
                                        @elseif($index == 1 && !request('q') && !request('disiplin_range') && $res->total_attendances > 0)
                                            <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 flex items-center justify-center mx-auto border border-gray-300 dark:border-gray-700 font-bold text-xs">2</div>
                                        @elseif($index == 2 && !request('q') && !request('disiplin_range') && $res->total_attendances > 0)
                                            <div class="w-7 h-7 rounded-full bg-amber-100/60 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 flex items-center justify-center mx-auto border border-amber-200 dark:border-amber-800/50 font-bold text-xs">3</div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 font-bold text-xs">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 min-w-[220px] max-w-[320px]">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 font-black border border-teal-200 dark:border-teal-800/60 text-xs flex-shrink-0 flex items-center justify-center">
                                                {{ strtoupper(substr($res->nama_dinas, 0, 2)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-bold text-gray-900 dark:text-gray-100 leading-snug line-clamp-2" title="{{ $res->nama_dinas }}">{{ $res->nama_dinas }}</div>
                                                <div class="text-[11px] text-gray-500 dark:text-gray-400 font-medium flex items-center gap-1 mt-0.5">
                                                    <i class="far fa-clock text-gray-400 dark:text-gray-500"></i> Jam Masuk: <span class="text-gray-700 dark:text-gray-300 font-bold font-mono">{{ $res->jam_mulai_masuk ?: '08:00:00' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-full font-bold text-xs inline-block">
                                            <strong class="font-mono">{{ $res->total_attendances }}</strong> <span class="text-[10px] text-gray-400 dark:text-gray-500 font-normal">Entri</span>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        @if($res->total_pelanggaran > 0)
                                            <div class="inline-flex items-center gap-1 px-3 py-1 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800/60 rounded-full">
                                                <span class="text-rose-700 dark:text-rose-300 font-black text-xs font-mono">{{ $res->total_pelanggaran }}</span>
                                                <span class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">({{ $res->total_terlambat }} Telat, {{ $res->total_alpa }} Alpa)</span>
                                            </div>
                                        @else
                                            <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 text-emerald-700 dark:text-emerald-300 font-bold rounded-full inline-flex items-center justify-center gap-1 text-xs">
                                                <i class="fas fa-check-circle"></i> Nihil
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-col gap-1 items-center">
                                            @php
                                                $barColor = 'bg-rose-500';
                                                $textColor = 'text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800/60';
                                                if ($res->tingkat_disiplin >= 90) {
                                                    $barColor = 'bg-emerald-500';
                                                    $textColor = 'text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60';
                                                } elseif ($res->tingkat_disiplin >= 70) {
                                                    $barColor = 'bg-blue-500';
                                                    $textColor = 'text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800/60';
                                                }
                                            @endphp
                                            <div class="w-20 bg-gray-100 dark:bg-gray-900 h-2 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                                                <div class="{{ $barColor }} h-2 rounded-full" style="width: {{ $res->tingkat_disiplin }}%"></div>
                                            </div>
                                            <span class="px-2.5 py-0.5 rounded text-[10px] font-black font-mono {{ $textColor }}">
                                                {{ number_format($res->tingkat_disiplin, 1) }}%
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="w-8 h-8 rounded-full bg-gray-50 dark:bg-gray-900 flex items-center justify-center mx-auto border border-gray-200 dark:border-gray-700">
                                            <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 text-xs transition-transform duration-200" :class="openRow === {{ $index }} ? 'rotate-180 text-teal-600 dark:text-teal-400' : ''"></i>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Expanded detail row --}}
                                <tr x-show="openRow === {{ $index }}" x-transition.opacity x-cloak>
                                    <td colspan="6" class="px-4 py-4 bg-gray-50/80 dark:bg-gray-900/60 border-y border-gray-100 dark:border-gray-700">
                                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 sm:p-5 shadow-xs space-y-4">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b pb-2 border-gray-100 dark:border-gray-700 gap-2">
                                                <h4 class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                                    <i class="fas fa-user-clock text-teal-600 dark:text-teal-400"></i> Detail Absensi Dinas & Pelanggaran
                                                </h4>
                                                <span class="text-xs text-gray-600 dark:text-gray-400 font-bold uppercase bg-gray-50 dark:bg-gray-900 px-3 py-1 rounded-full border border-gray-200 dark:border-gray-700 font-mono">
                                                    Hadir: {{ $res->total_hadir }} | Sakit: {{ $res->total_sakit }} | Izin: {{ $res->total_izin }}
                                                </span>
                                            </div>
                                            
                                            <div class="space-y-2">
                                                <div class="text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1">Peserta dengan Pelanggaran Kehadiran:</div>
                                                
                                                @if(count($res->pelanggar_list) > 0)
                                                    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                                                        <table class="w-full divide-y divide-gray-100 dark:divide-gray-700 text-xs text-left">
                                                            <thead class="bg-gray-50 dark:bg-gray-900">
                                                                <tr>
                                                                    <th class="px-4 py-3 font-black text-gray-500 dark:text-gray-400 uppercase">Nama Peserta / Asal</th>
                                                                    <th class="px-4 py-3 font-black text-gray-500 dark:text-gray-400 uppercase">Posisi</th>
                                                                    <th class="px-4 py-3 text-center font-black text-gray-500 dark:text-gray-400 uppercase w-24">Terlambat</th>
                                                                    <th class="px-4 py-3 text-center font-black text-gray-500 dark:text-gray-400 uppercase w-24">Alpa</th>
                                                                    <th class="px-4 py-3 text-center font-black text-gray-500 dark:text-gray-400 uppercase w-32">Total Pelanggaran</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 bg-white dark:bg-gray-800">
                                                                @foreach($res->pelanggar_list as $p)
                                                                <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-950/20">
                                                                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-gray-100">
                                                                        {{ $p['nama'] }}
                                                                        <div class="text-[11px] text-gray-500 dark:text-gray-400 font-medium mt-0.5">{{ $p['kampus'] }}</div>
                                                                    </td>
                                                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300 font-medium">{{ $p['posisi'] }}</td>
                                                                    <td class="px-4 py-3 text-center text-orange-600 dark:text-orange-400 font-bold font-mono">{{ $p['terlambat'] }}x</td>
                                                                    <td class="px-4 py-3 text-center text-rose-600 dark:text-rose-400 font-bold font-mono">{{ $p['alpa'] }}x</td>
                                                                    <td class="px-4 py-3 text-center font-black text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-900/50 font-mono">
                                                                        {{ $p['terlambat'] + $p['alpa'] }}x
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="text-center py-6 text-gray-500 flex flex-col items-center justify-center">
                                                        <i class="fas fa-check-circle text-emerald-500 dark:text-emerald-400 text-3xl mb-2"></i>
                                                        <p class="font-bold text-gray-800 dark:text-gray-200">Tidak Ada Pelanggaran Kehadiran</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Semua entri absensi masuk tepat waktu dan nihil alpa.</p>
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
                                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-700">
                                                <i class="fas fa-search text-2xl"></i>
                                            </div>
                                            <p class="text-gray-900 dark:text-gray-100 font-bold">Data tidak ditemukan</p>
                                            <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Coba sesuaikan filter pencarian Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Card View (<md) --}}
                    <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($instansis as $index => $res)
                        <div class="p-4 space-y-3.5 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition">
                            {{-- header: rank icon + name + status badge --}}
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if($index == 0 && !request('q') && !request('disiplin_range') && $res->total_attendances > 0)
                                        <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shadow-xs border border-amber-200 dark:border-amber-800/50 shrink-0">
                                            <i class="fas fa-crown text-xs"></i>
                                        </div>
                                    @elseif($index == 1 && !request('q') && !request('disiplin_range') && $res->total_attendances > 0)
                                        <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 flex items-center justify-center border border-gray-300 dark:border-gray-700 font-bold text-xs shrink-0">2</div>
                                    @elseif($index == 2 && !request('q') && !request('disiplin_range') && $res->total_attendances > 0)
                                        <div class="w-8 h-8 rounded-full bg-amber-100/60 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 flex items-center justify-center border border-amber-200 dark:border-amber-800/50 font-bold text-xs shrink-0">3</div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gray-50 dark:bg-gray-900 text-gray-400 dark:text-gray-500 flex items-center justify-center border border-gray-200 dark:border-gray-700 font-bold text-xs shrink-0">#{{ $index + 1 }}</div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2">
                                            <div class="h-8 w-8 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 font-black border border-teal-200 dark:border-teal-800/60 text-[10px] flex-shrink-0 flex items-center justify-center">
                                                {{ strtoupper(substr($res->nama_dinas, 0, 2)) }}
                                            </div>
                                            <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 leading-snug line-clamp-2" title="{{ $res->nama_dinas }}">{{ $res->nama_dinas }}</h4>
                                        </div>
                                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium flex items-center gap-1 mt-1">
                                            <i class="far fa-clock text-gray-400 dark:text-gray-500"></i> Jam Masuk: <span class="text-gray-700 dark:text-gray-300 font-bold font-mono">{{ $res->jam_mulai_masuk ?: '08:00:00' }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    @if($res->tingkat_disiplin >= 90)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60">
                                            <i class="fas fa-star text-[9px] mr-1"></i> Sangat Disiplin
                                        </span>
                                    @elseif($res->tingkat_disiplin >= 70)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800/60">
                                            <i class="fas fa-thumbs-up text-[9px] mr-1"></i> Cukup Disiplin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800/60">
                                            <i class="fas fa-exclamation-triangle text-[9px] mr-1"></i> Kurang Disiplin
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- detail box: mini grid stats --}}
                            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-100 dark:border-gray-700 space-y-3">
                                <div class="grid grid-cols-3 gap-2 text-center">
                                    <div>
                                        <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Absensi</p>
                                        <p class="text-sm font-black text-gray-900 dark:text-gray-100 font-mono mt-0.5">{{ $res->total_attendances }}</p>
                                    </div>
                                    <div class="border-x border-gray-200 dark:border-gray-700">
                                        <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Pelanggaran</p>
                                        @if($res->total_pelanggaran > 0)
                                            <p class="text-sm font-black text-rose-600 dark:text-rose-400 font-mono mt-0.5">{{ $res->total_pelanggaran }}</p>
                                        @else
                                            <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-0.5"><i class="fas fa-check-circle text-[10px]"></i> Nihil</p>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Detail</p>
                                        <p class="text-[10px] font-bold text-gray-600 dark:text-gray-300 mt-0.5 leading-tight">
                                            <span class="text-orange-600 dark:text-orange-400">{{ $res->total_terlambat }}</span> Telat<br>
                                            <span class="text-rose-600 dark:text-rose-400">{{ $res->total_alpa }}</span> Alpa
                                        </p>
                                    </div>
                                </div>

                                {{-- progress bar rate --}}
                                @php
                                    $barColor = 'bg-rose-500';
                                    $textColor = 'text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800/60';
                                    if ($res->tingkat_disiplin >= 90) {
                                        $barColor = 'bg-emerald-500';
                                        $textColor = 'text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60';
                                    } elseif ($res->tingkat_disiplin >= 70) {
                                        $barColor = 'bg-blue-500';
                                        $textColor = 'text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800/60';
                                    }
                                @endphp
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-100 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                                        <div class="{{ $barColor }} h-2 rounded-full" style="width: {{ $res->tingkat_disiplin }}%"></div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black font-mono {{ $textColor }}">
                                        {{ number_format($res->tingkat_disiplin, 1) }}%
                                    </span>
                                </div>
                            </div>

                            {{-- expandable: pelanggar list inline --}}
                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden" x-data="{ showDetail: false }">
                                <button type="button" @click="showDetail = !showDetail" class="w-full flex items-center justify-between px-3 py-2 text-xs font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition">
                                    <span class="flex items-center gap-2"><i class="fas fa-user-clock text-teal-600 dark:text-teal-400"></i> Peserta Pelanggaran</span>
                                    <span class="text-[10px] text-gray-400 dark:text-gray-500">Hadir {{ $res->total_hadir }} | Sakit {{ $res->total_sakit }} | Izin {{ $res->total_izin }}</span>
                                    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" :class="showDetail ? 'rotate-180 text-teal-600 dark:text-teal-400' : ''"></i>
                                </button>
                                <div x-show="showDetail" x-transition.opacity x-cloak class="border-t border-gray-100 dark:border-gray-700 p-3 space-y-2">
                                    @if(count($res->pelanggar_list) > 0)
                                        @foreach($res->pelanggar_list as $p)
                                        <div class="flex items-start justify-between gap-2 py-1.5 border-b border-gray-100 dark:border-gray-700/60 last:border-0">
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-gray-900 dark:text-gray-100">{{ $p['nama'] }}</p>
                                                <p class="text-[10px] text-gray-500 dark:text-gray-400">{{ $p['kampus'] }} · {{ $p['posisi'] }}</p>
                                            </div>
                                            <div class="flex gap-2 shrink-0 text-[10px] font-mono">
                                                <span class="text-orange-600 dark:text-orange-400 font-bold">Telat {{ $p['terlambat'] }}x</span>
                                                <span class="text-rose-600 dark:text-rose-400 font-bold">Alpa {{ $p['alpa'] }}x</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-3">
                                            <i class="fas fa-check-circle text-emerald-500 dark:text-emerald-400 text-xl mb-1"></i>
                                            <p class="text-xs font-bold text-gray-800 dark:text-gray-200">Tidak Ada Pelanggaran</p>
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Nihil telat & alpa.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-10 text-center text-gray-400 dark:text-gray-500">
                            <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-900 border border-transparent dark:border-gray-700 flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-search text-xl text-gray-300 dark:text-gray-600"></i>
                            </div>
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400">Data tidak ditemukan.</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Coba sesuaikan filter pencarian Anda.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
