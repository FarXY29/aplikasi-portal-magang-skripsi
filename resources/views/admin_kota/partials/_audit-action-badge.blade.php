@php
    $actionClass = match(true) {
        Str::contains($action, 'create') => 'bg-green-100 dark:bg-green-950/40 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800',
        Str::contains($action, 'update') => 'bg-blue-100 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
        Str::contains($action, 'delete') => 'bg-red-100 dark:bg-red-950/40 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800',
        default => 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700',
    };
@endphp

<span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $actionClass }}">
    {{ strtoupper($action) }}
</span>
