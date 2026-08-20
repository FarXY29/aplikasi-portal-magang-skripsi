<div class="flex items-center gap-2">
    <a href="<?php echo e(route('admin.users.edit', $user->id)); ?>" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-indigo-600 hover:bg-indigo-50 hover:border-indigo-300 transition shadow-sm">
        <i class="fas fa-edit"></i>
    </a>

    <?php if(auth()->id() != $user->id): ?>
        <form action="<?php echo e(route('admin.users.destroy', $user->id)); ?>" method="POST" @submit.prevent="$dispatch('open-confirm', { message: 'Yakin ingin menghapus user ini?', onConfirm: () => $el.submit() })">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button type="submit" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-red-500 hover:bg-red-50 hover:border-red-300 transition shadow-sm">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    <?php endif; ?>
</div><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\admin_kota\users\partials\action-buttons.blade.php ENDPATH**/ ?>