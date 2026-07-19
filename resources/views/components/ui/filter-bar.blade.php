@props([
    'action',
    'method' => 'GET',
    'resetUrl' => null,
])

<form action="{{ $action }}" method="{{ $method }}" {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm mb-6 transition-all hover:shadow-md']) }}>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3 flex-grow">
            <div class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest mr-2 border-r border-gray-200 dark:border-gray-700 pr-4">
                <i class="fas fa-filter text-teal-600"></i>
                <span>Filter</span>
            </div>
            {{ $slot }}
        </div>
        <div class="flex items-center gap-2 flex-shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100 dark:border-gray-700 justify-end">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition shadow-sm hover:shadow active:scale-95">
                <i class="fas fa-search"></i>
                <span>Terapkan</span>
            </button>
            @if($resetUrl)
                <a href="{{ $resetUrl }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 text-gray-600 dark:text-gray-400 text-xs font-bold transition">
                    <i class="fas fa-undo"></i>
                    <span>Reset</span>
                </a>
            @endif
        </div>
    </div>
</form>
