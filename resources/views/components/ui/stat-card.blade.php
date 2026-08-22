@props([
    'title' => 'Statistik',
    'value' => '0',
    'icon' => 'fas fa-chart-bar',
    'color' => 'indigo',
    'subtitle' => null,
    'href' => null,
])

@php
    $colorMap = [
        'indigo' => [
            'bg' => 'bg-white',
            'border' => 'border-indigo-100/90 hover:border-indigo-300',
            'iconBg' => 'bg-indigo-600 text-white shadow-2xs',
            'text' => 'text-slate-900',
        ],
        'emerald' => [
            'bg' => 'bg-white',
            'border' => 'border-emerald-100/90 hover:border-emerald-300',
            'iconBg' => 'bg-emerald-600 text-white shadow-2xs',
            'text' => 'text-slate-900',
        ],
        'amber' => [
            'bg' => 'bg-white',
            'border' => 'border-amber-100/90 hover:border-amber-300',
            'iconBg' => 'bg-amber-500 text-white shadow-2xs',
            'text' => 'text-slate-900',
        ],
        'rose' => [
            'bg' => 'bg-white',
            'border' => 'border-rose-100/90 hover:border-rose-300',
            'iconBg' => 'bg-rose-600 text-white shadow-2xs',
            'text' => 'text-slate-900',
        ],
        'blue' => [
            'bg' => 'bg-white',
            'border' => 'border-blue-100/90 hover:border-blue-300',
            'iconBg' => 'bg-blue-600 text-white shadow-2xs',
            'text' => 'text-slate-900',
        ],
        'teal' => [
            'bg' => 'bg-white',
            'border' => 'border-teal-100/90 hover:border-teal-300',
            'iconBg' => 'bg-teal-700 text-white shadow-2xs',
            'text' => 'text-slate-900',
        ],
        'purple' => [
            'bg' => 'bg-white',
            'border' => 'border-purple-100/90 hover:border-purple-300',
            'iconBg' => 'bg-purple-600 text-white shadow-2xs',
            'text' => 'text-slate-900',
        ],
        'green' => [
            'bg' => 'bg-white',
            'border' => 'border-green-100/90 hover:border-green-300',
            'iconBg' => 'bg-green-600 text-white shadow-2xs',
            'text' => 'text-slate-900',
        ],
    ];

    $style = $colorMap[$color] ?? $colorMap['indigo'];
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if($href) href="{{ $href }}" @endif {{ $attributes->merge(['class' => "group block relative overflow-hidden rounded-2xl border p-4 xl:p-6 shadow-2xs transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xs {$style['bg']} {$style['border']} dark:bg-gray-800 dark:border-gray-700 dark:hover:border-gray-600"]) }}>
    <div class="flex items-start justify-between gap-2 xl:gap-4">
        <div class="min-w-0">
            <p class="text-[10px] xl:text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-gray-400 truncate">{{ $title }}</p>
            <h3 class="mt-2 text-xl xl:text-3xl font-extrabold tracking-tight {{ $style['text'] }} dark:text-gray-100">{{ $value }}</h3>
            @if($subtitle)
                <p class="mt-1 text-xs font-medium text-slate-500 dark:text-gray-400 flex items-center gap-1">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
        <div class="flex h-10 w-10 xl:h-12 xl:w-12 flex-shrink-0 items-center justify-center rounded-xl shadow-2xs dark:shadow-none transition-transform duration-300 group-hover:scale-105 {{ $style['iconBg'] }}">
            <i class="{{ $icon }} text-base xl:text-lg"></i>
        </div>
    </div>
</{{ $tag }}>
