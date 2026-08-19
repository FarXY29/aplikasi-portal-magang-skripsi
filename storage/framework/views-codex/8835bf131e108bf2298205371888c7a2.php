<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'text', // text, title, avatar, button, card, image
    'class' => ''
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
    'type' => 'text', // text, title, avatar, button, card, image
    'class' => ''
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $baseClass = 'animate-pulse bg-gray-200/80 rounded-xl';
    
    $typeClasses = match($type) {
        'text' => 'h-4 w-3/4 rounded',
        'text-long' => 'h-4 w-full rounded',
        'title' => 'h-6 w-1/2 rounded-lg',
        'avatar' => 'h-12 w-12 rounded-full',
        'avatar-lg' => 'h-16 w-16 rounded-full',
        'button' => 'h-10 w-32 rounded-xl',
        'image' => 'h-48 w-full rounded-2xl',
        'card' => 'h-32 w-full rounded-2xl',
        default => 'h-4 w-full rounded'
    };
?>

<div <?php echo e($attributes->merge(['class' => "$baseClass $typeClasses $class"])); ?>></div>
<?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\components\ui\skeleton.blade.php ENDPATH**/ ?>