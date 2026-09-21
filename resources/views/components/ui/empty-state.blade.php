@props([
    'title' => 'Belum Ada Data',
    'subtitle' => null,
    'description' => null,
    'icon' => 'fas fa-folder-open',
    'colspan' => null,
    'mobile' => false,
])

@php
    $desc = $description ?? $subtitle;
    $iconClass = str_starts_with($icon, 'fa') && !str_contains($icon, ' ') ? 'fas ' . $icon : $icon;
    $inner = 'flex flex-col items-center justify-center text-center';
@endphp

@if($colspan)
    <tr>
        <td colspan="{{ $colspan }}" {{ $attributes->merge(['class' => 'px-6 py-16']) }}>
            <div class="{{ $inner }}">
                <div class="w-16 h-16 rounded-2xl bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-4 ring-8 ring-teal-50/70 dark:ring-teal-500/5">
                    <i class="{{ $iconClass }} text-2xl animate-pulse"></i>
                </div>
                <h3 class="text-sm md:text-base font-black text-slate-900 dark:text-white mb-1">{{ $title }}</h3>
                @if($desc)
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium max-w-sm">{{ $desc }}</p>
                @endif
                @if(isset($slot) && !empty(trim((string)$slot)))
                    <div class="flex flex-wrap items-center justify-center gap-3 mt-4">
                        {{ $slot }}
                    </div>
                @endif
            </div>
        </td>
    </tr>
@elseif($mobile)
    <div {{ $attributes->merge(['class' => 'p-10 md:p-14']) }}>
        <div class="{{ $inner }}">
            <div class="w-16 h-16 rounded-2xl bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-4 ring-8 ring-teal-50/70 dark:ring-teal-500/5">
                <i class="{{ $iconClass }} text-2xl animate-pulse"></i>
            </div>
            <h3 class="text-sm md:text-base font-black text-slate-900 dark:text-white mb-1">{{ $title }}</h3>
            @if($desc)
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium max-w-sm">{{ $desc }}</p>
            @endif
            @if(isset($slot) && !empty(trim((string)$slot)))
                <div class="flex flex-wrap items-center justify-center gap-3 mt-4">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </div>
@else
    <div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center p-8 md:p-12 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm text-center max-w-xl mx-auto']) }}>
        <div class="w-16 h-16 rounded-2xl bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-6 ring-8 ring-teal-50/70 dark:ring-teal-500/5">
            <i class="{{ $iconClass }} text-2xl animate-pulse"></i>
        </div>
        <h3 class="text-lg font-black text-gray-900 dark:text-gray-100 mb-2">{{ $title }}</h3>
        @if($desc)
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium leading-relaxed mb-6 max-w-sm">{{ $desc }}</p>
        @endif
        @if(isset($slot) && !empty(trim((string)$slot)))
            <div class="flex flex-wrap items-center justify-center gap-3">
                {{ $slot }}
            </div>
        @endif
    </div>
@endif
