{{-- Card Filter Rekapitulasi Modern 2 Baris --}}
@php
    $activeFilterCount = collect([
        request('instansi'),
        request('instansi_id'),
        (request('status') && request('status') !== 'semua' ? request('status') : null),
        request('start_date'),
        request('end_date'),
        request('periode_preset'),
        request('posisi'),
        request('q')
    ])->filter(fn($v) => !empty($v))->count();
@endphp

<div class="bg-white dark:bg-[#161f33] rounded-2xl md:rounded-3xl p-5 md:p-6 shadow-lg border border-slate-200 dark:border-slate-800/40 space-y-5">
    
    {{-- Card Header: Title, Subtitle, Active Filter Badge & Reset Button --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-slate-800/60">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-teal-100 dark:bg-teal-500/15 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/25 flex items-center justify-center shrink-0">
                <i class="fas fa-sliders-h text-sm"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm md:text-base font-black text-slate-900 dark:text-white">Filter & Parameter Rekapitulasi</h3>
                    @if($activeFilterCount > 0)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-teal-500/15 text-teal-600 dark:text-teal-400 border border-teal-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span>
                            {{ $activeFilterCount }} Filter Aktif
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">Saring data peserta berdasarkan asal sekolah/kampus, unit dinas, status, rentang waktu, dan formasi.</p>
            </div>
        </div>

        @if($activeFilterCount > 0)
            <a href="{{ route('admin.laporan.peserta_global') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30 hover:bg-rose-100 dark:hover:bg-rose-500/20 rounded-xl font-bold text-xs transition shadow-sm self-start sm:self-center">
                <i class="fas fa-rotate-left text-[10px]"></i> Reset Semua
            </a>
        @endif
    </div>

    <form method="GET" action="{{ route('admin.laporan.peserta_global') }}" x-ref="filterForm" class="space-y-4">
        @if(request()->filled('q'))
            <input type="hidden" name="q" value="{{ request('q') }}">
        @endif
        <input type="hidden" name="periode_preset" x-ref="presetInput" value="{{ request('periode_preset') }}">

        {{-- Baris 1: Kategori Utama (Kampus, Dinas, Status) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Filter Kampus --}}
            <div>
                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-1 flex items-center gap-1.5">
                    <i class="fas fa-university text-teal-500 text-[11px]"></i> Asal Sekolah / Kampus
                </label>
                <div class="relative">
                    <select name="instansi" class="w-full py-2.5 px-3.5 bg-slate-50/70 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark] transition">
                        <option value="">Semua Sekolah / Kampus</option>
                        @foreach($listInstansi as $instansi)
                            <option value="{{ $instansi }}" {{ request('instansi') == $instansi ? 'selected' : '' }}>
                                {{ $instansi }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Filter Dinas --}}
            <div>
                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-1 flex items-center gap-1.5">
                    <i class="fas fa-building text-teal-500 text-[11px]"></i> Dinas Penempatan
                </label>
                <div class="relative">
                    <select name="instansi_id" class="w-full py-2.5 px-3.5 bg-slate-50/70 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark] transition">
                        <option value="">Semua Dinas Penempatan</option>
                        @foreach($listDinas as $dinas)
                            <option value="{{ $dinas->id }}" {{ request('instansi_id') == $dinas->id ? 'selected' : '' }}>
                                {{ $dinas->nama_dinas }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Filter Status --}}
            <div>
                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-1 flex items-center gap-1.5">
                    <i class="fas fa-user-check text-teal-500 text-[11px]"></i> Status Magang
                </label>
                <div class="relative">
                    <select name="status" class="w-full py-2.5 px-3.5 bg-slate-50/70 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark] transition">
                        <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Aktif Magang</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai Magang</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending / Menunggu</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Filter Cepat Periode Magang --}}
        <div class="p-3 bg-slate-50/90 dark:bg-slate-900/60 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 space-y-2">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fas fa-bolt text-amber-500 text-xs"></i> Filter Cepat Periode:
                </span>
                @if(request()->filled('periode_preset') || (request()->filled('start_date') && request()->filled('end_date')))
                    <span class="text-[10px] text-teal-600 dark:text-teal-400 font-bold flex items-center gap-1">
                        <i class="fas fa-check-circle text-[10px]"></i>
                        @if(request('periode_preset') === '1_bulan')
                            Periode 1 Bulan Terakhir Aktif
                        @elseif(request('periode_preset') === '3_bulan')
                            Periode 3 Bulan (Triwulan) Aktif
                        @elseif(request('periode_preset') === 'semester')
                            Periode 1 Semester (6 Bulan) Aktif
                        @elseif(request('periode_preset') === 'tahun')
                            Periode 1 Tahun Aktif
                        @else
                            Rentang Tanggal Kustom Aktif
                        @endif
                    </span>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-2">
                {{-- 1 Bulan --}}
                <button type="button" 
                    @click="applyPreset('1_bulan')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition shadow-xs {{ request('periode_preset') === '1_bulan' ? 'bg-teal-600 text-white shadow-teal-600/25 ring-2 ring-teal-500/30' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700/80 hover:bg-teal-50 dark:hover:bg-slate-700 hover:text-teal-600 dark:hover:text-teal-400' }}">
                    <i class="far fa-calendar text-[11px] {{ request('periode_preset') === '1_bulan' ? 'text-white' : 'text-teal-500' }}"></i>
                    <span>1 Bulan</span>
                    <span class="text-[10px] {{ request('periode_preset') === '1_bulan' ? 'text-teal-100' : 'text-slate-400' }} hidden sm:inline">(30 Hari)</span>
                </button>

                {{-- 3 Bulan (Triwulan) --}}
                <button type="button" 
                    @click="applyPreset('3_bulan')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition shadow-xs {{ request('periode_preset') === '3_bulan' ? 'bg-teal-600 text-white shadow-teal-600/25 ring-2 ring-teal-500/30' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700/80 hover:bg-teal-50 dark:hover:bg-slate-700 hover:text-teal-600 dark:hover:text-teal-400' }}">
                    <i class="fas fa-layer-group text-[11px] {{ request('periode_preset') === '3_bulan' ? 'text-white' : 'text-teal-500' }}"></i>
                    <span>3 Bulan</span>
                    <span class="text-[10px] {{ request('periode_preset') === '3_bulan' ? 'text-teal-100' : 'text-slate-400' }} hidden sm:inline">(Triwulan)</span>
                </button>

                {{-- Semester (6 Bulan) --}}
                <button type="button" 
                    @click="applyPreset('semester')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition shadow-xs {{ request('periode_preset') === 'semester' ? 'bg-teal-600 text-white shadow-teal-600/25 ring-2 ring-teal-500/30' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700/80 hover:bg-teal-50 dark:hover:bg-slate-700 hover:text-teal-600 dark:hover:text-teal-400' }}">
                    <i class="fas fa-graduation-cap text-[11px] {{ request('periode_preset') === 'semester' ? 'text-white' : 'text-teal-500' }}"></i>
                    <span>1 Semester</span>
                    <span class="text-[10px] {{ request('periode_preset') === 'semester' ? 'text-teal-100' : 'text-slate-400' }} hidden sm:inline">(6 Bulan)</span>
                </button>

                {{-- 1 Tahun --}}
                <button type="button" 
                    @click="applyPreset('tahun')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition shadow-xs {{ request('periode_preset') === 'tahun' ? 'bg-teal-600 text-white shadow-teal-600/25 ring-2 ring-teal-500/30' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700/80 hover:bg-teal-50 dark:hover:bg-slate-700 hover:text-teal-600 dark:hover:text-teal-400' }}">
                    <i class="fas fa-calendar-alt text-[11px] {{ request('periode_preset') === 'tahun' ? 'text-white' : 'text-teal-500' }}"></i>
                    <span>1 Tahun</span>
                    <span class="text-[10px] {{ request('periode_preset') === 'tahun' ? 'text-teal-100' : 'text-slate-400' }} hidden sm:inline">(Tahunan)</span>
                </button>

                {{-- Reset Periode --}}
                @if(request()->filled('periode_preset') || request()->filled('start_date') || request()->filled('end_date'))
                    <button type="button" 
                        @click="clearPeriod()"
                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-[11px] font-bold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 hover:bg-rose-100 dark:hover:bg-rose-500/20 transition sm:ml-auto">
                        <i class="fas fa-times text-[10px]"></i>
                        <span>Reset Periode</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- Baris 2: Waktu, Posisi & Tombol Aksi --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end pt-1">
            {{-- Dari Tanggal --}}
            <div>
                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-1 flex items-center gap-1.5">
                    <i class="far fa-calendar-alt text-teal-500 text-[11px]"></i> Dari Tanggal (Mulai)
                </label>
                <input type="date" name="start_date" x-ref="startDateInput" @change="$refs.presetInput.value = ''" value="{{ request('start_date') }}"
                    class="w-full py-2.5 px-3 bg-slate-50/70 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark] transition">
            </div>

            {{-- Sampai Tanggal --}}
            <div>
                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-1 flex items-center gap-1.5">
                    <i class="far fa-calendar-check text-teal-500 text-[11px]"></i> Sampai Tanggal (Selesai)
                </label>
                <input type="date" name="end_date" x-ref="endDateInput" @change="$refs.presetInput.value = ''" value="{{ request('end_date') }}"
                    class="w-full py-2.5 px-3 bg-slate-50/70 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm cursor-pointer dark:[color-scheme:dark] transition">
            </div>

            {{-- Posisi / Formasi Magang --}}
            <div>
                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-1 flex items-center gap-1.5">
                    <i class="fas fa-briefcase text-teal-500 text-[11px]"></i> Posisi / Formasi
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-slate-500 pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="posisi" value="{{ request('posisi') }}" placeholder="Cari posisi / divisi..."
                        class="w-full pl-9 pr-3 py-2.5 bg-slate-50/70 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm transition">
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-2">
                @if($activeFilterCount > 0)
                    <a href="{{ route('admin.laporan.peserta_global') }}" title="Bersihkan Filter"
                        class="px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800/80 hover:bg-rose-50 dark:hover:bg-rose-500/10 text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs font-bold transition flex items-center justify-center shadow-sm">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
                <button type="submit" class="w-full py-2.5 px-4 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs font-black shadow-md shadow-teal-600/20 hover:shadow-teal-500/30 transition uppercase tracking-wider flex items-center justify-center gap-2 active:scale-95">
                    <i class="fas fa-filter text-xs"></i> Terapkan Filter
                </button>
            </div>
        </div>

        {{-- Active Search Query Tag if present --}}
        @if(request()->filled('q'))
            <div class="flex items-center justify-between gap-3 px-4 py-2.5 bg-teal-50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/25 rounded-xl mt-2">
                <span class="text-xs font-bold text-teal-700 dark:text-teal-300 truncate">
                    <i class="fas fa-search mr-1.5 text-[10px]"></i>Pencarian teks tabel: &ldquo;{{ request('q') }}&rdquo;
                </span>
                <a href="{{ route('admin.laporan.peserta_global', request()->except('q', 'page')) }}" class="text-[10px] font-black text-teal-600 dark:text-teal-400 hover:text-rose-600 dark:hover:text-rose-400 uppercase tracking-wider shrink-0 transition">
                    Hapus <i class="fas fa-times ml-0.5"></i>
                </a>
            </div>
        @endif
    </form>
</div>
