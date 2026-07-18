@props(['disabled' => false, 'icon' => null])

@if($icon)
    <div class="relative transition-all duration-300 group-focus-within:drop-shadow-sm w-full">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-teal-500 transition-colors">
            <i class="{{ $icon }}"></i>
        </div>
        <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 focus:border-teal-500 focus:ring focus:ring-teal-500/20 rounded-xl focus:bg-white transition-all text-sm font-medium text-gray-800 placeholder-gray-400']) !!}>
    </div>
@else
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-teal-500 focus:ring focus:ring-teal-500/20 rounded-xl focus:bg-white transition-all text-sm font-medium text-gray-800 placeholder-gray-400']) !!}>
@endif
