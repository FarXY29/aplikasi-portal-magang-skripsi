<!-- Vacancies Section Header & Results Count -->
<div class="reveal flex flex-col md:flex-row justify-between items-start md:items-end mb-6 gap-4 w-full" style="--reveal-delay: 0ms" x-intersect.once="$el.classList.add('revealed')">
    <div>
        <span class="text-xs font-bold text-teal-600 dark:text-teal-400 tracking-widest uppercase bg-teal-50 dark:bg-teal-950/60 px-4 py-2 rounded-full border border-teal-200 dark:border-teal-800/60">Eksplorasi Peran</span>
        <h2 id="lowongan" class="text-2xl sm:text-4xl font-extrabold text-slate-800 dark:text-gray-100 tracking-tight mt-4 scroll-mt-[95px]">Lowongan Magang Tersedia</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm sm:text-base font-medium">Dapatkan kesempatan berharga untuk mengabdi dan belajar langsung di instansi pemerintahan.</p>
    </div>
    
    <div class="flex items-center gap-3">
        <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-teal-50/80 dark:bg-teal-950/40 border border-teal-200/70 dark:border-teal-800/50 text-teal-700 dark:text-teal-300 text-xs font-bold shadow-2xs">
            <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
            <span>Ditemukan <strong>{{ $lowongans->total() }}</strong> Posisi</span>
        </div>

        @if(request()->anyFilled(['posisi', 'instansi_id', 'jurusan', 'major_category_id', 'search', 'sort']) || (request('filter_jurusan') === 'all' && ($userHasMajor ?? false)))
            <a href="{{ route('home') }}#lowongan" class="group flex items-center bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60 px-4 py-2.5 rounded-2xl text-xs font-bold hover:bg-rose-100 dark:hover:bg-rose-900/60 transition duration-300 shadow-2xs">
                <i class="fas fa-undo-alt mr-1.5 group-hover:-rotate-180 transition-transform duration-500 text-xs"></i> Reset
            </a>
        @endif
    </div>
</div>

@if(($isPeserta ?? false) && ($userHasMajor ?? false))
    <!-- Smart Qualification Filter Banner -->
    <div class="reveal mb-6 p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-teal-500/10 via-emerald-500/5 to-transparent dark:from-teal-950/40 dark:via-emerald-950/20 dark:to-transparent border border-teal-200/80 dark:border-teal-800/60 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-2xs" style="--reveal-delay: 25ms" x-intersect.once="$el.classList.add('revealed')">
        <div class="flex items-start sm:items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-teal-600 text-white flex items-center justify-center shrink-0 shadow-sm mt-0.5 sm:mt-0">
                <i class="fas fa-user-graduate text-base"></i>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-black text-slate-800 dark:text-slate-100 uppercase tracking-wider">Kualifikasi Jurusan Anda</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-teal-100 dark:bg-teal-900/60 text-teal-800 dark:text-teal-200 text-xs font-extrabold border border-teal-200/80 dark:border-teal-700/60">
                        <i class="fas fa-check-circle text-[11px] text-teal-600 dark:text-teal-400"></i>
                        {{ $userMajorLabel }}
                    </span>
                </div>
                <p class="text-slate-600 dark:text-slate-300 text-xs mt-1 font-medium leading-relaxed">
                    @if(($filterJurusanScope ?? '') === 'my_major')
                        Menampilkan lowongan yang sesuai kualifikasi jurusan Anda (<strong>{{ $totalMatchingCount }}</strong> posisi ditemukan).
                    @else
                        Menampilkan seluruh lowongan yang tersedia lintas jurusan dan kualifikasi.
                    @endif
                </p>
            </div>
        </div>

        <!-- Segmented Switch / Toggle Pills -->
        <div class="flex items-center p-1 bg-white/90 dark:bg-gray-800/90 rounded-2xl border border-slate-200 dark:border-gray-700 shadow-2xs shrink-0 self-start md:self-center">
            <button type="button"
                    @click="setFilterJurusan('my_major')"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ ($filterJurusanScope ?? '') === 'my_major' ? 'bg-teal-600 text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400' }}">
                <i class="fas fa-bullseye text-[11px]"></i>
                <span>Sesuai Jurusan ({{ $totalMatchingCount }})</span>
            </button>
            <button type="button"
                    @click="setFilterJurusan('all')"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ ($filterJurusanScope ?? '') === 'all' ? 'bg-slate-800 dark:bg-slate-700 text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="fas fa-globe text-[11px]"></i>
                <span>Semua Lowongan</span>
            </button>
        </div>
    </div>
