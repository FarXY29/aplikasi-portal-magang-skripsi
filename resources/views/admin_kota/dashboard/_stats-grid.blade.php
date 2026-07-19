<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 md:gap-4 mb-4 md:mb-5">
    <x-ui.stat-card 
        href="{{ route('admin.instansi.index') }}"
        title="Instansi" 
        value="{{ number_format($totalInstansi) }}" 
        icon="fas fa-building" 
        color="teal" 
    />

    <x-ui.stat-card 
        href="{{ route('admin.users.index') }}"
        title="Pengguna" 
        value="{{ number_format($totalUser) }}" 
        icon="fas fa-users" 
        color="blue" 
    />

    <x-ui.stat-card 
        href="{{ route('admin.laporan.peserta_global', ['status' => 'semua']) }}"
        title="Pendaftar" 
        value="{{ number_format($totalApplications) }}" 
        icon="fas fa-file-signature" 
        color="purple" 
    />

    <x-ui.stat-card 
        href="{{ route('admin.laporan.peserta_global', ['status' => 'diterima']) }}"
        title="Aktif" 
        value="{{ number_format($activeInterns) }}" 
        icon="fas fa-user-clock" 
        color="green" 
    />

    <x-ui.stat-card 
        href="{{ route('admin.laporan.peserta_global', ['status' => 'selesai']) }}"
        title="Selesai" 
        value="{{ number_format($completedInterns) }}" 
        icon="fas fa-graduation-cap" 
        color="indigo" 
    />

    <x-ui.stat-card 
        href="{{ route('admin.laporan.peserta_global', ['status' => 'pending']) }}"
        title="Pending" 
        value="{{ number_format($pendingApplications) }}" 
        icon="fas fa-hourglass-half" 
        color="amber" 
    />
</div>
