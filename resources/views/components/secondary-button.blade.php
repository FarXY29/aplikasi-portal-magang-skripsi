<button x-data="{ loading: false }" 
        x-on:click="if($el.closest('form') && $el.closest('form').checkValidity()) { loading = true; }"
        :disabled="loading"
        {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 hover:border-teal-500 hover:text-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 disabled:opacity-25 transition-all active:scale-95 ease-in-out duration-150 relative overflow-hidden group']) }}>
    
    <span :class="{'opacity-0': loading}" class="flex items-center justify-center transition-opacity duration-200">
        {{ $slot }}
    </span>
    
    <span x-show="loading" x-cloak class="absolute inset-0 flex items-center justify-center text-teal-600">
        <i class="fas fa-circle-notch fa-spin text-sm"></i>
    </span>
</button>
