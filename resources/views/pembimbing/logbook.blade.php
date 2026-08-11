<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-book-open text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Pemantauan Logbook Mahasiswa') }}
            </h2>
            <div class="flex items-center gap-3">
                <label for="switch_student" class="text-xs font-bold text-gray-500 dark:text-gray-400">Mahasiswa:</label>
                @if(isset($applications) && $applications->count() > 1)
                    <select id="switch_student" onchange="window.location.href = this.value" class="px-3.5 py-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-200 shadow-xs focus:ring-teal-500 focus:border-teal-500">
                        @foreach($applications as $item)
                            <option value="{{ route('pembimbing.peserta.logbook', $item->id) }}" {{ $item->id == $app->id ? 'selected' : '' }}>
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
                $isFiltered = request()->hasAny(['search', 'status_validasi'])
                    || (request()->has('filter_type') && request('filter_type') !== 'semua')
                    || (request()->has('date') && request('date') !== date('Y-m-d'));
            @endphp

            {{-- Filter Bar --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 print:hidden space-y-5">
                <form action="{{ route('pembimbing.peserta.logbook', $app->id) }}" method="GET"
                      x-data="{
                          filterType: @js($filterType),
                          selectedDate: @js($selectedDate),
                          monthValue: @js(\Carbon\Carbon::parse($selectedDate)->format('Y-m')),
                          get isMonthly() { return this.filterType === 'bulanan'; },
                          get isAll() { return this.filterType === 'semua'; },
                          resetFilter() {
                              window.location.href = @js(route('pembimbing.peserta.logbook', $app->id));
                          }
                      }"
                      class="space-y-4">

                    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 border-b border-gray-100 dark:border-gray-700 pb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 flex items-center justify-center text-teal-600 dark:text-teal-400">
                                <i class="fas fa-filter text-xs"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">Filter Pemantauan Logbook</h3>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">Filter data logbook berdasarkan rentang waktu, status validasi, atau pencarian kegiatan</p>
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
                                        <option value="{{ route('pembimbing.peserta.logbook', array_merge(['id' => $item->id], request()->except('id'))) }}" {{ $item->id == $app->id ? 'selected' : '' }}>
                                            {{ $item->user->name }}
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
                                <a href="{{ route('pembimbing.peserta.logbook', $app->id) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-bold transition">
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
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Belum ada aktivitas logbook pada periode yang dipilih.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start" x-data="{ activeTab: {{ $logs->first()->id }} }">
                    
                    {{-- Sidebar Log List --}}
                    <div class="md:col-span-4 col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-8">
                            <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-between items-center">
                                <h3 class="font-bold text-gray-800 dark:text-gray-200 text-xs uppercase tracking-wider flex items-center gap-2">
                                    <i class="fas fa-list-ul text-teal-600 dark:text-teal-400"></i> Riwayat Aktivitas
                                </h3>
                                <span class="text-[10px] font-black bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-0.5 rounded-full">{{ $logs->count() }}</span>
                            </div>
                            
                            <div class="max-h-[70vh] overflow-y-auto custom-scrollbar">
                                <ul class="divide-y divide-gray-100 dark:divide-gray-700/60">
                                    @foreach($logs as $log)
                                    <li>
                                        <button @click="activeTab = {{ $log->id }}"
                                            :class="{ 'bg-teal-50/70 dark:bg-teal-950/40 border-l-4 border-teal-500 dark:border-teal-400': activeTab === {{ $log->id }}, 'border-l-4 border-transparent hover:bg-gray-50 dark:hover:bg-gray-900/60': activeTab !== {{ $log->id }} }"
                                            class="w-full text-left px-4 py-3 transition duration-150 ease-in-out focus:outline-none group">
                                            
                                            <div class="flex justify-between items-start mb-1">
                                                <span class="text-xs font-bold text-gray-800 dark:text-gray-200" :class="{ 'text-teal-700 dark:text-teal-300': activeTab === {{ $log->id }} }">
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
                                                {{ Str::limit($log->kegiatan, 40) }}
                                            </p>
                                        </button>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Pane --}}
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
                                                <div class="relative group rounded-2xl overflow-hidden shadow-xs border border-gray-200 dark:border-gray-700 cursor-zoom-in">
                                                    <img src="{{ route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]) }}" class="w-full h-48 object-cover transition transform group-hover:scale-105 duration-500">
                                                    <a href="{{ route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]) }}" target="_blank" class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition flex items-center justify-center">
                                                        <i class="fas fa-search-plus text-white text-xl opacity-0 group-hover:opacity-100 transition duration-200 drop-shadow"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <div class="w-full h-44 bg-gray-50 dark:bg-gray-900 rounded-2xl flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 text-xs border-2 border-dashed border-gray-200 dark:border-gray-700">
                                                    <i class="far fa-image text-3xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                                    <span class="font-bold">Tidak ada foto bukti</span>
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

                                    @if($log->komentar_pembimbing_lapangan)
                                        <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                                            <div class="p-4 bg-blue-50/60 dark:bg-blue-950/40 rounded-2xl border border-blue-200 dark:border-blue-800/60 flex gap-3 items-start">
                                                <i class="fas fa-comment-dots text-blue-600 dark:text-blue-400 mt-0.5 text-base flex-shrink-0"></i>
                                                <div>
                                                    <span class="block text-xs font-bold text-blue-800 dark:text-blue-300 uppercase mb-1">Catatan Pembimbing Lapangan:</span>
                                                    <p class="text-xs sm:text-sm text-blue-900 dark:text-blue-200 italic">"{{ $log->komentar_pembimbing_lapangan }}"</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</x-app-layout>
