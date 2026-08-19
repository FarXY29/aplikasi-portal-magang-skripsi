<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-check-double text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Validasi Logbook') }}
            </h2>
            <div class="flex items-center gap-3">
                <label for="switch_student" class="text-xs font-bold text-gray-500 dark:text-gray-400">Mahasiswa:</label>
                @if(isset($interns) && $interns->count() > 1)
                    <select id="switch_student" onchange="window.location.href = this.value" class="px-3.5 py-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-200 shadow-xs focus:ring-teal-500 focus:border-teal-500">
                        @foreach($interns as $intern)
                            <option value="{{ route('pembimbing_lapangan.logbook', $intern->id) }}" {{ $intern->id == $app->id ? 'selected' : '' }}>
                                {{ $intern->user->name }}
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
            
            <div class="flex justify-between items-center mb-6 print:hidden">
                <a href="{{ route('pembimbing_lapangan.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" class="mb-4">
                    {{ session('success') }}
                </x-ui.alert>
            @endif

            @php
                $isFiltered = request()->hasAny(['search', 'status_validasi'])
                    || (request()->has('filter_type') && request('filter_type') !== 'semua')
                    || (request()->has('date') && request('date') !== date('Y-m-d'));
            @endphp

            {{-- Filter Bar --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 print:hidden space-y-5">
                <form action="{{ route('pembimbing_lapangan.logbook', $app->id) }}" method="GET"
                      x-data="{
                          filterType: @js($filterType),
                          selectedDate: @js($selectedDate),
                          monthValue: @js(\Carbon\Carbon::parse($selectedDate)->format('Y-m')),
                          get isMonthly() { return this.filterType === 'bulanan'; },
                          get isAll() { return this.filterType === 'semua'; },
                          resetFilter() {
                              window.location.href = @js(route('pembimbing_lapangan.logbook', $app->id));
                          }
                      }"
                      class="space-y-4">

                    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 border-b border-gray-100 dark:border-gray-700 pb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 flex items-center justify-center text-teal-600 dark:text-teal-400">
                                <i class="fas fa-filter text-xs"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">Filter Riwayat Logbook</h3>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">Filter berdasarkan rentang waktu, status validasi, atau pencarian kata kunci kegiatan</p>
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
                                @if(isset($interns))
                                    @foreach($interns as $intern)
                                        <option value="{{ route('pembimbing_lapangan.logbook', array_merge(['id' => $intern->id], request()->except('id'))) }}" {{ $intern->id == $app->id ? 'selected' : '' }}>
                                            {{ $intern->user->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- Search Input --}}
                        <div class="flex flex-col gap-1 lg:col-span-1">
                            <label for="search" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <i class="fas fa-search text-teal-600 dark:text-teal-400"></i> Cari Kegiatan Jurnal:
                            </label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Cari isi deskripsi..." class="w-full text-xs font-semibold rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                        </div>

                        {{-- Status Validasi Filter --}}
                        <div class="flex flex-col gap-1 lg:col-span-1">
                            <label for="status_validasi" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <i class="fas fa-check-double text-teal-600 dark:text-teal-400"></i> Status Validasi:
                            </label>
                            <select id="status_validasi" name="status_validasi" class="w-full text-xs font-semibold rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                                <option value="">Semua Validasi</option>
                                <option value="pending" {{ request('status_validasi') == 'pending' ? 'selected' : '' }}>Pending / Menunggu</option>
                                <option value="disetujui" {{ request('status_validasi') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="revisi" {{ request('status_validasi') == 'revisi' ? 'selected' : '' }}>Perlu Revisi</option>
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
                            @if($isFiltered || (isset($interns) && $interns->count() > 1))
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

                                @if(request('status_validasi'))
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60 capitalize">
                                        <i class="fas fa-check-double text-[10px]"></i> Validasi: {{ request('status_validasi') }}
                                        <a href="{{ request()->fullUrlWithQuery(['status_validasi' => null]) }}" class="hover:text-rose-500 transition ml-1" title="Hapus filter validasi"><i class="fas fa-times"></i></a>
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
                                <a href="{{ route('pembimbing_lapangan.logbook', $app->id) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-bold transition">
                                    <i class="fas fa-undo"></i>
                                    <span>Reset</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            @if($logs->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                        <i class="fas fa-book-open text-3xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Logbook Kosong</h3>
                    @if($filterType !== 'semua')
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tidak ada aktivitas logbook pada periode yang dipilih.</p>
                        <a href="{{ route('pembimbing_lapangan.logbook', $app->id) }}" class="mt-4 px-4 py-2 bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 font-bold rounded-xl text-xs hover:bg-teal-100 border border-teal-200 dark:border-teal-800/60 transition shadow-xs">Reset Filter</a>
                    @else
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Mahasiswa ini belum mengunggah aktivitas jurnal apapun.</p>
                    @endif
                </div>
            @else
                @php
                    $activeLogId = (int) session('last_id');
                    if (! $activeLogId || ! $logs->contains('id', $activeLogId)) {
                        $activeLogId = $logs->first()->id;
                    }
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start" 
                     x-data="{ activeTab: {{ $activeLogId }} }">
                    
                    {{-- Sidebar List Logbook --}}
                    <div class="md:col-span-4 col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-8">
                            <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-between items-center">
                                <h3 class="font-bold text-gray-800 dark:text-gray-200 text-xs uppercase tracking-wider flex items-center gap-2">
                                    <i class="fas fa-list-ul text-teal-600 dark:text-teal-400"></i> Riwayat Aktivitas
                                </h3>
                                <span class="text-[10px] font-black bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-0.5 rounded-full">{{ $logs->count() }}</span>
                            </div>
                            
                            <form action="{{ route('pembimbing_lapangan.logbook.batch_validasi') }}" method="POST" onsubmit="event.submitter && (event.submitter.disabled = true)">
                                @csrf
                                <div class="max-h-[60vh] overflow-y-auto custom-scrollbar">
                                    <ul class="divide-y divide-gray-100 dark:divide-gray-700/60">
                                        @foreach($logs as $log)
                                        <li class="flex items-center pr-2 hover:bg-gray-50 dark:hover:bg-gray-900/60 transition duration-150 group">
                                            @if($log->status_validasi != 'disetujui')
                                                <div class="pl-4 pr-1">
                                                    <input type="checkbox" name="log_ids[]" value="{{ $log->id }}" class="rounded border-gray-300 dark:border-gray-700 text-teal-600 focus:ring-teal-500 cursor-pointer">
                                                </div>
                                            @else
                                                <div class="pl-4 pr-1 opacity-0 w-5"></div>
                                            @endif

                                            <button type="button"
                                                @click="activeTab = {{ $log->id }}"
                                                :class="{ 'bg-teal-50/70 dark:bg-teal-950/40 border-l-4 border-teal-500 dark:border-teal-400': activeTab === {{ $log->id }}, 'border-l-4 border-transparent': activeTab !== {{ $log->id }} }"
                                                class="w-full text-left px-3 py-3 focus:outline-none">
                                                
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200" 
                                                          :class="{ 'text-teal-700 dark:text-teal-300': activeTab === {{ $log->id }} }">
                                                        {{ \Carbon\Carbon::parse($log->tanggal)->format('d M Y') }}
                                                    </span>
                                                    
                                                    @if($log->status_validasi == 'disetujui')
                                                        <i class="fas fa-check-circle text-emerald-500 text-xs" title="Disetujui"></i>
                                                    @elseif($log->status_validasi == 'revisi')
                                                        <i class="fas fa-exclamation-circle text-rose-500 text-xs" title="Revisi"></i>
                                                    @else
                                                        <div class="w-2.5 h-2.5 rounded-full bg-amber-400 mt-1" title="Pending"></div>
                                                    @endif
                                                </div>
                                                
                                                <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate group-hover:text-gray-700 dark:group-hover:text-gray-300">
                                                    {{ Str::limit($log->kegiatan, 32) }}
                                                </p>
                                            </button>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                
                                {{-- Batch Action Buttons --}}
                                <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex flex-col gap-2">
                                    <textarea name="komentar" id="batch-komentar" rows="2" placeholder="Catatan revisi (wajib jika memilih Revisi)..." class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-teal-500 focus:ring-teal-500 text-xs font-bold shadow-xs px-3 py-2"></textarea>
                                    @error('komentar')
                                        <p class="text-[10px] text-rose-600 dark:text-rose-400 font-bold">{{ $message }}</p>
                                    @enderror
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300 flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" onchange="document.querySelectorAll('input[name=\'log_ids[]\']').forEach(c => c.checked = this.checked)" class="rounded border-gray-300 dark:border-gray-700 text-teal-600 focus:ring-teal-500">
                                            Pilih Semua
                                        </label>
                                        <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider">Validasi Massal</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" name="status" value="disetujui" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold py-2 rounded-xl transition shadow-xs flex items-center justify-center">
                                            <i class="fas fa-check mr-1"></i> Terima
                                        </button>
                                        <button type="submit" name="status" value="revisi" onclick="var c = document.getElementById('batch-komentar'); if (c.value.trim() === '') { c.focus(); c.classList.add('border-rose-500'); return false; }" class="flex-1 bg-white dark:bg-gray-800 border border-rose-200 dark:border-rose-800/60 text-rose-700 dark:text-rose-300 hover:bg-rose-50 dark:hover:bg-gray-700 text-xs font-bold py-2 rounded-xl transition shadow-xs flex items-center justify-center">
                                            <i class="fas fa-undo mr-1"></i> Revisi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Main Detail Pane --}}
                    <div class="md:col-span-8 col-span-1">
                        @foreach($logs as $log)
                        <div x-show="activeTab === {{ $log->id }}" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display: none;">
                            
                            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                                
                                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <div>
                                        <h3 class="text-xl font-black text-gray-800 dark:text-gray-100">Detail Kegiatan Jurnal</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center font-bold">
                                            <i class="far fa-calendar-alt mr-1.5 text-teal-600 dark:text-teal-400"></i> 
                                            {{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('l, d F Y') }}
                                        </p>
                                    </div>
                                    
                                    @php
                                        $statusClass = match($log->status_validasi) {
                                            'disetujui' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                                            'revisi' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
                                            default => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60'
                                        };
                                        $statusIcon = match($log->status_validasi) {
                                            'disetujui' => 'fa-check-circle',
                                            'revisi' => 'fa-undo',
                                            default => 'fa-clock'
                                        };
                                    @endphp
                                    <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase border {{ $statusClass }} flex items-center gap-1.5">
                                        <i class="fas {{ $statusIcon }}"></i> {{ ucfirst($log->status_validasi) }}
                                    </span>
                                </div>

                                <div class="p-6 sm:p-8 space-y-6">
                                    <div class="flex flex-col lg:flex-row gap-6">
                                        
                                        <div class="w-full lg:w-1/3 flex-shrink-0">
                                            <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Dokumentasi</h4>
                                            @if($log->bukti_foto_path)
                                                @php
                                                    $fotoUrl = route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]);
                                                    $fotoTitle = 'Dokumentasi Logbook - ' . \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d F Y') . ' (' . $app->user->name . ')';
                                                @endphp
                                                <div class="relative group rounded-2xl overflow-hidden shadow-xs border border-gray-200 dark:border-gray-700 cursor-pointer" onclick="openImageModal('{{ $fotoUrl }}', '{{ addslashes($fotoTitle) }}')">
                                                    <img src="{{ $fotoUrl }}" class="w-full h-48 object-cover transition transform group-hover:scale-105 duration-500" alt="Dokumentasi">
                                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/25 transition flex items-center justify-center">
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-black/60 text-white text-xs font-bold backdrop-blur-sm opacity-0 group-hover:opacity-100 transition duration-200 drop-shadow">
                                                            <i class="fas fa-search-plus text-xs"></i> Perbesar Foto
                                                        </span>
                                                    </div>
                                                </div>
                                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-2 text-center">*Klik gambar untuk melihat ukuran penuh</p>
                                            @else
                                                <div class="w-full h-44 bg-gray-50 dark:bg-gray-900 rounded-2xl flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 text-xs border-2 border-dashed border-gray-200 dark:border-gray-700">
                                                    <i class="far fa-image text-3xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                                    <span class="font-bold">Tidak ada bukti foto</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="w-full lg:w-2/3">
                                            <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Deskripsi Pekerjaan</h4>
                                            <div class="p-5 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 text-xs sm:text-sm leading-relaxed whitespace-pre-line min-h-[11rem]">
                                                {{ $log->kegiatan }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                                        
                                        @if($log->komentar_pembimbing_lapangan)
                                            <div class="mb-6 p-4 bg-blue-50/60 dark:bg-blue-950/40 rounded-2xl border border-blue-200 dark:border-blue-800/60 flex gap-3 items-start">
                                                <i class="fas fa-comment-dots text-blue-600 dark:text-blue-400 mt-0.5 text-base flex-shrink-0"></i>
                                                <div>
                                                    <span class="block text-xs font-bold text-blue-800 dark:text-blue-300 uppercase mb-1">Catatan Anda Sebelumnya:</span>
                                                    <p class="text-xs sm:text-sm text-blue-900 dark:text-blue-200 italic">"{{ $log->komentar_pembimbing_lapangan }}"</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if($log->status_validasi != 'disetujui_permanen') 
                                            <form action="{{ route('pembimbing_lapangan.logbook.validasi', $log->id) }}" method="POST" onsubmit="event.submitter && (event.submitter.disabled = true)" class="bg-gray-50 dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-700">
                                                @csrf
                                                <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-3 flex items-center gap-2">
                                                    <i class="fas fa-pen-nib text-teal-600 dark:text-teal-400"></i> Berikan Validasi & Catatan
                                                </h4>
                                                
                                                <div class="flex flex-col sm:flex-row gap-3">
                                                    <input type="text" name="komentar" 
                                                        class="flex-grow rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-xs font-bold shadow-xs" 
                                                        placeholder="Tulis catatan revisi atau apresiasi (Opsional)..."
                                                        value="{{ $log->status_validasi == 'revisi' ? $log->komentar_pembimbing_lapangan : '' }}">
                                                    
                                                    <div class="flex gap-2 flex-shrink-0">
                                                        <button type="submit" name="status" value="disetujui" 
                                                            class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                                                            <i class="fas fa-check"></i> Terima
                                                        </button>
                                                        
                                                        <button type="submit" name="status" value="revisi" 
                                                            class="bg-white dark:bg-gray-800 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60 px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-rose-50 dark:hover:bg-gray-700 transition shadow-xs flex items-center gap-1.5">
                                                            <i class="fas fa-undo"></i> Revisi
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        @else
                                            <div class="text-center py-4">
                                                <p class="text-xs text-gray-400 dark:text-gray-500 italic">Logbook ini telah disetujui secara permanen.</p>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            @endif

        </div>
    </div>
</x-app-layout>