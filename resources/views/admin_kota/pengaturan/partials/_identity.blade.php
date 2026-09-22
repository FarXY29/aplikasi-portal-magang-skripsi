{{-- Card Identitas Aplikasi --}}
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700">
    <div class="p-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-inner">
            <i class="fas fa-laptop-code text-xl"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Identitas Aplikasi</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Konfigurasi nama dan branding dasar sistem.</p>
        </div>
    </div>
    <div class="p-8">
        <div class="max-w-2xl">
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Aplikasi</label>
            <div class="relative group">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition">
                    <i class="fas fa-heading"></i>
                </span>
                <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? 'SiMagang Banjarmasin') }}"
                    class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                    placeholder="Masukkan nama aplikasi...">
            </div>
            <p class="text-xs text-gray-400 mt-2 flex items-center">
                <i class="fas fa-info-circle mr-1.5"></i> Nama ini akan tampil di halaman login, title bar browser, dan footer.
            </p>
        </div>
    </div>
</div>
