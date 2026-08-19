@props([
    'type' => 'success',
])

@php
    $styles = [
        'success' => 'border-emerald-500/30 bg-emerald-500/10 text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-950/50 dark:text-emerald-300',
        'info' => 'border-teal-500/30 bg-teal-500/10 text-teal-800 dark:border-teal-500/30 dark:bg-teal-950/50 dark:text-teal-300',
        'error' => 'border-rose-500/30 bg-rose-500/10 text-rose-800 dark:border-rose-500/30 dark:bg-rose-950/50 dark:text-rose-300',
    ];
    $icons = [
        'success' => 'fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400',
        'info' => 'fa-solid fa-circle-info text-teal-600 dark:text-teal-400',
        'error' => 'fa-solid fa-circle-exclamation text-rose-600 dark:text-rose-400',
    ];
@endphp

@if ($slot->isNotEmpty())
    <div {{ $attributes->merge(['class' => 'mb-5 flex items-start gap-3 rounded-2xl border p-4 text-xs sm:text-sm font-semibold backdrop-blur-md transition-all ' . ($styles[$type] ?? $styles['success'])]) }}>
        <i class="{{ $icons[$type] ?? $icons['success'] }} mt-0.5 text-base flex-shrink-0"></i>
        <div class="flex-1 leading-relaxed">{{ $slot }}</div>
    </div>
@endif
