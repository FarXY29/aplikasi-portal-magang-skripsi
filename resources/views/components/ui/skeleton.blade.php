@props([
    'type' => 'text', // text, title, avatar, button, card, image
    'class' => ''
])

@php
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
@endphp

<div {{ $attributes->merge(['class' => "$baseClass $typeClasses $class"]) }}></div>
