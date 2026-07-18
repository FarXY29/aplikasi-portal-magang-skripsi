<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-teal-600 hover:bg-teal-700 active:bg-teal-800 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all active:scale-95 ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
