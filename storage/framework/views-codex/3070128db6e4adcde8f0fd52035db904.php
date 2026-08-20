
<div class="bg-white dark:bg-gray-800 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-4 md:p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-indigo-600 dark:bg-indigo-500 text-white flex items-center justify-center shadow-sm">
                <i class="fas fa-broadcast-tower text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm md:text-base font-black text-gray-800 dark:text-gray-200">Aktivitas Pendaftaran</h3>
                <p class="text-[10px] md:text-xs text-gray-500 dark:text-gray-400 font-medium mt-0.5">Feed pelamar magang terbaru</p>
            </div>
        </div>
        <a href="<?php echo e(route('admin.laporan.peserta_global', ['status' => 'semua'])); ?>" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-100 dark:border-indigo-800 text-[10px] font-black text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 transition">
            Semua <i class="fas fa-arrow-right text-[9px]"></i>
        </a>
    </div>
    
    <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
        <?php $__empty_1 = true; $__currentLoopData = $recentApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="p-4 md:p-5 flex items-center justify-between feed-item hover:bg-gray-50 dark:hover:bg-gray-900/60 gap-3 md:gap-4 transition">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-300 flex items-center justify-center font-black text-sm shrink-0 border border-indigo-100 dark:border-indigo-900/40">
                    <?php echo e(strtoupper(substr($app->user->name, 0, 1))); ?>

                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate"><?php echo e($app->user->name); ?></p>
                        <span class="text-[10px] text-gray-400 dark:text-gray-500 font-medium truncate hidden sm:inline">(<?php echo e($app->user->asal_instansi); ?>)</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                        <i class="fas fa-arrow-right text-[10px] text-gray-300 dark:text-gray-600 mr-1"></i> 
                        <span class="font-bold text-gray-700 dark:text-gray-300"><?php echo e($app->position->instansi->nama_dinas); ?></span> 
                        <span class="hidden sm:inline">&bull; <span class="italic text-gray-500 dark:text-gray-400"><?php echo e($app->position->judul_posisi); ?></span></span>
                    </p>
                </div>
            </div>
            <div class="text-right shrink-0">
                <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['status' => $app->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($app->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
                <span class="block text-[10px] text-gray-400 dark:text-gray-500 mt-1 font-medium">
                    <i class="far fa-clock text-[9px]"></i> <?php echo e($app->created_at->diffForHumans()); ?>

                </span>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="p-10 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-900 flex items-center justify-center mx-auto mb-3 border border-gray-200 dark:border-gray-700">
                <i class="fas fa-inbox text-2xl text-gray-400 dark:text-gray-500"></i>
            </div>
            <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Belum ada aktivitas lamaran</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Data akan muncul saat ada pendaftaran baru.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\admin_kota\dashboard\_recent-activity.blade.php ENDPATH**/ ?>