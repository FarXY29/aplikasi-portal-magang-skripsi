<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-white dark:bg-gray-800 border border-slate-300 dark:border-gray-600 rounded-xl font-bold text-xs text-slate-700 dark:text-gray-300 uppercase tracking-widest shadow-2xs hover:bg-slate-50 dark:hover:bg-gray-700 hover:border-teal-700 hover:text-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2 disabled:opacity-25 transition-all active:scale-95 ease-in-out duration-150 relative overflow-hidden group cursor-pointer']) }}>
    {{ $slot }}
</button>
