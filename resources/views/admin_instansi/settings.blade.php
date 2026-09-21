<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
        <style>
            #map-instansi {
                height: 320px;
                width: 100%;
                z-index: 10;
            }
            @media (min-width: 640px) {
                #map-instansi {
                    height: 384px;
                }
            }
        </style>
    @endpush
    <x-slot name="header">
        <x-ui.page-header
            title="Pengaturan Instansi"
            icon="fas fa-cog"
            :breadcrumbs="[['label' => 'Pengaturan Instansi']]" />
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div>
                <a href="{{ route('dinas.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" class="mb-4">
                    {{ session('success') }}
                </x-ui.alert>
            @endif

            @include('admin_instansi.partials._settings-jam')
            @include('admin_instansi.partials._settings-lokasi')
            @include('admin_instansi.partials._settings-qr')
            @include('admin_instansi.partials._settings-pejabat')

        </div>
    </div>

    @include('admin_instansi.partials._settings-scripts')
</x-app-layout>