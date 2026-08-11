@php
    $actionClass = match(true) {
        Str::contains($action, 'create') => 'bg-green-100 text-green-700',
        Str::contains($action, 'update') => 'bg-blue-100 text-blue-700',
        Str::contains($action, 'delete') => 'bg-red-100 text-red-700',
        default => 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300',
    };
@endphp

<span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $actionClass }}">
    {{ strtoupper($action) }}
</span>
