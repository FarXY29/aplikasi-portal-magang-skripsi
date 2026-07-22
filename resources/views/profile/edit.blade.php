<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/60 border border-teal-200/60 dark:border-teal-900/50 flex items-center justify-center text-teal-600 dark:text-teal-400 shadow-2xs">
                <i class="fas fa-user-cog text-sm"></i>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-900 dark:text-gray-100 leading-tight truncate">
                    {{ __('Pengaturan Akun') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-normal">Kelola preferensi akun dan informasi profil Anda.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Top Title Section -->
            <div class="mb-6 pb-5 border-b border-gray-200 dark:border-gray-700/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-gray-100 tracking-tight mb-1">Pengaturan Profil & Akun</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola preferensi akun, foto profil, dan informasi penting akun Anda.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 font-semibold text-xs border border-teal-200/60 dark:border-teal-900/50 shadow-2xs">
                        <i class="fas fa-shield-check text-teal-600 dark:text-teal-400"></i>
                        <span>{{ str_replace('_', ' ', strtoupper(Auth::user()->role)) }}</span>
                    </span>
                </div>
            </div>

            <!-- Cards Container -->
            <div class="space-y-6 animate-fade-in">
                <!-- 1. Form Update Informasi Dasar -->
                <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 flex flex-col rounded-xl border border-gray-200 dark:border-gray-700/80 shadow-xs p-6 sm:p-8 transition-all">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- 2. Form Ganti Password -->
                <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 flex flex-col rounded-xl border border-gray-200 dark:border-gray-700/80 shadow-xs p-6 sm:p-8 transition-all">
                    @include('profile.partials.update-password-form')
                </div>

                <!-- 3. Form Hapus Akun -->
                <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 flex flex-col rounded-xl border border-gray-200 dark:border-gray-700/80 shadow-xs p-6 sm:p-8 transition-all">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>