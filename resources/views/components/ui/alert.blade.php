@props([
    'type' => 'info', // success, error, warning, info
    'dismissible' => true,
])

@php
    $classes = match($type) {
        'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
        'error' => 'bg-rose-50 border-rose-200 text-rose-800',
        'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
        'info' => 'bg-blue-50 border-blue-200 text-blue-800',
        default => 'bg-gray-50 border-gray-200 text-gray-800',
    };

    $iconClasses = match($type) {
        'success' => 'fas fa-check-circle text-emerald-500',
        'error' => 'fas fa-times-circle text-rose-500',
        'warning' => 'fas fa-exclamation-triangle text-amber-500',
        'info' => 'fas fa-info-circle text-blue-500',
        default => 'fas fa-bell text-gray-500',
    };

    $buttonClasses = match($type) {
        'success' => 'text-emerald-500 hover:text-emerald-700 hover:bg-emerald-100/50',
        'error' => 'text-rose-500 hover:text-rose-700 hover:bg-rose-100/50',
        'warning' => 'text-amber-500 hover:text-amber-700 hover:bg-amber-100/50',
        'info' => 'text-blue-500 hover:text-blue-700 hover:bg-blue-100/50',
        default => 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50',
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
    {{ $attributes->merge(['class' => "flex items-start gap-3 px-4 py-3 rounded-xl border shadow-sm {$classes}"]) }}
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
