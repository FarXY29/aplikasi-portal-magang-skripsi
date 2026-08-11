<x-app-layout>
    @php
        $isUnfiltered = !request('q') && !request('instansi') && !request('instansi_id') && !request('predikat');
    @endphp
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-chart-line text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Analisis Kompetensi & Performa') }}
            </h2>
            <div class="text-xs font-bold text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-4 py-2 rounded-2xl shadow-xs border border-gray-200 dark:border-gray-700">
                Total Terfilter: <span class="font-black text-teal-600 dark:text-teal-400 font-mono">{{ $stats['total'] }}</span> Peserta
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Back Navigation & Download Buttons --}}
            <div class="flex justify-between items-center print:hidden">
                <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Pusat Laporan
                </a>
                
                @if($stats['total'] > 0)
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.laporan.grading.print', array_merge(request()->query(), ['format' => 'pdf'])) }}" target="_blank" class="flex-1 sm:flex-none px-3.5 py-1.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-gray-700 rounded-xl font-bold text-xs transition shadow-xs flex items-center justify-center gap-1.5" title="Download PDF">
                        <i class="fas fa-file-pdf text-rose-500"></i> PDF
                    </a>
                </div>
                @endif
            </div>

            {{-- 6 Stats Cards Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Total seluruh peserta magang se-Kota Banjarmasin yang telah memiliki penilaian evaluasi akhir.">
                    <div class="w-9 h-9 rounded-2xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-3 border border-teal-100 dark:border-teal-800/60 shadow-xs">
                        <i class="fas fa-users text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100 font-mono tracking-tight">{{ number_format($stats['total']) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Dinilai</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Jumlah peserta dengan predikat 'Sangat Baik'. Kriteria Nilai Rata-Rata: 86,00 s.d. 100,00.">
                    <div class="w-9 h-9 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-3 border border-emerald-100 dark:border-emerald-800/60 shadow-xs">
                        <i class="fas fa-check-circle text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono tracking-tight">{{ number_format($stats['sangat_baik']) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Sangat Baik</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Jumlah peserta dengan predikat 'Baik'. Kriteria Nilai Rata-Rata: 71,00 s.d. 85,99.">
                    <div class="w-9 h-9 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-3 border border-blue-100 dark:border-blue-800/60 shadow-xs">
                        <i class="fas fa-thumbs-up text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-blue-600 dark:text-blue-400 font-mono tracking-tight">{{ number_format($stats['baik']) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Baik</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Jumlah peserta dengan predikat 'Cukup'. Kriteria Nilai Rata-Rata: 56,00 s.d. 70,99.">
                    <div class="w-9 h-9 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto mb-3 border border-amber-100 dark:border-amber-800/60 shadow-xs">
                        <i class="fas fa-info-circle text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-amber-600 dark:text-amber-400 font-mono tracking-tight">{{ number_format($stats['cukup']) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Cukup</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Jumlah peserta dengan predikat 'Kurang'. Kriteria Nilai Rata-Rata: 0,00 s.d. 55,99.">
                    <div class="w-9 h-9 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-3 border border-rose-100 dark:border-rose-800/60 shadow-xs">
                        <i class="fas fa-times-circle text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-rose-600 dark:text-rose-400 font-mono tracking-tight">{{ number_format($stats['kurang']) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Kurang</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help bg-gradient-to-br from-teal-50/50 via-white to-indigo-50/30 dark:from-teal-950/20 dark:via-gray-800 dark:to-indigo-950/20" title="Nilai rata-rata evaluasi akhir akumulasi seluruh peserta yang telah dinilai di Kota Banjarmasin. Rumus: (Total Nilai Rata-Rata Seluruh Peserta / Jumlah Peserta Dinilai).">
                    <div class="w-9 h-9 rounded-2xl bg-teal-600 text-white flex items-center justify-center mx-auto mb-3 shadow-xs">
                        <i class="fas fa-star text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-teal-600 dark:text-teal-400 font-mono tracking-tight">{{ $stats['avg_nilai'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Rerata Nilai</p>
                </div>
            </div>

            {{-- 3-Component Rerata Mini Panel --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 grid grid-cols-1 md:grid-cols-3 gap-6 cursor-help" title="Perbandingan nilai rata-rata 3 aspek utama: Teknis, Disiplin, dan Perilaku peserta se-Kota Banjarmasin.">
                <div class="cursor-help" title="Rata-rata aspek keahlian teknis peserta. Rumus: (Total Nilai Teknis / Jumlah Peserta Dinilai). Nilai Teknis = Skill Pengetahuan.">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-laptop-code text-blue-500 dark:text-blue-400"></i> Rerata Teknis
                        </span>
                        <span class="text-sm font-black text-gray-800 dark:text-gray-200 font-mono">{{ $statsGlobal['avg_teknis'] }}/100</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-900 h-2 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $statsGlobal['avg_teknis'] }}%"></div>
                    </div>
                </div>
                <div class="cursor-help" title="Rata-rata aspek kedisiplinan dan ketepatan waktu peserta. Rumus: (Total Nilai Disiplin / Jumlah Peserta Dinilai).">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-calendar-check text-emerald-500 dark:text-emerald-400"></i> Rerata Disiplin
                        </span>
                        <span class="text-sm font-black text-gray-800 dark:text-gray-200 font-mono">{{ $statsGlobal['avg_disiplin'] }}/100</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-900 h-2 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $statsGlobal['avg_disiplin'] }}%"></div>
                    </div>
                </div>
                <div class="cursor-help" title="Rata-rata aspek etika, keaktifan, dan komunikasi peserta. Rumus: (Total Nilai Perilaku / Jumlah Peserta). Nilai Perilaku = (Adaptasi + Kreativitas + Kerajinan) / 3.">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-user-friends text-purple-500 dark:text-purple-400"></i> Rerata Perilaku
                        </span>
                        <span class="text-sm font-black text-gray-800 dark:text-gray-200 font-mono">{{ $statsGlobal['avg_perilaku'] }}/100</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-900 h-2 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $statsGlobal['avg_perilaku'] }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Collapsible Top 3 Podium Leaderboard --}}
            @if($podium->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 transition-all duration-300 cursor-help" x-data="{ showTop3: false }" title="Peringkat 3 alumni peserta magang dengan pencapaian nilai akhir evaluasi tertinggi se-Kota Banjarmasin.">
                <div class="flex items-center justify-between cursor-pointer select-none" @click="showTop3 = !showTop3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shadow-xs">
                            <i class="fas fa-trophy text-lg"></i>
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
                            <div class="w-full bg-gradient-to-t from-gray-100 to-gray-200/50 dark:from-gray-900 dark:to-gray-800/80 rounded-t-2xl pt-8 pb-4 text-center border-t border-gray-200 dark:border-gray-700 shadow-xs flex flex-col justify-center items-center h-28">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">SKOR AKHIR</span>
                                <span class="text-2xl font-black text-gray-700 dark:text-gray-200 font-mono mt-1">{{ $p2['rata_rata'] }}</span>
                                <span class="text-[9px] font-extrabold text-gray-500 dark:text-gray-400 mt-1 uppercase">{{ $p2['predikat'] }}</span>
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
                                    {{ strtoupper(substr($p1['nama'], 0, 2)) }}
                                    <span class="absolute -top-1 -right-1 w-7 h-7 rounded-full bg-amber-500 text-white text-[11px] font-black flex items-center justify-center border-2 border-white dark:border-gray-800 shadow">1</span>
                                </div>
                            </div>
                            <div class="text-center mb-2">
                                <p class="font-black text-gray-900 dark:text-gray-100 text-base truncate max-w-[200px]">{{ $p1['nama'] }}</p>
                                <p class="text-xs text-gray-700 dark:text-gray-300 truncate max-w-[200px] font-bold">{{ $p1['asal_instansi'] }}</p>
                                <p class="text-[10px] text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/50 border border-teal-200 dark:border-teal-900/40 px-2.5 py-0.5 rounded-full inline-block font-extrabold mt-1">{{ $p1['instansi'] }}</p>
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
                            <div class="w-full bg-gradient-to-t from-orange-50/50 to-orange-100/30 dark:from-orange-950/40 dark:to-orange-900/20 rounded-t-2xl pt-8 pb-4 text-center border-t border-orange-200 dark:border-orange-900/40 shadow-xs flex flex-col justify-center items-center h-24">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">SKOR AKHIR</span>
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
                <div class="bg-white dark:bg-gray-800 shadow-xs sm:rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    
                    {{-- Card Header: Title & Integrated Right-Side Filters --}}
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col xl:flex-row xl:items-center justify-between gap-4 bg-gray-50/50 dark:bg-gray-900/50">
                        <div>
                            <h3 class="font-extrabold text-gray-900 dark:text-gray-100 text-lg flex items-center gap-2.5">
                                <i class="fas fa-chart-line text-teal-600 dark:text-teal-400"></i>
                                Daftar Analisis Kompetensi & Performa
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Daftar peringkat berdasarkan nilai rata-rata akhir peserta terfilter.</p>
                        </div>

                        {{-- Integrated Right-Side Filter Form --}}
                        <form method="GET" action="{{ route('admin.laporan.grading') }}" class="flex flex-wrap sm:flex-nowrap lg:flex-row gap-2.5 items-center w-full xl:w-auto">
                            <!-- Search Name -->
                            <div class="relative w-full sm:w-44">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 pointer-events-none">
                                    <i class="fas fa-search text-xs"></i>
                                </span>
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama..."
                                    class="w-full pl-9 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-xl text-xs font-bold focus:ring-teal-500 focus:border-teal-500 shadow-xs">
                            </div>

                            <!-- Filter Kampus -->
                            <div class="w-full sm:w-40">
                                <select name="instansi" class="w-full py-2 px-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-100 focus:ring-teal-500 focus:border-teal-500 shadow-xs cursor-pointer [color-scheme:dark]">
                                    <option value="">Semua Kampus</option>
                                    @foreach($listCampus as $campus)
                                        <option value="{{ $campus }}" {{ request('instansi') == $campus ? 'selected' : '' }}>
                                            {{ Str::limit($campus, 25) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Dinas -->
                            <div class="w-full sm:w-40">
                                <select name="instansi_id" class="w-full py-2 px-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-100 focus:ring-teal-500 focus:border-teal-500 shadow-xs cursor-pointer [color-scheme:dark]">
                                    <option value="">Semua Dinas</option>
                                    @foreach($listDinas as $dinas)
                                        <option value="{{ $dinas->id }}" {{ request('instansi_id') == $dinas->id ? 'selected' : '' }}>
                                            {{ Str::limit($dinas->nama_dinas, 25) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Predikat -->
                            <div class="w-full sm:w-36">
                                <select name="predikat" class="w-full py-2 px-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-100 focus:ring-teal-500 focus:border-teal-500 shadow-xs cursor-pointer [color-scheme:dark]">
                                    <option value="">Semua Predikat</option>
                                    <option value="Sangat Baik" {{ request('predikat') == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                                    <option value="Baik" {{ request('predikat') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Cukup" {{ request('predikat') == 'Cukup' ? 'selected' : '' }}>Cukup</option>
                                    <option value="Kurang" {{ request('predikat') == 'Kurang' ? 'selected' : '' }}>Kurang</option>
                                </select>
                            </div>

                            <div class="flex gap-2 w-full sm:w-auto">
                                <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 w-full sm:w-auto">
                                    <i class="fas fa-filter text-xs"></i> Filter
                                </button>
                                @if(request()->anyFilled(['q', 'instansi', 'instansi_id', 'predikat']))
                                    <a href="{{ route('admin.laporan.grading') }}" class="px-3 py-2 border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl font-bold text-xs shadow-xs transition flex items-center justify-center">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Desktop Table View (>=md) --}}
                    <div class="hidden md:block overflow-x-auto max-h-[650px] overflow-y-auto">
                        <table class="w-full divide-y divide-gray-100 dark:divide-gray-700 border-collapse">
                            <thead class="bg-gray-50 dark:bg-gray-900 sticky top-0 z-20">
                                <tr>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-14">Rank</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-[200px] max-w-[260px]">Peserta & Asal Sekolah</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-[220px] max-w-[280px]">Penempatan Dinas & Posisi</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28">Skor Akhir</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">Predikat</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                                @forelse($gradedList as $index => $res)
                                <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-950/20 transition group cursor-pointer" @click="openRow = (openRow === {{ $index }} ? null : {{ $index }})">
                                    <td class="px-4 py-4 text-center">
                                        @if($index == 0 && $isUnfiltered)
                                            <div class="w-7 h-7 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto shadow-xs border border-amber-200 dark:border-amber-800/50">
                                                <i class="fas fa-crown text-xs"></i>
                                            </div>
                                        @elseif($index == 1 && $isUnfiltered)
                                            <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 flex items-center justify-center mx-auto border border-gray-300 dark:border-gray-700 font-bold text-xs">2</div>
                                        @elseif($index == 2 && $isUnfiltered)
                                            <div class="w-7 h-7 rounded-full bg-amber-100/60 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 flex items-center justify-center mx-auto border border-amber-200 dark:border-amber-800/50 font-bold text-xs">3</div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 font-bold text-xs">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 min-w-[200px] max-w-[260px]">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 font-black border border-teal-200 dark:border-teal-800/60 text-xs flex-shrink-0 flex items-center justify-center">
                                                {{ strtoupper(substr($res['nama'], 0, 2)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-bold text-gray-900 dark:text-gray-100 leading-snug line-clamp-2" title="{{ $res['nama'] }}">{{ $res['nama'] }}</div>
                                                <div class="text-[11px] text-gray-500 dark:text-gray-400 font-semibold leading-snug line-clamp-2 mt-0.5" title="{{ $res['asal_instansi'] }}">
                                                    <i class="fas fa-university mr-1 text-gray-400 dark:text-gray-500"></i> {{ $res['asal_instansi'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 min-w-[220px] max-w-[280px]">
                                        <div class="flex flex-col gap-0.5">
                                            <span class="font-bold text-gray-900 dark:text-gray-100 text-xs leading-snug line-clamp-2" title="{{ $res['instansi'] }}">
                                                <i class="far fa-building text-gray-400 dark:text-gray-500 mr-1"></i>
                                                {{ $res['instansi'] }}
                                            </span>
                                            <span class="text-[11px] text-teal-600 dark:text-teal-400 font-medium leading-snug line-clamp-2 mt-0.5" title="{{ $res['posisi'] }}">
                                                Posisi: {{ $res['posisi'] }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="text-sm font-black text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 px-2.5 py-1 rounded-full inline-block font-mono">{{ $res['rata_rata'] }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        @php
                                            $badgeColor = match($res['predikat']) {
                                                'Sangat Baik' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                                                'Baik' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800/60',
                                                'Cukup' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
                                                default => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60'
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-full border {{ $badgeColor }}">
                                            {{ $res['predikat'] }}
                                        </span>
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
                                            <h4 class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2 border-b pb-2 border-gray-100 dark:border-gray-700">
                                                <i class="fas fa-award text-teal-600 dark:text-teal-400"></i> Rincian Penilaian Kompetensi & Performa Peserta
                                            </h4>
                                            
                                            @if($res['nilai_rata_rata'] !== null)
                                                {{-- New Grading System --}}
                                                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                                        <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Kerajinan</div>
                                                        <div class="text-xl font-black text-gray-800 dark:text-gray-200 font-mono mt-1">{{ $res['kerajinan'] }}</div>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                                        <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Kedisiplinan</div>
                                                        <div class="text-xl font-black text-gray-800 dark:text-gray-200 font-mono mt-1">{{ $res['disiplin'] }}</div>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                                        <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Adaptasi</div>
                                                        <div class="text-xl font-black text-gray-800 dark:text-gray-200 font-mono mt-1">{{ $res['adaptasi'] }}</div>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                                        <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Kreatifitas</div>
                                                        <div class="text-xl font-black text-gray-800 dark:text-gray-200 font-mono mt-1">{{ $res['kreatifitas'] }}</div>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700 col-span-2 md:col-span-1">
                                                        <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Skill & Pengetahuan</div>
                                                        <div class="text-xl font-black text-gray-800 dark:text-gray-200 font-mono mt-1">{{ $res['skill'] }}</div>
                                                    </div>
                                                </div>
                                                <div class="mt-3 flex items-center justify-between text-xs text-gray-400 dark:text-gray-500 italic">
                                                    <span>*Sistem Penilaian Utama (5 Aspek)</span>
                                                    <span>Rata-rata: <strong class="text-gray-800 dark:text-gray-200 font-mono">{{ $res['rata_rata'] }}</strong></span>
                                                </div>
                                            @else
                                                {{-- Old Grading System --}}
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                                        <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Kompetensi Teknis</div>
                                                        <div class="text-xl font-black text-gray-800 dark:text-gray-200 font-mono mt-1">{{ $res['teknis'] }}</div>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                                        <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Kedisiplinan</div>
                                                        <div class="text-xl font-black text-gray-800 dark:text-gray-200 font-mono mt-1">{{ $res['disiplin'] }}</div>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                                        <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Sikap & Perilaku</div>
                                                        <div class="text-xl font-black text-gray-800 dark:text-gray-200 font-mono mt-1">{{ $res['perilaku'] }}</div>
                                                    </div>
                                                </div>
                                                <div class="mt-3 flex items-center justify-between text-xs text-gray-400 dark:text-gray-500 italic">
                                                    <span>*Sistem Penilaian Tambahan (3 Aspek)</span>
                                                    <span>Rata-rata: <strong class="text-gray-800 dark:text-gray-200 font-mono">{{ $res['rata_rata'] }}</strong></span>
                                                </div>
                                            @endif
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
                        @forelse($gradedList as $index => $res)
                        <div class="p-4 space-y-3.5 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition" x-data="{ open: false }">
                            {{-- Header: rank icon + peserta + predikat badge --}}
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="flex-shrink-0">
                                        @if($index == 0 && $isUnfiltered)
                                            <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shadow-xs border border-amber-200 dark:border-amber-800/50">
                                                <i class="fas fa-crown text-xs"></i>
                                            </div>
                                        @elseif($index == 1 && $isUnfiltered)
                                            <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 flex items-center justify-center border border-gray-300 dark:border-gray-700 font-bold text-xs">2</div>
                                        @elseif($index == 2 && $isUnfiltered)
                                            <div class="w-8 h-8 rounded-full bg-amber-100/60 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 flex items-center justify-center border border-amber-200 dark:border-amber-800/50 font-bold text-xs">3</div>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gray-50 dark:bg-gray-900 text-gray-400 dark:text-gray-500 flex items-center justify-center border border-gray-200 dark:border-gray-700 font-bold text-xs">#{{ $index + 1 }}</div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 leading-snug line-clamp-2" title="{{ $res['nama'] }}">{{ $res['nama'] }}</h4>
                                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-semibold leading-snug line-clamp-2 mt-0.5" title="{{ $res['asal_instansi'] }}">
                                            <i class="fas fa-university mr-1 text-gray-400 dark:text-gray-500"></i>{{ $res['asal_instansi'] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    @php
                                        $badgeColorMobile = match($res['predikat']) {
                                            'Sangat Baik' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                                            'Baik' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800/60',
                                            'Cukup' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
                                            default => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60'
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-full border {{ $badgeColorMobile }}">
                                        {{ $res['predikat'] }}
                                    </span>
                                </div>
                            </div>

                            {{-- Detail: dinas, posisi, skor akhir --}}
                            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-100 dark:border-gray-700 space-y-2.5">
                                <div class="flex items-start gap-2 text-xs">
                                    <i class="far fa-building text-gray-400 dark:text-gray-500 mt-0.5"></i>
                                    <span class="font-bold text-gray-900 dark:text-gray-100 leading-snug line-clamp-2" title="{{ $res['instansi'] }}">{{ $res['instansi'] }}</span>
                                </div>
                                <div class="flex items-start gap-2 text-[11px]">
                                    <i class="fas fa-briefcase text-teal-500 dark:text-teal-400 mt-0.5"></i>
                                    <span class="text-teal-600 dark:text-teal-400 font-medium leading-snug line-clamp-2" title="{{ $res['posisi'] }}">{{ $res['posisi'] }}</span>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <span class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Skor Akhir</span>
                                    <span class="text-sm font-black text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 px-2.5 py-1 rounded-full inline-block font-mono">{{ $res['rata_rata'] }}</span>
                                </div>
                            </div>

                            {{-- Expandable detail (rincian penilaian) --}}
                            <button type="button" @click="open = !open" class="w-full flex items-center justify-center gap-1.5 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <span x-text="open ? 'Sembunyikan Rincian' : 'Lihat Rincian'"></span>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180 text-teal-600 dark:text-teal-400' : ''"></i>
                            </button>
                            <div x-show="open" x-transition.opacity x-cloak class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 shadow-xs space-y-3">
                                <h4 class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2 border-b pb-2 border-gray-100 dark:border-gray-700">
                                    <i class="fas fa-award text-teal-600 dark:text-teal-400"></i> Rincian Penilaian
                                </h4>
                                @if($res['nilai_rata_rata'] !== null)
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="bg-gray-50 dark:bg-gray-900 p-2.5 rounded-xl border border-gray-200 dark:border-gray-700">
                                            <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Kerajinan</div>
                                            <div class="text-base font-black text-gray-800 dark:text-gray-200 font-mono mt-0.5">{{ $res['kerajinan'] }}</div>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-900 p-2.5 rounded-xl border border-gray-200 dark:border-gray-700">
                                            <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Kedisiplinan</div>
                                            <div class="text-base font-black text-gray-800 dark:text-gray-200 font-mono mt-0.5">{{ $res['disiplin'] }}</div>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-900 p-2.5 rounded-xl border border-gray-200 dark:border-gray-700">
                                            <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Adaptasi</div>
                                            <div class="text-base font-black text-gray-800 dark:text-gray-200 font-mono mt-0.5">{{ $res['adaptasi'] }}</div>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-900 p-2.5 rounded-xl border border-gray-200 dark:border-gray-700">
                                            <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Kreatifitas</div>
                                            <div class="text-base font-black text-gray-800 dark:text-gray-200 font-mono mt-0.5">{{ $res['kreatifitas'] }}</div>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-900 p-2.5 rounded-xl border border-gray-200 dark:border-gray-700 col-span-2">
                                            <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Skill & Pengetahuan</div>
                                            <div class="text-base font-black text-gray-800 dark:text-gray-200 font-mono mt-0.5">{{ $res['skill'] }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-gray-400 dark:text-gray-500 italic">
                                        <span>*Sistem Penilaian Utama (5 Aspek)</span>
                                        <span>Rata-rata: <strong class="text-gray-800 dark:text-gray-200 font-mono">{{ $res['rata_rata'] }}</strong></span>
                                    </div>
                                @else
                                    <div class="grid grid-cols-3 gap-2">
                                        <div class="bg-gray-50 dark:bg-gray-900 p-2.5 rounded-xl border border-gray-200 dark:border-gray-700">
                                            <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Teknis</div>
                                            <div class="text-base font-black text-gray-800 dark:text-gray-200 font-mono mt-0.5">{{ $res['teknis'] }}</div>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-900 p-2.5 rounded-xl border border-gray-200 dark:border-gray-700">
                                            <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Disiplin</div>
                                            <div class="text-base font-black text-gray-800 dark:text-gray-200 font-mono mt-0.5">{{ $res['disiplin'] }}</div>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-900 p-2.5 rounded-xl border border-gray-200 dark:border-gray-700">
                                            <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase">Perilaku</div>
                                            <div class="text-base font-black text-gray-800 dark:text-gray-200 font-mono mt-0.5">{{ $res['perilaku'] }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-gray-400 dark:text-gray-500 italic">
                                        <span>*Sistem Penilaian Tambahan (3 Aspek)</span>
                                        <span>Rata-rata: <strong class="text-gray-800 dark:text-gray-200 font-mono">{{ $res['rata_rata'] }}</strong></span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="p-10 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-700">
                                    <i class="fas fa-search text-2xl"></i>
                                </div>
                                <p class="text-gray-900 dark:text-gray-100 font-bold">Data tidak ditemukan</p>
                                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Coba sesuaikan filter pencarian Anda.</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>