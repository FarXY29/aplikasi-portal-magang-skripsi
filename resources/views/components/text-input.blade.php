@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl shadow-xs transition duration-150 text-sm font-medium bg-gray-50 focus:bg-white']) !!}>
