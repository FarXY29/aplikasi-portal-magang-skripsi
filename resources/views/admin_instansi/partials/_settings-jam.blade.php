{{-- CARD 1: JAM OPERASIONAL ABSENSI --}}
<div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/60 flex items-center justify-center text-blue-600 dark:text-blue-400 text-xl border border-blue-100 dark:border-blue-900/50 flex-shrink-0">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Jam Operasional Absensi</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tentukan batas waktu pembukaan absen datang dan pulang bagi peserta magang.</p>
        </div>
    </div>
    
    <div class="p-6 sm:p-8">
        <form action="{{ route('dinas.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Jam Buka Absen Datang</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                            <i class="fas fa-sign-in-alt"></i>
                        </span>
                        <input type="time" name="jam_mulai_masuk" value="{{ $instansi->jam_mulai_masuk }}" 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 text-sm font-bold shadow-xs [color-scheme:dark]">
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">*Tombol absen datang aktif setelah jam ini.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Jam Buka Absen Pulang</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                            <i class="fas fa-sign-out-alt"></i>
                        </span>
                        <input type="time" name="jam_mulai_pulang" value="{{ $instansi->jam_mulai_pulang }}" 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 text-sm font-bold shadow-xs [color-scheme:dark]">
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">*Tombol absen pulang aktif setelah jam ini.</p>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-md transition flex items-center active:scale-95">
                    <i class="fas fa-save mr-2 text-sm"></i> Simpan Jam Kerja
                </button>
            </div>
        </form>
    </div>
</div>
