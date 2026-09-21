<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    <x-slot name="header">
        <x-ui.header
            icon="fas fa-building"
            :title="__('Analisis Kedisiplinan Instansi')"
            countLabel="Total Terfilter:"
            :count="$stats['total_instansi']"
            countSuffix="Instansi" />
    </x-slot>

    @include('admin_kota.laporan.partials.chart-loader')

    @php
        $isUnfiltered = !request('q') && !request('disiplin_range');

        $chartTopDisiplin = $instansis
            ->take(10)
            ->map(fn($r) => [
                'nama' => $r->nama_dinas,
                'nilai' => round($r->tingkat_disiplin, 1),
            ])
            ->values();

        $chartPelanggaran = [
            'labels' => ['Terlambat', 'Alpa'],
            'values' => [(int) $stats['total_terlambat'], (int) $stats['total_alpa']],
            'colors' => ['#fb923c', '#f43f5e'],
        ];
    @endphp

    <div class="font-[Inter] -mx-4 -mt-4 -mb-24 md:-mx-6 md:-mt-6 md:-mb-8 lg:-mx-8 lg:-mt-8 px-4 pt-4 pb-24 md:px-6 md:pt-6 md:pb-8 lg:px-8 lg:pt-8 min-h-full bg-gray-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-100">
        <div class="max-w-7xl mx-auto space-y-5 md:space-y-6">

            {{-- Back Navigation & Export Buttons --}}
            <x-ui.toolbar
                :printRoute="$stats['total_instansi'] > 0 ? route('admin.laporan.instansi_disiplin.print', array_merge(request()->query(), ['format' => 'pdf'])) : null" />

            {{-- Stats Cards Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4">
                <x-ui.stat-card
                    title="Total Instansi"
                    :value="number_format($stats['total_instansi'])"
                    icon="fas fa-building"
                    color="teal"
                    tooltip="Total jumlah instansi/dinas Pemerintah Kota Banjarmasin yang masuk dalam evaluasi kedisiplinan presensi." />
                <x-ui.stat-card
                    title="Rerata Disiplin"
                    :value="$stats['avg_disiplin'] . '%'"
                    icon="fas fa-percentage"
                    color="teal"
                    :featured="true"
                    tooltip="Rata-rata persentase kedisiplinan instansi se-Kota Banjarmasin. Rumus Disiplin Instansi: 100% - ((Total Pelanggaran / Total Absensi) x 100%). Pelanggaran = Terlambat + Alpa." />
                <x-ui.stat-card
                    title="Total Absensi"
                    :value="number_format($stats['total_kehadiran'])"
                    icon="fas fa-calendar-check"
                    color="blue"
                    tooltip="Total seluruh log entri presensi/kehadiran peserta magang pada instansi yang dinilai." />
                <x-ui.stat-card
                    title="Total Pelanggaran"
                    :value="number_format($stats['total_pelanggaran'])"
                    icon="fas fa-exclamation-triangle"
                    color="rose"
                    tooltip="Total akumulasi kasus pelanggaran presensi peserta magang. Rumus: Total Terlambat + Total Alpa." />
                <x-ui.stat-card
                    title="Total Terlambat"
                    :value="number_format($stats['total_terlambat'])"
                    icon="fas fa-user-clock"
                    color="orange"
                    tooltip="Total kehadiran peserta yang waktu masuknya melewati jam kerja resmi instansi (clock_in > jam_mulai_masuk dinas)." />
                <x-ui.stat-card
                    title="Total Alpa"
                    :value="number_format($stats['total_alpa'])"
                    icon="fas fa-user-times"
                    color="amber"
                    tooltip="Total ketidakhadiran peserta tanpa keterangan atau tanpa dokumen izin/sakit yang sah." />
            </div>

            {{-- Charts Row --}}
            @if($stats['total_instansi'] > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    <div class="lg:col-span-2">
                        <x-ui.chart-card
                            icon="fas fa-medal"
                            iconColor="emerald"
                            title="TOP 10 Instansi Paling Disiplin"
                            subtitle="Persentase kedisiplinan presensi peserta per instansi."
                            canvasId="chart-top-disiplin"
                            height="h-64 md:h-80" />
                    </div>
                    <x-ui.chart-card
                        icon="fas fa-chart-pie"
                        iconColor="rose"
                        title="Komposisi Pelanggaran"
                        subtitle="Perbandingan kasus terlambat vs alpa se-Kota."
                        canvasId="chart-pelanggaran"
                        height="h-64 md:h-80" />
                </div>
            @endif

            @include('admin_kota.laporan.partials._disiplin-podium')
            @include('admin_kota.laporan.partials._disiplin-table')

        </div>
    </div>

    @include('admin_kota.laporan.partials._disiplin-chart')
</x-app-layout>
