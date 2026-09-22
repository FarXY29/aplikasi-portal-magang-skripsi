<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-cogs text-teal-600"></i>
                {{ __('Pengaturan Sistem') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 dark:bg-gray-950 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition group">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" class="mb-4">
                    {{ session('success') }}
                </x-ui.alert>
            @endif

            @if(session('error'))
                <x-ui.alert type="error" class="mb-4">
                    {{ session('error') }}
                </x-ui.alert>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-8">
                    @include('admin_kota.pengaturan.partials._identity')
                    @include('admin_kota.pengaturan.partials._pejabat')
                    @include('admin_kota.pengaturan.partials._kop')
                    @include('admin_kota.pengaturan.partials._pengumuman')

                    <div class="flex items-center justify-end pt-2">
                        <button type="submit" class="inline-flex items-center px-8 py-3 bg-gray-900 dark:bg-teal-600 text-white font-bold rounded-xl shadow-lg hover:bg-gray-800 dark:hover:bg-teal-500 hover:shadow-xl transition transform hover:-translate-y-0.5 active:scale-95">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan Pengaturan
                        </button>
                    </div>
                </div>
            </form>

            @include('admin_kota.pengaturan.partials._backup')
        </div>
    </div>
</x-app-layout>
