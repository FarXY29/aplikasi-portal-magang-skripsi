<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'breadcrumbs' => [],
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
    'title',
    'breadcrumbs' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="mb-6 md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <?php if(count($breadcrumbs) > 0): ?>
            <nav class="flex text-sm text-gray-500 dark:text-gray-400 mb-2 font-medium" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <div class="flex items-center">
                                <?php if(isset($breadcrumb['url'])): ?>
                                    <a href="<?php echo e($breadcrumb['url']); ?>" class="hover:text-teal-600 transition-colors">
                                        <?php echo e($breadcrumb['label']); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-900 dark:text-gray-100"><?php echo e($breadcrumb['label']); ?></span>
                                <?php endif; ?>
                                
                                <?php if($index < count($breadcrumbs) - 1): ?>
                                    <i class="fas fa-chevron-right text-[10px] mx-2 text-gray-400"></i>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ol>
            </nav>
        <?php endif; ?>
        <h2 class="text-xl font-bold leading-7 text-gray-900 dark:text-gray-100 sm:truncate sm:text-2xl sm:tracking-tight lg:text-3xl">
            <?php echo e($title); ?>

        </h2>
    </div>
    
    <?php if(isset($actions)): ?>
        <div class="mt-4 flex md:mt-0 md:ml-4 gap-3">
            <?php echo e($actions); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\components\ui\page-header.blade.php ENDPATH**/ ?>