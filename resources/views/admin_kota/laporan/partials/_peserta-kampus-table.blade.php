{{-- Matriks Rekapitulasi per Asal Instansi Pendidikan (Sekolah / Perguruan Tinggi) --}}
<div class="bg-white dark:bg-[#161f33] rounded-2xl md:rounded-3xl shadow-lg border border-slate-200 dark:border-slate-800/40 overflow-hidden">
    <div class="p-5 md:p-6 border-b border-slate-100 dark:border-slate-800/60 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-slate-50/60 dark:bg-slate-900/40">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-2xl bg-indigo-100 dark:bg-indigo-500/15 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/25 flex items-center justify-center shrink-0">
                <i class="fas fa-university text-base"></i>
            </div>
            <div>
                <h3 class="text-sm md:text-base font-black text-slate-900 dark:text-white">Rekapitulasi Asal Instansi Pendidikan</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Distribusi jumlah peserta aktif, selesai, dan total per sekolah atau perguruan tinggi.</p>
            </div>
        </div>
        <span class="text-xs font-bold text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 px-3 py-1 rounded-full border border-indigo-200 dark:border-indigo-800/60">
            {{ isset($rekapKampus) ? $rekapKampus->count() : 0 }} Instansi Pendidikan
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-slate-100 dark:divide-slate-800/60">
            <thead class="bg-slate-50 dark:bg-slate-900">
                <tr>
                    <th class="px-4 py-3 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-12">No</th>
                    <th class="px-4 py-3 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[200px]">Asal Sekolah / Kampus</th>
                    <th class="px-4 py-3 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-32">Peserta Aktif</th>
                    <th class="px-4 py-3 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-32">Peserta Selesai</th>
                    <th class="px-4 py-3 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-32">Pending / Seleksi</th>
                    <th class="px-4 py-3 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-32">Total Peserta</th>
                    <th class="px-4 py-3 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-28">Proporsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium">
                @php
                    $totalAllPeserta = isset($rekapKampus) ? $rekapKampus->sum('total_peserta') : 0;
                @endphp
                @forelse($rekapKampus ?? [] as $kampus)
                    @php
                        $proporsi = $totalAllPeserta > 0 ? round(($kampus->total_peserta / $totalAllPeserta) * 100, 1) : 0;
                    @endphp
                    <tr class="hover:bg-indigo-50/20 dark:hover:bg-slate-900/60 transition">
                        <td class="px-4 py-3 text-center font-bold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-graduation-cap text-indigo-500/70 text-xs"></i>
                            <span>{{ $kampus->asal_instansi }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                {{ $kampus->total_aktif }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20">
                                {{ $kampus->total_selesai }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                                {{ $kampus->total_pending }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center font-black text-slate-800 dark:text-slate-100">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-400 border border-teal-200 dark:border-teal-500/20">
                                {{ $kampus->total_peserta }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-slate-500 font-bold">
                            {{ $proporsi }}%
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                            Tidak ada data asal instansi pendidikan pada periode atau kriteria filter ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if(isset($rekapKampus) && $rekapKampus->isNotEmpty())
                <tfoot class="bg-slate-50/80 dark:bg-slate-900/80 border-t-2 border-slate-200 dark:border-slate-700 text-xs font-black">
                    <tr>
                        <td colspan="2" class="px-4 py-3 text-right uppercase tracking-wider text-slate-600 dark:text-slate-300">
                            Total Akumulatif:
                        </td>
                        <td class="px-4 py-3 text-center text-emerald-600 dark:text-emerald-400">
                            {{ $rekapKampus->sum('total_aktif') }}
                        </td>
                        <td class="px-4 py-3 text-center text-blue-600 dark:text-blue-400">
                            {{ $rekapKampus->sum('total_selesai') }}
                        </td>
                        <td class="px-4 py-3 text-center text-amber-600 dark:text-amber-400">
                            {{ $rekapKampus->sum('total_pending') }}
                        </td>
                        <td class="px-4 py-3 text-center text-teal-600 dark:text-teal-400">
                            {{ $totalAllPeserta }}
                        </td>
                        <td class="px-4 py-3 text-center text-slate-600 dark:text-slate-300">
                            100%
                        </td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>
