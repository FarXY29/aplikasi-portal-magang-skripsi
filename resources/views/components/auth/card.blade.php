@props([
    'heading' => null,
    'description' => null,
    'maxWidth' => 'md',
])

@php
    $widths = [
        'sm' => 'max-w-md',
        'md' => 'max-w-xl',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'auth-card auth-animate-in relative w-full ' . ($widths[$maxWidth] ?? $widths['md']) . ' rounded-2xl p-6 sm:rounded-3xl sm:p-8 lg:p-10 transition-all duration-300']) }}>
    @if ($heading)
        <div class="mb-6 border-b border-gray-100 dark:border-gray-800/80 pb-5">
            <h2 class="font-display text-2xl font-black tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                {{ $heading }}
            </h2>
            @if ($description)
                <div class="mt-2 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400 leading-relaxed">
                    {!! $description !!}
                </div>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
