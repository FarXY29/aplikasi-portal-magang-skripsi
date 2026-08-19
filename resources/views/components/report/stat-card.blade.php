@props([
    'title' => 'Statistik',
    'value' => '0',
    'icon' => 'fas fa-chart-bar',
    'color' => 'teal',
    'tooltip' => null,
    'featured' => false,
    'textMode' => false,
    'subtitle' => null,
])

@php
    $hoverMap = [
        'teal' => 'hover:border-teal-500/40 hover:shadow-teal-500/10',
        'blue' => 'hover:border-blue-500/40 hover:shadow-blue-500/10',
        'emerald' => 'hover:border-emerald-500/40 hover:shadow-emerald-500/10',
        'indigo' => 'hover:border-indigo-500/40 hover:shadow-indigo-500/10',
        'amber' => 'hover:border-amber-500/40 hover:shadow-amber-500/10',
        'orange' => 'hover:border-orange-500/40 hover:shadow-orange-500/10',
        'rose' => 'hover:border-rose-500/40 hover:shadow-rose-500/10',
        'purple' => 'hover:border-purple-500/40 hover:shadow-purple-500/10',
        'slate' => 'hover:border-slate-500/40 hover:shadow-slate-500/10',
    ];

    $iconMap = [
        'teal' => 'bg-teal-500 shadow-teal-500/30',
        'blue' => 'bg-blue-500 shadow-blue-500/30',
        'emerald' => 'bg-emerald-500 shadow-emerald-500/30',
        'indigo' => 'bg-indigo-500 shadow-indigo-500/30',
        'amber' => 'bg-amber-500 shadow-amber-500/30',
        'orange' => 'bg-orange-500 shadow-orange-500/30',
        'rose' => 'bg-rose-500 shadow-rose-500/30',
        'purple' => 'bg-purple-500 shadow-purple-500/30',
        'slate' => 'bg-slate-500 shadow-slate-500/30',
    ];

    $hover = $hoverMap[$color] ?? $hoverMap['teal'];
    $iconBg = $iconMap[$color] ?? $iconMap['teal'];

    $valueClass = $textMode
        ? 'text-xs md:text-sm font-extrabold leading-snug line-clamp-2'
        : 'text-xl md:text-2xl font-extrabold tracking-tight font-mono';
@endphp

@if($featured)
    {{-- Featured / highlight card --}}
    <div {{ $attributes->merge(['class' => 'group relative overflow-hidden rounded-2xl md:rounded-3xl bg-gradient-to-br from-teal-500 to-emerald-600 p-4 md:p-5 shadow-lg shadow-teal-500/25 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-teal-500/30 cursor-help']) }}
        @if($tooltip) title="{{ $tooltip }}" @endif>
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-[10px] md:text-xs font-black uppercase tracking-wider text-teal-50/90">{{ $title }}</p>
                <h3 class="mt-2 {{ $valueClass }} text-white" @if($textMode) title="{{ $value }}" @endif>{{ $value }}</h3>
                @if($subtitle)
                    <p class="mt-1 text-[10px] md:text-xs font-semibold text-teal-50/80">{{ $subtitle }}</p>
                @endif
            </div>
            <div class="flex h-10 w-10 md:h-11 md:w-11 shrink-0 items-center justify-center rounded-xl bg-white/15 border border-white/20 text-white shadow-md transition-transform duration-300 group-hover:scale-110">
                <i class="{{ $icon }} text-base"></i>
            </div>
        </div>
    </div>
@else
    {{-- Standard card --}}
    <div {{ $attributes->merge(['class' => "group relative overflow-hidden rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 bg-white dark:bg-[#161f33] p-4 md:p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl cursor-help {$hover}"]) }}
        @if($tooltip) title="{{ $tooltip }}" @endif>
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-[10px] md:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $title }}</p>
                <h3 class="mt-2 {{ $valueClass }} text-slate-900 dark:text-white" @if($textMode) title="{{ $value }}" @endif>{{ $value }}</h3>
                @if($subtitle)
                    <p class="mt-1 text-[10px] md:text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $subtitle }}</p>
                @endif
            </div>
            <div class="flex h-10 w-10 md:h-11 md:w-11 shrink-0 items-center justify-center rounded-xl text-white shadow-md transition-transform duration-300 group-hover:scale-110 {{ $iconBg }}">
                <i class="{{ $icon }} text-base"></i>
            </div>
        </div>
    </div>
@endif
