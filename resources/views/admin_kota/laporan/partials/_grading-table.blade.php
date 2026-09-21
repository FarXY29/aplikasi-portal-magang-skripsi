{{-- Main Table Card with Integrated Header Filters --}}
<div class="w-full space-y-6" x-data="{ openRow: null }">
    <div class="bg-white dark:bg-[#161f33] shadow-lg rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 overflow-hidden">

        {{-- Card Header: Title & Integrated Right-Side Filters --}}
        <div class="p-5 md:p-6 border-b border-slate-100 dark:border-slate-800/60 flex flex-col xl:flex-row xl:items-center justify-between gap-4 bg-slate-50/60 dark:bg-slate-900/40">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-2xl bg-teal-100 dark:bg-teal-500/15 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/25 flex items-center justify-center shrink-0">
                    <i class="fas fa-chart-line text-base"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 dark:text-white text-base md:text-lg leading-tight">
                        Daftar Analisis Kompetensi & Performa
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Daftar peringkat berdasarkan nilai rata-rata akhir peserta terfilter.</p>
                </div>
            </div>

            {{-- Integrated Right-Side Filter Form --}}
            <form method="GET" action="{{ route('admin.laporan.grading') }}" class="flex flex-wrap sm:flex-nowrap lg:flex-row gap-2.5 items-stretch sm:items-center w-full xl:w-auto">
                {{-- Search Name --}}
                <div class="relative w-full sm:w-44">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-slate-500 pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama..."
                        class="w-full pl-9 py-2 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl text-xs font-bold focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                </div>

                {{-- Filter Kampus --}}
                <div class="w-full sm:w-40">
                    <select name="instansi" class="w-full py-2 px-3 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark]">
                        <option value="">Semua Kampus</option>
                        @foreach($listCampus as $campus)
                            <option value="{{ $campus }}" {{ request('instansi') == $campus ? 'selected' : '' }}>
                                {{ Str::limit($campus, 25) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Dinas --}}
                <div class="w-full sm:w-40">
                    <select name="instansi_id" class="w-full py-2 px-3 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark]">
                        <option value="">Semua Dinas</option>
                        @foreach($listDinas as $dinas)
                            <option value="{{ $dinas->id }}" {{ request('instansi_id') == $dinas->id ? 'selected' : '' }}>
                                {{ Str::limit($dinas->nama_dinas, 25) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Predikat --}}
                <div class="w-full sm:w-36">
                    <select name="predikat" class="w-full py-2 px-3 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark]">
                        <option value="">Semua Predikat</option>
                        <option value="Sangat Baik" {{ request('predikat') == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                        <option value="Baik" {{ request('predikat') == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Cukup" {{ request('predikat') == 'Cukup' ? 'selected' : '' }}>Cukup</option>
                        <option value="Kurang" {{ request('predikat') == 'Kurang' ? 'selected' : '' }}>Kurang</option>
                    </select>
                </div>

                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="submit" class="flex-1 sm:flex-none px-4 py-2 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs font-bold shadow-md shadow-teal-500/20 transition active:scale-95 flex items-center justify-center gap-1.5">
                        <i class="fas fa-filter text-xs"></i> Filter
                    </button>
                    @if(request()->anyFilled(['q', 'instansi', 'instansi_id', 'predikat']))
                        <a href="{{ route('admin.laporan.grading') }}" class="px-3 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl font-bold text-xs shadow-sm transition flex items-center justify-center">
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
                        <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[200px] max-w-[260px]">Peserta & Asal Sekolah</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[220px] max-w-[280px]">Penempatan Dinas & Posisi</th>
                        <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-28">Skor Akhir</th>
                        <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-32">Predikat</th>
                        <th class="px-4 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-16">Detail</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-[#161f33] divide-y divide-slate-100 dark:divide-slate-800/60 text-sm">
                    @forelse($gradedList as $index => $res)
                    <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-500/5 transition group cursor-pointer" @click="openRow = (openRow === {{ $index }} ? null : {{ $index }})">
                        <td class="px-4 py-4 text-center">
                            <x-ui.rank-badge :index="$index" :eligible="$isUnfiltered" class="mx-auto" />
                        </td>
                        <td class="px-6 py-4 min-w-[200px] max-w-[260px]">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-300 font-black border border-teal-200 dark:border-teal-500/25 text-xs shrink-0 flex items-center justify-center">
                                    {{ strtoupper(substr($res['nama'], 0, 2)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $res['nama'] }}">{{ $res['nama'] }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold leading-snug line-clamp-2 mt-0.5" title="{{ $res['asal_instansi'] }}">
                                        <i class="fas fa-university mr-1 text-slate-400 dark:text-slate-500"></i> {{ $res['asal_instansi'] }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 min-w-[220px] max-w-[280px]">
                            <div class="flex flex-col gap-0.5">
                                <span class="font-bold text-slate-900 dark:text-white text-xs leading-snug line-clamp-2" title="{{ $res['instansi'] }}">
                                    <i class="far fa-building text-slate-400 dark:text-slate-500 mr-1"></i>
                                    {{ $res['instansi'] }}
                                </span>
                                <span class="text-[11px] text-teal-600 dark:text-teal-400 font-medium leading-snug line-clamp-2 mt-0.5" title="{{ $res['posisi'] }}">
                                    Posisi: {{ $res['posisi'] }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="text-sm font-black text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/25 px-2.5 py-1 rounded-full inline-block font-mono">{{ $res['rata_rata'] }}</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @php
                                $predikatColor = match($res['predikat']) {
                                    'Sangat Baik' => 'emerald',
                                    'Baik' => 'blue',
                                    'Cukup' => 'amber',
                                    default => 'rose',
                                };
                            @endphp
                            <x-ui.pill :color="$predikatColor" class="uppercase text-[10px] font-black">{{ $res['predikat'] }}</x-ui.pill>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-slate-900/60 flex items-center justify-center mx-auto border border-slate-200 dark:border-slate-700">
                                <i class="fas fa-chevron-down text-slate-400 dark:text-slate-500 text-xs transition-transform duration-200" :class="openRow === {{ $index }} ? 'rotate-180 text-teal-600 dark:text-teal-400' : ''"></i>
                            </div>
                        </td>
                    </tr>

                    {{-- Expanded detail row --}}
                    <tr x-show="openRow === {{ $index }}" x-transition.opacity x-cloak>
                        <td colspan="6" class="px-4 py-4 bg-slate-50/80 dark:bg-slate-900/40 border-y border-slate-100 dark:border-slate-800/60">
                            <div class="bg-white dark:bg-[#161f33] rounded-2xl border border-slate-200 dark:border-slate-800/60 p-4 sm:p-5 shadow-sm space-y-4">
                                <h4 class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-2 border-b pb-2 border-slate-100 dark:border-slate-800/60">
                                    <i class="fas fa-award text-teal-600 dark:text-teal-400"></i> Rincian Penilaian Kompetensi & Performa Peserta
                                </h4>

                                @if($res['nilai_rata_rata'] !== null)
                                    {{-- New Grading System --}}
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Kerajinan</div>
                                            <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ $res['kerajinan'] }}</div>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Kedisiplinan</div>
                                            <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ $res['disiplin'] }}</div>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Adaptasi</div>
                                            <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ $res['adaptasi'] }}</div>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Kreatifitas</div>
                                            <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ $res['kreatifitas'] }}</div>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60 col-span-2 md:col-span-1">
                                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Skill & Pengetahuan</div>
                                            <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ $res['skill'] }}</div>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between text-xs text-slate-400 dark:text-slate-500 italic">
                                        <span>*Sistem Penilaian Utama (5 Aspek)</span>
                                        <span>Rata-rata: <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $res['rata_rata'] }}</strong></span>
                                    </div>
                                @else
                                    {{-- Old Grading System --}}
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Kompetensi Teknis</div>
                                            <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ $res['teknis'] }}</div>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Kedisiplinan</div>
                                            <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ $res['disiplin'] }}</div>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                            <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Sikap & Perilaku</div>
                                            <div class="text-xl font-black text-slate-800 dark:text-slate-200 font-mono mt-1">{{ $res['perilaku'] }}</div>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between text-xs text-slate-400 dark:text-slate-500 italic">
                                        <span>*Sistem Penilaian Tambahan (3 Aspek)</span>
                                        <span>Rata-rata: <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $res['rata_rata'] }}</strong></span>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <x-ui.empty-state
                        :colspan="6"
                        title="Data tidak ditemukan"
                        subtitle="Coba sesuaikan filter pencarian Anda." />
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card View (<md) --}}
        <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800/60">
            @forelse($gradedList as $index => $res)
            <div class="p-4 space-y-3.5 hover:bg-slate-50 dark:hover:bg-slate-900/40 transition" x-data="{ open: false }">
                {{-- Header: rank icon + peserta + predikat badge --}}
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="shrink-0">
                            <x-ui.rank-badge :index="$index" :eligible="$isUnfiltered" size="md" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $res['nama'] }}">{{ $res['nama'] }}</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold leading-snug line-clamp-2 mt-0.5" title="{{ $res['asal_instansi'] }}">
                                <i class="fas fa-university mr-1 text-slate-400 dark:text-slate-500"></i>{{ $res['asal_instansi'] }}
                            </p>
                        </div>
                    </div>
                    <div class="shrink-0">
                        @php
                            $predikatColorMobile = match($res['predikat']) {
                                'Sangat Baik' => 'emerald',
                                'Baik' => 'blue',
                                'Cukup' => 'amber',
                                default => 'rose',
                            };
                        @endphp
                        <x-ui.pill :color="$predikatColorMobile" class="uppercase text-[10px] font-black">{{ $res['predikat'] }}</x-ui.pill>
                    </div>
                </div>

                {{-- Detail: dinas, posisi, skor akhir --}}
                <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-2xl border border-slate-100 dark:border-slate-800/60 space-y-2.5">
                    <div class="flex items-start gap-2 text-xs">
                        <i class="far fa-building text-slate-400 dark:text-slate-500 mt-0.5"></i>
                        <span class="font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $res['instansi'] }}">{{ $res['instansi'] }}</span>
                    </div>
                    <div class="flex items-start gap-2 text-[11px]">
                        <i class="fas fa-briefcase text-teal-500 dark:text-teal-400 mt-0.5"></i>
                        <span class="text-teal-600 dark:text-teal-400 font-medium leading-snug line-clamp-2" title="{{ $res['posisi'] }}">{{ $res['posisi'] }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-slate-200 dark:border-slate-700/60">
                        <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">Skor Akhir</span>
                        <span class="text-sm font-black text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/25 px-2.5 py-1 rounded-full inline-block font-mono">{{ $res['rata_rata'] }}</span>
                    </div>
                </div>

                {{-- Expandable detail (rincian penilaian) --}}
                <button type="button" @click="open = !open" class="w-full flex items-center justify-center gap-1.5 py-2 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                    <span x-text="open ? 'Sembunyikan Rincian' : 'Lihat Rincian'"></span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180 text-teal-600 dark:text-teal-400' : ''"></i>
                </button>
                <div x-show="open" x-transition.opacity x-cloak class="bg-white dark:bg-slate-900/40 rounded-2xl border border-slate-200 dark:border-slate-700/60 p-4 shadow-sm space-y-3">
                    <h4 class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-2 border-b pb-2 border-slate-100 dark:border-slate-800/60">
                        <i class="fas fa-award text-teal-600 dark:text-teal-400"></i> Rincian Penilaian
                    </h4>
                    @if($res['nilai_rata_rata'] !== null)
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Kerajinan</div>
                                <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res['kerajinan'] }}</div>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Kedisiplinan</div>
                                <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res['disiplin'] }}</div>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Adaptasi</div>
                                <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res['adaptasi'] }}</div>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Kreatifitas</div>
                                <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res['kreatifitas'] }}</div>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60 col-span-2">
                                <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Skill & Pengetahuan</div>
                                <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res['skill'] }}</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-slate-400 dark:text-slate-500 italic">
                            <span>*Sistem Penilaian Utama (5 Aspek)</span>
                            <span>Rata-rata: <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $res['rata_rata'] }}</strong></span>
                        </div>
                    @else
                        <div class="grid grid-cols-3 gap-2">
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Teknis</div>
                                <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res['teknis'] }}</div>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Disiplin</div>
                                <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res['disiplin'] }}</div>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/60">
                                <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">Perilaku</div>
                                <div class="text-base font-black text-slate-800 dark:text-slate-200 font-mono mt-0.5">{{ $res['perilaku'] }}</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-slate-400 dark:text-slate-500 italic">
                            <span>*Sistem Penilaian Tambahan (3 Aspek)</span>
                            <span>Rata-rata: <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $res['rata_rata'] }}</strong></span>
                        </div>
                    @endif
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
