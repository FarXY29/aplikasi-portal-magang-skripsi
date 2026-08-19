@props([
    'icon' => 'fas fa-chart-pie',
    'iconColor' => 'teal',
    'title' => '',
    'subtitle' => null,
    'canvasId' => '',
    'height' => 'h-64 md:h-72',
])

@php
    $tileMap = [
        'teal' => 'bg-teal-100 dark:bg-teal-500/15 text-teal-600 dark:text-teal-400 border-teal-200 dark:border-teal-500/25',
        'blue' => 'bg-blue-100 dark:bg-blue-500/15 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-500/25',
        'emerald' => 'bg-emerald-100 dark:bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/25',
        'indigo' => 'bg-indigo-100 dark:bg-indigo-500/15 text-indigo-600 dark:text-indigo-400 border-indigo-200 dark:border-indigo-500/25',
        'amber' => 'bg-amber-100 dark:bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-500/25',
        'orange' => 'bg-orange-100 dark:bg-orange-500/15 text-orange-600 dark:text-orange-400 border-orange-200 dark:border-orange-500/25',
        'rose' => 'bg-rose-100 dark:bg-rose-500/15 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-500/25',
        'purple' => 'bg-purple-100 dark:bg-purple-500/15 text-purple-600 dark:text-purple-400 border-purple-200 dark:border-purple-500/25',
    ];

    $tile = $tileMap[$iconColor] ?? $tileMap['teal'];
@endphp

<div class="bg-white dark:bg-[#161f33] rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 shadow-lg p-5 md:p-6 flex flex-col print:hidden">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl border flex items-center justify-center shrink-0 {{ $tile }}">
            <i class="{{ $icon }} text-base"></i>
        </div>
        <div class="min-w-0">
            <h3 class="text-sm md:text-base font-black text-slate-900 dark:text-white truncate">{{ $title }}</h3>
            @if($subtitle)
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    <div class="relative {{ $height }} mt-5">
        <canvas id="{{ $canvasId }}"></canvas>
    </div>
</div>
