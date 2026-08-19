<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3 md:gap-4">
    
    <a href="<?php echo e(route('admin.instansi.index')); ?>" class="group block relative overflow-hidden rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 bg-white dark:bg-[#161f33] p-4 md:p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-teal-500/40 hover:shadow-teal-500/10">
        <div class="flex flex-wrap items-start justify-between gap-2">
            <div class="min-w-0">
                <p class="text-[10px] md:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">INSTANSI</p>
                <h3 id="stat-instansi" class="mt-2 text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white"><?php echo e(number_format($totalInstansi)); ?></h3>
                <p class="mt-1 text-[10px] md:text-xs font-semibold text-slate-500 dark:text-slate-400">Total terdaftar</p>
            </div>
            <div class="flex h-10 w-10 md:h-12 md:w-12 shrink-0 items-center justify-center rounded-xl bg-teal-500 text-white shadow-md shadow-teal-500/30 transition-transform duration-300 group-hover:scale-110">
                <i class="fas fa-building text-base md:text-lg"></i>
            </div>
        </div>
    </a>

    
    <a href="<?php echo e(route('admin.users.index')); ?>" class="group block relative overflow-hidden rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 bg-white dark:bg-[#161f33] p-4 md:p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-blue-500/40 hover:shadow-blue-500/10">
        <div class="flex flex-wrap items-start justify-between gap-2">
            <div class="min-w-0">
                <p class="text-[10px] md:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">PENGGUNA</p>
                <h3 id="stat-pengguna" class="mt-2 text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white"><?php echo e(number_format($totalUser)); ?></h3>
                <p class="mt-1 text-[10px] md:text-xs font-semibold text-slate-500 dark:text-slate-400">Semua role</p>
            </div>
            <div class="flex h-10 w-10 md:h-12 md:w-12 shrink-0 items-center justify-center rounded-xl bg-blue-500 text-white shadow-md shadow-blue-500/30 transition-transform duration-300 group-hover:scale-110">
                <i class="fas fa-users text-base md:text-lg"></i>
            </div>
        </div>
    </a>

    
    <a href="<?php echo e(route('admin.laporan.peserta_global', ['status' => 'semua'])); ?>" class="group block relative overflow-hidden rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 bg-white dark:bg-[#161f33] p-4 md:p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-purple-500/40 hover:shadow-purple-500/10">
        <div class="flex flex-wrap items-start justify-between gap-2">
            <div class="min-w-0">
                <p class="text-[10px] md:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">PENDAFTAR</p>
                <h3 id="stat-pendaftar" class="mt-2 text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white"><?php echo e(number_format($totalApplications)); ?></h3>
                <p class="stat-period-subtitle mt-1 text-[10px] md:text-xs font-semibold text-slate-500 dark:text-slate-400"><?php echo e($periodText); ?></p>
            </div>
            <div class="flex h-10 w-10 md:h-12 md:w-12 shrink-0 items-center justify-center rounded-xl bg-purple-500 text-white shadow-md shadow-purple-500/30 transition-transform duration-300 group-hover:scale-110">
                <i class="fas fa-file-signature text-base md:text-lg"></i>
            </div>
        </div>
    </a>

    
    <a href="<?php echo e(route('admin.laporan.peserta_global', ['status' => 'diterima'])); ?>" class="group block relative overflow-hidden rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 bg-white dark:bg-[#161f33] p-4 md:p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500/40 hover:shadow-emerald-500/10">
        <div class="flex flex-wrap items-start justify-between gap-2">
            <div class="min-w-0">
                <p class="text-[10px] md:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">AKTIF</p>
                <h3 id="stat-aktif" class="mt-2 text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white"><?php echo e(number_format($activeInterns)); ?></h3>
                <p class="stat-period-subtitle mt-1 text-[10px] md:text-xs font-semibold text-slate-500 dark:text-slate-400"><?php echo e($periodText); ?></p>
            </div>
            <div class="flex h-10 w-10 md:h-12 md:w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-md shadow-emerald-500/30 transition-transform duration-300 group-hover:scale-110">
                <i class="fas fa-user-check text-base md:text-lg"></i>
            </div>
        </div>
    </a>

    
    <a href="<?php echo e(route('admin.laporan.peserta_global', ['status' => 'selesai'])); ?>" class="group block relative overflow-hidden rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 bg-white dark:bg-[#161f33] p-4 md:p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-indigo-500/40 hover:shadow-indigo-500/10">
        <div class="flex flex-wrap items-start justify-between gap-2">
            <div class="min-w-0">
                <p class="text-[10px] md:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">SELESAI</p>
                <h3 id="stat-selesai" class="mt-2 text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white"><?php echo e(number_format($completedInterns)); ?></h3>
                <p class="stat-period-subtitle mt-1 text-[10px] md:text-xs font-semibold text-slate-500 dark:text-slate-400"><?php echo e($periodText); ?></p>
            </div>
            <div class="flex h-10 w-10 md:h-12 md:w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-500 text-white shadow-md shadow-indigo-500/30 transition-transform duration-300 group-hover:scale-110">
                <i class="fas fa-graduation-cap text-base md:text-lg"></i>
            </div>
        </div>
    </a>

    
    <a href="<?php echo e(route('admin.laporan.peserta_global', ['status' => 'pending'])); ?>" class="group block relative overflow-hidden rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800/40 bg-white dark:bg-[#161f33] p-4 md:p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-amber-500/40 hover:shadow-amber-500/10">
        <div class="flex flex-wrap items-start justify-between gap-2">
            <div class="min-w-0">
                <p class="text-[10px] md:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">PENDING</p>
                <h3 id="stat-pending" class="mt-2 text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white"><?php echo e(number_format($pendingApplications)); ?></h3>
                <p class="stat-period-subtitle mt-1 text-[10px] md:text-xs font-semibold text-slate-500 dark:text-slate-400"><?php echo e($periodText); ?></p>
            </div>
            <div class="flex h-10 w-10 md:h-12 md:w-12 shrink-0 items-center justify-center rounded-xl bg-amber-500 text-white shadow-md shadow-amber-500/30 transition-transform duration-300 group-hover:scale-110">
                <i class="fas fa-hourglass-half text-base md:text-lg"></i>
            </div>
        </div>
    </a>
</div>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\admin_kota\dashboard\_stats-grid.blade.php ENDPATH**/ ?>