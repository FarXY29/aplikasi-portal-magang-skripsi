@props([
    'printRoute' => null,
    'backRoute' => null,
])

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 print:hidden">
    <a href="{{ $backRoute ?? route('admin.laporan.hub') }}"
        class="group inline-flex items-center gap-2 text-xs md:text-sm font-bold text-slate-500 dark:text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
        <span class="w-8 h-8 rounded-xl bg-white dark:bg-[#161f33] border border-slate-200 dark:border-slate-800/60 flex items-center justify-center shadow-sm group-hover:border-teal-500/50 group-hover:shadow-teal-500/10 transition">
            <i class="fas fa-arrow-left text-xs"></i>
        </span>
        Kembali ke Pusat Laporan
    </a>

    @if($printRoute)
        <div class="flex flex-wrap gap-2">
            <a href="{{ $printRoute }}" target="_blank" title="Download PDF"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-[#161f33] text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800/60 hover:border-teal-500/50 hover:text-teal-700 dark:hover:text-teal-300 rounded-xl font-bold text-xs shadow-sm hover:shadow-md hover:shadow-teal-500/10 transition active:scale-95">
                <i class="fas fa-file-pdf text-rose-500"></i>
                Download PDF
            </a>
        </div>
    @endif
</div>
