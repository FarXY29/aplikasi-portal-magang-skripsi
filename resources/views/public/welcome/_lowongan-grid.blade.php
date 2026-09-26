<!-- Lowongan Pekerjaan Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-grow w-full"
     x-data="lowonganGridManager()"
     x-init="initGrid()"
     id="lowongan-explorer-container">

    {{-- Global Announcement & Penempatan Otomatis Banners --}}
    @include('public.welcome.lowongan._announcement-banner')

    {{-- Filter Bar (Headers, Filter Chips, Mobile Sheet, Desktop Filters, Quick Tags) --}}
    @include('public.welcome.lowongan._filter-bar')

    <!-- Skeleton Loading State (Shown During Live Fetching) -->
    <div x-show="isLoading" x-cloak class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 w-full animate-pulse">
        @for($i = 0; $i < 6; $i++)
            <div class="bg-white dark:bg-gray-800 rounded-[2rem] border border-slate-200/80 dark:border-gray-700 p-6 flex flex-col h-[340px] justify-between shadow-xs">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <div class="h-5 w-20 bg-slate-200 dark:bg-gray-700 rounded-lg"></div>
                        <div class="h-5 w-24 bg-slate-200 dark:bg-gray-700 rounded-lg"></div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-slate-200 dark:bg-gray-700 shrink-0"></div>
                        <div class="space-y-2 flex-grow">
                            <div class="h-5 w-3/4 bg-slate-200 dark:bg-gray-700 rounded"></div>
                            <div class="h-3 w-1/2 bg-slate-200 dark:bg-gray-700 rounded"></div>
                        </div>
                    </div>
                    <div class="h-10 w-full bg-slate-100 dark:bg-gray-700/50 rounded-xl"></div>
                </div>
                <div class="h-11 w-full bg-slate-200 dark:bg-gray-700 rounded-2xl"></div>
            </div>
        @endfor
    </div>

    <!-- Vacancies Card Grid (Live Results) -->
    <div x-show="!isLoading" id="vacancies-cards-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 w-full">
        @forelse($lowongans as $loker)
            @include('public.welcome.lowongan._job-card', [
                'loker' => $loker,
                'isPeserta' => $isPeserta ?? false,
                'userHasMajor' => $userHasMajor ?? false,
                'userMajorLabel' => $userMajorLabel ?? '',
            ])
        @empty
            <!-- Enhanced Empty State Lowongan -->
            <div class="col-span-full py-16 sm:py-20 text-center bg-white dark:bg-gray-800/80 rounded-[2.5rem] border border-dashed border-slate-200 dark:border-gray-700 p-8 shadow-xs">
                <div class="w-20 h-20 bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400 rounded-3xl flex items-center justify-center mx-auto mb-4 border border-teal-100 dark:border-teal-900/50 shadow-inner">
                    <i class="fas fa-magnifying-glass text-3xl"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-black text-slate-800 dark:text-gray-100">Lowongan Belum Ditemukan</h3>
                <p class="text-slate-500 dark:text-gray-400 mt-2 max-w-md mx-auto text-xs sm:text-sm font-medium leading-relaxed">
                    @if(($isPeserta ?? false) && ($userHasMajor ?? false) && ($filterJurusanScope ?? '') === 'my_major')
                        Belum ada posisi magang aktif yang membuka kualifikasi khusus untuk jurusan <strong>{{ $userMajorLabel }}</strong>. Anda dapat melihat semua lowongan yang tersedia atau mencoba penempatan otomatis.
                    @else
                        Tidak ada posisi magang aktif yang sesuai dengan kombinasi filter atau kata kunci saat ini. Anda dapat mereset filter atau mencoba opsi alokasi cerdas.
                    @endif
                </p>

                <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
                    @if(($isPeserta ?? false) && ($userHasMajor ?? false) && ($filterJurusanScope ?? '') === 'my_major')
                        <button type="button" @click="setFilterJurusan('all')" class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-2xl text-xs font-bold transition shadow-md uppercase tracking-wider active:scale-95">
                            <i class="fas fa-globe text-xs"></i> Lihat Semua Lowongan
                        </button>
                    @endif
                    <button type="button" @click="resetAllFilters()" class="inline-flex items-center gap-2 bg-slate-900 dark:bg-teal-600 hover:bg-teal-600 dark:hover:bg-teal-500 text-white px-6 py-3 rounded-2xl text-xs font-bold transition shadow-md uppercase tracking-wider cursor-pointer">
                        <i class="fas fa-undo text-xs"></i> Reset Semua Filter
                    </button>
                    <a href="{{ route('peserta.apply_automatic.form') }}" class="inline-flex items-center gap-2 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 px-6 py-3 rounded-2xl text-xs font-bold transition uppercase tracking-wider">
                        <i class="fas fa-wand-magic-sparkles text-xs"></i> Coba Penempatan Otomatis
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination Container --}}
    <div id="lowongan-pagination-container">
        @include('public.welcome.lowongan._pagination')
    </div>

    {{-- Centralized Single Dynamic Detail Modal --}}
    @include('public.welcome.lowongan._detail-modal')

    {{-- Scripts --}}
    @include('public.welcome.lowongan._grid-scripts')
</div>