@elseif(($isPeserta ?? false) && !($userHasMajor ?? false))
    <!-- Incomplete Profile Notice Banner -->
    <div class="reveal mb-6 p-4 sm:p-5 rounded-2xl bg-amber-50/90 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-800/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-2xs" style="--reveal-delay: 25ms" x-intersect.once="$el.classList.add('revealed')">
        <div class="flex items-start sm:items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm mt-0.5 sm:mt-0">
                <i class="fas fa-triangle-exclamation text-base"></i>
            </div>
            <div>
                <h4 class="text-xs sm:text-sm font-black text-amber-900 dark:text-amber-200 uppercase tracking-wider">Lengkapi Program Studi Anda</h4>
                <p class="text-amber-800/80 dark:text-amber-300/80 text-xs mt-0.5 font-medium leading-relaxed">
                    Anda belum memilih Jurusan / Program Studi di profil. Lengkapi profil agar katalog lowongan magang otomatis disaring sesuai bidang Anda.
                </p>
            </div>
        </div>
        <a href="{{ route('profile.edit') }}" class="shrink-0 inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition shadow-xs">
            <span>Lengkapi Profil</span>
            <i class="fas fa-arrow-right text-[11px]"></i>
        </a>
    </div>
@endif

<!-- Active Filter Chips Bar (Touch-Swipeable on Mobile) -->
@php
    $activeInstansi = request('instansi_id') ? $instansis->firstWhere('id', request('instansi_id')) : null;
    $activeCategory = request('major_category_id') && isset($majorCategories) ? $majorCategories->firstWhere('id', request('major_category_id')) : null;
@endphp

@if(request()->anyFilled(['search', 'instansi_id', 'major_category_id', 'jurusan', 'sort']) || (request('filter_jurusan') === 'all' && ($userHasMajor ?? false)))
    <div class="reveal mb-5 p-3.5 sm:p-4 rounded-2xl bg-slate-50 dark:bg-gray-800/60 border border-slate-200/70 dark:border-gray-700/60 flex items-center gap-2 text-xs overflow-x-auto no-scrollbar scroll-smooth flex-nowrap w-full -mx-4 px-4 sm:mx-0 sm:px-4" style="--reveal-delay: 50ms" x-intersect.once="$el.classList.add('revealed')">
        <span class="font-extrabold text-slate-500 dark:text-gray-400 uppercase tracking-wider text-[10px] flex items-center gap-1.5 shrink-0 mr-1">
            <i class="fas fa-filter text-teal-600 dark:text-teal-400"></i> Filter Aktif:
        </span>

        @if(request('search'))
            @php
                $querySearch = request()->except('search');
            @endphp
            <a href="{{ route('home', $querySearch) }}#lowongan" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-slate-200 font-bold hover:border-rose-400 hover:text-rose-600 transition shadow-2xs group">
                <span>Kata Kunci: <em>"{{ request('search') }}"</em></span>
                <i class="fas fa-times text-[10px] text-slate-400 group-hover:text-rose-500 transition"></i>
            </a>
        @endif

        @if($activeInstansi)
            @php
                $queryInstansi = request()->except('instansi_id');
            @endphp
            <a href="{{ route('home', $queryInstansi) }}#lowongan" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-slate-200 font-bold hover:border-rose-400 hover:text-rose-600 transition shadow-2xs group">
                <i class="fas fa-building text-teal-600 dark:text-teal-400 text-xs"></i>
                <span>{{ Str::limit($activeInstansi->nama_dinas, 24) }}</span>
                <i class="fas fa-times text-[10px] text-slate-400 group-hover:text-rose-500 transition"></i>
            </a>
        @endif

        @if($activeCategory)
            @php
                $queryCategory = request()->except('major_category_id');
            @endphp
            <a href="{{ route('home', $queryCategory) }}#lowongan" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-slate-200 font-bold hover:border-rose-400 hover:text-rose-600 transition shadow-2xs group">
                <i class="fas fa-layer-group text-teal-600 dark:text-teal-400 text-xs"></i>
                <span>{{ $activeCategory->name }}</span>
                <i class="fas fa-times text-[10px] text-slate-400 group-hover:text-rose-500 transition"></i>
            </a>
        @endif

        @if(request('jurusan'))
            @php
                $queryJurusan = request()->except('jurusan');
            @endphp
            <a href="{{ route('home', $queryJurusan) }}#lowongan" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-slate-200 font-bold hover:border-rose-400 hover:text-rose-600 transition shadow-2xs group">
                <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400 text-xs"></i>
                <span>Jurusan: "{{ request('jurusan') }}"</span>
                <i class="fas fa-times text-[10px] text-slate-400 group-hover:text-rose-500 transition"></i>
            </a>
        @endif

        @if(request('sort') && request('sort') !== 'latest')
            @php
                $querySort = request()->except('sort');
                $sortLabel = request('sort') === 'deadline_asc' ? 'Batas Waktu' : 'Kuota';
            @endphp
            <a href="{{ route('home', $querySort) }}#lowongan" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-slate-200 font-bold hover:border-rose-400 hover:text-rose-600 transition shadow-2xs group">
                <i class="fas fa-arrow-down-short-wide text-teal-600 dark:text-teal-400 text-xs"></i>
                <span>Urut: {{ $sortLabel }}</span>
                <i class="fas fa-times text-[10px] text-slate-400 group-hover:text-rose-500 transition"></i>
            </a>
        @endif

        @if(request('filter_jurusan') === 'all' && ($userHasMajor ?? false))
            @php
                $queryNoFilterJurusan = request()->except('filter_jurusan');
            @endphp
            <a href="{{ route('home', $queryNoFilterJurusan) }}#lowongan" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-slate-200 font-bold hover:border-teal-400 hover:text-teal-600 transition shadow-2xs group">
                <i class="fas fa-globe text-teal-600 dark:text-teal-400 text-xs"></i>
                <span>Mode: Semua Lowongan</span>
                <i class="fas fa-times text-[10px] text-slate-400 group-hover:text-rose-500 transition"></i>
            </a>
        @endif

        <a href="{{ route('home') }}#lowongan" class="shrink-0 text-rose-600 dark:text-rose-400 hover:underline font-bold text-xs ml-auto pl-2">
            Reset
        </a>
    </div>
