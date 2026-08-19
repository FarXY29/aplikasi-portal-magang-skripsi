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
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-briefcase text-teal-600 dark:text-teal-400"></i>
                <?php echo e(__('Kelola Lowongan Magang')); ?>

            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400 font-medium bg-white dark:bg-gray-800 px-4 py-1.5 rounded-full shadow-sm border border-gray-100 dark:border-gray-700">
                Total Posisi: <span class="font-bold text-teal-600 dark:text-teal-400"><?php echo e($lowongans->count()); ?></span>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col gap-4 mb-6 print:hidden">
                <div class="flex justify-between items-center">
                    <a href="<?php echo e(route('dinas.dashboard')); ?>" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                        <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-sm">
                            <i class="fas fa-arrow-left text-xs"></i>
                        </div>
                        Kembali ke Dashboard
                    </a>
                </div>

                <?php if (isset($component)) { $__componentOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14f469cfc51ebc3cb5d7fb0ffa2702ed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.filter-bar','data' => ['action' => route('dinas.lowongan.index'),'resetUrl' => request()->hasAny(['search', 'status']) ? route('dinas.lowongan.index') : null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.filter-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dinas.lowongan.index')),'resetUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->hasAny(['search', 'status']) ? route('dinas.lowongan.index') : null)]); ?>
                    <div class="flex-grow min-w-[200px]">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari posisi, syarat, atau deskripsi..." class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-sm font-medium">
                    </div>

                    <div class="min-w-[150px]">
                        <select name="status" class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 focus:border-teal-500 focus:ring-teal-500 py-2 pl-3 pr-8 cursor-pointer shadow-sm font-bold">
                            <option value="">Semua Status</option>
                            <option value="buka" <?php echo e(request('status') == 'buka' ? 'selected' : ''); ?>>Buka</option>
                            <option value="tutup" <?php echo e(request('status') == 'tutup' ? 'selected' : ''); ?>>Tutup</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center ml-auto pl-4 border-l border-gray-100 dark:border-gray-700">
                        <a href="<?php echo e(route('dinas.lowongan.create')); ?>" class="flex items-center px-4 py-2 bg-teal-600 dark:bg-teal-500 text-white rounded-xl font-bold hover:bg-teal-700 dark:hover:bg-teal-600 shadow-sm transition transform active:scale-95 text-xs uppercase tracking-wider">
                            <i class="fas fa-plus mr-2 text-[10px]"></i> Buat Lowongan
                        </a>
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

            <?php if(session('success')): ?>
                <?php if (isset($component)) { $__componentOriginal746de018ded8594083eb43be3f1332e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal746de018ded8594083eb43be3f1332e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alert','data' => ['type' => 'success','class' => 'mb-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'success','class' => 'mb-4']); ?>
                    <?php echo e(session('success')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal746de018ded8594083eb43be3f1332e1)): ?>
<?php $attributes = $__attributesOriginal746de018ded8594083eb43be3f1332e1; ?>
<?php unset($__attributesOriginal746de018ded8594083eb43be3f1332e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal746de018ded8594083eb43be3f1332e1)): ?>
<?php $component = $__componentOriginal746de018ded8594083eb43be3f1332e1; ?>
<?php unset($__componentOriginal746de018ded8594083eb43be3f1332e1); ?>
<?php endif; ?>
            <?php endif; ?>

            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/3">Posisi & Syarat</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kuota & Deadline</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-5 py-3.5 text-right text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60">
                            <?php $__empty_1 = true; $__currentLoopData = $lowongans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="table-row hover:bg-teal-50/15 dark:hover:bg-gray-900/60 transition duration-150 group">
                                
                                <td class="px-5 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-1 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition">
                                            <?php echo e($loker->judul_posisi); ?>

                                        </span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-2" title="<?php echo e($loker->deskripsi); ?>">
                                            <?php echo e($loker->deskripsi); ?>

                                        </p>
                                        <div class="flex items-center gap-1.5 text-[10px] text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-100 dark:border-teal-900/40 px-2.5 py-1 rounded-lg w-fit font-bold">
                                            <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400"></i> <?php echo e(Str::limit($loker->required_major, 30)); ?>

                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center text-sm font-bold text-gray-800 dark:text-gray-200">
                                            <i class="fas fa-users mr-2 text-gray-400 dark:text-gray-500"></i> <?php echo e($loker->kuota); ?> <span class="text-xs font-normal text-gray-500 dark:text-gray-400 ml-1">orang</span>
                                        </div>
                                        <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                            <i class="far fa-calendar-alt mr-2 text-gray-400 dark:text-gray-500"></i> 
                                            <?php echo e(\Carbon\Carbon::parse($loker->batas_daftar)->format('d M Y')); ?>

                                        </div>
                                        
                                        <?php
                                            $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($loker->batas_daftar), false);
                                        ?>
                                        <?php if($daysLeft >= 0): ?>
                                            <span class="text-[10px] text-green-600 dark:text-green-400 font-bold mt-1">Sisa <?php echo e(ceil($daysLeft)); ?> hari lagi</span>
                                        <?php else: ?>
                                            <span class="text-[10px] text-red-500 dark:text-red-400 font-bold mt-1">Pendaftaran Tutup</span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <?php if($loker->status == 'buka'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 dark:bg-green-950/60 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span> Buka
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 dark:bg-red-950/60 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800">
                                            Tutup
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="<?php echo e(route('dinas.lowongan.edit', $loker->id)); ?>" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition shadow-xs" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="<?php echo e(route('dinas.lowongan.destroy', $loker->id)); ?>" method="POST" @submit.prevent="$dispatch('open-confirm', { message: 'Hapus lowongan ini? Data tidak dapat dikembalikan.', onConfirm: () => $el.submit() })">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 transition shadow-xs" title="Hapus Lowongan">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-4 text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-700">
                                            <i class="fas fa-briefcase text-3xl"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Belum Ada Lowongan</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Buat lowongan magang pertama Anda sekarang.</p>
                                        <a href="<?php echo e(route('dinas.lowongan.create')); ?>" class="mt-4 text-teal-600 dark:text-teal-400 font-bold text-sm hover:underline">
                                            + Tambah Lowongan
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
<?php endif; ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\admin_instansi\lowongan\index.blade.php ENDPATH**/ ?>