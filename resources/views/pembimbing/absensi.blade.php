<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-clipboard-list text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Pemantauan Absensi Mahasiswa') }}
            </h2>
            <div class="flex items-center gap-3">
                <label for="switch_student" class="text-xs font-bold text-gray-500 dark:text-gray-400">Mahasiswa:</label>
                @if(isset($applications) && $applications->count() > 1)
                    <select id="switch_student" onchange="window.location.href = this.value" class="px-3.5 py-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-200 shadow-xs focus:ring-teal-500 focus:border-teal-500">
                        @foreach($applications as $item)
                            <option value="{{ route('pembimbing.peserta.absensi', $item->id) }}" {{ $item->id == $app->id ? 'selected' : '' }}>
                                {{ $item->user->name }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <span class="px-3.5 py-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full text-xs font-bold text-gray-800 dark:text-gray-200 shadow-xs">
                        {{ $app->user->name }}
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('pembimbing.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            @php
                $isFiltered = request()->hasAny(['search', 'status'])
                    || (request()->has('filter_type') && request('filter_type') !== 'semua')
                    || (request()->has('date') && request('date') !== date('Y-m-d'));
            @endphp

            {{-- Filter Bar --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 print:hidden space-y-5">
                <form action="{{ route('pembimbing.peserta.absensi', $app->id) }}" method="GET"
                      x-data="{
                          filterType: @js($filterType),
                          selectedDate: @js($selectedDate),
                          monthValue: @js(\Carbon\Carbon::parse($selectedDate)->format('Y-m')),
                          get isMonthly() { return this.filterType === 'bulanan'; },
                          get isAll() { return this.filterType === 'semua'; },
                          resetFilter() {
                              window.location.href = @js(route('pembimbing.peserta.absensi', $app->id));
                          }
                      }"
                      class="space-y-4">

                    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 border-b border-gray-100 dark:border-gray-700 pb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 flex items-center justify-center text-teal-600 dark:text-teal-400">
                                <i class="fas fa-filter text-xs"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">Filter Pemantauan Absensi</h3>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">Filter data absensi mahasiswa berdasarkan rentang waktu, status, atau pencarian</p>
                            </div>
                        </div>

                        {{-- Rentang Waktu Segmented Control --}}
                        <div class="w-full xl:w-auto">
                            <div class="grid grid-cols-4 gap-1 bg-gray-100 dark:bg-gray-900 p-1 rounded-xl border border-gray-200 dark:border-gray-700">
                                <label class="cursor-pointer">
                                    <input type="radio" name="filter_type" value="semua" x-model="filterType" class="sr-only peer">
                                    <span class="block text-center text-[10px] font-bold py-1.5 px-3 rounded-lg text-gray-500 dark:text-gray-400 peer-checked:bg-white dark:peer-checked:bg-gray-800 peer-checked:text-teal-600 dark:peer-checked:text-teal-400 peer-checked:shadow-xs transition">
                                        Semua
                                    </span>
                                </label>
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
                            </div>
                        </div>
                    </div>

                    {{-- Multi-Field Filters Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

                        {{-- Dropdown Peserta Bimbingan --}}
                        <div class="flex flex-col gap-1 lg:col-span-1">
                            <label for="filter_student" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <i class="fas fa-user-graduate text-teal-600 dark:text-teal-400"></i> Mahasiswa Bimbingan:
                            </label>
                            <select id="filter_student" onchange="window.location.href = this.value" class="w-full text-xs font-semibold rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                                @if(isset($applications))
                                    @foreach($applications as $item)
                                        <option value="{{ route('pembimbing.peserta.absensi', array_merge(['id' => $item->id], request()->except('id'))) }}" {{ $item->id == $app->id ? 'selected' : '' }}>
                                            {{ $item->user->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- Search Input --}}
                        <div class="flex flex-col gap-1 lg:col-span-1">
                            <label for="search" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <i class="fas fa-search text-teal-600 dark:text-teal-400"></i> Cari Catatan:
                            </label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Cari keterangan..." class="w-full text-xs font-semibold rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                        </div>

                        {{-- Status Kehadiran Filter --}}
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

                        {{-- Date / Month Picker --}}
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

                    </div>

                    {{-- Tombol Aksi & Active Filter Badges --}}
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        {{-- Active Filter Badges (Left Side) --}}
                        <div class="flex flex-wrap items-center gap-2">
                            @if($isFiltered || (isset($applications) && $applications->count() > 1))
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400">Filter Aktif:</span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60">
                                    <i class="fas fa-user-graduate text-[10px]"></i> {{ $app->user->name }}
                                </span>

                                @if(request('search'))
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60">
                                        <i class="fas fa-search text-[10px]"></i> "<span>{{ request('search') }}</span>"
                                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="hover:text-rose-500 transition ml-1" title="Hapus pencarian"><i class="fas fa-times"></i></a>
                                    </span>
                                @endif

                                @if(request('filter_type') && request('filter_type') !== 'semua')
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
                            @endif
                        </div>

                        {{-- Tombol Aksi (Right Side) --}}
                        <div class="flex items-center justify-end gap-2 shrink-0">
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition shadow-xs hover:shadow active:scale-95">
                                <i class="fas fa-search"></i>
                                <span>Terapkan Filter</span>
                            </button>

                            @if($isFiltered)
                                <a href="{{ route('pembimbing.peserta.absensi', $app->id) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-bold transition">
                                    <i class="fas fa-undo"></i>
                                    <span>Reset</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- Main Table Container --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-calendar-check text-teal-600 dark:text-teal-400"></i> Rekap Kehadiran
                    </h3>
                    <span class="bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 text-xs font-black px-3 py-1 rounded-full border border-teal-200 dark:border-teal-800/60">
                        Total Hari: {{ $attendances->count() }}
                    </span>
                </div>
                
                <div>
                    @if($attendances->isEmpty())
                        <div class="p-16 text-center text-gray-400 dark:text-gray-500">
                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-200 dark:border-gray-700">
                                <i class="far fa-clipboard text-3xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <p class="font-bold text-gray-700 dark:text-gray-300 text-sm">Belum Ada Rekaman Absensi</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Belum ada catatan kehadiran untuk mahasiswa ini pada periode yang dipilih.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Masuk</th>
                                        <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Pulang</th>
                                        <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catatan / Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                                    @foreach($attendances as $absen)
                                        <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-950/20 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                                    {{ \Carbon\Carbon::parse($absen->date)->translatedFormat('l, d M Y') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                                @if($absen->clock_in)
                                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-mono font-bold text-gray-800 dark:text-gray-200">
                                                        {{ \Carbon\Carbon::parse($absen->clock_in)->format('H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                                @if($absen->clock_out)
                                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-mono font-bold text-gray-800 dark:text-gray-200">
                                                        {{ \Carbon\Carbon::parse($absen->clock_out)->format('H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-rose-600 dark:text-rose-400 italic bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-900/40 px-2.5 py-0.5 rounded-md font-bold">Belum Pulang</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                                @php
                                                    $statusClass = match($absen->status) {
                                                        'hadir' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                                                        'izin', 'sakit' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
                                                        'alpa' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
                                                        default => 'bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700'
                                                    };
                                                @endphp
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $statusClass }}">
                                                    {{ ucfirst($absen->status) }}
                                                </span>
                                                
                                                @if($absen->validation_status == 'valid')
                                                    <div class="mt-1 flex items-center justify-center text-[10px] text-emerald-600 dark:text-emerald-400 font-bold" title="Divalidasi Pembimbing Lapangan">
                                                        <i class="fas fa-check-circle mr-1"></i> Valid
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-xs sm:text-sm text-gray-800 dark:text-gray-200 font-medium leading-relaxed max-w-xs truncate" title="{{ $absen->description }}">
                                                    {{ $absen->description ?: '-' }}
                                                </div>
                                                @if($absen->pembimbing_lapangan_note)
                                                    <div class="mt-1.5 p-2 bg-blue-50/60 dark:bg-blue-950/40 rounded-xl border border-blue-200 dark:border-blue-800/60 text-xs text-blue-900 dark:text-blue-200 italic">
                                                        <strong class="not-italic text-blue-800 dark:text-blue-300">Catatan Lapangan:</strong> {{ $absen->pembimbing_lapangan_note }}
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
