{{-- Card Papan Pengumuman --}}
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700"
     x-data="{ announcement: @js(old('announcement', $settings['announcement'] ?? '')) }">
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
