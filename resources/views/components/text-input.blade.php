@props(['disabled' => false, 'icon' => null])

@if($icon)
    <div class="relative transition-all duration-300 group-focus-within:drop-shadow-sm w-full">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-teal-600 transition-colors">
            <i class="{{ $icon }}"></i>
        </div>
        <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full pl-11 pr-4 py-3 bg-white dark:bg-gray-900 border border-slate-300 dark:border-gray-700 focus:border-teal-600 focus:ring focus:ring-teal-600/20 rounded-xl focus:bg-white dark:focus:bg-gray-800 transition-all text-sm font-medium text-slate-800 dark:text-gray-100 placeholder-slate-400 dark:placeholder-gray-500 shadow-2xs']) !!}>
    </div>
@else
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full px-4 py-3 bg-white dark:bg-gray-900 border border-slate-300 dark:border-gray-700 focus:border-teal-600 focus:ring focus:ring-teal-600/20 rounded-xl focus:bg-white dark:focus:bg-gray-800 transition-all text-sm font-medium text-slate-800 dark:text-gray-100 placeholder-slate-400 dark:placeholder-gray-500 shadow-2xs']) !!}>
@endif

