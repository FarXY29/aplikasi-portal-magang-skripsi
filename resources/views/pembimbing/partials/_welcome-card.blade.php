{{-- Welcome Card --}}
<div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-6 sm:p-8">
        <h3 class="text-2xl font-black text-gray-900 dark:text-gray-100 mb-2">Selamat datang, {{ Auth::user()->name }}!</h3>
        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-6 font-medium leading-relaxed">
            Anda login sebagai <strong class="text-gray-800 dark:text-gray-200">Pembimbing Akademik</strong>.
            @if(Auth::user()->asal_instansi)
                Mahasiswa di bawah ini adalah mereka yang secara spesifik telah memilih Anda sebagai dosen pembimbing mereka pada portal magang ini.
            @else
                <span class="text-rose-600 dark:text-rose-400 block mt-2 font-bold">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Anda belum mengisi Asal Sekolah / Kampus di profil Anda. Silakan perbarui profil Anda.
                </span>
            @endif
        </p>

        <div class="bg-teal-50/60 dark:bg-teal-950/40 border-l-4 border-teal-500 dark:border-teal-400 p-4 rounded-r-2xl border-t border-b border-r border-teal-200 dark:border-teal-900/40">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-teal-600 dark:text-teal-400 text-base mt-0.5 flex-shrink-0"></i>
                <p class="text-xs sm:text-sm text-teal-900 dark:text-teal-200 font-medium leading-relaxed">
                    Di halaman ini Anda dapat melihat daftar mahasiswa yang sedang magang dan memantau kegiatan harian (Logbook) serta kehadiran (Absensi) mereka secara langsung.
                </p>
            </div>
        </div>
    </div>
</div>
