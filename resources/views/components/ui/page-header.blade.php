@props([
    'title',
    'subtitle' => null,
    'icon' => null,
    'iconColor' => 'teal',
    'breadcrumbs' => [],
])

@php
    // Warna ikon header. Default teal agar tetap konsisten dengan tema portal.
    // Setiap varian menyediakan kelas untuk mode terang & gelap.
    $iconVariants = [
        'teal'   => 'bg-teal-50 dark:bg-teal-950/60 border-teal-200/70 dark:border-teal-800/60 text-teal-600 dark:text-teal-400',
        'blue'   => 'bg-blue-50 dark:bg-blue-950/60 border-blue-200/70 dark:border-blue-800/60 text-blue-600 dark:text-blue-400',
        'purple' => 'bg-purple-50 dark:bg-purple-950/60 border-purple-200/70 dark:border-purple-800/60 text-purple-600 dark:text-purple-400',
        'amber'  => 'bg-amber-50 dark:bg-amber-950/60 border-amber-200/70 dark:border-amber-800/60 text-amber-600 dark:text-amber-400',
        'orange' => 'bg-orange-50 dark:bg-orange-950/60 border-orange-200/70 dark:border-orange-800/60 text-orange-600 dark:text-orange-400',
        'emerald'=> 'bg-emerald-50 dark:bg-emerald-950/60 border-emerald-200/70 dark:border-emerald-800/60 text-emerald-600 dark:text-emerald-400',
        'indigo' => 'bg-indigo-50 dark:bg-indigo-950/60 border-indigo-200/70 dark:border-indigo-800/60 text-indigo-600 dark:text-indigo-400',
        'rose'   => 'bg-rose-50 dark:bg-rose-950/60 border-rose-200/70 dark:border-rose-800/60 text-rose-600 dark:text-rose-400',
        'slate'  => 'bg-slate-100 dark:bg-slate-800/60 border-slate-200/70 dark:border-slate-700/60 text-slate-600 dark:text-slate-300',
    ];
    $iconClass = $iconVariants[$iconColor] ?? $iconVariants['teal'];
@endphp

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
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl border flex items-center justify-center shadow-2xs flex-shrink-0 text-lg sm:text-xl {{ $iconClass }}">
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
