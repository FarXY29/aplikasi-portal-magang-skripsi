<section>
    <div class="border-b border-red-100 dark:border-red-900/40 pb-5 mb-6">
        <h3 class="font-semibold text-lg text-red-600 dark:text-red-400 flex items-center gap-2">
            <i class="fas fa-exclamation-triangle text-red-500"></i>
            <?php echo e(__('Hapus Akun Permanen')); ?>

        </h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            <?php echo e(__('Setelah akun Anda dihapus, seluruh sumber daya, logbook, absensi, dan data yang terkait akan terhapus secara permanen.')); ?>

        </p>
    </div>

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 p-5 rounded-xl bg-red-50/60 dark:bg-red-950/30 border border-red-200/60 dark:border-red-900/40">
        <div class="space-y-1 max-w-xl">
            <h4 class="font-bold text-sm text-gray-900 dark:text-gray-100 flex items-center gap-1.5">
                <span><?php echo e(__('Tindakan Tidak Dapat Dibatalkan')); ?></span>
            </h4>
            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                <?php echo e(__('Pastikan Anda telah mengunduh semua data atau sertifikat yang ingin Anda simpan sebelum menghapus akun ini.')); ?>

            </p>
        </div>

        <button
            type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium transition-all focus:outline-none focus:ring-2 focus:ring-red-600/20 text-white h-9 px-4 py-2 bg-red-600 hover:bg-red-700 active:scale-95 shadow-xs shrink-0 cursor-pointer"
        >
            <i class="fas fa-trash-alt text-xs"></i>
            <span><?php echo e(__('Hapus Akun')); ?></span>
        </button>
    </div>

    <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['name' => 'confirm-user-deletion','show' => $errors->userDeletion->isNotEmpty(),'focusable' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'confirm-user-deletion','show' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->userDeletion->isNotEmpty()),'focusable' => true]); ?>
        <form method="post" action="<?php echo e(route('profile.destroy')); ?>" class="p-6 sm:p-7 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
            <?php echo csrf_field(); ?>
            <?php echo method_field('delete'); ?>

            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-950/60 text-red-600 dark:text-red-400 flex items-center justify-center shrink-0">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                        <?php echo e(__('Apakah Anda yakin ingin menghapus akun ini?')); ?>

                    </h2>
                    <p class="text-xs text-red-600 dark:text-red-400 font-medium">Tindakan ini permanen dan tidak dapat dipulihkan.</p>
                </div>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
                <?php echo e(__('Setelah akun Anda dihapus, semua sumber daya dan data akan terhapus selamanya. Silakan masukkan kata sandi Anda untuk mengonfirmasi penghapusan akun ini.')); ?>

            </p>

            <div class="space-y-1.5">
                <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 dark:text-gray-300 select-none" for="delete_password">
                    <span><?php echo e(__('Kata Sandi Konfirmasi')); ?></span> <span class="text-red-500">*</span>
                </label>
                <input
                    id="delete_password"
                    name="password"
                    type="password"
                    class="w-full min-w-0 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 shadow-2xs transition-all outline-none focus:border-red-600 focus:ring-3 focus:ring-red-600/15"
                    placeholder="<?php echo e(__('Masukkan password Anda')); ?>"
                />

                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->userDeletion->get('password'),'class' => 'mt-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->userDeletion->get('password')),'class' => 'mt-1']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
            </div>

            <div class="mt-7 pt-5 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium transition-all border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-2xs hover:bg-gray-50 dark:hover:bg-gray-900 text-gray-700 dark:text-gray-300 h-9 px-4 py-2 cursor-pointer">
                    <?php echo e(__('Batal')); ?>

                </button>

                <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium transition-all focus:outline-none focus:ring-2 focus:ring-red-600/20 text-white h-9 px-5 py-2 bg-red-600 hover:bg-red-700 active:scale-95 shadow-xs cursor-pointer">
                    <i class="fas fa-trash-alt text-xs"></i>
                    <span><?php echo e(__('Hapus Akun Permanen')); ?></span>
                </button>
            </div>
        </form>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
</section>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views/profile/partials/delete-user-form.blade.php ENDPATH**/ ?>