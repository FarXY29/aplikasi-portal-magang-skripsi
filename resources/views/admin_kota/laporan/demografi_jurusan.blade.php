<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Demografi Kualifikasi Jurusan') }}
            </h2>
            <div class="text-xs font-bold text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-4 py-2 rounded-2xl shadow-xs border border-gray-200 dark:border-gray-700">
                Total Kualifikasi: <span class="font-black text-teal-600 dark:text-teal-400 font-mono">{{ $jurusans->count() }}</span> Jurusan
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans" x-data="{ searchQuery: '{{ request('q') }}' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Navigation & Export Buttons --}}
            <div class="flex justify-between items-center print:hidden">
                <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Pusat Laporan
                </a>
                
                @if($jurusans->count() > 0)
                <div class="flex gap-2">
                    <a href="{{ route('admin.laporan.demografi_jurusan.print', array_merge(request()->query(), ['format' => 'pdf'])) }}" target="_blank" class="px-3.5 py-1.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-gray-700 rounded-xl font-bold text-xs transition shadow-xs flex items-center gap-1.5" title="Download PDF">
                        <i class="fas fa-file-pdf text-rose-500"></i> PDF
                    </a>
                    <a href="{{ route('admin.laporan.demografi_jurusan.print', array_merge(request()->query(), ['format' => 'excel'])) }}" class="px-3.5 py-1.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-gray-700 rounded-xl font-bold text-xs transition shadow-xs flex items-center gap-1.5" title="Download Excel">
                        <i class="fas fa-file-excel text-emerald-500"></i> Excel
                    </a>
                    <a href="{{ route('admin.laporan.demografi_jurusan.print', array_merge(request()->query(), ['format' => 'csv'])) }}" class="px-3.5 py-1.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-gray-700 rounded-xl font-bold text-xs transition shadow-xs flex items-center gap-1.5" title="Download CSV">
                        <i class="fas fa-file-csv text-blue-500"></i> CSV
                    </a>
                </div>
                @endif
            </div>

            {{-- 4 Summary KPI Stats Cards --}}
            @php
                $totalLowongan = $jurusans->sum('total_lowongan');
                $totalKuota = $jurusans->sum('total_kuota');
                $topJurusan = $jurusans->first() ? $jurusans->first()->required_major : '-';
                $maxKuota = $jurusans->max('total_kuota') ?: 1;
            @endphp
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-2xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-3 border border-teal-100 dark:border-teal-800/60 shadow-xs">
                        <i class="fas fa-graduation-cap text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100 font-mono tracking-tight">{{ number_format($jurusans->count()) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Kualifikasi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-3 border border-blue-100 dark:border-blue-800/60 shadow-xs">
                        <i class="fas fa-briefcase text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-blue-600 dark:text-blue-400 font-mono tracking-tight">{{ number_format($totalLowongan) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Lowongan Terbuka</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-3 border border-indigo-100 dark:border-indigo-800/60 shadow-xs">
                        <i class="fas fa-chair text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400 font-mono tracking-tight">{{ number_format($totalKuota) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Kuota Kursi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition bg-gradient-to-br from-teal-50/50 via-white to-indigo-50/30 dark:from-teal-950/20 dark:via-gray-800 dark:to-indigo-950/20">
                    <div class="w-9 h-9 rounded-2xl bg-teal-600 text-white flex items-center justify-center mx-auto mb-3 shadow-xs">
                        <i class="fas fa-star text-xs"></i>
                    </div>
                    <p class="text-xs font-extrabold text-teal-600 dark:text-teal-400 truncate w-full px-1" title="{{ $topJurusan }}">{{ $topJurusan }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Jurusan Terfavorit</p>
                </div>
            </div>

            {{-- Main Table Card with Integrated Right-Side Filters --}}
            <div class="w-full space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-xs rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    
                    {{-- Card Header: Title & Integrated Right-Side Filters --}}
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50 dark:bg-gray-900/50">
                        <div>
                            <h3 class="font-extrabold text-gray-900 dark:text-gray-100 text-lg flex items-center gap-2.5">
                                <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400"></i>
                                Daftar Pemeringkatan Jurusan Paling Dicari
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Kualifikasi jurusan terurut dari alokasi kuota terbanyak ke terendah.</p>
                        </div>

                        {{-- Integrated Right-Side Filter Form --}}
                        <form method="GET" action="{{ route('admin.laporan.demografi_jurusan') }}" class="flex flex-col sm:flex-row gap-2.5 items-center w-full sm:w-auto">
                            <div class="relative w-full sm:w-64">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 pointer-events-none">
                                    <i class="fas fa-search text-xs"></i>
                                </span>
                                <input type="text" name="q" value="{{ request('q') }}" x-model="searchQuery"
                                    placeholder="Cari nama jurusan..."
                                    class="w-full pl-9 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-xl text-xs font-bold focus:ring-teal-500 focus:border-teal-500 shadow-xs">
                            </div>

                            <div class="flex gap-2 w-full sm:w-auto">
                                <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 w-full sm:w-auto">
                                    <i class="fas fa-filter text-xs"></i> Filter
                                </button>
                                @if(request()->filled('q'))
                                    <a href="{{ route('admin.laporan.demografi_jurusan') }}" class="px-3 py-2 border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl font-bold text-xs shadow-xs transition flex items-center justify-center">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Table Content --}}
                    <div class="overflow-x-auto max-h-[650px] overflow-y-auto">
                        <table class="w-full divide-y divide-gray-100 dark:divide-gray-700 border-collapse">
                            <thead class="bg-gray-50 dark:bg-gray-900 sticky top-0 z-20">
                                <tr>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-14">Rank</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-[220px] max-w-[320px]">Jurusan / Kualifikasi</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">Lowongan Terkait</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Visual Proporsi Kuota</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">Total Kuota Kursi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                                @forelse($jurusans as $index => $jurusan)
                                <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-950/20 transition group"
                                    x-show="!searchQuery || '{{ strtolower($jurusan->required_major) }}'.includes(searchQuery.toLowerCase())">
                                    <td class="px-4 py-4 text-center">
                                        @if($index == 0)
                                            <div class="w-7 h-7 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto shadow-xs border border-amber-200 dark:border-amber-800/50">
                                                <i class="fas fa-crown text-xs"></i>
                                            </div>
                                        @elseif($index == 1)
                                            <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 flex items-center justify-center mx-auto border border-gray-300 dark:border-gray-700 font-bold text-xs">2</div>
                                        @elseif($index == 2)
                                            <div class="w-7 h-7 rounded-full bg-amber-100/60 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 flex items-center justify-center mx-auto border border-amber-200 dark:border-amber-800/50 font-bold text-xs">3</div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 font-bold text-xs">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 min-w-[220px] max-w-[320px]">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 font-black border border-teal-200 dark:border-teal-800/60 text-xs flex-shrink-0 flex items-center justify-center">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-bold text-gray-900 dark:text-gray-100 leading-snug line-clamp-2" title="{{ $jurusan->required_major }}">{{ $jurusan->required_major }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="px-3 py-1 bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800/60 text-blue-700 dark:text-blue-300 rounded-full font-bold text-xs inline-block">
                                            <strong class="font-mono">{{ $jurusan->total_lowongan }}</strong> Lowongan
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @php
                                                $percentage = round(($jurusan->total_kuota / $maxKuota) * 100);
                                            @endphp
                                            <div class="w-full bg-gray-100 dark:bg-gray-900 h-2 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                                                <div class="bg-gradient-to-r from-teal-500 to-indigo-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="text-[10px] font-bold font-mono text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $percentage }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="font-black text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 px-3.5 py-1 rounded-full text-xs inline-block font-mono">
                                            {{ $jurusan->total_kuota }} Kursi
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-700">
                                                <i class="fas fa-search text-2xl"></i>
                                            </div>
                                            <p class="text-gray-900 dark:text-gray-100 font-bold">Data jurusan tidak ditemukan</p>
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
