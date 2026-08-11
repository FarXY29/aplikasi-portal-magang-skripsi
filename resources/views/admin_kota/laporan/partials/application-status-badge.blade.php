@php
    $statusVal = $status instanceof \App\Enums\ApplicationStatus ? $status->value : $status;

    $badgeClass = match($statusVal) {
        'diterima' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
        'selesai' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800/60',
        'pending', 'menunggu' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
        'ditolak' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
        default => 'bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700',
    };

    $label = match($statusVal) {
        'diterima' => 'Aktif',
        'selesai' => 'Selesai',
        'pending', 'menunggu' => 'Pending',
        'ditolak' => 'Ditolak',
        default => ucfirst((string) $statusVal),
    };
@endphp

<span class="px-3 py-1 inline-flex text-xs font-bold rounded-full border {{ $badgeClass }} {{ $extraClass ?? '' }}">
    {{ $label }}
</span>
