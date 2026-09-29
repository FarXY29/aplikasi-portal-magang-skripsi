@props([
    'action' => null,
    'method' => 'GET',
    'resetUrl' => null,
    // Richer pembimbing props:
    'routeName' => null,
    'title' => 'Filter',
    'subtitle' => '',
    'searchLabel' => 'Cari:',
    'searchPlaceholder' => 'Cari...',
    'statusField' => 'status',
    'statusOptions' => [],
    'statusBadgeLabel' => 'Status',
    'isFiltered' => null,
    'app' => null,
    'applications' => [],
    'filterType' => 'semua',
    'selectedDate' => null,
])

@php
    $isApplicationModel = $app instanceof \App\Models\Application;
    $formAction = $action ?? ($routeName ? ($isApplicationModel ? route($routeName, $app->id) : route($routeName)) : '#');
    $hasRichFeatures = !empty($routeName) || !empty($statusOptions) || $isApplicationModel;
@endphp

@if(!$hasRichFeatures)
    {{-- Standard simple filter-bar with slot --}}
    <form action="{{ $formAction }}" method="{{ $method }}" {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm mb-6 transition-all hover:shadow-md']) }}>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3 flex-grow">
                <div class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest mr-2 border-r border-gray-200 dark:border-gray-700 pr-4">
                    <i class="fas fa-filter text-teal-600"></i>
                    <span>Filter</span>
                </div>
                {{ $slot }}
            </div>
            <div class="flex items-center gap-2 flex-shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100 dark:border-gray-700 justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition shadow-sm hover:shadow active:scale-95">
                    <i class="fas fa-search"></i>
                    <span>Terapkan</span>
                </button>
                @if($resetUrl)
                    <a href="{{ $resetUrl }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 text-gray-600 dark:text-gray-400 text-xs font-bold transition">
                        <i class="fas fa-undo"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </div>
    </form>
@else
    {{-- Richer pembimbing filter-bar --}}
    @php
        $today = \Carbon\Carbon::today()->toDateString();
        $selectedDate = $selectedDate ?: $today;
        $isFiltered ??= request()->hasAny(['search', $statusField])
            || (request('filter_type') && request('filter_type') !== 'semua')
            || (request('date') && request('date') !== $today);
        $resetUrl = $resetUrl ?? ($routeName && $isApplicationModel ? route($routeName, $app->id) : null);
    @endphp

    <form action="{{ $formAction }}" method="{{ $method }}"
          x-data="{
              filterType: @js($filterType),
              selectedDate: @js($selectedDate),
              monthValue: @js(\Carbon\Carbon::parse($selectedDate)->format('Y-m')),
              get isMonthly() { return this.filterType === 'bulanan'; },
              get isAll() { return this.filterType === 'semua'; }
          }"
          {{ $attributes->merge(['class' => 'space-y-4']) }}>

        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 border-b border-gray-100 dark:border-gray-700 pb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-800/60 flex items-center justify-center text-teal-600 dark:text-teal-400">
                    <i class="fas fa-filter text-xs"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $title }}</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
                </div>
            </div>

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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <div class="flex flex-col gap-1 lg:col-span-1">
                <label for="search" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                    <i class="fas fa-search text-teal-600 dark:text-teal-400"></i> {{ $searchLabel }}
                </label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="{{ $searchPlaceholder }}" class="w-full text-xs font-semibold rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
            </div>

            <div class="flex flex-col gap-1 lg:col-span-1">
                <label for="{{ $statusField }}" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                    <i class="fas fa-tag text-teal-600 dark:text-teal-400"></i>
                    <span>{{ $statusField === 'status_validasi' ? 'Status Validasi:' : 'Status Kehadiran:' }}</span>
                </label>
                <select id="{{ $statusField }}" name="{{ $statusField }}" class="w-full text-xs font-semibold rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                    <option value="">{{ $statusField === 'status_validasi' ? 'Semua Validasi' : 'Semua Status' }}</option>
                    @foreach($statusOptions as $option)
                        <option value="{{ $option['value'] }}" {{ request($statusField) == $option['value'] ? 'selected' : '' }}>
                            {{ $option['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-1 lg:col-span-1">
                <label for="date" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                    <i class="far fa-calendar-alt text-teal-600 dark:text-teal-400"></i>
                    <span x-text="isMonthly ? 'Periode Bulan:' : (isAll ? 'Tanggal (Dinonaktifkan):' : 'Pilih Tanggal:')"></span>
                </label>
                <div class="relative">
                    <input type="hidden" name="date" id="date" :value="isMonthly ? monthValue + '-01' : selectedDate" :disabled="isAll">

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

        @if(isset($slot) && $slot->isNotEmpty())
            <div class="flex flex-wrap items-center gap-3">
                {{ $slot }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pt-3 border-t border-gray-100 dark:border-gray-700">
            <div class="flex flex-wrap items-center gap-2">
                @if($isFiltered || (isset($applications) && count($applications) > 1))
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400">Filter Aktif:</span>
                    @if($isApplicationModel && $app->user)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60">
                            <i class="fas fa-user-graduate text-[10px]"></i> {{ $app->user->name }}
                        </span>
                    @endif

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

                    @php
                        $activeStatusLabel = collect($statusOptions)->firstWhere('value', request($statusField))['label'] ?? null;
                    @endphp
                    @if($activeStatusLabel)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60">
                            <i class="fas fa-tag text-[10px]"></i> {{ $statusBadgeLabel }}: {{ $activeStatusLabel }}
                            <a href="{{ request()->fullUrlWithQuery([$statusField => null]) }}" class="hover:text-rose-500 transition ml-1" title="Hapus filter status"><i class="fas fa-times"></i></a>
                        </span>
                    @endif
                @endif
            </div>

            <div class="flex items-center justify-end gap-2 shrink-0">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition shadow-xs hover:shadow active:scale-95">
                    <i class="fas fa-search"></i>
                    <span>Terapkan Filter</span>
                </button>

                @if($isFiltered && $resetUrl)
                    <a href="{{ $resetUrl }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-bold transition">
                        <i class="fas fa-undo"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </div>
    </form>
@endif
