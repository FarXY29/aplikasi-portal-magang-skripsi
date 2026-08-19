@props([
    'index' => 0,
    'eligible' => true,
    'size' => 'sm',
])

@php
    $sizeClass = $size === 'md' ? 'w-8 h-8' : 'w-7 h-7';
    $title = 'Peringkat ' . ($index + 1);

    if ($eligible && $index === 0) {
        $variantClass = "{$sizeClass} rounded-xl bg-gradient-to-br from-amber-300 to-amber-500 text-white shadow-md shadow-amber-500/30";
        $label = '<i class="fas fa-crown text-xs"></i>';
    } elseif ($eligible && $index === 1) {
        $variantClass = "{$sizeClass} rounded-xl bg-gradient-to-br from-slate-200 to-slate-400 text-slate-700 shadow-md shadow-slate-400/25";
        $label = '2';
    } elseif ($eligible && $index === 2) {
        $variantClass = "{$sizeClass} rounded-xl bg-gradient-to-br from-orange-300 to-amber-600 text-white shadow-md shadow-amber-600/25";
        $label = '3';
    } else {
        $variantClass = "{$sizeClass} rounded-xl bg-slate-100 dark:bg-slate-800/80 text-slate-400 dark:text-slate-500 border border-slate-200 dark:border-slate-700/60 font-bold";
        $label = '#' . ($index + 1);
    }
@endphp

<div {{ $attributes->merge(['class' => "flex items-center justify-center font-black text-xs shrink-0 {$variantClass}"]) }} title="{{ $title }}">
    {!! $label !!}
</div>
