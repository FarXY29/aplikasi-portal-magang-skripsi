<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                        <i class="fas fa-calendar-alt text-teal-600 dark:text-teal-400 text-lg"></i>
                    </div>
                    Riwayat Absensi Peserta
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">
                    Memantau rincian kehadiran peserta magang: <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->user->name); ?></span>
                </p>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('dinas.peserta.absensi.pdf', $app->id)); ?>" target="_blank" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 border border-transparent rounded-xl text-white text-xs font-bold transition shadow-xs flex items-center uppercase tracking-wider">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 print:hidden">
                <a href="<?php echo e(route('dinas.peserta.index')); ?>" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Daftar Peserta
                </a>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Tepat Waktu</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono"><?php echo e($stats['tepat_waktu'] ?? 0); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-emerald-800/60 flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-lg shadow-xs">
                        <i class="fas fa-check"></i>
                    </div>
                </div>

                <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Terlambat</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono"><?php echo e($stats['terlambat'] ?? 0); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 border border-amber-100 dark:border-amber-800/60 flex items-center justify-center text-amber-600 dark:text-amber-400 text-lg shadow-xs">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>

                <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Izin / Sakit</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono"><?php echo e($stats['izin'] ?? 0); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/60 border border-blue-100 dark:border-blue-800/60 flex items-center justify-center text-blue-600 dark:text-blue-400 text-lg shadow-xs">
                        <i class="fas fa-file-medical"></i>
                    </div>
                </div>

                <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Alpha</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono"><?php echo e($stats['alpha'] ?? 0); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-800/60 flex items-center justify-center text-rose-600 dark:text-rose-400 text-lg shadow-xs">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>

            
            <div class="glass-panel hover-lift rounded-3xl overflow-hidden">
                
                <div class="p-6 border-b border-gray-100/50 dark:border-gray-700/50 flex flex-col md:flex-row justify-between md:items-center gap-4 bg-white/30 dark:bg-gray-900/30">
                    <h3 class="font-bold text-gray-800 dark:text-gray-100 text-base flex items-center gap-2">
                        <i class="fas fa-list-check text-teal-600 dark:text-teal-400"></i> Daftar Kehadiran Peserta
                    </h3>
                    
                    <form action="" method="GET" class="flex items-center gap-2">
                        <div class="relative">
                            <i class="fas fa-calendar absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500 text-xs"></i>
                            <select name="bulan" onchange="this.form.submit()" class="pl-9 pr-8 py-2 text-xs font-bold border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 rounded-xl shadow-xs cursor-pointer [color-scheme:dark]">
                                <option value="" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">Semua Periode</option>
                                <option value="01" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('bulan') == '01' ? 'selected' : ''); ?>>Januari</option>
                                <option value="02" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('bulan') == '02' ? 'selected' : ''); ?>>Februari</option>
                                <option value="03" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('bulan') == '03' ? 'selected' : ''); ?>>Maret</option>
                                <option value="04" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('bulan') == '04' ? 'selected' : ''); ?>>April</option>
                                <option value="05" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('bulan') == '05' ? 'selected' : ''); ?>>Mei</option>
                                <option value="06" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('bulan') == '06' ? 'selected' : ''); ?>>Juni</option>
                                <option value="07" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('bulan') == '07' ? 'selected' : ''); ?>>Juli</option>
                                <option value="08" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('bulan') == '08' ? 'selected' : ''); ?>>Agustus</option>
                                <option value="09" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('bulan') == '09' ? 'selected' : ''); ?>>September</option>
                                <option value="10" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('bulan') == '10' ? 'selected' : ''); ?>>Oktober</option>
                                <option value="11" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('bulan') == '11' ? 'selected' : ''); ?>>November</option>
                                <option value="12" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" <?php echo e(request('bulan') == '12' ? 'selected' : ''); ?>>Desember</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Masuk</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Pulang</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catatan / Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                            <?php $__empty_1 = true; $__currentLoopData = $absensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="table-row-hover border-b border-gray-100/50 dark:border-gray-700/50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            <?php echo e(\Carbon\Carbon::parse($log->date)->isoFormat('dddd, D MMMM Y')); ?>

                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-medium">Hari ke-<?php echo e($loop->iteration); ?></span>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <?php if($log->clock_in): ?>
                                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-mono font-bold text-gray-800 dark:text-gray-200">
                                            <?php echo e(\Carbon\Carbon::parse($log->clock_in)->format('H:i')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-400 dark:text-gray-500">-</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <?php if($log->clock_out): ?>
                                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-mono font-bold text-gray-800 dark:text-gray-200">
                                            <?php echo e(\Carbon\Carbon::parse($log->clock_out)->format('H:i')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs text-rose-600 dark:text-rose-400 italic bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-900/40 px-2.5 py-0.5 rounded-md font-bold">Belum Pulang</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <?php if($log->status == 'hadir'): ?>
                                        <?php if($log->clock_in > '08:00:00'): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60 gap-1.5">
                                                <i class="fas fa-exclamation-triangle text-[10px]"></i> Terlambat
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 gap-1.5">
                                                <i class="fas fa-check-circle text-[10px]"></i> Tepat Waktu
                                            </span>
                                        <?php endif; ?>
                                    <?php elseif($log->status == 'izin'): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60 gap-1.5">
                                            <i class="fas fa-info-circle text-[10px]"></i> Izin
                                        </span>
                                    <?php elseif($log->status == 'sakit'): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60 gap-1.5">
                                            <i class="fas fa-procedures text-[10px]"></i> Sakit
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60 gap-1.5">
                                            <i class="fas fa-times text-[10px]"></i> Alpha
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-xs sm:text-sm text-gray-800 dark:text-gray-200 font-medium leading-relaxed">
                                        <?php echo e($log->description ?? '-'); ?>

                                    </div>
                                    
                                    <?php if($log->proof_file): ?>
                                        <?php if(Str::endsWith(strtolower($log->proof_file), '.pdf')): ?>
                                            <a href="<?php echo e(route('storage.access', ['type' => 'attendance', 'filename' => basename($log->proof_file)])); ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-teal-50 hover:bg-teal-100 dark:bg-teal-950/40 dark:hover:bg-teal-900/60 border border-teal-200 dark:border-teal-800 text-teal-700 dark:text-teal-300 rounded-xl text-xs font-bold transition shadow-xs mt-1.5">
                                                <i class="fas fa-file-pdf text-rose-500 text-sm"></i> Lihat Dokumen PDF
                                            </a>
                                        <?php else: ?>
                                            <div class="relative group w-full h-32 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 cursor-pointer shadow-sm mt-1.5" onclick="openImageModal('<?php echo e(route('storage.access', ['type' => 'attendance', 'filename' => basename($log->proof_file)])); ?>')">
                                                <img src="<?php echo e(route('storage.access', ['type' => 'attendance', 'filename' => basename($log->proof_file)])); ?>" class="w-full h-full object-cover transition transform group-hover:scale-110 duration-500">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition flex items-end p-4">
                                                    <span class="text-white text-xs font-bold bg-black/60 backdrop-blur-sm px-3 py-1.5 rounded-full"><i class="fas fa-expand-alt mr-1.5"></i> Perbesar Foto</span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                            <i class="far fa-calendar-times text-3xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <p class="font-bold text-gray-700 dark:text-gray-300 text-sm">Belum Ada Data Absensi</p>
                                        <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">Belum ada catatan kehadiran untuk peserta pada periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-100/50 dark:border-gray-700/50 bg-white/30 dark:bg-gray-900/30">
                    <?php echo e($absensi->links()); ?>

                </div>
            </div>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\admin_instansi\peserta\absensi.blade.php ENDPATH**/ ?>