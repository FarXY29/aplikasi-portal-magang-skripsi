<x-app-layout>
    <x-slot name="header">
        @include('pembimbing.partials._page-header', [
            'title' => 'Pemantauan Logbook Mahasiswa',
            'icon' => 'fas fa-book-open',
            'applications' => $applications,
            'app' => $app,
            'routeName' => 'pembimbing.peserta.logbook',
        ])
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @include('pembimbing.partials._back-link')

            {{-- Filter Bar --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 print:hidden space-y-5">
                <x-pembimbing.filter-bar
                    route-name="pembimbing.peserta.logbook"
                    :app="$app"
                    :applications="$applications"
                    :filter-type="$filterType"
                    :selected-date="$selectedDate"
                    title="Filter Pemantauan Logbook"
                    subtitle="Filter data logbook berdasarkan rentang waktu, status validasi, atau pencarian kegiatan"
                    search-label="Cari Kegiatan Jurnal:"
                    search-placeholder="Cari isi deskripsi..."
                    status-field="status_validasi"
                    :status-options="[
                        ['value' => 'pending', 'label' => 'Pending / Menunggu'],
                        ['value' => 'disetujui', 'label' => 'Disetujui'],
                        ['value' => 'revisi', 'label' => 'Perlu Revisi'],
                        ['value' => 'ditolak', 'label' => 'Ditolak'],
                    ]"
                    status-badge-label="Validasi"
                />
            </div>

            @if($logs->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                        <i class="fas fa-book-open text-3xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Logbook Kosong</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Belum ada aktivitas logbook pada periode yang dipilih.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start" x-data="{ activeTab: {{ $logs->first()->id }} }">
                    {{-- Sidebar Log List --}}
                    @include('pembimbing.partials._logbook-list', ['logs' => $logs])

                    {{-- Detail Pane --}}
                    @include('pembimbing.partials._logbook-detail', ['logs' => $logs, 'app' => $app])
                </div>
            @endif

            <x-pembimbing.pagination :paginator="$logs" />
        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            function openFromTrigger(el) {
                if (typeof openImageModal !== 'function') return;
                openImageModal(el.dataset.modalSrc, el.dataset.modalTitle);
            }

            document.addEventListener('click', function (event) {
                const trigger = event.target.closest('[data-image-modal]');
                if (trigger) openFromTrigger(trigger);
            });

            document.addEventListener('keydown', function (event) {
                if (event.key !== 'Enter' && event.key !== ' ') return;
                const trigger = event.target.closest('[data-image-modal]');
                if (trigger) {
                    event.preventDefault();
                    openFromTrigger(trigger);
                }
            });
        })();
    </script>
    @endpush
</x-app-layout>
