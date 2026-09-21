{{-- Collapsible Top 3 Podium Leaderboard --}}
@if($podium->count() > 0)
<div class="bg-white dark:bg-[#161f33] rounded-2xl md:rounded-3xl p-5 md:p-6 shadow-lg border border-slate-200 dark:border-slate-800/40 transition-all duration-300 cursor-help" x-data="{ showTop3: false }"
    title="Peringkat 3 alumni peserta magang dengan pencapaian nilai akhir evaluasi tertinggi se-Kota Banjarmasin.">
    <div class="flex items-center justify-between cursor-pointer select-none" @click="showTop3 = !showTop3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 md:w-11 md:h-11 rounded-2xl bg-gradient-to-br from-amber-300 to-amber-500 text-white flex items-center justify-center shadow-md shadow-amber-500/30 shrink-0">
                <i class="fas fa-trophy text-base md:text-lg"></i>
            </div>
            <div>
                <h3 class="text-sm md:text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
                    TOP 3 PERFORMER TERBAIK KOTA
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Apresiasi khusus untuk peserta magang dengan pencapaian performa tertinggi se-Kota Banjarmasin.</p>
            </div>
        </div>
        <button type="button" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition shrink-0">
            <span x-text="showTop3 ? 'Sembunyikan Podium' : 'Tampilkan TOP 3'"></span>
            <i class="fas fa-chevron-down text-xs transition-transform duration-300" :class="showTop3 ? 'rotate-180 text-teal-500' : ''"></i>
        </button>
    </div>

    <div x-show="showTop3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak class="mt-6 border-t border-slate-100 dark:border-slate-800/60 pt-6">
        <div class="flex flex-col md:flex-row items-end justify-center gap-6 md:gap-4 max-w-4xl mx-auto pt-4">

            {{-- Juara 2 (Perak) --}}
            @if($podium->count() > 1)
            @php $p2 = $podium[1]; @endphp
            <div class="w-full md:w-1/3 order-2 md:order-1 flex flex-col items-center">
                <div class="relative mb-3 flex flex-col items-center">
                    <div class="h-16 w-16 rounded-full bg-gradient-to-br from-slate-200 to-slate-400 flex items-center justify-center text-slate-700 font-black border-4 border-white dark:border-slate-700 shadow-lg text-lg relative">
                        {{ strtoupper(substr($p2['nama'], 0, 2)) }}
                        <span class="absolute -top-2 -right-1 w-6 h-6 rounded-full bg-slate-400 text-white text-[10px] font-bold flex items-center justify-center border-2 border-white dark:border-slate-800 shadow">2</span>
                    </div>
                </div>
                <div class="text-center mb-2">
                    <p class="font-bold text-slate-800 dark:text-slate-200 text-sm truncate max-w-[180px]">{{ $p2['nama'] }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate max-w-[180px] font-semibold">{{ $p2['asal_instansi'] }}</p>
                    <p class="text-[9px] text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/25 px-2 py-0.5 rounded-full inline-block font-bold mt-1">{{ $p2['instansi'] }}</p>
                </div>
                <div class="w-full bg-gradient-to-t from-slate-100 to-slate-200/60 dark:from-slate-900 dark:to-slate-800/60 rounded-t-2xl pt-8 pb-4 text-center border-t border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-center items-center h-28">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">SKOR AKHIR</span>
                    <span class="text-2xl font-black text-slate-700 dark:text-slate-200 font-mono mt-1">{{ $p2['rata_rata'] }}</span>
                    <span class="text-[9px] font-extrabold text-slate-500 dark:text-slate-400 mt-1 uppercase">{{ $p2['predikat'] }}</span>
                </div>
            </div>
            @endif

            {{-- Juara 1 (Emas) --}}
            @if($podium->count() > 0)
            @php $p1 = $podium[0]; @endphp
            <div class="w-full md:w-1/3 order-1 md:order-2 flex flex-col items-center transform md:-translate-y-4">
                <div class="relative mb-3 flex flex-col items-center">
                    <div class="absolute -top-7 text-amber-500 text-2xl drop-shadow">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-amber-300 to-amber-500 flex items-center justify-center text-white font-black border-4 border-white dark:border-slate-700 shadow-lg shadow-amber-500/40 text-xl relative">
                        {{ strtoupper(substr($p1['nama'], 0, 2)) }}
                        <span class="absolute -top-1 -right-1 w-7 h-7 rounded-full bg-amber-500 text-white text-[11px] font-black flex items-center justify-center border-2 border-white dark:border-slate-800 shadow">1</span>
                    </div>
                </div>
                <div class="text-center mb-2">
                    <p class="font-black text-slate-900 dark:text-white text-base truncate max-w-[200px]">{{ $p1['nama'] }}</p>
                    <p class="text-xs text-slate-700 dark:text-slate-300 truncate max-w-[200px] font-bold">{{ $p1['asal_instansi'] }}</p>
                    <p class="text-[10px] text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/25 px-2.5 py-0.5 rounded-full inline-block font-extrabold mt-1">{{ $p1['instansi'] }}</p>
                </div>
                <div class="w-full bg-gradient-to-t from-amber-50 to-amber-100 dark:from-amber-950/40 dark:to-amber-900/20 rounded-t-2xl pt-10 pb-6 text-center border-t-2 border-amber-300 dark:border-amber-700 shadow flex flex-col justify-center items-center h-36">
                    <span class="text-xs font-black text-amber-800 dark:text-amber-300 uppercase tracking-wider">SKOR AKHIR</span>
                    <span class="text-3xl font-black text-amber-700 dark:text-amber-400 font-mono mt-1">{{ $p1['rata_rata'] }}</span>
                    <span class="text-[10px] font-black text-amber-600 dark:text-amber-300 mt-1 uppercase">{{ $p1['predikat'] }}</span>
                </div>
            </div>
            @endif

            {{-- Juara 3 (Perunggu) --}}
            @if($podium->count() > 2)
            @php $p3 = $podium[2]; @endphp
            <div class="w-full md:w-1/3 order-3 md:order-3 flex flex-col items-center">
                <div class="relative mb-3 flex flex-col items-center">
                    <div class="h-16 w-16 rounded-full bg-gradient-to-br from-orange-300 to-amber-600 flex items-center justify-center text-white font-black border-4 border-white dark:border-slate-700 shadow-lg text-lg relative">
                        {{ strtoupper(substr($p3['nama'], 0, 2)) }}
                        <span class="absolute -top-2 -right-1 w-6 h-6 rounded-full bg-amber-600 text-white text-[10px] font-bold flex items-center justify-center border-2 border-white dark:border-slate-800 shadow">3</span>
                    </div>
                </div>
                <div class="text-center mb-2">
                    <p class="font-bold text-slate-800 dark:text-slate-200 text-sm truncate max-w-[180px]">{{ $p3['nama'] }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate max-w-[180px] font-semibold">{{ $p3['asal_instansi'] }}</p>
                    <p class="text-[9px] text-teal-600 dark:text-teal-300 bg-teal-50 dark:bg-teal-500/10 border border-teal-100 dark:border-teal-500/25 px-2 py-0.5 rounded-full inline-block font-bold mt-1">{{ $p3['instansi'] }}</p>
                </div>
                <div class="w-full bg-gradient-to-t from-orange-50/60 to-orange-100/40 dark:from-orange-950/40 dark:to-orange-900/20 rounded-t-2xl pt-8 pb-4 text-center border-t border-orange-200 dark:border-orange-900/40 shadow-sm flex flex-col justify-center items-center h-24">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">SKOR AKHIR</span>
                    <span class="text-xl font-black text-amber-700 dark:text-amber-400 font-mono mt-1">{{ $p3['rata_rata'] }}</span>
                    <span class="text-[9px] font-extrabold text-orange-700 dark:text-orange-400 mt-1 uppercase">{{ $p3['predikat'] }}</span>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endif
