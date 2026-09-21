<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-user-clock text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Monitoring Absensi Mahasiswa') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center mb-6 print:hidden">
                <a href="{{ route('pembimbing_lapangan.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="space-y-6">
                @php
                    $isFiltered = request()->hasAny(['search', 'application_id', 'status', 'validation_status'])
                        || (request()->has('filter_type') && request('filter_type') !== 'harian')
                        || (request()->has('date') && request('date') !== date('Y-m-d'));
                @endphp

                @include('pembimbing_lapangan.partials._attendance-filter')
                @include('pembimbing_lapangan.partials._attendance-table')
            </div>

            {{-- Modals extracted outside <tbody> for clean and valid HTML rendering --}}
            @foreach($attendances as $row)
                @include('pembimbing_lapangan.partials._attendance-modals', ['row' => $row])
            @endforeach

        </div>
    </div>
</x-app-layout>