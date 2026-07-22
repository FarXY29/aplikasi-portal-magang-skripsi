@props([
    'status' => null,
    'class' => '',
    'icon' => null,
    'label' => null,
])

@php
    $badgeClass = 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700';
    $displayIcon = $icon;
    $displayLabel = $label ?? $status;

    if (is_object($status)) {
        if (method_exists($status, 'badgeClass')) {
            $badgeClass = $status->badgeClass();
        }
        if (method_exists($status, 'icon') && !$displayIcon) {
            $displayIcon = $status->icon();
        }
        if (method_exists($status, 'label') && !$label) {
            $displayLabel = $status->label();
        }
    } elseif (is_string($status)) {
        $statusLower = strtolower($status);
        if (in_array($statusLower, ['diterima', 'approved', 'valid', 'hadir', 'selesai'])) {
            $badgeClass = 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200/80 dark:border-emerald-800/60';
            $displayIcon = $displayIcon ?? 'fas fa-check-circle';
        } elseif (in_array($statusLower, ['pending', 'menunggu', 'sakit', 'izin'])) {
            $badgeClass = 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200/80 dark:border-amber-800/60';
            $displayIcon = $displayIcon ?? 'fas fa-clock';
        } elseif (in_array($statusLower, ['ditolak', 'rejected', 'dikeluarkan', 'alpa', 'dibatalkan'])) {
            $badgeClass = 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200/80 dark:border-rose-800/60';
            $displayIcon = $displayIcon ?? 'fas fa-times-circle';
        }
    }
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border shadow-2xs transition-all duration-200 {$badgeClass} {$class}"]) }}>
    @if($displayIcon)
        <i class="{{ $displayIcon }} text-[10px]"></i>
    @endif
    <span>{{ $displayLabel ?? '-' }}</span>
</span>
