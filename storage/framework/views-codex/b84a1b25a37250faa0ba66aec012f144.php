<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['disabled' => false, 'icon' => null]));

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

foreach (array_filter((['disabled' => false, 'icon' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if($icon): ?>
    <div class="relative transition-all duration-300 group-focus-within:drop-shadow-sm w-full">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-teal-500 transition-colors">
            <i class="<?php echo e($icon); ?>"></i>
        </div>
        <input <?php echo e($disabled ? 'disabled' : ''); ?> <?php echo $attributes->merge(['class' => 'w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-900 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 dark:border-gray-700 focus:border-teal-500 focus:ring focus:ring-teal-500/20 rounded-xl focus:bg-white dark:focus:bg-gray-800 dark:focus:bg-gray-800 transition-all text-sm font-medium text-gray-800 dark:text-gray-200 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500']); ?>>
    </div>
<?php else: ?>
    <input <?php echo e($disabled ? 'disabled' : ''); ?> <?php echo $attributes->merge(['class' => 'w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 dark:border-gray-700 focus:border-teal-500 focus:ring focus:ring-teal-500/20 rounded-xl focus:bg-white dark:focus:bg-gray-800 dark:focus:bg-gray-800 transition-all text-sm font-medium text-gray-800 dark:text-gray-200 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500']); ?>>
<?php endif; ?>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views/components/text-input.blade.php ENDPATH**/ ?>