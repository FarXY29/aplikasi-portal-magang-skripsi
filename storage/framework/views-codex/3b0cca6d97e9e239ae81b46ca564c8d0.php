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
    <?php $__env->startPush('head'); ?>
        <?php echo app('Illuminate\Foundation\Vite')('resources/css/peserta.css'); ?>
    <?php $__env->stopPush(); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-history text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                <?php echo e(__('Riwayat Kehadiran (Absensi)')); ?>

            </h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            
            <div class="mb-6 print:hidden">
                <a href="<?php echo e(route('peserta.dashboard')); ?>" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            
            <?php
                $totalRecords = $attendanceSummary['total'];
                $hadirCount   = $attendanceSummary['hadir'];
                $izinCount    = $attendanceSummary['izin'];
                $alpaCount    = $attendanceSummary['alpa'];
            ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Hari</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono"><?php echo e($totalRecords); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/60 border border-teal-100 dark:border-teal-800/60 flex items-center justify-center text-teal-600 dark:text-teal-400 text-lg shadow-xs">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>

                <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Hadir</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono"><?php echo e($hadirCount); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-emerald-800/60 flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-lg shadow-xs">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>

                <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Izin / Sakit</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono"><?php echo e($izinCount); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 border border-amber-100 dark:border-amber-800/60 flex items-center justify-center text-amber-600 dark:text-amber-400 text-lg shadow-xs">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>

                <div class="glass-panel hover-lift p-5 rounded-3xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Alpha</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono"><?php echo e($alpaCount); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-800/60 flex items-center justify-center text-rose-600 dark:text-rose-400 text-lg shadow-xs">
                        <i class="fas fa-user-times"></i>
                    </div>
                </div>
            </div>

            
            <div class="glass-panel hover-lift rounded-3xl overflow-hidden">
                <div class="p-6 border-b border-gray-100/50 dark:border-gray-700/50 bg-white/30 dark:bg-gray-900/30 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                            <i class="fas fa-list-check text-teal-600 dark:text-teal-400"></i> Histori Absen Saya
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-medium">Data absen Anda pada instansi <span class="font-bold text-teal-600 dark:text-teal-400"><?php echo e($application->position->instansi->nama_dinas); ?></span></p>
                    </div>

                    
                    <?php if (isset($component)) { $__componentOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.filter-bar','data' => ['action' => route('peserta.absensi.index'),'resetUrl' => request()->has('month') ? route('peserta.absensi.index') : null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.filter-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('peserta.absensi.index')),'resetUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->has('month') ? route('peserta.absensi.index') : null)]); ?>
                        <div class="flex items-center gap-2 min-w-[200px]">
                            <label for="month" class="text-xs font-bold text-gray-600 dark:text-gray-400 shrink-0"><i class="far fa-calendar-alt text-teal-600 dark:text-teal-400 mr-1"></i> Bulan:</label>
                            <input type="month" id="month" name="month" value="<?php echo e(request('month')); ?>" class="w-full text-xs font-bold rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs [color-scheme:dark]">
                        </div>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed)): ?>
<?php $attributes = $__attributesOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed; ?>
<?php unset($__attributesOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed)): ?>
<?php $component = $__componentOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed; ?>
<?php unset($__componentOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed); ?>
<?php endif; ?>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Masuk</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Pulang</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Durasi</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Validasi & Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                            <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $absen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $durationText = '-';
                                    if ($absen->clock_in && $absen->clock_out) {
                                        $in  = \Carbon\Carbon::parse($absen->clock_in);
                                        $out = \Carbon\Carbon::parse($absen->clock_out);
                                        $diff = $in->diff($out);
                                        $durationText = $diff->h . 'j ' . $diff->i . 'm';
                                    }
                                ?>
                                <tr class="table-row-hover border-b border-gray-100/50 dark:border-gray-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100"><?php echo e(\Carbon\Carbon::parse($absen->date)->translatedFormat('l, d M Y')); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <?php if($absen->status == 'hadir'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60"><i class="fas fa-check-circle text-[10px]"></i>Hadir</span>
                                        <?php elseif($absen->status == 'izin'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60"><i class="fas fa-file-alt text-[10px]"></i>Izin</span>
                                        <?php elseif($absen->status == 'sakit'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60"><i class="fas fa-procedures text-[10px]"></i>Sakit</span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60"><i class="fas fa-times-circle text-[10px]"></i>Alpa</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <?php if($absen->clock_in): ?>
                                            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-mono font-bold text-gray-800 dark:text-gray-200 inline-flex items-center gap-1.5">
                                                <i class="fas fa-sign-in-alt text-teal-600 dark:text-teal-400 text-xs"></i><?php echo e(\Carbon\Carbon::parse($absen->clock_in)->format('H:i')); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <?php if($absen->clock_out): ?>
                                            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-mono font-bold text-gray-800 dark:text-gray-200 inline-flex items-center gap-1.5">
                                                <i class="fas fa-sign-out-alt text-rose-500 text-xs"></i><?php echo e(\Carbon\Carbon::parse($absen->clock_out)->format('H:i')); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-xs text-rose-600 dark:text-rose-400 italic bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-900/40 px-2.5 py-0.5 rounded-md font-bold">Belum Pulang</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-xs text-gray-600 dark:text-gray-400 font-medium">
                                        <?php if($durationText !== '-'): ?>
                                            <span class="inline-flex items-center gap-1 font-bold text-teal-700 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/60 px-2.5 py-1 rounded-xl border border-teal-200 dark:border-teal-800/60"><i class="fas fa-clock text-xs"></i><?php echo e($durationText); ?></span>
                                        <?php else: ?>
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-700 dark:text-gray-300">
                                        <?php if($absen->validation_status == 'disetujui' || $absen->validation_status == 'valid'): ?>
                                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-bold"><i class="fas fa-check-circle"></i> Tervalidasi</span>
                                        <?php elseif($absen->validation_status == 'ditolak'): ?>
                                            <span class="inline-flex items-center gap-1 text-rose-600 dark:text-rose-400 font-bold"><i class="fas fa-times-circle"></i> Ditolak</span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 text-gray-400 dark:text-gray-500 font-bold"><i class="fas fa-clock"></i> Menunggu</span>
                                        <?php endif; ?>
                                        
                                        <?php if($absen->description): ?>
                                            <p class="mt-1 text-xs text-gray-600 dark:text-gray-400 italic">"<?php echo e($absen->description); ?>"</p>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                                <i class="far fa-calendar-times text-3xl text-gray-400 dark:text-gray-500"></i>
                                            </div>
                                            <p class="font-bold text-gray-700 dark:text-gray-300 text-sm">Belum Ada Data Absensi</p>
                                            <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">Tidak ada catatan absensi pada periode yang dipilih.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-t border-gray-100/50 dark:border-gray-700/50 bg-white/30 dark:bg-gray-900/30">
                    <?php echo e($attendances->links()); ?>

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
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views/peserta/absensi/index.blade.php ENDPATH**/ ?>