@endif

<!-- 1. MOBILE COMPACT FILTER BAR (Screen < sm / Mobile Phones) -->
<div class="sm:hidden mb-5 space-y-3">
    <!-- Live Search Input Box -->
    <div class="relative w-full">
        <input type="text" 
               name="search" 
               x-model="filterState.search" 
               @input.debounce.400ms="applyFilter()" 
               placeholder="Cari posisi atau dinas..." 
               class="w-full pl-11 pr-10 py-3.5 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-2xl text-sm font-bold text-slate-800 dark:text-gray-100 placeholder-slate-400 dark:placeholder-gray-500 shadow-2xs focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
            <i class="fas fa-search text-sm text-teal-600 dark:text-teal-400"></i>
        </div>
        <button type="button" x-show="filterState.search && filterState.search.length > 0" @click="filterState.search = ''; applyFilter()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
            <i class="fas fa-times-circle text-sm"></i>
        </button>
    </div>

    <!-- Filter Trigger Button & Compact Sort -->
    <div class="flex items-center gap-2.5">
        <!-- Bottom Sheet Filter Opener -->
        <button type="button" 
                @click="openMobileFilter()" 
                class="flex-1 py-3 px-4 rounded-2xl border text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 shadow-2xs active:scale-95 transition"
                :class="activeFilterCount() > 0 ? 'bg-teal-600 text-white border-teal-600 shadow-md shadow-teal-600/25' : 'bg-white dark:bg-gray-800 text-slate-700 dark:text-gray-200 border-slate-200 dark:border-gray-700'">
            <i class="fas fa-sliders text-xs" :class="activeFilterCount() > 0 ? 'text-white' : 'text-teal-600 dark:text-teal-400'"></i>
            <span>Filter Lengkap</span>
            <template x-if="activeFilterCount() > 0">
                <span class="px-2 py-0.5 rounded-full bg-white text-teal-700 text-[10px] font-black shadow-2xs" x-text="activeFilterCount()"></span>
            </template>
        </button>

        <!-- Compact Sorting Dropdown -->
        <div class="relative group shrink-0">
            <select name="sort" x-model="filterState.sort" @change="applyFilter()" class="pl-8 pr-7 py-3 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-2xl text-xs font-bold text-slate-700 dark:text-gray-200 appearance-none shadow-2xs cursor-pointer focus:ring-2 focus:ring-teal-500">
                <option value="latest">✨ Terbaru</option>
                <option value="deadline_asc">⏰ Batas Waktu</option>
                <option value="quota_desc">💺 Kuota</option>
            </select>
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="fas fa-arrow-down-short-wide text-[11px] text-teal-600 dark:text-teal-400"></i>
            </div>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i class="fas fa-chevron-down text-[9px]"></i>
            </div>
        </div>
    </div>
