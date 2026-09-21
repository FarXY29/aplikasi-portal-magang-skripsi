{{-- 6 Stats Cards Grid (Clean & Eye-Comfort) --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
    {{-- Total Pendaftar --}}
    <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-4 shadow-2xs border border-slate-200 dark:border-slate-700/80 text-center cursor-help transition hover:shadow-md" title="Total permohonan pendaftaran magang yang terdata.">
        <div class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-2 border border-teal-100 dark:border-teal-900/50">
            <i class="fas fa-file-signature text-xs"></i>
        </div>
        <p class="text-xl font-black text-slate-800 dark:text-slate-100">{{ number_format($stats['total'] ?? 0) }}</p>
        <p class="text-[9px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-wider mt-1">Total Pendaftar</p>
    </div>

    {{-- Status Pending --}}
    <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-4 shadow-2xs border border-slate-200 dark:border-slate-700/80 text-center cursor-help transition hover:shadow-md" title="Permohonan baru yang masih menantikan proses peninjauan / verifikasi awal dinas.">
        <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto mb-2 border border-amber-100 dark:border-amber-900/50">
            <i class="fas fa-hourglass-start text-xs"></i>
        </div>
        <p class="text-xl font-black text-amber-600 dark:text-amber-400">{{ number_format($stats['pending'] ?? 0) }}</p>
        <p class="text-[9px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-wider mt-1">Pending (Baru)</p>
    </div>

    {{-- Status Menunggu / Waiting List --}}
    <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-4 shadow-2xs border border-slate-200 dark:border-slate-700/80 text-center cursor-help transition hover:shadow-md" title="Permohonan yang dimasukkan ke dalam antrian daftar tunggu kuota magang.">
        <div class="w-8 h-8 rounded-xl bg-yellow-50 dark:bg-yellow-950/60 text-yellow-600 dark:text-yellow-400 flex items-center justify-center mx-auto mb-2 border border-yellow-100 dark:border-yellow-900/50">
            <i class="fas fa-clock text-xs"></i>
        </div>
        <p class="text-xl font-black text-yellow-600 dark:text-yellow-400">{{ number_format($stats['menunggu'] ?? 0) }}</p>
        <p class="text-[9px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-wider mt-1">Daftar Tunggu</p>
    </div>

    {{-- Status Diterima --}}
    <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-4 shadow-2xs border border-slate-200 dark:border-slate-700/80 text-center cursor-help transition hover:shadow-md" title="Permohonan yang telah disetujui / diterima dan sedang aktif menjalani kegiatan magang.">
        <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-2 border border-emerald-100 dark:border-emerald-900/50">
            <i class="fas fa-check-circle text-xs"></i>
        </div>
        <p class="text-xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($stats['diterima'] ?? 0) }}</p>
        <p class="text-[9px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-wider mt-1">Diterima / Aktif</p>
    </div>

    {{-- Status Ditolak --}}
    <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-4 shadow-2xs border border-slate-200 dark:border-slate-700/80 text-center cursor-help transition hover:shadow-md" title="Permohonan yang tidak lolos seleksi berkas atau kuota penuh.">
        <div class="w-8 h-8 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-2 border border-rose-100 dark:border-rose-900/50">
            <i class="fas fa-times-circle text-xs"></i>
        </div>
        <p class="text-xl font-black text-rose-600 dark:text-rose-400">{{ number_format($stats['ditolak'] ?? 0) }}</p>
        <p class="text-[9px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-wider mt-1">Ditolak</p>
    </div>

    {{-- Status Selesai --}}
    <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-4 shadow-2xs border border-slate-200 dark:border-slate-700/80 text-center cursor-help transition hover:shadow-md" title="Peserta yang telah menuntaskan seluruh periode magang secara tuntas.">
        <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-2 border border-blue-100 dark:border-blue-900/50">
            <i class="fas fa-graduation-cap text-xs"></i>
        </div>
        <p class="text-xl font-black text-blue-600 dark:text-blue-400">{{ number_format($stats['selesai'] ?? 0) }}</p>
        <p class="text-[9px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-wider mt-1">Selesai Magang</p>
    </div>
</div>
