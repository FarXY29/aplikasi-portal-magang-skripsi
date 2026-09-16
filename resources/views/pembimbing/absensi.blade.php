<x-app-layout>
    <x-slot name="header">
        @include('pembimbing.partials._page-header', [
            'title' => 'Pemantauan Absensi Mahasiswa',
            'icon' => 'fas fa-clipboard-list',
            'applications' => $applications,
            'app' => $app,
            'routeName' => 'pembimbing.peserta.absensi',
        ])
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @include('pembimbing.partials._back-link')

            {{-- Filter Bar --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 print:hidden space-y-5">
                <x-pembimbing.filter-bar
                    route-name="pembimbing.peserta.absensi"
                    :app="$app"
                    :applications="$applications"
                    :filter-type="$filterType"
                    :selected-date="$selectedDate"
                    title="Filter Pemantauan Absensi"
                    subtitle="Filter data absensi mahasiswa berdasarkan rentang waktu, status, atau pencarian"
                    search-label="Cari Catatan:"
                    search-placeholder="Cari keterangan..."
                    status-field="status"
                    :status-options="[
                        ['value' => 'hadir', 'label' => 'Hadir'],
                        ['value' => 'izin', 'label' => 'Izin'],
                        ['value' => 'sakit', 'label' => 'Sakit'],
                        ['value' => 'alpa', 'label' => 'Alpa'],
                    ]"
                    status-badge-label="Status"
                />
            </div>

            {{-- Main Table Container --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-calendar-check text-teal-600 dark:text-teal-400"></i> Rekap Kehadiran
                    </h3>
                    <span class="bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 text-xs font-black px-3 py-1 rounded-full border border-teal-200 dark:border-teal-800/60">
                        Jumlah Catatan: {{ $attendances->count() }}
                    </span>
                </div>
                
                <div>
                    @if($attendances->isEmpty())
                        <div class="p-16 text-center text-gray-400 dark:text-gray-500">
                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-200 dark:border-gray-700">
                                <i class="far fa-clipboard text-3xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <p class="font-bold text-gray-700 dark:text-gray-300 text-sm">Belum Ada Rekaman Absensi</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Belum ada catatan kehadiran untuk mahasiswa ini pada periode yang dipilih.</p>
                        </div>
                    @else
                        @include('pembimbing.partials._absensi-table', ['attendances' => $attendances])
                    @endif

                    <x-pembimbing.pagination :paginator="$attendances" />
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
