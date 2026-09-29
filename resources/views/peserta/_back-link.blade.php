{{--
    Reusable "back" link used across peserta pages.

    Props:
      - $href  (string) : target URL (usually route(...))
      - $label (string) : link text
--}}
@props(['href', 'label'])

<div class="mb-6 print:hidden">
    <a href="{{ $href }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
        <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
            <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
        </div>
        {{ $label }}
    </a>
</div>
