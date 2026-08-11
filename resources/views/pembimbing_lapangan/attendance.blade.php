<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-user-clock text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Monitoring Absensi Mahasiswa') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center mb-6 print:hidden">
                <a href="{{ route('pembimbing_lapangan.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="space-y-6">

                @php
                    $isFiltered = request()->hasAny(['search', 'application_id', 'status', 'validation_status'])
                        || (request()->has('filter_type') && request('filter_type') !== 'harian')
                        || (request()->has('date') && request('date') !== date('Y-m-d'));
                @endphp

                {{-- Filter Bar --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 print:hidden space-y-5">
                    <form action="{{ route('pembimbing_lapangan.attendance.index') }}" method="GET"
                          x-data="{
                              filterType: @js($filterType),
                              selectedDate: @js($selectedDate),
                              monthValue: @js(\Carbon\Carbon::parse($selectedDate)->format('Y-m')),
                              get isMonthly() { return this.filterType === 'bulanan'; },
                              get isAll() { return this.filterType === 'semua'; },
                              resetFilter() {
                                  window.location.href = @js(route('pembimbing_lapangan.attendance.index'));
                              }
                          }"
                          class="space-y-5">

                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 border-b border-gray-100 dark:border-gray-700 pb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 flex items-center justify-center text-teal-600 dark:text-teal-400">
                                    <i class="fas fa-filter text-xs"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">Filter Monitoring Absensi</h3>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Sesuaikan kriteria pencarian dan rentang waktu absensi mahasiswa</p>
                                </div>
                            </div>

                            {{-- Rentang Waktu Segmented Control --}}
                            <div class="w-full xl:w-auto">
                                <div class="grid grid-cols-4 gap-1 bg-gray-100 dark:bg-gray-900 p-1 rounded-xl border border-gray-200 dark:border-gray-700">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="filter_type" value="harian" x-model="filterType" class="sr-only peer">
                                        <span class="block text-center text-[10px] font-bold py-1.5 px-3 rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition">
                                            Harian
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="filter_type" value="mingguan" x-model="filterType" class="sr-only peer">
                                        <span class="block text-center text-[10px] font-bold py-1.5 px-3 rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition">
                                            Mingguan
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="filter_type" value="bulanan" x-model="filterType" class="sr-only peer">
                                        <span class="block text-center text-[10px] font-bold py-1.5 px-3 rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition">
                                            Bulanan
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="filter_type" value="semua" x-model="filterType" class="sr-only peer">
                                        <span class="block text-center text-[10px] font-bold py-1.5 px-3 rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition">
                                            Semua
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Multi-Field Filters Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">

                            {{-- Search Input --}}
                            <div class="flex flex-col gap-1 lg:col-span-1">
                                <label for="search" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <i class="fas fa-search text-teal-600 dark:text-teal-400"></i> Cari Peserta/Catatan:
                                </label>
                                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Nama atau alasan..." class="w-full text-xs font-semibold rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                            </div>

                            {{-- Dropdown Peserta --}}
                            <div class="flex flex-col gap-1 lg:col-span-1">
                                <label for="application_id" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <i class="fas fa-user-graduate text-teal-600 dark:text-teal-400"></i> Mahasiswa Bimbingan:
                                </label>
                                <select id="application_id" name="application_id" class="w-full text-xs font-semibold rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                                    <option value="">Semua Mahasiswa</option>
                                    @if(isset($interns))
                                        @foreach($interns as $intern)
                                            <option value="{{ $intern->id }}" {{ request('application_id') == $intern->id ? 'selected' : '' }}>
                                                {{ $intern->user->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            {{-- Date Picker --}}
                            <div class="flex flex-col gap-1 lg:col-span-1">
                                <label for="date" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <i class="far fa-calendar-alt text-teal-600 dark:text-teal-400"></i>
                                    <span x-text="isMonthly ? 'Periode Bulan:' : (isAll ? 'Tanggal (Dinonaktifkan):' : 'Pilih Tanggal:')"></span>
                                </label>
                                <div class="relative">
                                    <input type="hidden" name="date" :value="isMonthly ? monthValue + '-01' : selectedDate" :disabled="isAll">

                                    <template x-if="isMonthly">
                                        <input type="month" x-model="monthValue"
                                            class="w-full border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-semibold shadow-xs focus:border-teal-500 focus:ring-teal-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition py-2 px-3 [color-scheme:dark]">
                                    </template>

                                    <template x-if="!isMonthly">
                                        <input type="date" x-model="selectedDate" :disabled="isAll"
                                            class="w-full border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-semibold shadow-xs focus:border-teal-500 focus:ring-teal-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition py-2 px-3 [color-scheme:dark] disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-gray-800">
                                    </template>
                                </div>
                            </div>

                            {{-- Status Kehadiran --}}
                            <div class="flex flex-col gap-1 lg:col-span-1">
                                <label for="status" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <i class="fas fa-tag text-teal-600 dark:text-teal-400"></i> Status Kehadiran:
                                </label>
                                <select id="status" name="status" class="w-full text-xs font-semibold rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                                    <option value="">Semua Status</option>
                                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="alpa" {{ request('status') == 'alpa' ? 'selected' : '' }}>Alpa</option>
                                </select>
                            </div>

                            {{-- Status Validasi --}}
                            <div class="flex flex-col gap-1 lg:col-span-1">
                                <label for="validation_status" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <i class="fas fa-check-double text-teal-600 dark:text-teal-400"></i> Status Validasi:
                                </label>
                                <select id="validation_status" name="validation_status" class="w-full text-xs font-semibold rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                                    <option value="">Semua Validasi</option>
                                    <option value="pending" {{ request('validation_status') == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                                    <option value="approved" {{ request('validation_status') == 'approved' ? 'selected' : '' }}>Disetujui / Valid</option>
                                    <option value="rejected" {{ request('validation_status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>

                        </div>

                        {{-- Tombol Aksi & Active Filter Badges --}}
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                            {{-- Active Filter Badges (Left Side) --}}
                            <div class="flex flex-wrap items-center gap-2">
                                @if($isFiltered)
                                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400">Filter Aktif:</span>
                                    @if(request('search'))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60">
                                            <i class="fas fa-search text-[10px]"></i> "<span>{{ request('search') }}</span>"
                                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="hover:text-rose-500 transition ml-1" title="Hapus pencarian"><i class="fas fa-times"></i></a>
                                        </span>
                                    @endif

                                    @if(request('application_id') && isset($interns))
                                        @php
                                            $selectedIntern = $interns->firstWhere('id', request('application_id'));
                                        @endphp
                                        @if($selectedIntern)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60">
                                                <i class="fas fa-user-graduate text-[10px]"></i> {{ $selectedIntern->user->name }}
                                                <a href="{{ request()->fullUrlWithQuery(['application_id' => null]) }}" class="hover:text-rose-500 transition ml-1" title="Hapus filter peserta"><i class="fas fa-times"></i></a>
                                            </span>
                                        @endif
                                    @endif

                                    @if(request('filter_type') && request('filter_type') !== 'harian')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60 capitalize">
                                            <i class="fas fa-calendar text-[10px]"></i> Rentang: {{ request('filter_type') }}
                                            <a href="{{ request()->fullUrlWithQuery(['filter_type' => null]) }}" class="hover:text-rose-500 transition ml-1" title="Reset rentang"><i class="fas fa-times"></i></a>
                                        </span>
                                    @endif

                                    @if(request('status'))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60 capitalize">
                                            <i class="fas fa-tag text-[10px]"></i> Status: {{ request('status') }}
                                            <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="hover:text-rose-500 transition ml-1" title="Hapus filter status"><i class="fas fa-times"></i></a>
                                        </span>
                                    @endif

                                    @if(request('validation_status'))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60 capitalize">
                                            <i class="fas fa-check-double text-[10px]"></i> Validasi: {{ request('validation_status') }}
                                            <a href="{{ request()->fullUrlWithQuery(['validation_status' => null]) }}" class="hover:text-rose-500 transition ml-1" title="Hapus filter validasi"><i class="fas fa-times"></i></a>
                                        </span>
                                    @endif
                                @endif
                            </div>

                            {{-- Tombol Aksi & Cepat Harian (Right Side) --}}
                            <div class="flex items-center justify-end gap-3 shrink-0 flex-wrap">
                                {{-- Pilihan Cepat Harian --}}
                                <template x-if="filterType === 'harian'">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase mr-1">Cepat:</span>
                                        @foreach($dateList->take(4) as $dateItem)
                                            @php
                                                $isActive = $dateItem->format('Y-m-d') == $selectedDate;
                                                $activeClass = $isActive ? 'bg-teal-600 text-white border-teal-600 shadow-xs' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-teal-500 dark:hover:border-teal-400';
                                            @endphp
                                            <a href="{{ route('pembimbing_lapangan.attendance.index', array_merge(request()->query(), ['date' => $dateItem->format('Y-m-d'), 'filter_type' => 'harian'])) }}"
                                               class="text-[10px] text-center py-1 px-2.5 rounded-lg border transition duration-200 font-bold {{ $activeClass }}">
                                                {{ $dateItem->isToday() ? 'Hari Ini' : $dateItem->translatedFormat('D, d M') }}
                                            </a>
                                        @endforeach
                                    </div>
                                </template>

                                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition shadow-xs hover:shadow active:scale-95">
                                    <i class="fas fa-search"></i>
                                    <span>Terapkan Filter</span>
                                </button>

                                @if($isFiltered)
                                    <a href="{{ route('pembimbing_lapangan.attendance.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-bold transition">
                                        <i class="fas fa-undo"></i>
                                        <span>Reset</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Tabel Utama Absensi --}}
                <div class="w-full">
                    <div class="bg-white dark:bg-gray-800 shadow-xs rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-gray-800">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                    <i class="fas fa-calendar-check text-teal-600 dark:text-teal-400"></i>
                                    @if($filterType === 'mingguan')
                                        Rekap Absensi Mingguan
                                    @elseif($filterType === 'bulanan')
                                        Rekap Absensi Bulanan
                                    @elseif($filterType === 'semua')
                                        Rekap Semua Absensi
                                    @else
                                        Rekap Absensi Harian
                                    @endif
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">
                                    @if($filterType === 'mingguan')
                                        Periode: <span class="font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 px-2 py-0.5 rounded-md">{{ \Carbon\Carbon::parse($selectedDate)->startOfWeek()->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($selectedDate)->endOfWeek()->translatedFormat('d M Y') }}</span>
                                    @elseif($filterType === 'bulanan')
                                        Bulan: <span class="font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 px-2 py-0.5 rounded-md">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('F Y') }}</span>
                                    @elseif($filterType === 'semua')
                                        Rentang: <span class="font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 px-2 py-0.5 rounded-md">Semua Riwayat Tanggal</span>
                                    @else
                                        Tanggal: <span class="font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 px-2 py-0.5 rounded-md">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}</span>
                                    @endif
                                </p>
                            </div>
                            
                            <button onclick="window.print()" class="bg-gray-800 dark:bg-gray-700 text-white hover:bg-gray-700 dark:hover:bg-gray-600 px-4 py-2 rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-2 print:hidden">
                                <i class="fas fa-print"></i> Cetak
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Peserta</th>
                                        @if($filterType !== 'harian')
                                            <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                        @endif
                                        <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Masuk</th>
                                        <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Pulang</th>
                                        <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-5 py-4 text-right text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi Validasi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                                    @forelse($attendances as $row)
                                    <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-950/20 transition duration-150">
                                        
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="h-9 w-9 bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-300 rounded-full flex items-center justify-center font-black text-xs border border-teal-200 dark:border-teal-800/60 flex-shrink-0 shadow-xs">
                                                    {{ strtoupper(substr($row->application->user->name, 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">{{ $row->application->user->name }}</div>
                                                    <div class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold truncate">{{ Str::limit($row->application->position->judul_posisi, 28) }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        @if($filterType !== 'harian')
                                            <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-bold text-gray-700 dark:text-gray-300">
                                                {{ \Carbon\Carbon::parse($row->date)->translatedFormat('d M Y') }}
                                            </td>
                                        @endif
                                        
                                        <td class="px-5 py-4 whitespace-nowrap text-center">
                                            @if($row->status == 'hadir')
                                                <span class="text-xs font-mono font-bold text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 px-2.5 py-1 rounded-md">
                                                    {{ \Carbon\Carbon::parse($row->clock_in)->format('H:i') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">-</span>
                                            @endif
                                        </td>

                                        <td class="px-5 py-4 whitespace-nowrap text-center">
                                            @if($row->status == 'hadir')
                                                @if($row->clock_out)
                                                    <span class="text-xs font-mono font-bold text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 px-2.5 py-1 rounded-md">
                                                        {{ \Carbon\Carbon::parse($row->clock_out)->format('H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-[10px] text-rose-600 dark:text-rose-400 italic bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-900/40 px-2 py-0.5 rounded-md font-bold">Belum Absen</span>
                                                @endif
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">-</span>
                                            @endif
                                        </td>

                                        <td class="px-5 py-4 whitespace-nowrap text-center">
                                            @php
                                                $statusStyles = [
                                                    'hadir' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                                                    'sakit' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
                                                    'izin' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800/60',
                                                    'alpa' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
                                                ];
                                                $statusVal = $row->status instanceof \App\Enums\AttendanceStatus ? $row->status->value : $row->status;
                                                $style = $statusStyles[$statusVal] ?? 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-700';
                                                
                                                // Cek Pending Validation
                                                $isPending = ($statusVal != 'hadir' && ($row->validation_status instanceof \App\Enums\ValidationStatus ? $row->validation_status->value : $row->validation_status) == 'pending');
                                            @endphp

                                            <div class="relative inline-block">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $style }}">
                                                    {{ ucfirst($statusVal) }}
                                                </span>
                                                @if($isPending)
                                                    <span class="absolute -top-1 -right-2 flex h-3 w-3">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 whitespace-nowrap text-right">
                                            @if($row->status == 'hadir')
                                                <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider flex items-center justify-end gap-1">
                                                    <i class="fas fa-check-double text-teal-500"></i> Auto
                                                </span>
                                            @else
                                                @if($row->validation_status == 'pending')
                                                    <div class="flex items-center justify-end gap-2">
                                                        <button x-data="" x-on:click="$dispatch('open-modal', 'modal-bukti-{{ $row->id }}')" 
                                                            class="p-1.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-teal-50 dark:hover:bg-teal-900/50 hover:text-teal-600 transition border border-gray-200 dark:border-gray-600" title="Lihat Bukti" aria-label="Lihat Bukti">
                                                            <i class="fas fa-eye text-xs"></i>
                                                        </button>

                                                        <form action="{{ route('pembimbing_lapangan.attendance.validate', $row->id) }}" method="POST" class="inline" onsubmit="event.submitter && (event.submitter.disabled = true)">
                                                            @csrf
                                                            <input type="hidden" name="status_validasi" value="approved">
                                                            <button type="submit" class="p-1.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-xl border border-emerald-200 dark:border-emerald-800/60 hover:bg-emerald-100 transition" title="Setujui" aria-label="Setujui">
                                                                <i class="fas fa-check text-xs"></i>
                                                            </button>
                                                        </form>

                                                        <button x-data="" x-on:click="$dispatch('open-modal', 'modal-tolak-{{ $row->id }}')" 
                                                            class="p-1.5 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 rounded-xl border border-rose-200 dark:border-rose-800/60 hover:bg-rose-100 transition" title="Tolak" aria-label="Tolak">
                                                            <i class="fas fa-times text-xs"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class="flex items-center justify-end gap-2">
                                                        <button x-data="" x-on:click="$dispatch('open-modal', 'modal-bukti-{{ $row->id }}')" class="text-xs text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 underline decoration-dashed font-bold">
                                                            Detail
                                                        </button>
                                                        @if($row->validation_status == 'approved')
                                                            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1"><i class="fas fa-check-circle"></i> Valid</span>
                                                        @else
                                                            <span class="text-xs text-rose-600 dark:text-rose-400 font-bold flex items-center gap-1"><i class="fas fa-times-circle"></i> Ditolak</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>

                                    @if($row->proof_file)
                                    <x-modal name="modal-bukti-{{ $row->id }}" focusable>
                                        <div class="p-6 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                            <div class="flex justify-between items-center mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
                                                <h2 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                                    <i class="fas fa-file-medical text-teal-600 dark:text-teal-400"></i> Bukti Pengajuan {{ ucfirst($row->status) }}
                                                </h2>
                                                <button x-on:click="$dispatch('close')" aria-label="Tutup" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300"><i class="fas fa-times"></i></button>
                                            </div>
                                            
                                            <div class="flex justify-center bg-gray-50 dark:bg-gray-900 rounded-2xl p-3 mb-4 border border-gray-200 dark:border-gray-700 w-full">
                                                @if(Str::endsWith(strtolower($row->proof_file), '.pdf'))
                                                    <iframe src="{{ route('storage.access', ['type' => 'attendance', 'filename' => basename($row->proof_file)]) }}" class="w-full h-[50vh] rounded-xl border-0"></iframe>
                                                @else
                                                    <img src="{{ route('storage.access', ['type' => 'attendance', 'filename' => basename($row->proof_file)]) }}" class="max-h-[60vh] rounded-xl shadow-xs hover:scale-105 transition duration-300" alt="Bukti">
                                                @endif
                                            </div>
                                            
                                            <div class="bg-teal-50/60 dark:bg-teal-950/40 p-4 rounded-2xl border border-teal-200 dark:border-teal-800/60">
                                                <p class="text-[10px] text-teal-700 dark:text-teal-300 font-bold uppercase mb-1">Keterangan Mahasiswa</p>
                                                <p class="text-teal-900 dark:text-teal-200 text-xs sm:text-sm italic font-medium">"{{ $row->description }}"</p>
                                            </div>
                                            
                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">Tutup</x-secondary-button>
                                            </div>
                                        </div>
                                    </x-modal>
                                    @endif

                                    <x-modal name="modal-tolak-{{ $row->id }}" focusable>
                                        <div class="p-6 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                            <h2 class="text-base font-bold text-rose-600 dark:text-rose-400 mb-4 border-b border-gray-100 dark:border-gray-700 pb-3 flex items-center gap-2">
                                                <i class="fas fa-user-times"></i> Tolak Pengajuan {{ ucfirst($row->status) }}
                                            </h2>
                                            <form action="{{ route('pembimbing_lapangan.attendance.validate', $row->id) }}" method="POST" onsubmit="event.submitter && (event.submitter.disabled = true)">
                                                @csrf
                                                <input type="hidden" name="status_validasi" value="rejected">
                                                
                                                <div class="mb-4">
                                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Alasan Penolakan</label>
                                                    <textarea name="pembimbing_lapangan_note" rows="3" class="w-full border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 shadow-xs focus:border-rose-500 focus:ring-rose-500 text-xs font-bold" required placeholder="Contoh: Bukti surat dokter tidak jelas atau tidak menyantumkan tanggal..."></textarea>
                                                </div>
                                                
                                                <div class="mt-6 flex justify-end gap-3">
                                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-xs transition">
                                                        Konfirmasi Tolak
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </x-modal>

                                    @empty
                                    <tr>
                                        <td colspan="{{ $filterType !== 'harian' ? 6 : 5 }}" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                                <div class="h-16 w-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                                    <i class="fas fa-clipboard-check text-3xl text-gray-400 dark:text-gray-500"></i>
                                                </div>
                                                <p class="font-bold text-gray-700 dark:text-gray-300 text-sm">Data absensi kosong</p>
                                                <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">Tidak ada data absensi untuk tanggal/periode yang dipilih.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($attendances instanceof \Illuminate\Pagination\LengthAwarePaginator && $attendances->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            {{ $attendances->links() }}
                        </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>