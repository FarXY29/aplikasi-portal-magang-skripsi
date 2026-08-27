@props([
    'type' => 'info', // success, error, warning, info
    'dismissible' => true,
])

@php
    $classes = match($type) {
        'success' => 'bg-emerald-50/90 dark:bg-emerald-950/40 border-emerald-200/80 dark:border-emerald-800/60 text-emerald-900 dark:text-emerald-200',
        'error' => 'bg-rose-50/90 dark:bg-rose-950/40 border-rose-200/80 dark:border-rose-800/60 text-rose-900 dark:text-rose-200',
        'warning' => 'bg-amber-50/90 dark:bg-amber-950/40 border-amber-200/80 dark:border-amber-800/60 text-amber-900 dark:text-amber-200',
        'info' => 'bg-teal-50/90 dark:bg-teal-950/40 border-teal-200/80 dark:border-teal-800/60 text-teal-900 dark:text-teal-200',
        default => 'bg-slate-50 dark:bg-gray-900 border-slate-200/80 dark:border-gray-700 text-slate-800 dark:text-gray-200',
    };

    $iconClasses = match($type) {
        'success' => 'fas fa-check-circle text-emerald-600 dark:text-emerald-400',
        'error' => 'fas fa-times-circle text-rose-600 dark:text-rose-400',
        'warning' => 'fas fa-exclamation-triangle text-amber-600 dark:text-amber-400',
        'info' => 'fas fa-info-circle text-teal-600 dark:text-teal-400',
        default => 'fas fa-bell text-slate-500 dark:text-gray-400',
    };

    $buttonClasses = match($type) {
        'success' => 'text-emerald-700 hover:text-emerald-900 hover:bg-emerald-100/60 dark:text-emerald-400',
        'error' => 'text-rose-700 hover:text-rose-900 hover:bg-rose-100/60 dark:text-rose-400',
        'warning' => 'text-amber-700 hover:text-amber-900 hover:bg-amber-100/60 dark:text-amber-400',
        'info' => 'text-teal-700 hover:text-teal-900 hover:bg-teal-100/60 dark:text-teal-400',
        default => 'text-slate-500 dark:text-gray-400 hover:text-slate-700 dark:hover:text-gray-200 hover:bg-slate-100 dark:hover:bg-gray-800/50',
    };
@endphp

<div 
    @if($dismissible)
        x-data="{ show: true }" 
        x-show="show" 
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
    @endif
    {{ $attributes->merge(['class' => "flex items-start gap-3 px-4 py-3 rounded-2xl border shadow-2xs {$classes}"]) }}
>
    <div class="flex-shrink-0 mt-0.5">
        <i class="{{ $iconClasses }} text-lg"></i>
    </div>
    <div class="flex-1 min-w-0 text-sm font-medium">
        {{ $slot }}
    </div>
    @if($dismissible)
        <button 
            type="button" 
            @click="show = false" 
            class="flex-shrink-0 -mr-1.5 -mt-1.5 p-1.5 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-1 {{ $buttonClasses }}"
            aria-label="Tutup alert"
        >
            <i class="fas fa-times text-sm"></i>
        </button>
    @endif
</div>
