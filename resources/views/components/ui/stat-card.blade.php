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
            'bg' => 'bg-gradient-to-br from-indigo-500/10 via-indigo-50/40 to-white',
            'border' => 'border-indigo-100/80 hover:border-indigo-300',
            'iconBg' => 'bg-indigo-500 text-white shadow-indigo-200',
            'text' => 'text-indigo-950',
        ],
        'emerald' => [
            'bg' => 'bg-gradient-to-br from-emerald-500/10 via-emerald-50/40 to-white',
            'border' => 'border-emerald-100/80 hover:border-emerald-300',
            'iconBg' => 'bg-emerald-500 text-white shadow-emerald-200',
            'text' => 'text-emerald-950',
        ],
        'amber' => [
            'bg' => 'bg-gradient-to-br from-amber-500/10 via-amber-50/40 to-white',
            'border' => 'border-amber-100/80 hover:border-amber-300',
            'iconBg' => 'bg-amber-500 text-white shadow-amber-200',
            'text' => 'text-amber-950',
        ],
        'rose' => [
            'bg' => 'bg-gradient-to-br from-rose-500/10 via-rose-50/40 to-white',
            'border' => 'border-rose-100/80 hover:border-rose-300',
            'iconBg' => 'bg-rose-500 text-white shadow-rose-200',
            'text' => 'text-rose-950',
        ],
        'blue' => [
            'bg' => 'bg-gradient-to-br from-blue-500/10 via-blue-50/40 to-white',
            'border' => 'border-blue-100/80 hover:border-blue-300',
            'iconBg' => 'bg-blue-500 text-white shadow-blue-200',
            'text' => 'text-blue-950',
        ],
        'teal' => [
            'bg' => 'bg-gradient-to-br from-teal-500/10 via-teal-50/40 to-white',
            'border' => 'border-teal-100/80 hover:border-teal-300',
            'iconBg' => 'bg-teal-500 text-white shadow-teal-200',
            'text' => 'text-teal-950',
        ],
        'purple' => [
            'bg' => 'bg-gradient-to-br from-purple-500/10 via-purple-50/40 to-white',
            'border' => 'border-purple-100/80 hover:border-purple-300',
            'iconBg' => 'bg-purple-500 text-white shadow-purple-200',
            'text' => 'text-purple-950',
        ],
        'green' => [
            'bg' => 'bg-gradient-to-br from-green-500/10 via-green-50/40 to-white',
            'border' => 'border-green-100/80 hover:border-green-300',
            'iconBg' => 'bg-green-500 text-white shadow-green-200',
            'text' => 'text-green-950',
        ],
    ];

    $style = $colorMap[$color] ?? $colorMap['indigo'];
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if($href) href="{{ $href }}" @endif {{ $attributes->merge(['class' => "group block relative overflow-hidden rounded-xl border p-4 xl:p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md {$style['bg']} {$style['border']} dark:bg-gray-800 dark:bg-none dark:border-gray-700 dark:hover:border-gray-600"]) }}>
    <div class="flex items-start justify-between gap-2 xl:gap-4">
        <div class="min-w-0">
            <p class="text-[10px] xl:text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 truncate">{{ $title }}</p>
            <h3 class="mt-2 text-xl xl:text-3xl font-extrabold tracking-tight {{ $style['text'] }} dark:text-gray-100">{{ $value }}</h3>
            @if($subtitle)
                <p class="mt-1 text-xs font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
        <div class="flex h-10 w-10 xl:h-12 xl:w-12 flex-shrink-0 items-center justify-center rounded-xl shadow-lg dark:shadow-none transition-transform duration-300 group-hover:scale-110 {{ $style['iconBg'] }}">
            <i class="{{ $icon }} text-base xl:text-lg"></i>
        </div>
    </div>
</{{ $tag }}>
