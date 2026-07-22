<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-chart-bar text-teal-600 dark:text-teal-400"></i>
                {{ __('Laporan Penyerapan Kuota (City-Wide)') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400 font-medium bg-white dark:bg-gray-800 px-4 py-1.5 rounded-full shadow-sm border border-gray-100 dark:border-gray-700">
                Total: <span class="font-bold text-teal-600 dark:text-teal-400">{{ $penyerapan->count() }}</span> Instansi
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans" x-data="{ searchQuery: '{{ request('q') }}' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Navigation & Export Buttons --}}
            <div class="flex justify-between items-center print:hidden">
                <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-sm">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Pusat Laporan
                </a>
                
                @if($penyerapan->count() > 0)
                <div class="flex gap-2">
                    <a href="{{ route('admin.laporan.penyerapan_kuota.print', array_merge(request()->query(), ['format' => 'pdf'])) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-300 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-teal-950/30 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download PDF">
                        <i class="fas fa-file-pdf text-red-500"></i> <span class="hidden sm:inline">PDF</span>
                    </a>
                    <a href="{{ route('admin.laporan.penyerapan_kuota.print', array_merge(request()->query(), ['format' => 'excel'])) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-300 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-teal-950/30 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download Excel">
                        <i class="fas fa-file-excel text-green-600"></i> <span class="hidden sm:inline">Excel</span>
                    </a>
                    <a href="{{ route('admin.laporan.penyerapan_kuota.print', array_merge(request()->query(), ['format' => 'csv'])) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-300 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-teal-950/30 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download CSV">
                        <i class="fas fa-file-csv text-blue-600"></i> <span class="hidden sm:inline">CSV</span>
                    </a>
                </div>
                @endif
            </div>

            {{-- 4 Summary KPI Stats Cards --}}
            @php
                $totalKuotaKota = $penyerapan->sum('total_kuota');
                $totalTerserapKota = $penyerapan->sum('total_terserap');
                $avgPenyerapanKota = $totalKuotaKota > 0 ? round(($totalTerserapKota / $totalKuotaKota) * 100, 1) : 0;
            @endphp
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-xl bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-3 border border-teal-100 dark:border-teal-900/40">
                        <i class="fas fa-building text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight">{{ $penyerapan->count() }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Instansi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-3 border border-blue-100 dark:border-blue-900/40">
                        <i class="fas fa-chair text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-blue-600 dark:text-blue-400 tracking-tight">{{ $totalKuotaKota }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Kuota Disediakan</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-xl bg-green-50 dark:bg-green-950/40 text-green-600 dark:text-green-400 flex items-center justify-center mx-auto mb-3 border border-green-100 dark:border-green-900/40">
                        <i class="fas fa-user-check text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-green-600 dark:text-green-400 tracking-tight">{{ $totalTerserapKota }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Peserta Terserap</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition bg-gradient-to-br from-teal-50/50 via-white to-indigo-50/30 dark:from-teal-950/20 dark:via-gray-800 dark:to-indigo-950/20">
                    <div class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center mx-auto mb-3 shadow-sm shadow-teal-500/20">
                        <i class="fas fa-chart-pie text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-teal-700 dark:text-teal-400 tracking-tight">{{ $avgPenyerapanKota }}%</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Rerata Penyerapan Kota</p>
                </div>
            </div>

            {{-- Highlight Banner --}}
            <div class="bg-gradient-to-r from-teal-800 via-teal-700 to-indigo-700 dark:from-teal-900 dark:via-teal-950 dark:to-indigo-950 rounded-3xl p-6 text-white shadow-lg border border-teal-600/30 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/10 dark:bg-gray-800/40 backdrop-blur-md flex items-center justify-center text-3xl flex-shrink-0 border border-white/20 dark:border-gray-700 shadow-md">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="text-center sm:text-left flex-grow space-y-1">
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-teal-200">Evaluasi Efektivitas Kuota Magang Kota</p>
                    <h2 class="text-xl font-extrabold mt-0.5 tracking-tight">Rasio Keterisian Kuota Magang per Instansi</h2>
                    <p class="text-xs text-teal-50/90 font-medium">Evaluasi seberapa baik setiap instansi pemerintah daerah dalam memanfaatkan dan memenuhi kuota yang dibuka.</p>
                </div>
            </div>

            {{-- Filter GET Form Card --}}
            <form method="GET" action="{{ route('admin.laporan.penyerapan_kuota') }}" class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 print:hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Pencarian Nama Instansi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 pointer-events-none">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" name="q" value="{{ request('q') }}" x-model="searchQuery"
                                placeholder="Ketik nama dinas / badan..."
                                class="w-full pl-9 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Status Keterisian Kuota</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 pointer-events-none">
                                <i class="fas fa-chart-pie text-xs"></i>
                            </span>
                            <select name="status_keterisian" class="w-full pl-9 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 hover:bg-white dark:hover:bg-gray-800 transition">
                                <option value="">Semua Status</option>
                                <option value="optimal" {{ request('status_keterisian') == 'optimal' ? 'selected' : '' }}>Optimal (>= 80%)</option>
                                <option value="cukup" {{ request('status_keterisian') == 'cukup' ? 'selected' : '' }}>Cukup (50% - 79%)</option>
                                <option value="rendah" {{ request('status_keterisian') == 'rendah' ? 'selected' : '' }}>Rendah (< 50%)</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <button type="submit" class="bg-teal-600 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-teal-500/20 hover:bg-teal-700 transition transform active:scale-95 text-sm flex items-center gap-2">
                            <i class="fas fa-search"></i> Terapkan Filter
                        </button>
                        @if(request()->anyFilled(['q', 'status_keterisian']))
                            <a href="{{ route('admin.laporan.penyerapan_kuota') }}" class="inline-flex items-center justify-center border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Main Table Card (100% Fluid Width without Horizontal Scroll) --}}
            <div class="w-full space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-gray-200 text-lg">Daftar Penyerapan Kuota per Instansi</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Daftar tingkat penyerapan kuota terurut dari persentase tertinggi ke terendah.</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto max-h-[650px] overflow-y-auto">
                        <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900 sticky top-0 z-20">
                                <tr>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">Rank</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Instansi</th>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-36">Kuota Disediakan</th>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">Total Terserap</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tingkat Penyerapan</th>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-50 dark:divide-gray-700/60 text-sm">
                                @forelse($penyerapan as $index => $instansi)
                                <tr class="hover:bg-teal-50/15 dark:hover:bg-gray-900/60 transition group"
                                    x-show="!searchQuery || '{{ strtolower($instansi->nama_dinas) }}'.includes(searchQuery.toLowerCase())">
                                    <td class="px-3 py-3 text-center">
                                        @if($index == 0 && $instansi->persentase_penyerapan > 0)
                                            <div class="w-7 h-7 rounded-full bg-yellow-100 dark:bg-yellow-950/60 text-yellow-600 dark:text-yellow-400 flex items-center justify-center mx-auto shadow-sm ring-2 ring-yellow-200 dark:ring-yellow-800/50">
                                                <i class="fas fa-crown text-xs"></i>
                                            </div>
                                        @elseif($index == 1 && $instansi->persentase_penyerapan > 0)
                                            <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 flex items-center justify-center mx-auto border border-gray-300 dark:border-gray-700 font-bold text-xs">2</div>
                                        @elseif($index == 2 && $instansi->persentase_penyerapan > 0)
                                            <div class="w-7 h-7 rounded-full bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-400 flex items-center justify-center mx-auto border border-orange-200 dark:border-orange-800/50 font-bold text-xs">3</div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 font-bold text-xs">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-950 dark:to-teal-900 flex items-center justify-center text-teal-700 dark:text-teal-300 font-black border border-teal-200/50 dark:border-teal-800/50 text-xs flex-shrink-0">
                                                {{ strtoupper(substr($instansi->nama_dinas, 0, 2)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-bold text-gray-900 dark:text-gray-100 truncate" title="{{ $instansi->nama_dinas }}">{{ $instansi->nama_dinas }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-full font-bold text-xs inline-block">
                                            {{ $instansi->total_kuota }} Kursi
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="px-3 py-1 bg-green-50 dark:bg-green-950/40 border border-green-100 dark:border-green-900/40 text-green-700 dark:text-green-400 rounded-full font-bold text-xs inline-block">
                                            {{ $instansi->total_terserap }} Orang
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            @php
                                                $rate = min(100, round($instansi->persentase_penyerapan, 1));
                                                $barBg = 'from-red-500 to-orange-500';
                                                if ($rate >= 80) {
                                                    $barBg = 'from-teal-500 to-emerald-500';
                                                } elseif ($rate >= 50) {
                                                    $barBg = 'from-blue-500 to-indigo-500';
                                                }
                                            @endphp
                                            <div class="w-full bg-gray-100 dark:bg-gray-900 h-2 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                                                <div class="bg-gradient-to-r {{ $barBg }} h-2 rounded-full" style="width: {{ $rate }}%"></div>
                                            </div>
                                            <span class="text-xs font-black text-gray-800 dark:text-gray-100 min-w-[45px] text-right">{{ $rate }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        @if($instansi->persentase_penyerapan >= 80)
                                            <span class="px-2.5 py-0.5 text-[9px] font-black uppercase rounded-full bg-green-100 dark:bg-green-950/60 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800">Optimal</span>
                                        @elseif($instansi->persentase_penyerapan >= 50)
                                            <span class="px-2.5 py-0.5 text-[9px] font-black uppercase rounded-full bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">Cukup</span>
                                        @else
                                            <span class="px-2.5 py-0.5 text-[9px] font-black uppercase rounded-full bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-400 border border-orange-200 dark:border-orange-800">Rendah</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 text-gray-300 dark:text-gray-600 border border-transparent dark:border-gray-700">
                                                <i class="fas fa-search text-2xl"></i>
                                            </div>
                                            <p class="text-gray-900 dark:text-gray-100 font-bold">Data penyerapan tidak ditemukan</p>
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
