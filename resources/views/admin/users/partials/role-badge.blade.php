@php
    $colors = [
        'admin_kota' => 'bg-purple-100 text-purple-700 border-purple-200',
        'admin_instansi' => 'bg-teal-100 text-teal-700 border-teal-200',
        'pembimbing_lapangan'     => 'bg-blue-100 text-blue-700 border-blue-200',
        'peserta'    => 'bg-green-100 text-green-700 border-green-200',
        'dosen/guru' => 'bg-orange-100 text-orange-700 border-orange-200',
    ];
    $roleName = [
        'admin_kota' => 'Super Admin',
        'admin_instansi' => 'Admin INSTANSI',
        'pembimbing_lapangan'     => 'Pembimbing Lapangan Lap.',
        'peserta'    => 'Peserta',
    ][$role] ?? ucwords(str_replace('_', ' ', $role));
@endphp
<span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $colors[$role] ?? 'bg-gray-100' }}">
    {{ $roleName }}
</span>