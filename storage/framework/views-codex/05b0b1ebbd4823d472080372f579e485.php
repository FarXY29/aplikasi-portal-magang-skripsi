<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'action',
    'method' => 'GET',
    'resetUrl' => null,
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
    'action',
    'method' => 'GET',
    'resetUrl' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<form action="<?php echo e($action); ?>" method="<?php echo e($method); ?>" <?php echo e($attributes->merge(['class' => 'bg-white dark:bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm mb-6 transition-all hover:shadow-md'])); ?>>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3 flex-grow">
            <div class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest mr-2 border-r border-gray-200 dark:border-gray-700 pr-4">
                <i class="fas fa-filter text-teal-600"></i>
                <span>Filter</span>
            </div>
            <?php echo e($slot); ?>

        </div>
        <div class="flex items-center gap-2 flex-shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100 dark:border-gray-700 justify-end">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition shadow-sm hover:shadow active:scale-95">
                <i class="fas fa-search"></i>
                <span>Terapkan</span>
            </button>
            <?php if($resetUrl): ?>
                <a href="<?php echo e($resetUrl); ?>" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 text-gray-600 dark:text-gray-400 text-xs font-bold transition">
                    <i class="fas fa-undo"></i>
                    <span>Reset</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</form>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views/components/ui/filter-bar.blade.php ENDPATH**/ ?>