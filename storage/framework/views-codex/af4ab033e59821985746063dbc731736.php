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
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Kelola Pembimbing Lapangan</h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex gap-6">
            
            <!-- Form Tambah Pembimbing Lapangan -->
            <div class="w-1/3">
                <div class="bg-white dark:bg-gray-800 p-6 shadow-sm rounded-lg">
                    <h3 class="font-bold mb-4">Tambah Pegawai (Pembimbing Lapangan)</h3>
                    <form action="<?php echo e(route('dinas.pembimbing_lapangan.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="text-xs font-bold">Nama Pegawai</label>
                            <input type="text" name="name" class="w-full border-gray-300 dark:border-gray-600 rounded text-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="text-xs font-bold">NIP</label>
                            <input type="text" name="nip" class="w-full border-gray-300 dark:border-gray-600 rounded text-sm">
                        </div>
                        <div class="mb-3">
                            <label class="text-xs font-bold">Email Login</label>
                            <input type="email" name="email" class="w-full border-gray-300 dark:border-gray-600 rounded text-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="text-xs font-bold">Password</label>
                            <input type="password" name="password" class="w-full border-gray-300 dark:border-gray-600 rounded text-sm" required>
                        </div>
                        <button class="w-full bg-teal-600 text-white py-2 rounded font-bold hover:bg-teal-700">Simpan Akun</button>
                    </form>
                </div>
            </div>

            <!-- List Pembimbing Lapangan -->
            <div class="w-2/3 bg-white dark:bg-gray-800 p-6 shadow-sm rounded-lg">
                <h3 class="font-bold mb-4">Daftar Pembimbing Lapangan</h3>
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="pb-2">Nama / NIP</th>
                            <th class="pb-2">Email</th>
                            <th class="pb-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pembimbing_lapangan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pembimbing_lapangan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b">
                            <td class="py-3">
                                <div class="font-bold"><?php echo e($pembimbing_lapangan->name); ?></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($pembimbing_lapangan->nik); ?></div>
                            </td>
                            <td><?php echo e($pembimbing_lapangan->email); ?></td>
                            <td>
                                <form action="<?php echo e(route('dinas.pembimbing_lapangan.destroy', $pembimbing_lapangan->id)); ?>" method="POST" @submit.prevent="$dispatch('open-confirm', { message: 'Hapus akun ini?', onConfirm: () => $el.submit() })">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="text-red-500 text-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
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
<?php endif; ?><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\pembimbing_lapangan\index.blade.php ENDPATH**/ ?>