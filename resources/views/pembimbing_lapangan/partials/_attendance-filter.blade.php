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
                <label for="date_input" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                    <i class="far fa-calendar-alt text-teal-600 dark:text-teal-400"></i>
                    <span x-text="isMonthly ? 'Periode Bulan:' : (isAll ? 'Tanggal (Dinonaktifkan):' : 'Pilih Tanggal:')"></span>
                </label>
                <div class="relative">
                    <input type="hidden" name="date" :value="isMonthly ? monthValue + '-01' : selectedDate" :disabled="isAll">

                    <template x-if="isMonthly">
                        <input type="month" id="date_input" x-model="monthValue"
                            class="w-full border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-semibold shadow-xs focus:border-teal-500 focus:ring-teal-500 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition py-2 px-3 [color-scheme:dark]">
                    </template>

                    <template x-if="!isMonthly">
                        <input type="date" id="date_input" x-model="selectedDate" :disabled="isAll"
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
