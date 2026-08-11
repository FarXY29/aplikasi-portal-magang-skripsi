<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-chart-bar text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Laporan Penyerapan Kuota (City-Wide)') }}
            </h2>
            <div class="text-xs font-bold text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-4 py-2 rounded-2xl shadow-xs border border-gray-200 dark:border-gray-700">
                Total Terfilter: <span class="font-black text-teal-600 dark:text-teal-400 font-mono">{{ $penyerapan->count() }}</span> Instansi
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans" x-data="{ searchQuery: @js(request('q')) }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Navigation & Export Buttons --}}
            <div class="flex justify-between items-center print:hidden">
                <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Pusat Laporan
                </a>
                
                @if($penyerapan->count() > 0)
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.laporan.penyerapan_kuota.print', request()->query()) }}" target="_blank" class="flex-1 sm:flex-none px-3.5 py-1.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-gray-700 rounded-xl font-bold text-xs transition shadow-xs flex items-center justify-center gap-1.5" title="Download PDF">
                        <i class="fas fa-file-pdf text-rose-500"></i> PDF
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
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Total jumlah instansi/dinas Pemerintah Kota Banjarmasin yang membuka formasi kuota penerimaan magang.">
                    <div class="w-9 h-9 rounded-2xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-3 border border-teal-100 dark:border-teal-800/60 shadow-xs">
                        <i class="fas fa-building text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100 font-mono tracking-tight">{{ number_format($penyerapan->count()) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Instansi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Total akumulasi daya tampung kursi magang yang disediakan oleh seluruh instansi Pemko.">
                    <div class="w-9 h-9 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-3 border border-blue-100 dark:border-blue-800/60 shadow-xs">
                        <i class="fas fa-chair text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-blue-600 dark:text-blue-400 font-mono tracking-tight">{{ number_format($totalKuotaKota) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Kuota Disediakan</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Total peserta magang (diterima & selesai) yang telah menempati dan mengisi kuota magang di dinas.">
                    <div class="w-9 h-9 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-3 border border-emerald-100 dark:border-emerald-800/60 shadow-xs">
                        <i class="fas fa-user-check text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono tracking-tight">{{ number_format($totalTerserapKota) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Peserta Terserap</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help bg-gradient-to-br from-teal-50/50 via-white to-indigo-50/30 dark:from-teal-950/20 dark:via-gray-800 dark:to-indigo-950/20" title="Persentase keterisian total kuota magang se-Kota Banjarmasin. Rumus: (Total Peserta Terserap / Total Kuota Disediakan) x 100%.">
                    <div class="w-9 h-9 rounded-2xl bg-teal-600 text-white flex items-center justify-center mx-auto mb-3 shadow-xs">
                        <i class="fas fa-chart-pie text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-teal-600 dark:text-teal-400 font-mono tracking-tight">{{ $avgPenyerapanKota }}%</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Rerata Penyerapan Kota</p>
                </div>
            </div>

            {{-- Main Table Card with Integrated Right-Side Filters --}}
            <div class="w-full space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-xs rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    
                    {{-- Card Header: Title & Integrated Right-Side Filters --}}
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-gray-50/50 dark:bg-gray-900/50">
                        <div>
                            <h3 class="font-extrabold text-gray-900 dark:text-gray-100 text-lg flex items-center gap-2.5">
                                <i class="fas fa-chart-bar text-teal-600 dark:text-teal-400"></i>
                                Daftar Penyerapan Kuota per Instansi
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Daftar tingkat penyerapan kuota terurut dari persentase tertinggi ke terendah.</p>
                        </div>

                        {{-- Integrated Right-Side Filter Form --}}
                        <form method="GET" action="{{ route('admin.laporan.penyerapan_kuota') }}" class="flex flex-col sm:flex-row gap-2.5 items-center w-full lg:w-auto">
                            <div class="relative w-full sm:w-56">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 pointer-events-none">
                                    <i class="fas fa-search text-xs"></i>
                                </span>
                                <input type="text" name="q" value="{{ request('q') }}" x-model="searchQuery"
                                    placeholder="Cari nama dinas..."
                                    class="w-full pl-9 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-xl text-xs font-bold focus:ring-teal-500 focus:border-teal-500 shadow-xs">
                            </div>

                            <div class="w-full sm:w-48">
                                <select name="status_keterisian" class="w-full py-2 px-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-100 focus:ring-teal-500 focus:border-teal-500 shadow-xs cursor-pointer [color-scheme:dark]">
                                    <option value="">Semua Status</option>
                                    <option value="optimal" {{ request('status_keterisian') == 'optimal' ? 'selected' : '' }}>Optimal (>= 80%)</option>
                                    <option value="cukup" {{ request('status_keterisian') == 'cukup' ? 'selected' : '' }}>Cukup (50% - 79%)</option>
                                    <option value="rendah" {{ request('status_keterisian') == 'rendah' ? 'selected' : '' }}>Rendah (< 50%)</option>
                                </select>
                            </div>

                            <div class="flex gap-2 w-full sm:w-auto">
                                <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 w-full sm:w-auto">
                                    <i class="fas fa-filter text-xs"></i> Filter
                                </button>
                                @if(request()->anyFilled(['q', 'status_keterisian']))
                                    <a href="{{ route('admin.laporan.penyerapan_kuota') }}" class="px-3 py-2 border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl font-bold text-xs shadow-xs transition flex items-center justify-center">
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
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-[220px] max-w-[320px]">Nama Instansi</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">Kuota Disediakan</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-36">Total Terserap</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tingkat Penyerapan</th>
                                    <th class="px-4 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                                @forelse($penyerapan as $index => $instansi)
                                <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-950/20 transition group"
                                    x-show="!searchQuery || @js(strtolower($instansi->nama_dinas)).includes(searchQuery.toLowerCase())">
                                    <td class="px-4 py-4 text-center">
                                        @if($index == 0 && $instansi->persentase_penyerapan > 0)
                                            <div class="w-7 h-7 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto shadow-xs border border-amber-200 dark:border-amber-800/50">
                                                <i class="fas fa-crown text-xs"></i>
                                            </div>
                                        @elseif($index == 1 && $instansi->persentase_penyerapan > 0)
                                            <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 flex items-center justify-center mx-auto border border-gray-300 dark:border-gray-700 font-bold text-xs">2</div>
                                        @elseif($index == 2 && $instansi->persentase_penyerapan > 0)
                                            <div class="w-7 h-7 rounded-full bg-amber-100/60 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 flex items-center justify-center mx-auto border border-amber-200 dark:border-amber-800/50 font-bold text-xs">3</div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 font-bold text-xs">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 min-w-[220px] max-w-[320px]">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 font-black border border-teal-200 dark:border-teal-800/60 text-xs flex-shrink-0 flex items-center justify-center">
                                                {{ strtoupper(substr($instansi->nama_dinas, 0, 2)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-bold text-gray-900 dark:text-gray-100 leading-snug line-clamp-2" title="{{ $instansi->nama_dinas }}">{{ $instansi->nama_dinas }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-full font-bold text-xs inline-block font-mono">
                                            {{ $instansi->total_kuota }} Kursi
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 text-emerald-700 dark:text-emerald-300 rounded-full font-bold text-xs inline-block font-mono">
                                            {{ $instansi->total_terserap }} Orang
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @php
                                                $rate = min(100, round($instansi->persentase_penyerapan, 1));
                                                $barBg = 'from-rose-500 to-orange-500';
                                                if ($rate >= 80) {
                                                    $barBg = 'from-teal-500 to-emerald-500';
                                                } elseif ($rate >= 50) {
                                                    $barBg = 'from-blue-500 to-indigo-500';
                                                }
                                            @endphp
                                            <div class="w-full bg-gray-100 dark:bg-gray-900 h-2 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                                                <div class="bg-gradient-to-r {{ $barBg }} h-2 rounded-full" style="width: {{ $rate }}%"></div>
                                            </div>
                                            <span class="text-xs font-black font-mono text-gray-800 dark:text-gray-100 min-w-[45px] text-right">{{ $rate }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        @if($instansi->persentase_penyerapan >= 80)
                                            <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">Optimal</span>
                                        @elseif($instansi->persentase_penyerapan >= 50)
                                            <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-full bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60">Cukup</span>
                                        @else
                                            <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-full bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60">Rendah</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-700">
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

                    {{-- Mobile Card-View --}}
                    <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($penyerapan as $index => $instansi)
                            @php
                                $rate = min(100, round($instansi->persentase_penyerapan, 1));
                                $barBg = 'from-rose-500 to-orange-500';
                                if ($rate >= 80) {
                                    $barBg = 'from-teal-500 to-emerald-500';
                                } elseif ($rate >= 50) {
                                    $barBg = 'from-blue-500 to-indigo-500';
                                }
                            @endphp
                            <div class="p-4 space-y-3.5" x-show="!searchQuery || @js(strtolower($instansi->nama_dinas)).includes(searchQuery.toLowerCase())">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 font-black border border-teal-200 dark:border-teal-800/60 text-xs flex-shrink-0 flex items-center justify-center">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="h-9 w-9 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 font-black border border-teal-200 dark:border-teal-800/60 text-xs flex-shrink-0 flex items-center justify-center">
                                        {{ strtoupper(substr($instansi->nama_dinas, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-bold text-gray-900 dark:text-gray-100 leading-snug line-clamp-2" title="{{ $instansi->nama_dinas }}">{{ $instansi->nama_dinas }}</div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-100 dark:border-gray-700 grid grid-cols-3 gap-3">
                                    <div class="text-center">
                                        <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Kuota</p>
                                        <span class="font-mono font-black text-gray-800 dark:text-gray-100 text-sm">{{ $instansi->total_kuota }}</span>
                                    </div>
                                    <div class="text-center border-x border-gray-200 dark:border-gray-700">
                                        <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Terisi</p>
                                        <span class="font-mono font-black text-emerald-600 dark:text-emerald-400 text-sm">{{ $instansi->total_terserap }}</span>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Rate</p>
                                        <span class="font-mono font-black text-gray-800 dark:text-gray-100 text-sm">{{ $rate }}%</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-full bg-gray-100 dark:bg-gray-900 h-2 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                                        <div class="bg-gradient-to-r {{ $barBg }} h-2 rounded-full" style="width: {{ $rate }}%"></div>
                                    </div>
                                    @if($instansi->persentase_penyerapan >= 80)
                                        <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 whitespace-nowrap">Optimal</span>
                                    @elseif($instansi->persentase_penyerapan >= 50)
                                        <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-full bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60 whitespace-nowrap">Cukup</span>
                                    @else
                                        <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-full bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60 whitespace-nowrap">Rendah</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-700">
                                        <i class="fas fa-search text-2xl"></i>
                                    </div>
                                    <p class="text-gray-900 dark:text-gray-100 font-bold">Data penyerapan tidak ditemukan</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
