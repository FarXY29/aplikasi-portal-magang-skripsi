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
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Pengaturan Tanda Tangan Sertifikat')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-blue-50 dark:bg-blue-950/20 border-l-4 border-blue-500 p-4 mb-6 rounded-r shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            Data yang Anda isi di sini akan otomatis muncul pada bagian <strong>"Mengetahui" (Tanda Tangan Kiri)</strong> di Transkrip Nilai dan Sertifikat Magang.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    
                    <form action="<?php echo e(route('dinas.pejabat.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="grid grid-cols-1 gap-6">
                            
                            <div>
                                <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">
                                    Jabatan Penandatangan
                                </label>
                                <input type="text" name="jabatan_pejabat" 
                                       value="<?php echo e(old('jabatan_pejabat', $instansi->jabatan_pejabat)); ?>"
                                       placeholder="Contoh: Kabid. Aplikasi Informatika / Kepala Dinas"
                                       class="w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-teal-500 focus:ring-teal-500 transition" 
                                       required>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jabatan ini akan muncul di baris pertama tanda tangan.</p>
                            </div>

                            <div>
                                <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">
                                    Nama Lengkap Pejabat
                                </label>
                                <input type="text" name="nama_pejabat" 
                                       value="<?php echo e(old('nama_pejabat', $instansi->nama_pejabat)); ?>"
                                       class="w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-teal-500 focus:ring-teal-500 transition" 
                                       required>
                            </div>

                            <div>
                                <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">
                                    NIP (Nomor Induk Pegawai)
                                </label>
                                <input type="text" name="nip_pejabat" 
                                       value="<?php echo e(old('nip_pejabat', $instansi->nip_pejabat)); ?>"
                                       class="w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-teal-500 focus:ring-teal-500 transition" 
                                       required>
                            </div>

                        </div>

                        <div class="mt-8 flex items-center justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-teal-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-teal-700 active:bg-teal-900 focus:outline-none focus:border-teal-900 focus:ring ring-teal-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-lg shadow-teal-100">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
            
            <div class="mt-8">
                <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-4">Preview Tanda Tangan:</h3>
                <div class="bg-gray-100 dark:bg-gray-800 p-8 rounded-xl border border-gray-200 dark:border-gray-700 flex justify-center">
                    <div class="text-center bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-300 dark:border-gray-600 w-1/2">
                        <p>Mengetahui,</p>
                        <p class="font-bold mb-8"><?php echo e($instansi->jabatan_pejabat ?? 'Nama Jabatan'); ?></p>
                        
                        <div class="h-16"></div> 
                        
                        <p class="font-bold underline"><?php echo e($instansi->nama_pejabat ?? 'Nama Pejabat'); ?></p>
                        <p>NIP. <?php echo e($instansi->nip_pejabat ?? '....................'); ?></p>
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
<?php endif; ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\admin_instansi\profil\edit_pejabat.blade.php ENDPATH**/ ?>