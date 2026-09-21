<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 min-w-0">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-teal-500 text-white flex items-center justify-center shadow-md shadow-teal-500/25 shrink-0">
                    <i class="fas fa-users-cog text-sm md:text-base"></i>
                </div>
                <h2 class="font-black text-base md:text-xl text-slate-900 dark:text-white leading-tight truncate">
                    {{ __('Rekapitulasi Global Peserta Magang') }}
                </h2>
            </div>
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                @if(request()->anyFilled(['instansi', 'instansi_id', 'status', 'posisi', 'q', 'start_date', 'end_date', 'periode_preset']))
                    <a href="{{ route('admin.laporan.peserta_global') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30 hover:bg-rose-100 dark:hover:bg-rose-500/20 rounded-xl font-bold text-xs transition shadow-sm">
                        <i class="fas fa-redo-alt text-[10px]"></i> Reset Filter
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    @include('admin_kota.laporan.partials.chart-loader')

    @php
        $sumKnownStatus = (int) $stats['aktif'] + (int) $stats['selesai'] + (int) $stats['pending'];
        $otherStatus = max(0, (int) $stats['total'] - $sumKnownStatus);

        $chartStatusLabels = ['Aktif Magang', 'Selesai Magang', 'Pending / Menunggu'];
        $chartStatusValues = [(int) $stats['aktif'], (int) $stats['selesai'], (int) $stats['pending']];
        $chartStatusColors = ['#10b981', '#3b82f6', '#f59e0b'];
        if ($otherStatus > 0) {
            $chartStatusLabels[] = 'Lainnya';
            $chartStatusValues[] = $otherStatus;
            $chartStatusColors[] = '#94a3b8';
        }
        $chartStatusGlobal = [
            'labels' => $chartStatusLabels,
            'values' => $chartStatusValues,
            'colors' => $chartStatusColors,
        ];
    @endphp

    <div class="font-[Inter] -mx-4 -mt-4 -mb-24 md:-mx-6 md:-mt-6 md:-mb-8 lg:-mx-8 lg:-mt-8 px-4 pt-4 pb-24 md:px-6 md:pt-6 md:pb-8 lg:px-8 lg:pt-8 min-h-full bg-gray-50 dark:bg-[#0f172a] text-slate-900 dark:text-slate-100"
        x-data="{ 
            quickSearch: '',
            formatDate(d) {
                const year = d.getFullYear();
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            },
            applyPreset(preset) {
                const today = new Date();
                const end = this.formatDate(today);
                let start = '';
                if (preset === '1_bulan') {
                    const d = new Date();
                    d.setDate(d.getDate() - 30);
                    start = this.formatDate(d);
                } else if (preset === '3_bulan') {
                    const d = new Date();
                    d.setMonth(d.getMonth() - 3);
                    start = this.formatDate(d);
                } else if (preset === 'semester') {
                    const d = new Date();
                    d.setMonth(d.getMonth() - 6);
                    start = this.formatDate(d);
                } else if (preset === 'tahun') {
                    const d = new Date();
                    d.setFullYear(d.getFullYear() - 1);
                    start = this.formatDate(d);
                }
                this.$refs.startDateInput.value = start;
                this.$refs.endDateInput.value = end;
                this.$refs.presetInput.value = preset;
                this.$refs.filterForm.submit();
            },
            clearPeriod() {
                this.$refs.startDateInput.value = '';
                this.$refs.endDateInput.value = '';
                this.$refs.presetInput.value = '';
                this.$refs.filterForm.submit();
            }
        }">
        <div class="max-w-7xl mx-auto space-y-5 md:space-y-6">

            {{-- Back Navigation & Export Buttons --}}
            <x-ui.toolbar
                :printRoute="$stats['total'] > 0 ? route('admin.laporan.peserta_global.print', array_merge(request()->query(), ['format' => 'pdf'])) : null" />

            {{-- Ringkasan Statistik Utama --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                <x-ui.stat-card
                    title="Total Peserta"
                    :value="number_format($stats['total'])"
                    icon="fas fa-users"
                    color="teal"
                    tooltip="Jumlah seluruh peserta yang sesuai dengan filter laporan saat ini." />
                <x-ui.stat-card
                    title="Aktif Magang"
                    :value="number_format($stats['aktif'])"
                    icon="fas fa-user-check"
                    color="emerald"
                    tooltip="Jumlah peserta magang dengan status 'diterima' yang saat ini sedang aktif menjalani kegiatan magang di instansi Pemko." />
                <x-ui.stat-card
                    title="Selesai Magang"
                    :value="number_format($stats['selesai'])"
                    icon="fas fa-graduation-cap"
                    color="blue"
                    tooltip="Jumlah alumni peserta magang yang telah menyelesaikan seluruh program magang secara tuntas (status 'selesai')." />
                <x-ui.stat-card
                    title="Pending / Menunggu"
                    :value="number_format($stats['pending'])"
                    icon="fas fa-clock"
                    color="amber"
                    tooltip="Jumlah pendaftaran magang yang masih dalam proses verifikasi seleksi / pending (status 'pending'/'menunggu')." />
            </div>

            {{-- Chart: Distribusi Status --}}
            @if($stats['total'] > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    <x-ui.chart-card
                        icon="fas fa-chart-pie"
                        iconColor="teal"
                        title="Distribusi Status Peserta"
                        subtitle="Komposisi status magang peserta terfilter."
                        canvasId="chart-status-global"
                        height="h-64 md:h-72" />
                    <div class="lg:col-span-2 bg-white dark:bg-[#161f33] rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 shadow-lg p-5 md:p-6 flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-2xl bg-teal-100 dark:bg-teal-500/15 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/25 flex items-center justify-center shrink-0">
                                <i class="fas fa-circle-info text-base"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-sm md:text-base font-black text-slate-900 dark:text-white truncate">Ringkasan Rekapitulasi</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">Gambaran umum data peserta pada laporan ini.</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-800/60 p-4 text-center">
                                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Aktif</p>
                                <p class="text-2xl font-extrabold font-mono text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($stats['aktif']) }}</p>
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mt-0.5">{{ $stats['total'] > 0 ? round($stats['aktif'] / $stats['total'] * 100, 1) : 0 }}% dari total</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-800/60 p-4 text-center">
                                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Selesai</p>
                                <p class="text-2xl font-extrabold font-mono text-blue-600 dark:text-blue-400 mt-1">{{ number_format($stats['selesai']) }}</p>
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mt-0.5">{{ $stats['total'] > 0 ? round($stats['selesai'] / $stats['total'] * 100, 1) : 0 }}% dari total</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-800/60 p-4 text-center">
                                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Pending</p>
                                <p class="text-2xl font-extrabold font-mono text-amber-600 dark:text-amber-400 mt-1">{{ number_format($stats['pending']) }}</p>
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mt-0.5">{{ $stats['total'] > 0 ? round($stats['pending'] / $stats['total'] * 100, 1) : 0 }}% dari total</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @include('admin_kota.laporan.partials._peserta-filter')
            @include('admin_kota.laporan.partials._peserta-kampus-table')
            @include('admin_kota.laporan.partials._peserta-table')

        </div>
    </div>

    @include('admin_kota.laporan.partials._peserta-chart')
</x-app-layout>
