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
                <i class="fas fa-tasks text-teal-600"></i>
                <?php echo e(__('Validasi Logbook Peserta')); ?>

            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400 font-medium bg-white dark:bg-gray-800 px-4 py-1.5 rounded-full shadow-sm border border-gray-100 dark:border-gray-700">
                Menunggu Validasi: <span class="font-bold text-yellow-600"><?php echo e($logs->where('status_validasi', 'pending')->count()); ?></span>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50 dark:bg-gray-900/50 min-h-screen font-sans">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center mb-6 print:hidden">
                <a href="<?php echo e(route('pembimbing_lapangan.dashboard')); ?>" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-4">
                <div class="h-16 w-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-2xl shadow-md border-2 border-white">
                    <?php echo e(strtoupper(substr($app->user->name, 0, 1))); ?>

                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100"><?php echo e($app->user->name); ?></h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                        <span><i class="far fa-envelope text-gray-400"></i> <?php echo e($app->user->email); ?></span>
                        <span class="text-gray-300">|</span>
                        <span><i class="fas fa-briefcase text-gray-400"></i> <?php echo e($app->position->judul_posisi); ?></span>
                    </p>
                </div>
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

            <div class="space-y-6">
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition duration-300">
                    <div class="flex flex-col lg:flex-row">
                        
                        <div class="lg:w-1/4 bg-gray-50 dark:bg-gray-900 p-6 flex flex-col items-center justify-center border-b lg:border-b-0 lg:border-r border-gray-100 dark:border-gray-700 text-center">
                            <div class="mb-4">
                                <span class="block text-3xl font-black text-gray-700 dark:text-gray-300"><?php echo e(\Carbon\Carbon::parse($log->tanggal)->format('d')); ?></span>
                                <span class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest"><?php echo e(\Carbon\Carbon::parse($log->tanggal)->translatedFormat('F Y')); ?></span>
                                <span class="block text-[10px] text-gray-400 mt-1"><?php echo e(\Carbon\Carbon::parse($log->tanggal)->translatedFormat('l')); ?></span>
                            </div>
                            
                            <?php if($log->bukti_foto_path): ?>
                                <div class="relative group w-full h-32 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 cursor-pointer shadow-sm" 
                                     onclick="openImageModal('<?php echo e(route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)])); ?>')">
                                    <img src="<?php echo e(route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)])); ?>" class="w-full h-full object-cover transition transform group-hover:scale-110 duration-500">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <span class="text-white text-xs font-bold flex items-center gap-1"><i class="fas fa-search-plus"></i> Zoom</span>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="w-full h-32 bg-gray-200/50 rounded-xl flex flex-col items-center justify-center text-gray-400 text-xs border-2 border-dashed border-gray-300 dark:border-gray-600">
                                    <i class="far fa-image text-2xl mb-1"></i>
                                    <span>Tanpa Foto</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="lg:w-2/4 p-6 flex flex-col">
                            <div class="flex justify-between items-start mb-3 border-b border-gray-50 pb-2">
                                <h4 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Uraian Kegiatan</h4>
                            </div>
                            
                            <div class="text-gray-800 dark:text-gray-200 text-sm leading-relaxed whitespace-pre-line flex-grow font-medium">
                                <?php echo e($log->kegiatan); ?>

                            </div>

                            <?php if($log->komentar_pembimbing_lapangan): ?>
                                <div class="mt-4 bg-yellow-50 p-3 rounded-xl border border-yellow-100 flex gap-3 items-start relative">
                                    <div class="absolute -top-1.5 left-4 w-3 h-3 bg-yellow-50 border-t border-l border-yellow-100 transform rotate-45"></div>
                                    <i class="fas fa-comment-dots text-yellow-500 mt-1"></i>
                                    <div>
                                        <span class="block text-[10px] font-bold text-yellow-700 uppercase mb-0.5">Catatan Anda Sebelumnya</span>
                                        <p class="text-xs text-yellow-800 italic">"<?php echo e($log->komentar_pembimbing_lapangan); ?>"</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="lg:w-1/4 bg-white dark:bg-gray-800 p-6 border-t lg:border-t-0 lg:border-l border-gray-100 dark:border-gray-700 flex flex-col justify-center">
                            
                            <div class="mb-5 text-center">
                                <?php
                                    $statusConfig = [
                                        'disetujui' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200', 'icon' => 'fa-check-circle'],
                                        'revisi'    => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => 'fa-exclamation-circle'],
                                        'pending'   => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-200', 'icon' => 'fa-clock'],
                                    ];
                                    $s = $statusConfig[$log->status_validasi] ?? $statusConfig['pending'];
                                ?>
                                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase border <?php echo e($s['bg']); ?> $s['text'] $s['border'] inline-flex items-center gap-1.5">
                                    <i class="fas <?php echo e($s['icon']); ?>"></i> <?php echo e($log->status_validasi); ?>

                                </span>
                            </div>

                            <form action="<?php echo e(route('pembimbing_lapangan.logbook.validasi', $log->id)); ?>" method="POST" class="space-y-3">
                                <?php echo csrf_field(); ?>
                                
                                <div>
                                    <label class="sr-only">Komentar</label>
                                    <textarea name="komentar" rows="2" 
                                        class="w-full text-xs border-gray-200 dark:border-gray-700 rounded-xl focus:border-indigo-500 focus:ring focus:ring-indigo-100 bg-gray-50 dark:bg-gray-900 placeholder-gray-400 resize-none"
                                        placeholder="Tulis catatan untuk peserta..."></textarea>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="submit" name="status" value="disetujui" 
                                        class="flex items-center justify-center w-full py-2 bg-green-600 text-white rounded-lg text-xs font-bold hover:bg-green-700 transition shadow-sm hover:shadow-md transform active:scale-95">
                                        <i class="fas fa-check mr-1.5"></i> Setuju
                                    </button>
                                    
                                    <button type="submit" name="status" value="revisi" 
                                        class="flex items-center justify-center w-full py-2 bg-white dark:bg-gray-800 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-900/50 rounded-lg text-xs font-bold hover:bg-red-50 dark:hover:bg-red-950/20 transition shadow-sm hover:shadow-md transform active:scale-95">
                                        <i class="fas fa-undo mr-1.5"></i> Revisi
                                    </button>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-600 text-center">
                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-4 text-gray-300">
                        <i class="fas fa-book-open text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Logbook Kosong</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Mahasiswa ini belum mengunggah aktivitas apapun.</p>
                </div>
                <?php endif; ?>
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
<?php endif; ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\admin_instansi\pembimbing_lapangan\logbook.blade.php ENDPATH**/ ?>