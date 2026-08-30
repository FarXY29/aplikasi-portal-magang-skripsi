@props([
    'title',
    'subtitle' => null,
    'icon' => null,
    'breadcrumbs' => [],
])

<div {{ $attributes->merge(['class' => 'mb-6']) }}>
    @if(count($breadcrumbs) > 0)
        <nav class="flex text-xs font-semibold text-slate-500 dark:text-gray-400 mb-2.5" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 flex-wrap">
                <li>
                    <a href="{{ route('dashboard') }}" class="hover:text-teal-600 dark:hover:text-teal-400 transition-colors flex items-center gap-1.5">
                        <i class="fas fa-home text-xs"></i>
                        <span>Beranda</span>
                    </a>
                </li>
                @foreach($breadcrumbs as $index => $breadcrumb)
                    <li class="flex items-center">
                        <i class="fas fa-chevron-right text-[9px] mx-2 text-slate-300 dark:text-gray-600"></i>
                        @if(isset($breadcrumb['url']))
                            <a href="{{ $breadcrumb['url'] }}" class="hover:text-teal-600 dark:hover:text-teal-400 transition-colors">
                                {{ $breadcrumb['label'] }}
                            </a>
                        @else
                            <span class="text-slate-800 dark:text-gray-200 font-bold">{{ $breadcrumb['label'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Left: Icon + Title + Subtitle + Badge -->
        <div class="flex items-start sm:items-center gap-3.5 min-w-0">
            @if($icon)
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/60 border border-teal-200/70 dark:border-teal-800/60 flex items-center justify-center text-teal-600 dark:text-teal-400 shadow-2xs flex-shrink-0 text-lg sm:text-xl">
                    <i class="{{ $icon }}"></i>
                </div>
            @endif
            <div class="min-w-0">
                <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-gray-100 tracking-tight leading-tight">
                        {{ $title }}
                    </h1>
                    @if(isset($badge))
                        <div class="inline-flex items-center">
                            {{ $badge }}
                        </div>
                    @endif
                </div>
                @if($subtitle)
                    <p class="text-xs sm:text-sm font-medium text-slate-500 dark:text-gray-400 mt-0.5 sm:mt-1">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Right: Actions Slot -->
        @if(isset($actions))
            <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap flex-shrink-0">
                {{ $actions }}
            </div>
        @endif
    </div>

    @if(isset($slot) && !empty(trim($slot)))
        <div class="mt-4">
            {{ $slot }}
        </div>
    @endif
</div>
