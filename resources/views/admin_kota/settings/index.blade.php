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

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

                <div class="space-y-8">

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
                                    <input type="text" name="app_name" value="{{ $settings['app_name'] ?? 'SiMagang Banjarmasin' }}"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                                        placeholder="Masukkan nama aplikasi...">
                                </div>
                                <p class="text-xs text-gray-400 mt-2 flex items-center">
                                    <i class="fas fa-info-circle mr-1.5"></i> Nama ini akan tampil di halaman login, title bar browser, dan footer.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700"
                         x-data="{ announcement: '{{ addslashes($settings['announcement'] ?? '') }}' }">

                        <div class="p-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-orange-100 dark:bg-orange-950/40 flex items-center justify-center text-orange-600 dark:text-orange-400 shadow-inner">
                                <i class="fas fa-bullhorn text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Papan Pengumuman</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Informasi global untuk seluruh peserta magang.</p>
                            </div>
                        </div>

                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Isi Pengumuman</label>
                                <textarea name="announcement" x-model="announcement" rows="5"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-sm text-sm"
                                    placeholder="Contoh: Pendaftaran magang periode Juli dibuka mulai tanggal..."></textarea>
                                <p class="text-xs text-gray-400 mt-2">
                                    Kosongkan jika tidak ada pengumuman.
                                </p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 border border-dashed border-gray-300 dark:border-gray-700 flex flex-col h-full">
                                <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 block text-center">Live Preview Dashboard</span>

                                <div class="bg-yellow-50 dark:bg-yellow-950/20 border-l-4 border-yellow-400 dark:border-yellow-500 p-4 rounded-r shadow-sm flex-grow">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700 dark:text-yellow-400 font-medium">
                                                <span x-text="announcement ? announcement : 'Tidak ada pengumuman aktif saat ini.'"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-950/40 flex items-center justify-center text-purple-600 dark:text-purple-400 shadow-inner">
                                    <i class="fas fa-database text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Backup Database</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Mencadangkan seluruh data sistem saat ini (Format .sql).</p>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                                <input type="password" name="password" autocomplete="current-password" placeholder="Konfirmasi password" class="rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm" aria-label="Konfirmasi password untuk backup">
                                <button type="submit" formaction="{{ route('admin.settings.backup') }}" formmethod="POST" class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 dark:bg-purple-700 hover:bg-purple-700 dark:hover:bg-purple-600 text-white font-bold rounded-xl shadow-sm transition">
                                    <i class="fas fa-database mr-2"></i> Antrekan Backup
                                </button>
                            </div>
                        </div>
                        @if($backups->isNotEmpty())
                            <div class="px-6 py-4 space-y-2 text-sm border-t border-gray-100 dark:border-gray-700">
                                @foreach($backups as $backup)
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <span class="text-gray-600 dark:text-gray-300">{{ $backup->filename }} · {{ ucfirst($backup->status) }}</span>
                                        @if($backup->download_url)
                                            <a href="{{ $backup->download_url }}" class="font-bold text-purple-600 dark:text-purple-400 hover:underline">Unduh (berlaku sampai {{ $backup->expires_at->format('d M H:i') }})</a>
                                        @elseif($backup->status === 'failed')
                                            <span class="text-red-600 dark:text-red-400">{{ $backup->error_message }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="inline-flex items-center px-8 py-3 bg-gray-900 dark:bg-teal-600 text-white font-bold rounded-xl shadow-lg hover:bg-gray-800 dark:hover:bg-teal-500 hover:shadow-xl transition transform hover:-translate-y-0.5 active:scale-95">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>
