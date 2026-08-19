@php
    $statusVal = $status instanceof \App\Enums\ApplicationStatus ? $status->value : $status;

    $badgeClass = match($statusVal) {
        'diterima' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-500/30',
        'selesai' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-500/30',
        'pending', 'menunggu' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-500/30',
        'ditolak' => 'bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-500/30',
        default => 'bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700/60',
    };

    $label = match($statusVal) {
        'diterima' => 'Aktif',
        'selesai' => 'Selesai',
        'pending', 'menunggu' => 'Pending',
        'ditolak' => 'Ditolak',
        default => ucfirst((string) $statusVal),
    };
@endphp

<span class="px-3 py-1 inline-flex text-xs font-bold rounded-full border shadow-sm {{ $badgeClass }} {{ $extraClass ?? '' }}">
    {{ $label }}
</span>
