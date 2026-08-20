<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'Belum Ada Data',
    'description' => 'Data yang Anda cari tidak ditemukan atau belum ditambahkan.',
    'icon' => 'fa-folder-open',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'title' => 'Belum Ada Data',
    'description' => 'Data yang Anda cari tidak ditemukan atau belum ditambahkan.',
    'icon' => 'fa-folder-open',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => 'flex flex-col items-center justify-center p-8 md:p-12 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm text-center max-w-xl mx-auto'])); ?>>
    <div class="w-16 h-16 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 mb-6 ring-8 ring-teal-50/50">
        <i class="fas <?php echo e($icon); ?> text-2xl animate-pulse"></i>
    </div>
    <h3 class="text-lg font-black text-gray-900 dark:text-gray-100 mb-2">
        <?php echo e($title); ?>

    </h3>
    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium leading-relaxed mb-6 max-w-sm">
        <?php echo e($description); ?>

    </p>
    <?php if($slot->isNotEmpty()): ?>
        <div class="flex flex-wrap items-center justify-center gap-3">
            <?php echo e($slot); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\components\ui\empty-state.blade.php ENDPATH**/ ?>