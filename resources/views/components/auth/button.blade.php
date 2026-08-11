@props([
    'icon' => null,
    'variant' => 'primary',
])

@php
    $variants = [
        'primary' => 'auth-btn',
        'secondary' => 'inline-flex w-full items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 px-6 py-3.5 text-sm font-extrabold uppercase tracking-wider transition-all duration-200 hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-gray-400/20',
        'ghost' => 'inline-flex items-center justify-center text-sm font-bold text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition-colors focus:outline-none',
    ];
@endphp

<button
    type="submit"
    {{ $attributes->merge(['class' => ($variants[$variant] ?? $variants['primary'])]) }}
>
    <span>{{ $slot }}</span>
    @if ($icon)
        <i class="{{ $icon }} ml-2.5 text-sm transition-transform group-hover:translate-x-0.5"></i>
    @endif
</button>
