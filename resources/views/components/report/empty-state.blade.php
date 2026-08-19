@props([
    'title' => 'Data tidak ditemukan',
    'subtitle' => null,
    'icon' => 'fas fa-search',
    'colspan' => null,
    'mobile' => false,
])

@php
    $inner = 'flex flex-col items-center justify-center text-center';
@endphp

@if($mobile)
    <div class="p-10 md:p-14">
        <div class="{{ $inner }}">
            <div class="w-16 h-16 rounded-2xl bg-teal-50 dark:bg-teal-500/10 text-teal-500 dark:text-teal-400 flex items-center justify-center mb-4 ring-8 ring-teal-50/70 dark:ring-teal-500/5">
                <i class="{{ $icon }} text-2xl animate-pulse"></i>
            </div>
            <p class="text-sm md:text-base font-black text-slate-900 dark:text-white">{{ $title }}</p>
            @if($subtitle)
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
@elseif($colspan)
    <tr>
        <td colspan="{{ $colspan }}" class="px-6 py-16">
            <div class="{{ $inner }}">
                <div class="w-16 h-16 rounded-2xl bg-teal-50 dark:bg-teal-500/10 text-teal-500 dark:text-teal-400 flex items-center justify-center mb-4 ring-8 ring-teal-50/70 dark:ring-teal-500/5">
                    <i class="{{ $icon }} text-2xl animate-pulse"></i>
                </div>
                <p class="text-sm md:text-base font-black text-slate-900 dark:text-white">{{ $title }}</p>
                @if($subtitle)
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">{{ $subtitle }}</p>
                @endif
            </div>
        </td>
    </tr>
@else
    <div class="p-10 md:p-14 {{ $inner }}">
        <div class="w-16 h-16 rounded-2xl bg-teal-50 dark:bg-teal-500/10 text-teal-500 dark:text-teal-400 flex items-center justify-center mb-4 ring-8 ring-teal-50/70 dark:ring-teal-500/5">
            <i class="{{ $icon }} text-2xl animate-pulse"></i>
        </div>
        <p class="text-sm md:text-base font-black text-slate-900 dark:text-white">{{ $title }}</p>
        @if($subtitle)
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">{{ $subtitle }}</p>
        @endif
    </div>
@endif
