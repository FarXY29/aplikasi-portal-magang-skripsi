@props([
    'icon' => 'fas fa-chart-bar',
    'title' => '',
    'countLabel' => null,
    'count' => null,
    'countSuffix' => '',
])

<div class="flex flex-col md:flex-row md:items-center justify-between gap-2 min-w-0">
    <div class="flex items-center gap-3 min-w-0">
        <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-teal-500 text-white flex items-center justify-center shadow-md shadow-teal-500/25 shrink-0">
            <i class="{{ $icon }} text-sm md:text-base"></i>
        </div>
        <div class="min-w-0">
            <h2 class="font-black text-base md:text-xl text-slate-900 dark:text-white leading-tight truncate">
                {{ $title }}
            </h2>
        </div>
    </div>

    @if($countLabel !== null)
        <div class="hidden md:inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 dark:text-slate-300 bg-white dark:bg-[#161f33] px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800/60 shadow-sm shrink-0">
            {{ $countLabel }}
            <span class="font-black text-teal-600 dark:text-teal-400 font-mono">{{ $count }}</span>
            {{ $countSuffix }}
        </div>
    @endif
</div>
