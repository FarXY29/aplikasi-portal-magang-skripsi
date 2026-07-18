@props([
    'status' => null,
    'class' => '',
    'icon' => null,
    'label' => null,
])

@php
    $badgeClass = 'bg-gray-100 text-gray-700 border-gray-200';
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
            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/80';
            $displayIcon = $displayIcon ?? 'fas fa-check-circle';
        } elseif (in_array($statusLower, ['pending', 'menunggu', 'sakit', 'izin'])) {
            $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200/80';
            $displayIcon = $displayIcon ?? 'fas fa-clock';
        } elseif (in_array($statusLower, ['ditolak', 'rejected', 'dikeluarkan', 'alpa', 'dibatalkan'])) {
            $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200/80';
            $displayIcon = $displayIcon ?? 'fas fa-times-circle';
        }
    }
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border shadow-2xs transition-all duration-200 {$badgeClass} {$class}"]) }}>
    @if($displayIcon)
        <i class="{{ $displayIcon }} text-[10px]"></i>
    @endif
    <span>{{ $displayLabel ?? '-' }}</span>
</span>