</div>

<!-- 2. DESKTOP FILTER DOCK CARD (Screen >= sm / Tablets & Desktops) -->
<div class="reveal hidden sm:block bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-[2.5rem] shadow-xs border border-slate-100 dark:border-gray-700 mb-8 w-full" style="--reveal-delay: 100ms" x-intersect.once="$el.classList.add('revealed')">
    <form action="{{ route('home') }}#lowongan" method="GET" id="filter-form" @submit.prevent="applyFilter()" class="w-full">
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}" x-model="filterState.search">
        @endif
        @if(request('filter_jurusan'))
            <input type="hidden" name="filter_jurusan" value="{{ request('filter_jurusan') }}" x-model="filterState.filter_jurusan">
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 lg:gap-5 items-end w-full">
            <!-- Select Instansi -->
            <div class="lg:col-span-4 w-full">
                <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase mb-2 ml-1.5 tracking-wider flex items-center gap-2">
                    <i class="fas fa-building text-teal-600 dark:text-teal-400"></i> Instansi / Dinas
                </label>
                <div class="relative w-full group">
                    <select name="instansi_id" x-model="filterState.instansi_id" @change="applyFilter()" class="w-full pl-4 pr-10 py-3.5 border border-slate-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm font-bold bg-slate-50/50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-900 transition duration-300 appearance-none cursor-pointer text-slate-800 dark:text-gray-100 shadow-xs [color-scheme:dark]">
                        <option value="" class="bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100">🏢 Semua Instansi</option>
                        @foreach($instansis as $instansi)
                            <option value="{{ $instansi->id }}" {{ request('instansi_id') == $instansi->id ? 'selected' : '' }} class="bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100">
                                {{ Str::limit($instansi->nama_dinas, 40) }}
                            </option>
                        @endforeach
                    </select>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400 dark:text-gray-500 transition-colors group-hover:text-teal-600 dark:group-hover:text-teal-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </span>
                </div>
            </div>

            <!-- Select Rumpun Keilmuan -->
            <div class="lg:col-span-3 w-full">
                <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase mb-2 ml-1.5 tracking-wider flex items-center gap-2">
                    <i class="fas fa-layer-group text-teal-600 dark:text-teal-400"></i> Rumpun Keilmuan
                </label>
                <div class="relative w-full group">
                    <select name="major_category_id" x-model="filterState.major_category_id" @change="applyFilter()" class="w-full pl-4 pr-10 py-3.5 border border-slate-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm font-bold bg-slate-50/50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-900 transition duration-300 appearance-none cursor-pointer text-slate-800 dark:text-gray-100 shadow-xs [color-scheme:dark]">
                        <option value="" class="bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100">🌐 Semua Rumpun</option>
                        @if(isset($majorCategories))
                            @foreach($majorCategories as $cat)
                                <option value="{{ $cat->id }}" {{ request('major_category_id') == $cat->id ? 'selected' : '' }} class="bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100">
                                    {{ $cat->name }} ({{ $cat->code }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400 dark:text-gray-500 transition-colors group-hover:text-teal-600 dark:group-hover:text-teal-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </span>
                </div>
            </div>

            <!-- Input Jurusan / Keyword -->
            <div class="lg:col-span-3 w-full">
                <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase mb-2 ml-1.5 tracking-wider flex items-center gap-2">
                    <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400"></i> Cari Jurusan / Posisi
                </label>
                <div class="relative w-full">
                    <input type="text" name="jurusan" id="jurusan-input" x-model="filterState.jurusan" @input.debounce.400ms="applyFilter()" value="{{ request('jurusan') }}" placeholder="Contoh: Informatika, Akuntansi..." 
                        class="w-full px-4 py-3.5 border border-slate-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm font-bold bg-slate-50/50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-900 transition duration-300 text-slate-800 dark:text-gray-100 placeholder-slate-400 dark:placeholder-gray-500 shadow-xs">
                </div>
            </div>

            <!-- Urutkan (Sorting) -->
            <div class="lg:col-span-2 w-full flex flex-col gap-2">
                <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase mb-2 ml-1.5 tracking-wider flex items-center gap-2">
                    <i class="fas fa-sort text-teal-600 dark:text-teal-400"></i> Urutan
                </label>
                <div class="relative w-full group">
                    <select name="sort" x-model="filterState.sort" @change="applyFilter()" class="w-full pl-3 pr-8 py-3.5 border border-slate-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-xs font-bold bg-slate-50/50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-900 transition duration-300 appearance-none cursor-pointer text-slate-800 dark:text-gray-100 shadow-xs [color-scheme:dark]">
                        <option value="latest" {{ request('sort') == 'latest' || !request('sort') ? 'selected' : '' }}>✨ Terbaru</option>
                        <option value="deadline_asc" {{ request('sort') == 'deadline_asc' ? 'selected' : '' }}>⏰ Batas Terdekat</option>
                        <option value="quota_desc" {{ request('sort') == 'quota_desc' ? 'selected' : '' }}>💺 Kuota Terbanyak</option>
                    </select>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400 dark:text-gray-500 transition-colors group-hover:text-teal-600 dark:group-hover:text-teal-400">
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </span>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- 3. MOBILE FILTER BOTTOM SHEET MODAL (Screen < sm) -->
<template x-teleport="body">
    <div x-show="mobileFilterOpen" 
         x-cloak
         @keydown.escape.window="closeMobileFilter()"
         class="fixed inset-0 z-[9998] sm:hidden overflow-hidden" 
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop Overlay -->
        <div x-show="mobileFilterOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             @click="closeMobileFilter()"
             class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm transition-opacity"></div>

        <!-- Slide-Up Bottom Sheet Panel -->
        <div class="fixed inset-x-0 bottom-0 max-h-[88vh] bg-white dark:bg-gray-900 border-t border-slate-200/80 dark:border-gray-800 shadow-2xl rounded-t-[2.5rem] flex flex-col z-10 overflow-hidden"
             x-show="mobileFilterOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             @click.stop>
            
            <!-- Grab Handle & Header -->
            <div class="px-6 pt-3 pb-4 border-b border-slate-100 dark:border-gray-800 text-center shrink-0">
                <div class="w-12 h-1.5 bg-slate-300 dark:bg-gray-700 rounded-full mx-auto mb-3"></div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-teal-950/80 text-teal-600 dark:text-teal-400 flex items-center justify-center shadow-2xs">
                            <i class="fas fa-sliders text-xs"></i>
                        </div>
                        <h3 class="text-base font-black text-slate-800 dark:text-white font-display">Filter Lowongan</h3>
                    </div>
                    <button @click="closeMobileFilter()" type="button" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-gray-400 flex items-center justify-center active:scale-95 transition">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- Bottom Sheet Body Form -->
            <div class="p-6 overflow-y-auto space-y-4 flex-grow overscroll-contain">
                @if(($isPeserta ?? false) && ($userHasMajor ?? false))
                    <!-- Filter Sesuai Jurusan Toggle Mobile -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                            🎯 Cakupan Rekomendasi
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" 
                                    @click="tempFilter.filter_jurusan = 'my_major'" 
                                    class="py-2.5 px-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-1.5"
                                    :class="tempFilter.filter_jurusan === 'my_major' ? 'bg-teal-600 text-white border-teal-600 shadow-xs' : 'bg-slate-50 dark:bg-gray-800 text-slate-700 dark:text-gray-300 border-slate-200 dark:border-gray-700'">
                                <i class="fas fa-bullseye text-[11px]"></i>
                                <span>Sesuai Jurusan</span>
                            </button>
                            <button type="button" 
                                    @click="tempFilter.filter_jurusan = 'all'" 
                                    class="py-2.5 px-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-1.5"
                                    :class="tempFilter.filter_jurusan === 'all' ? 'bg-slate-800 text-white border-slate-800 dark:bg-slate-700 dark:border-slate-700 shadow-xs' : 'bg-slate-50 dark:bg-gray-800 text-slate-700 dark:text-gray-300 border-slate-200 dark:border-gray-700'">
                                <i class="fas fa-globe text-[11px]"></i>
                                <span>Semua Posisi</span>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Select Instansi -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        🏢 Instansi / Dinas
                    </label>
                    <select x-model="tempFilter.instansi_id" class="w-full px-4 py-3.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-2xl text-xs font-bold text-slate-800 dark:text-gray-100 focus:ring-2 focus:ring-teal-500 [color-scheme:dark]">
                        <option value="">🏢 Semua Instansi</option>
                        @foreach($instansis as $instansi)
                            <option value="{{ $instansi->id }}">{{ $instansi->nama_dinas }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Select Rumpun Keilmuan -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        🌐 Rumpun Keilmuan
                    </label>
                    <select x-model="tempFilter.major_category_id" class="w-full px-4 py-3.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-2xl text-xs font-bold text-slate-800 dark:text-gray-100 focus:ring-2 focus:ring-teal-500 [color-scheme:dark]">
                        <option value="">🌐 Semua Rumpun Keilmuan</option>
                        @if(isset($majorCategories))
                            @foreach($majorCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Input Jurusan Mobile -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        🎓 Jurusan / Program Studi
                    </label>
                    <input type="text" x-model="tempFilter.jurusan" placeholder="Contoh: Teknik Komputer..." class="w-full px-4 py-3.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-2xl text-xs font-bold text-slate-800 dark:text-gray-100 placeholder-slate-400 focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Select Urutan Mobile -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        🔄 Urutkan Hasil
                    </label>
                    <select x-model="tempFilter.sort" class="w-full px-4 py-3.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-2xl text-xs font-bold text-slate-800 dark:text-gray-100 focus:ring-2 focus:ring-teal-500 [color-scheme:dark]">
                        <option value="latest">✨ Terbaru</option>
                        <option value="deadline_asc">⏰ Batas Terdekat</option>
                        <option value="quota_desc">💺 Kuota Terbanyak</option>
                    </select>
                </div>
            </div>

            <!-- Bottom Sheet Action Footer -->
            <div class="p-4 sm:p-5 border-t border-slate-100 dark:border-gray-800 bg-slate-50 dark:bg-gray-900 flex items-center gap-3 shrink-0">
                <button type="button" @click="resetMobileFilter()" class="w-1/3 py-3.5 px-4 bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 font-bold text-xs rounded-2xl border border-slate-200 dark:border-gray-700 transition active:scale-95 text-center">
                    Reset
                </button>
                <button type="button" @click="applyMobileFilter()" class="w-2/3 py-3.5 px-4 bg-teal-600 hover:bg-teal-700 text-white font-black text-xs rounded-2xl shadow-lg shadow-teal-600/30 transition active:scale-95 text-center uppercase tracking-wider">
                    Terapkan Filter
                </button>
            </div>
        </div>
    </div>
</template>

<!-- Quick Filter Scrollable Pills (Touch-Swipeable Ribbon on Mobile) -->
<div class="reveal flex items-center gap-2.5 sm:gap-3.5 overflow-x-auto pb-3 mb-8 no-scrollbar scroll-smooth snap-x snap-mandatory w-full max-w-full -mx-4 px-4 sm:mx-0 sm:px-0" style="--reveal-delay: 200ms" x-intersect.once="$el.classList.add('revealed')">
    <span class="text-[11px] sm:text-xs font-black text-slate-400 dark:text-gray-400 uppercase tracking-wider shrink-0 mr-1 flex items-center">
        <i class="fas fa-bolt text-amber-500 mr-2"></i> Filter Cepat:
    </span>
    <button type="button" @click="setQuickJurusan('')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ !request('jurusan') && !request('instansi_id') && !request('major_category_id') && !request('search') ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
        ✨ Semua Posisi
    </button>
    <button type="button" @click="setQuickJurusan('Informatika')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ stripos(request('jurusan'), 'Informatika') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
        💻 Informatika & Komputer
    </button>
    <button type="button" @click="setQuickJurusan('Akuntansi')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ stripos(request('jurusan'), 'Akuntansi') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
        📊 Akuntansi & Keuangan
    </button>
    <button type="button" @click="setQuickJurusan('Administrasi')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ stripos(request('jurusan'), 'Administrasi') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
        🏛️ Administrasi & Perkantoran
    </button>
    <button type="button" @click="setQuickJurusan('Desain')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ stripos(request('jurusan'), 'Desain') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
        🎨 Desain & Multimedia
    </button>
    <button type="button" @click="setQuickJurusan('Hukum')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ stripos(request('jurusan'), 'Hukum') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
        ⚖️ Hukum & Legal
    </button>
    <button type="button" @click="setQuickJurusan('Kesehatan')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ stripos(request('jurusan'), 'Kesehatan') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
        🏥 Medis & Kesehatan
    </button>
    <button type="button" @click="setQuickJurusan('SMK')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ stripos(request('jurusan'), 'SMK') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
        🏫 Khusus SMK
    </button>
    <button type="button" @click="setQuickJurusan('S1')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ stripos(request('jurusan'), 'S1') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
        🎓 Mahasiswa (S1/D3)
    </button>
</div>
