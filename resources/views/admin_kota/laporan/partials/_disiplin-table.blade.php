{{-- Main Table Card (Daftar Pemeringkatan Kepatuhan Instansi) with Integrated Filters --}}
<div class="w-full space-y-6" x-data="{ openRow: null }">
    <div class="bg-white dark:bg-[#161f33] shadow-lg rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 overflow-hidden">

        {{-- Card Header: Title & Right-Side Filter Form --}}
        <div class="p-5 md:p-6 border-b border-slate-100 dark:border-slate-800/60 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-slate-50/60 dark:bg-slate-900/40">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-2xl bg-teal-100 dark:bg-teal-500/15 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/25 flex items-center justify-center shrink-0">
                    <i class="fas fa-building text-base"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 dark:text-white text-base md:text-lg leading-tight">
                        Daftar Pemeringkatan Kepatuhan Instansi
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Instansi dengan tingkat kepatuhan terurut dari tertinggi ke terendah.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.laporan.instansi_disiplin') }}" class="flex flex-col sm:flex-row gap-2.5 items-stretch sm:items-center w-full lg:w-auto">
                <div class="relative w-full sm:w-56">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-slate-500 pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama dinas..."
                        class="w-full pl-9 py-2 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl text-xs font-bold focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                </div>

                <div class="w-full sm:w-48">
                    <select name="disiplin_range" class="w-full py-2 px-3 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark]">
                        <option value="">Semua Kategori</option>
                        <option value="sangat" {{ request('disiplin_range') == 'sangat' ? 'selected' : '' }}>Sangat Disiplin (>= 90%)</option>
                        <option value="cukup" {{ request('disiplin_range') == 'cukup' ? 'selected' : '' }}>Cukup Disiplin (70-89%)</option>
                        <option value="kurang" {{ request('disiplin_range') == 'kurang' ? 'selected' : '' }}>Kurang Disiplin (< 70%)</option>
                    </select>
                </div>

                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="submit" class="flex-1 sm:flex-none px-4 py-2 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs font-bold shadow-md shadow-teal-500/20 transition active:scale-95 flex items-center justify-center gap-1.5">
                        <i class="fas fa-filter text-xs"></i> Filter
                    </button>
                    @if(request()->anyFilled(['q', 'disiplin_range']))
                        <a href="{{ route('admin.laporan.instansi_disiplin') }}" class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl font-bold text-xs shadow-sm transition flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Desktop Table View (>=md) --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full divide-y divide-slate-100 dark:divide-slate-800/60 border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-900">
                    <tr>
                        <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-16">Rank</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[220px] max-w-[320px]">Instansi</th>
                        <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-32">Total Absensi</th>
                        <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-44">Pelanggaran</th>
                        <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-40">Tingkat Disiplin</th>
                        <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-16">Detail</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-[#161f33] divide-y divide-slate-100 dark:divide-slate-800/60 text-sm">
                    @forelse($instansis as $index => $res)
                    <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-500/5 transition group cursor-pointer" @click="openRow = (openRow === {{ $index }} ? null : {{ $index }})">
                        <td class="px-4 py-4 text-center">
                            <x-ui.rank-badge :index="$index" :eligible="$isUnfiltered && $res->total_attendances > 0" class="mx-auto" />
                        </td>
                        <td class="px-6 py-4 min-w-[220px] max-w-[320px]">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-300 font-black border border-teal-200 dark:border-teal-500/25 text-xs shrink-0 flex items-center justify-center">
                                    {{ strtoupper(substr($res->nama_dinas, 0, 2)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $res->nama_dinas }}">{{ $res->nama_dinas }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 font-medium flex items-center gap-1 mt-0.5">
                                        <i class="far fa-clock text-slate-400 dark:text-slate-500"></i> Jam Masuk: <span class="text-slate-700 dark:text-slate-300 font-bold font-mono">{{ $res->jam_mulai_masuk ?: '08:00:00' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <x-ui.pill color="slate"><strong class="font-mono">{{ $res->total_attendances }}</strong>&nbsp;<span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">Entri</span></x-ui.pill>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($res->total_pelanggaran > 0)
                                <div class="inline-flex items-center gap-1 px-3 py-1 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 rounded-full">
                                    <span class="text-rose-700 dark:text-rose-300 font-black text-xs font-mono">{{ $res->total_pelanggaran }}</span>
                                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">({{ $res->total_terlambat }} Telat, {{ $res->total_alpa }} Alpa)</span>
                                </div>
                            @else
                                <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 font-bold rounded-full inline-flex items-center justify-center gap-1 text-xs">
                                    <i class="fas fa-check-circle"></i> Nihil
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col gap-1.5 items-center">
                                @php
                                    $disiplinVal = round($res->tingkat_disiplin, 1);
                                    $barColor = $disiplinVal >= 90 ? 'bg-emerald-500' : ($disiplinVal >= 70 ? 'bg-amber-500' : 'bg-rose-500');
                                    $badgeColor = $disiplinVal >= 90 ? 'emerald' : ($disiplinVal >= 70 ? 'amber' : 'rose');
                                @endphp
                                <div class="flex items-center gap-2">
                                    <x-ui.pill :color="$badgeColor" class="font-mono font-black text-xs">{{ $disiplinVal }}%</x-ui.pill>
                                </div>
                                <div class="w-24 bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                    <div class="{{ $barColor }} h-1.5 rounded-full" style="width: {{ min(100, max(0, $disiplinVal)) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-slate-900/60 flex items-center justify-center mx-auto border border-slate-200 dark:border-slate-700">
                                <i class="fas fa-chevron-down text-slate-400 dark:text-slate-500 text-xs transition-transform duration-200" :class="openRow === {{ $index }} ? 'rotate-180 text-teal-600 dark:text-teal-400' : ''"></i>
                            </div>
                        </td>
                    </tr>

                    {{-- Expanded Detail Row --}}
                    <tr x-show="openRow === {{ $index }}" x-transition.opacity x-cloak>
                        <td colspan="6" class="px-4 py-4 bg-slate-50/80 dark:bg-slate-900/40 border-y border-slate-100 dark:border-slate-800/60">
                            <div class="bg-white dark:bg-[#161f33] rounded-2xl border border-slate-200 dark:border-slate-800/60 p-4 sm:p-5 shadow-sm space-y-4">
                                <h4 class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-2 border-b pb-2 border-slate-100 dark:border-slate-800/60">
                                    <i class="fas fa-info-circle text-teal-600 dark:text-teal-400"></i> Detail Statistik Kedisiplinan {{ $res->nama_dinas }}
                                </h4>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                        <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Total Peserta</div>
                                        <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ number_format($res->total_peserta) }}</div>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                        <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Tepat Waktu</div>
                                        <div class="text-xl font-black text-emerald-600 dark:text-emerald-400 font-mono mt-1">{{ number_format($res->total_tepat_waktu) }}</div>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                        <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Terlambat Masuk</div>
                                        <div class="text-xl font-black text-orange-600 dark:text-orange-400 font-mono mt-1">{{ number_format($res->total_terlambat) }}</div>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                        <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Alpa / Tanpa Keterangan</div>
                                        <div class="text-xl font-black text-rose-600 dark:text-rose-400 font-mono mt-1">{{ number_format($res->total_alpa) }}</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <x-ui.empty-state
                        :colspan="6"
                        title="Data instansi tidak ditemukan"
                        subtitle="Coba sesuaikan filter pencarian Anda." />
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card View (<md) --}}
        <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800/60">
            @forelse($instansis as $index => $res)
            <div class="p-4 space-y-3.5 hover:bg-slate-50 dark:hover:bg-slate-900/40 transition" x-data="{ open: false }">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="shrink-0">
                            <x-ui.rank-badge :index="$index" :eligible="$isUnfiltered && $res->total_attendances > 0" size="md" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $res->nama_dinas }}">{{ $res->nama_dinas }}</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium leading-snug mt-0.5">
                                <i class="far fa-clock text-slate-400 dark:text-slate-500 mr-0.5"></i> Jam: {{ $res->jam_mulai_masuk ?: '08:00:00' }}
                            </p>
                        </div>
                    </div>
                    <div class="shrink-0">
                        @php
                            $disiplinVal = round($res->tingkat_disiplin, 1);
                            $badgeColor = $disiplinVal >= 90 ? 'emerald' : ($disiplinVal >= 70 ? 'amber' : 'rose');
                        @endphp
                        <x-ui.pill :color="$badgeColor" class="font-mono font-black text-xs">{{ $disiplinVal }}%</x-ui.pill>
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-2xl border border-slate-100 dark:border-slate-800/60 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Total Absensi</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 font-mono">{{ $res->total_attendances }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Pelanggaran</span>
                        <span class="font-bold font-mono {{ $res->total_pelanggaran > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                            {{ $res->total_pelanggaran }} ({{ $res->total_terlambat }} Telat, {{ $res->total_alpa }} Alpa)
                        </span>
                    </div>
                </div>

                <button type="button" @click="open = !open" class="w-full flex items-center justify-center gap-1.5 py-2 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                    <span x-text="open ? 'Sembunyikan Rincian' : 'Lihat Rincian'"></span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180 text-teal-600 dark:text-teal-400' : ''"></i>
                </button>
                <div x-show="open" x-transition.opacity x-cloak class="bg-white dark:bg-slate-900/40 rounded-2xl border border-slate-200 dark:border-slate-700/60 p-4 shadow-sm space-y-3">
                    <div class="grid grid-cols-2 gap-2 text-center">
                        <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60">
                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Peserta</div>
                            <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res->total_peserta }}</div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60">
                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Tepat Waktu</div>
                            <div class="text-base font-black text-emerald-600 dark:text-emerald-400 font-mono mt-0.5">{{ $res->total_tepat_waktu }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <x-ui.empty-state
                :mobile="true"
                title="Data tidak ditemukan"
                subtitle="Coba sesuaikan filter pencarian Anda." />
            @endforelse
        </div>
    </div>
</div>
