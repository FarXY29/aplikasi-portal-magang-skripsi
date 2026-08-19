@props([
    'width' => 0,
    'barClass' => 'bg-gradient-to-r from-teal-500 to-emerald-500',
    'height' => 'h-2',
])

<div {{ $attributes->merge(['class' => "w-full bg-slate-100 dark:bg-slate-800 {$height} rounded-full overflow-hidden"]) }}>
    <div class="{{ $barClass }} {{ $height }} rounded-full transition-all duration-500" style="width: {{ max(0, min(100, (float) $width)) }}%"></div>
</div>
