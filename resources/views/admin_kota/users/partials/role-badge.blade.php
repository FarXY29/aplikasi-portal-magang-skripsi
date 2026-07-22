@php
    $colors = [
        'admin_kota' => 'bg-purple-100 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border-purple-200 dark:border-purple-800',
        'admin_instansi' => 'bg-teal-100 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border-teal-200 dark:border-teal-800',
        'pembimbing_lapangan' => 'bg-blue-100 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
        'peserta'    => 'bg-green-100 dark:bg-green-950/40 text-green-700 dark:text-green-300 border-green-200 dark:border-green-800',
        'pembimbing' => 'bg-orange-100 dark:bg-orange-950/40 text-orange-700 dark:text-orange-300 border-orange-200 dark:border-orange-800',
    ];
    $roleName = [
        'admin_kota' => 'Super Admin',
        'admin_instansi' => 'Admin Instansi',
        'pembimbing_lapangan' => 'Pembimbing Lapangan',
        'peserta'    => 'Peserta',
        'pembimbing' => 'Pembimbing Sekolah',
    ][$role] ?? ucwords(str_replace('_', ' ', $role));
@endphp
<span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $colors[$role] ?? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700' }}">
    {{ $roleName }}
</span>