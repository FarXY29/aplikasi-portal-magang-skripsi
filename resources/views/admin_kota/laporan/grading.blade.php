<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    <x-slot name="header">
        <x-ui.header
            icon="fas fa-chart-line"
            :title="__('Analisis Kompetensi & Performa')"
            countLabel="Total Terfilter:"
            :count="$stats['total']"
            countSuffix="Peserta" />
    </x-slot>

    @include('admin_kota.laporan.partials.chart-loader')

    @php
        $isUnfiltered = !request('q') && !request('instansi') && !request('instansi_id') && !request('predikat');

        $chartPredikat = [
            'labels' => ['Sangat Baik', 'Baik', 'Cukup', 'Kurang'],
            'values' => [
                (int) $stats['sangat_baik'],
                (int) $stats['baik'],
                (int) $stats['cukup'],
                (int) $stats['kurang'],
            ],
            'colors' => ['#10b981', '#3b82f6', '#f59e0b', '#f43f5e'],
        ];
    @endphp

    <div class="font-[Inter] -mx-4 -mt-4 -mb-24 md:-mx-6 md:-mt-6 md:-mb-8 lg:-mx-8 lg:-mt-8 px-4 pt-4 pb-24 md:px-6 md:pt-6 md:pb-8 lg:px-8 lg:pt-8 min-h-full bg-gray-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-100">
        <div class="max-w-7xl mx-auto space-y-5 md:space-y-6">

            {{-- Back Navigation & Download Buttons --}}
            <x-ui.toolbar
                :printRoute="$stats['total'] > 0 ? route('admin.laporan.grading.print', array_merge(request()->query(), ['format' => 'pdf'])) : null" />

            {{-- Stats Cards Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4">
                <x-ui.stat-card
                    title="Total Dinilai"
                    :value="number_format($stats['total'])"
                    icon="fas fa-users"
                    color="teal"
                    tooltip="Total seluruh peserta magang se-Kota Banjarmasin yang telah memiliki penilaian evaluasi akhir." />
                <x-ui.stat-card
                    title="Sangat Baik"
                    :value="number_format($stats['sangat_baik'])"
                    icon="fas fa-check-circle"
                    color="emerald"
                    tooltip="Jumlah peserta dengan predikat 'Sangat Baik'. Kriteria Nilai Rata-Rata: 86,00 s.d. 100,00." />
                <x-ui.stat-card
                    title="Baik"
                    :value="number_format($stats['baik'])"
                    icon="fas fa-thumbs-up"
                    color="blue"
                    tooltip="Jumlah peserta dengan predikat 'Baik'. Kriteria Nilai Rata-Rata: 71,00 s.d. 85,99." />
                <x-ui.stat-card
                    title="Cukup"
                    :value="number_format($stats['cukup'])"
                    icon="fas fa-info-circle"
                    color="amber"
                    tooltip="Jumlah peserta dengan predikat 'Cukup'. Kriteria Nilai Rata-Rata: 56,00 s.d. 70,99." />
                <x-ui.stat-card
                    title="Kurang"
                    :value="number_format($stats['kurang'])"
                    icon="fas fa-times-circle"
                    color="rose"
                    tooltip="Jumlah peserta dengan predikat 'Kurang'. Kriteria Nilai Rata-Rata: 0,00 s.d. 55,99." />
                <x-ui.stat-card
                    title="Rerata Nilai"
                    :value="$stats['avg_nilai']"
                    icon="fas fa-star"
                    color="teal"
                    :featured="true"
                    tooltip="Nilai rata-rata evaluasi akhir akumulasi seluruh peserta yang telah dinilai di Kota Banjarmasin. Rumus: (Total Nilai Rata-Rata Seluruh Peserta / Jumlah Peserta Dinilai)." />
            </div>

            {{-- Charts Row: Predikat Distribution + 3-Component Rerata Panel --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <x-ui.chart-card
                    icon="fas fa-chart-pie"
                    iconColor="emerald"
                    title="Distribusi Predikat"
                    subtitle="Sebaran predikat evaluasi akhir peserta."
                    canvasId="chart-predikat"
                    height="h-64 md:h-72" />

                {{-- 3-Component Rerata Mini Panel --}}
                <div class="lg:col-span-2 bg-white dark:bg-[#161f33] rounded-2xl md:rounded-3xl p-5 md:p-6 shadow-lg border border-slate-200 dark:border-slate-800/40 grid grid-cols-1 md:grid-cols-3 gap-6 cursor-help content-center"
                    title="Perbandingan nilai rata-rata 3 aspek utama: Teknis, Disiplin, dan Perilaku peserta se-Kota Banjarmasin.">
                    <div class="cursor-help" title="Rata-rata aspek keahlian teknis peserta. Rumus: (Total Nilai Teknis / Jumlah Peserta Dinilai). Nilai Teknis = Skill Pengetahuan.">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fas fa-laptop-code text-blue-500 dark:text-blue-400"></i> Rerata Teknis
                            </span>
                            <span class="text-sm font-black text-slate-800 dark:text-slate-200 font-mono">{{ $statsGlobal['avg_teknis'] }}/100</span>
                        </div>
                        <x-ui.progress :width="$statsGlobal['avg_teknis']" barClass="bg-blue-500" />
                    </div>
                    <div class="cursor-help" title="Rata-rata aspek kedisiplinan dan ketepatan waktu peserta. Rumus: (Total Nilai Disiplin / Jumlah Peserta Dinilai).">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fas fa-calendar-check text-emerald-500 dark:text-emerald-400"></i> Rerata Disiplin
                            </span>
                            <span class="text-sm font-black text-slate-800 dark:text-slate-200 font-mono">{{ $statsGlobal['avg_disiplin'] }}/100</span>
                        </div>
                        <x-ui.progress :width="$statsGlobal['avg_disiplin']" barClass="bg-emerald-500" />
                    </div>
                    <div class="cursor-help" title="Rata-rata aspek etika, keaktifan, dan komunikasi peserta. Rumus: (Total Nilai Perilaku / Jumlah Peserta). Nilai Perilaku = (Adaptasi + Kreativitas + Kerajinan) / 3.">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fas fa-user-friends text-purple-500 dark:text-purple-400"></i> Rerata Perilaku
                            </span>
                            <span class="text-sm font-black text-slate-800 dark:text-slate-200 font-mono">{{ $statsGlobal['avg_perilaku'] }}/100</span>
                        </div>
                        <x-ui.progress :width="$statsGlobal['avg_perilaku']" barClass="bg-purple-500" />
                    </div>
                </div>
            </div>

            @include('admin_kota.laporan.partials._grading-podium')
            @include('admin_kota.laporan.partials._grading-table')

        </div>
    </div>

    @include('admin_kota.laporan.partials._grading-chart')
</x-app-layout>
