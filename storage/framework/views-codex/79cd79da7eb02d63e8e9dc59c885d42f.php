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
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-certificate text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                <?php echo e(__('Penerbitan Sertifikat Kelulusan')); ?>

            </h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div>
                <a href="<?php echo e(route('dinas.peserta.index')); ?>" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Daftar Peserta
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 p-6 flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                        <div class="flex-shrink-0">
                            <?php if($app->user->profile_photo_path): ?>
                                <img src="<?php echo e(Storage::url($app->user->profile_photo_path)); ?>" class="w-24 h-24 rounded-full object-cover border-4 border-teal-50 dark:border-teal-950/60 shadow-xs">
                            <?php else: ?>
                                <div class="w-24 h-24 rounded-full bg-teal-50 dark:bg-teal-950/60 flex items-center justify-center text-teal-600 dark:text-teal-300 text-3xl font-black border-4 border-teal-100 dark:border-teal-900/60 shadow-xs">
                                    <?php echo e(strtoupper(substr($app->user->name, 0, 1))); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 truncate"><?php echo e($app->user->name); ?></h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 truncate"><?php echo e($app->user->email); ?></p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-800/60">
                                <?php echo e($app->position->judul_posisi); ?>

                            </span>
                            <div class="mt-4 text-xs text-gray-600 dark:text-gray-400 font-semibold flex items-center justify-center sm:justify-start gap-2">
                                <i class="far fa-calendar-alt text-teal-500"></i>
                                <?php echo e(\Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d M Y')); ?> — 
                                <?php echo e(\Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d M Y')); ?>

                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-teal-50/50 dark:bg-teal-950/30 flex justify-between items-center">
                            <h4 class="font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2 text-sm">
                                <i class="fas fa-clipboard-check text-teal-600 dark:text-teal-400"></i> Verifikasi Nilai Akhir
                            </h4>
                            <div class="text-2xl font-black text-teal-600 dark:text-teal-400">
                                <?php echo e(round($app->nilai_rata_rata ?? $app->avg_nilai ?? 0, 1)); ?>

                                <span class="text-xs font-medium text-gray-400 dark:text-gray-500">/100</span>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs font-medium">
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-2xl border border-gray-100 dark:border-gray-700/60 flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Kerajinan</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->nilai_kerajinan ?? '-'); ?></span>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-2xl border border-gray-100 dark:border-gray-700/60 flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Disiplin</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->nilai_disiplin ?? '-'); ?></span>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-2xl border border-gray-100 dark:border-gray-700/60 flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Adaptasi</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->nilai_adaptasi ?? '-'); ?></span>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-2xl border border-gray-100 dark:border-gray-700/60 flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Kreatifitas</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->nilai_kreatifitas ?? '-'); ?></span>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-2xl border border-gray-100 dark:border-gray-700/60 flex justify-between col-span-1 md:col-span-2">
                                    <span class="text-gray-600 dark:text-gray-400">Skill dan Pengetahuan</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($app->nilai_skill_pengetahuan ?? '-'); ?></span>
                                </div>
                            </div>
                            
                            <div class="p-4 bg-amber-50 dark:bg-amber-950/40 rounded-2xl border border-amber-200 dark:border-amber-900/60 text-xs text-amber-800 dark:text-amber-300 flex items-start gap-2.5">
                                <i class="fas fa-info-circle text-amber-600 dark:text-amber-400 mt-0.5 text-sm"></i>
                                <p class="leading-relaxed">Pastikan semua nilai di atas sudah benar sebelum menerbitkan sertifikat. Sertifikat yang sudah diterbitkan akan menggunakan nilai ini secara permanen.</p>
                            </div>
                        </div>
                    </div>

                </div>

                
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-8">
                        <div class="bg-gradient-to-r from-teal-600 to-teal-700 p-5 text-white">
                            <h3 class="font-bold text-base flex items-center gap-2">
                                <i class="fas fa-file-signature"></i> Legalisasi Sertifikat
                            </h3>
                            <p class="text-teal-100 text-xs mt-1">Isi nomor registrasi dan tanggal penerbitan.</p>
                        </div>
                        
                        <div class="p-6">
                            <form action="<?php echo e(route('dinas.sertifikat.store', $app->id)); ?>" method="POST" target="_blank" class="space-y-5">
                                <?php echo csrf_field(); ?>
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Nomor Sertifikat</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                            <i class="fas fa-barcode text-sm"></i>
                                        </div>
                                        <input type="text" name="nomor_sertifikat" value="<?php echo e(old('nomor_sertifikat', $app->nomor_sertifikat ?? $autoNumber)); ?>" required
                                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-teal-500 focus:border-teal-500 text-xs font-bold"
                                            placeholder="Contoh: 001/MAGANG/DINAS/2026">
                                    </div>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">*Nomor ini akan tercetak secara sah di sertifikat.</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Tanggal Terbit</label>
                                    <input type="date" name="tanggal_sertifikat" value="<?php echo e(old('tanggal_sertifikat', date('Y-m-d'))); ?>" required
                                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-teal-500 focus:border-teal-500 text-xs font-bold [color-scheme:dark]">
                                </div>

                                <hr class="border-gray-100 dark:border-gray-700">

                                <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['class' => 'w-full justify-center py-3 text-xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-full justify-center py-3 text-xs']); ?>
                                    <i class="fas fa-file-pdf mr-2 text-sm"></i> Simpan & Generate PDF
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                                
                                <p class="text-center text-[11px] text-gray-400 dark:text-gray-500">
                                    File PDF akan otomatis terbuka di tab baru.
                                </p>
                            </form>
                        </div>
                    </div>
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
<?php endif; ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\admin_instansi\sertifikat\create.blade.php ENDPATH**/ ?>