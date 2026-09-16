@props([
    'title' => '',
    'icon' => 'fas fa-book-open',
    'applications' => [],
    'app' => null,
    'routeName' => '',
])

<div class="flex flex-col md:flex-row justify-between items-center gap-4">
    <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
            <i class="{{ $icon }} text-teal-600 dark:text-teal-400 text-lg"></i>
        </div>
        {{ __($title) }}
    </h2>
    <x-pembimbing.student-switcher :applications="$applications" :current="$app" :route-name="$routeName" />
</div>
