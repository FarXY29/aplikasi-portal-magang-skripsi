<button x-data="{ loading: false }" 
        x-on:click="if($el.closest('form') && $el.closest('form').checkValidity()) { loading = true; }"
        :disabled="loading"
        {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 hover:bg-teal-700 active:bg-teal-800 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all active:scale-95 ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed relative overflow-hidden group']) }}>
    
    <span :class="{'opacity-0': loading}" class="flex items-center justify-center transition-opacity duration-200">
        {{ $slot }}
    </span>
    
    <span x-show="loading" x-cloak class="absolute inset-0 flex items-center justify-center text-white">
        <i class="fas fa-circle-notch fa-spin text-sm"></i>
    </span>
</button>
