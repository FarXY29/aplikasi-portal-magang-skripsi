@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'type' => 'text',
    'value' => '',
    'placeholder' => null,
    'required' => false,
    'autofocus' => false,
    'autocomplete' => null,
    'icon' => null,
    'hint' => null,
    'errors' => null,
])

@php
    $errorBag = $errors ?? null;
    $hasError = $errorBag && $errorBag->has($name);
    $inputId = $id ?? $name;
    $isPassword = $type === 'password';
@endphp

<div @if($isPassword) x-data="{ show: false }" @endif class="w-full">
    @if ($label)
        <label for="{{ $inputId }}" class="auth-label">{{ $label }}</label>
    @endif

    <div class="relative flex items-center">
        @if ($icon)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 dark:text-gray-500">
                <i class="{{ $icon }} text-sm transition-colors group-focus-within:text-teal-500"></i>
            </div>
        @endif

        <input
            id="{{ $inputId }}"
            name="{{ $name }}"
            :type="{{ $isPassword ? "show ? 'text' : 'password'" : "'".$type."'" }}"
            value="{{ $value }}"
            @if ($placeholder) placeholder="{{ $placeholder }}" @endif
            @if ($required) required @endif
            @if ($autofocus) autofocus @endif
            @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            {{ $attributes->merge(['class' => 'auth-input' . ($icon ? ' !pl-11' : '') . ($isPassword ? ' !pr-11' : '') . ($hasError ? ' !border-rose-500 focus:!border-rose-500 focus:!ring-rose-500/20' : '')]) }}
        >

        @if ($isPassword)
            <button
                type="button"
                @click="show = !show"
                tabindex="-1"
                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-teal-600 dark:text-gray-500 dark:hover:text-teal-400 focus:outline-none transition-colors"
                title="Tampilkan / Sembunyikan Password"
            >
                <i class="fas text-sm" :class="show ? 'fa-eye-slash text-teal-600 dark:text-teal-400' : 'fa-eye'"></i>
            </button>
        @endif
    </div>

    @if ($hint)
        <p class="mt-1.5 ml-1 text-xs text-gray-500 dark:text-gray-400 font-medium">{{ $hint }}</p>
    @endif

    @if ($hasError)
        <x-input-error :messages="$errorBag->get($name)" class="mt-1.5 ml-1" />
    @endif
</div>
