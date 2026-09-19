{{-- Banner Unduh Dokumen Lamaran --}}
@if($hasApplications ?? false)
    <div class="bg-teal-50/70 dark:bg-teal-950/30 border border-teal-200/80 dark:border-teal-800/60 rounded-2xl sm:rounded-3xl p-4 sm:p-5 flex items-start gap-3 mb-6 shadow-2xs">
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl bg-teal-100/80 dark:bg-teal-900/60 text-teal-700 dark:text-teal-300 flex items-center justify-center flex-shrink-0 border border-teal-200/80 dark:border-teal-800/60">
            <i class="fas fa-file-download text-base sm:text-lg"></i>
        </div>
        <div class="flex-grow">
            <h4 class="text-xs font-black text-teal-800 dark:text-teal-300 uppercase tracking-wider mb-1">Unduh Dokumen Lamaran</h4>
            <p class="text-xs sm:text-sm text-teal-900 dark:text-teal-200 font-medium leading-relaxed">
                Klik tombol <strong>Detail</strong> pada setiap lamaran untuk mengunduh dokumen seperti <strong>ID Card</strong>, <strong>Surat Balasan</strong>, <strong>Rekap Logbook</strong>, <strong>Sertifikat</strong>, dan <strong>Transkrip</strong>.
            </p>
        </div>
    </div>
@endif
