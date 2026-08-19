<x-app-layout>
    @push('head')
        @vite('resources/css/peserta.css')
    @endpush
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-history text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Riwayat Kehadiran (Absensi)') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Back Button --}}
            <div class="mb-6 print:hidden">
                <a href="{{ route('peserta.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            {{-- Summary Stats Bar --}}
            @php
                $totalRecords = $attendanceSummary['total'];
                $hadirCount   = $attendanceSummary['hadir'];
                $izinCount    = $attendanceSummary['izin'];
                $alpaCount    = $attendanceSummary['alpa'];
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Hari</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono">{{ $totalRecords }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/60 border border-teal-100 dark:border-teal-800/60 flex items-center justify-center text-teal-600 dark:text-teal-400 text-lg shadow-xs">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>

                <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Hadir</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono">{{ $hadirCount }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-emerald-800/60 flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-lg shadow-xs">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>

                <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Izin / Sakit</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono">{{ $izinCount }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 border border-amber-100 dark:border-amber-800/60 flex items-center justify-center text-amber-600 dark:text-amber-400 text-lg shadow-xs">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>

                <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Alpha</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono">{{ $alpaCount }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-800/60 flex items-center justify-center text-rose-600 dark:text-rose-400 text-lg shadow-xs">
                        <i class="fas fa-user-times"></i>
                    </div>
                </div>
            </div>

            {{-- Main Table Card --}}
            <div class="glass-panel rounded-3xl overflow-hidden">
                <div class="p-6 border-b border-gray-100/50 dark:border-gray-700/50 bg-white/30 dark:bg-gray-900/30 space-y-4">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                <i class="fas fa-list-check text-teal-600 dark:text-teal-400"></i> Histori Absen Saya
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-medium">Data absen Anda pada instansi <span class="font-bold text-teal-600 dark:text-teal-400">{{ $application->position->instansi->nama_dinas }}</span></p>
                        </div>
                    </div>

                    @php
                        $isFiltered = request()->hasAny(['month', 'status', 'search', 'sort']);
                    @endphp

                    {{-- Multi-Field Filter Bar --}}
                    <x-ui.filter-bar :action="route('peserta.absensi.index')" :resetUrl="$isFiltered ? route('peserta.absensi.index') : null">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 w-full">
                            {{-- Search Input --}}
                            <div class="flex flex-col gap-1">
                                <label for="search" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <i class="fas fa-search text-teal-600 dark:text-teal-400"></i> Cari Catatan:
                                </label>
                                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci..." class="w-full text-xs font-semibold rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                            </div>

                            {{-- Month Filter Dropdown / Picker --}}
                            <div class="flex flex-col gap-1">
                                <label for="month" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <i class="far fa-calendar-alt text-teal-600 dark:text-teal-400"></i> Periode Bulan:
                                </label>
                                @if(isset($periodMonths) && count($periodMonths) > 0)
                                    <select id="month" name="month" class="w-full text-xs font-semibold rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                                        <option value="">Semua Bulan Magang</option>
                                        @foreach($periodMonths as $ym => $label)
                                            <option value="{{ $ym }}" {{ request('month') == $ym ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="month" id="month" name="month" value="{{ request('month') }}" class="w-full text-xs font-semibold rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs [color-scheme:dark]">
                                @endif
                            </div>

                            {{-- Status Filter --}}
                            <div class="flex flex-col gap-1">
                                <label for="status" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <i class="fas fa-filter text-teal-600 dark:text-teal-400"></i> Status Kehadiran:
                                </label>
                                <select id="status" name="status" class="w-full text-xs font-semibold rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                                    <option value="">Semua Status</option>
                                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="alpa" {{ request('status') == 'alpa' ? 'selected' : '' }}>Alpa</option>
                                </select>
                            </div>

                            {{-- Sort Order --}}
                            <div class="flex flex-col gap-1">
                                <label for="sort" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <i class="fas fa-sort-amount-down text-teal-600 dark:text-teal-400"></i> Urutkan Tanggal:
                                </label>
                                <select id="sort" name="sort" class="w-full text-xs font-semibold rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                                    <option value="desc" {{ request('sort') == 'desc' || !request('sort') ? 'selected' : '' }}>Terbaru → Terlama</option>
                                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Terlama → Terbaru</option>
                                </select>
                            </div>
                        </div>
                    </x-ui.filter-bar>

                    {{-- Active Filter Badges --}}
                    @if($isFiltered)
                        <div class="flex flex-wrap items-center gap-2 pt-1">
                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400">Filter Aktif:</span>
                            @if(request('search'))
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60">
                                    <i class="fas fa-search text-[10px]"></i> "<span>{{ request('search') }}</span>"
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="hover:text-rose-500 transition ml-1" title="Hapus pencarian"><i class="fas fa-times"></i></a>
                                </span>
                            @endif
                            @if(request('month'))
                                @php
                                    $selectedMonthLabel = $periodMonths[request('month')] ?? null;
                                    if (!$selectedMonthLabel) {
                                        try {
                                            $selectedMonthLabel = \Carbon\Carbon::parse(request('month'))->translatedFormat('F Y');
                                        } catch (\Exception $e) {
                                            $selectedMonthLabel = request('month');
                                        }
                                    }
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60">
                                    <i class="far fa-calendar-alt text-[10px]"></i> {{ $selectedMonthLabel }}
                                    <a href="{{ request()->fullUrlWithQuery(['month' => null]) }}" class="hover:text-rose-500 transition ml-1" title="Hapus filter bulan"><i class="fas fa-times"></i></a>
                                </span>
                            @endif
                            @if(request('status'))
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60 capitalize">
                                    <i class="fas fa-tag text-[10px]"></i> Status: {{ request('status') }}
                                    <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="hover:text-rose-500 transition ml-1" title="Hapus filter status"><i class="fas fa-times"></i></a>
                                </span>
                            @endif
                            @if(request('sort') && request('sort') !== 'desc')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60">
                                    <i class="fas fa-sort text-[10px]"></i> Terlama Pertama
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => null]) }}" class="hover:text-rose-500 transition ml-1" title="Reset urutan"><i class="fas fa-times"></i></a>
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Masuk</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Pulang</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Durasi</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Validasi & Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                            @forelse($attendances as $absen)
                                @php
                                    $durationText = '-';
                                    if ($absen->clock_in && $absen->clock_out) {
                                        $in  = \Carbon\Carbon::parse($absen->clock_in);
                                        $out = \Carbon\Carbon::parse($absen->clock_out);
                                        $diff = $in->diff($out);
                                        $durationText = $diff->h . 'j ' . $diff->i . 'm';
                                    }
                                @endphp
                                <tr class="table-row-hover border-b border-gray-100/50 dark:border-gray-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($absen->date)->translatedFormat('l, d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($absen->status == 'hadir')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60"><i class="fas fa-check-circle text-[10px]"></i>Hadir</span>
                                        @elseif($absen->status == 'izin')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60"><i class="fas fa-file-alt text-[10px]"></i>Izin</span>
                                        @elseif($absen->status == 'sakit')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60"><i class="fas fa-procedures text-[10px]"></i>Sakit</span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60"><i class="fas fa-times-circle text-[10px]"></i>Alpa</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($absen->clock_in)
                                            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-mono font-bold text-gray-800 dark:text-gray-200 inline-flex items-center gap-1.5">
                                                <i class="fas fa-sign-in-alt text-teal-600 dark:text-teal-400 text-xs"></i>{{ \Carbon\Carbon::parse($absen->clock_in)->format('H:i') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($absen->clock_out)
                                            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-mono font-bold text-gray-800 dark:text-gray-200 inline-flex items-center gap-1.5">
                                                <i class="fas fa-sign-out-alt text-rose-500 text-xs"></i>{{ \Carbon\Carbon::parse($absen->clock_out)->format('H:i') }}
                                            </span>
                                        @else
                                            <span class="text-xs text-rose-600 dark:text-rose-400 italic bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-900/40 px-2.5 py-0.5 rounded-md font-bold">Belum Pulang</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-xs text-gray-600 dark:text-gray-400 font-medium">
                                        @if($durationText !== '-')
                                            <span class="inline-flex items-center gap-1 font-bold text-teal-700 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/60 px-2.5 py-1 rounded-xl border border-teal-200 dark:border-teal-800/60"><i class="fas fa-clock text-xs"></i>{{ $durationText }}</span>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-700 dark:text-gray-300">
                                        @if($absen->validation_status == 'disetujui' || $absen->validation_status == 'valid' || $absen->validation_status == 'approved')
                                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-bold"><i class="fas fa-check-circle"></i> Tervalidasi</span>
                                        @elseif($absen->validation_status == 'ditolak' || $absen->validation_status == 'rejected')
                                            <span class="inline-flex items-center gap-1 text-rose-600 dark:text-rose-400 font-bold"><i class="fas fa-times-circle"></i> Ditolak</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-gray-400 dark:text-gray-500 font-bold"><i class="fas fa-clock"></i> Menunggu</span>
                                        @endif
                                        
                                        @if($absen->description)
                                            <p class="mt-1 text-xs text-gray-600 dark:text-gray-400 italic">"{{ $absen->description }}"</p>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                                <i class="far fa-calendar-times text-3xl text-gray-400 dark:text-gray-500"></i>
                                            </div>
                                            <p class="font-bold text-gray-700 dark:text-gray-300 text-sm">Belum Ada Data Absensi</p>
                                            <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">Tidak ada catatan absensi pada kriteria filter yang dipilih.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-t border-gray-100/50 dark:border-gray-700/50 bg-white/30 dark:bg-gray-900/30">
                    {{ $attendances->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
