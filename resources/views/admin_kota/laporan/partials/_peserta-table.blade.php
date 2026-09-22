{{-- Tabel Utama Data Peserta --}}
<div class="bg-white dark:bg-[#161f33] rounded-2xl md:rounded-3xl shadow-lg border border-slate-200 dark:border-slate-800/40 overflow-hidden">
    <div class="p-5 md:p-6 border-b border-slate-100 dark:border-slate-800/60 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/60 dark:bg-slate-900/40">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-2xl bg-teal-100 dark:bg-teal-500/15 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/25 flex items-center justify-center shrink-0">
                <i class="fas fa-list text-base"></i>
            </div>
            <div>
                <h3 class="text-sm md:text-base font-black text-slate-900 dark:text-white">Daftar Peserta Magang</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Rekapitulasi informasi peserta magang secara menyeluruh.</p>
            </div>
        </div>

        {{-- Client-Side Quick Search Bar --}}
        <div class="w-full sm:w-64 relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-slate-500 pointer-events-none">
                <i class="fas fa-search text-xs"></i>
            </span>
            <input type="text" x-model="quickSearch" placeholder="Cari nama/sekolah..."
                class="w-full pl-9 pr-8 py-2 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-teal-500 focus:border-teal-500 shadow-sm transition">
            <button type="button" x-show="quickSearch !== ''" @click="quickSearch = ''" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                <i class="fas fa-times-circle text-xs"></i>
            </button>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full divide-y divide-slate-100 dark:divide-slate-800/60">
            <thead class="bg-slate-50 dark:bg-slate-900">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-12">No</th>
                    <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[180px]">Nama Peserta</th>
                    <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[220px]">Asal Sekolah / Kampus</th>
                    <th class="px-6 py-4 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[220px]">Dinas & Posisi</th>
                    <th class="px-6 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[190px]">Periode Magang</th>
                    <th class="px-6 py-4 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-28">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-[#161f33] divide-y divide-slate-100 dark:divide-slate-800/60 text-sm">
                @forelse($allInterns as $data)
                <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-500/5 transition duration-150"
                    x-show="quickSearch === '' ||
                            @js(strtolower($data->user->name ?? '')).includes(quickSearch.toLowerCase()) ||
                            @js(strtolower($data->user->asal_instansi ?? '')).includes(quickSearch.toLowerCase()) ||
                            @js(strtolower($data->position->instansi->nama_dinas ?? '')).includes(quickSearch.toLowerCase()) ||
                            @js(strtolower($data->position->judul_posisi ?? '')).includes(quickSearch.toLowerCase())">

                    <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-400 dark:text-slate-500">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-6 py-4 min-w-[180px] max-w-[240px]">
                        <div class="text-sm font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $data->user->name ?? '-' }}">{{ $data->user->name ?? '-' }}</div>
                        <div class="text-xs text-slate-400 dark:text-slate-500 font-medium truncate mt-0.5">{{ $data->user->email ?? '-' }}</div>
                    </td>

                    <td class="px-6 py-4 min-w-[220px] max-w-[280px]">
                        <div class="inline-flex items-start gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-500/25 leading-snug">
                            <i class="fas fa-university text-[10px] mt-0.5 shrink-0"></i>
                            <span class="line-clamp-2" title="{{ $data->user->asal_instansi ?? '-' }}">{{ $data->user->asal_instansi ?? '-' }}</span>
                        </div>
                    </td>

                    <td class="px-6 py-4 min-w-[220px] max-w-[280px]">
                        <div class="text-xs font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $data->position->instansi->nama_dinas ?? '-' }}">{{ $data->position->instansi->nama_dinas ?? '-' }}</div>
                        <div class="text-xs text-teal-600 dark:text-teal-400 font-medium mt-1 leading-snug line-clamp-2" title="{{ $data->position->judul_posisi ?? '-' }}">{{ $data->position->judul_posisi ?? '-' }}</div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-bold text-slate-700 dark:text-slate-300">
                        @if($data->tanggal_mulai && $data->tanggal_selesai)
                            <span class="px-3 py-1 bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/60 rounded-xl font-mono inline-block">
                                {{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d M Y') }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400 dark:text-slate-500 italic">Tanggal belum ditentukan</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @include('admin_kota.laporan.partials._application-status-badge', ['status' => $data->status])
                    </td>
                </tr>
                @empty
                <x-ui.empty-state
                    :colspan="6"
                    icon="fas fa-user-circle"
                    title="Tidak Ada Data Peserta"
                    subtitle="Belum ada data peserta yang sesuai dengan filter pilihan Anda." />
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View (<md) --}}
    <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800/60">
        @forelse($allInterns as $data)
        <div class="p-4 space-y-3.5 hover:bg-slate-50 dark:hover:bg-slate-900/40 transition"
            x-show="quickSearch === '' ||
                    @js(strtolower($data->user->name ?? '')).includes(quickSearch.toLowerCase()) ||
                    @js(strtolower($data->user->asal_instansi ?? '')).includes(quickSearch.toLowerCase()) ||
                    @js(strtolower($data->position->instansi->nama_dinas ?? '')).includes(quickSearch.toLowerCase()) ||
                    @js(strtolower($data->position->judul_posisi ?? '')).includes(quickSearch.toLowerCase())">

            {{-- header row: iteration + name + status badge --}}
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-start gap-3 min-w-0">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 shrink-0 pt-0.5">{{ $loop->iteration }}</span>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $data->user->name ?? '-' }}">{{ $data->user->name ?? '-' }}</div>
                        <div class="text-xs text-slate-400 dark:text-slate-500 font-medium truncate mt-0.5">{{ $data->user->email ?? '-' }}</div>
                    </div>
                </div>
                @include('admin_kota.laporan.partials._application-status-badge', ['status' => $data->status, 'extraClass' => 'shrink-0'])
            </div>

            {{-- detail block: key-value mini grid --}}
            <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-2xl border border-slate-100 dark:border-slate-800/60 space-y-2.5">
                <div class="flex items-start gap-2">
                    <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-24 shrink-0 pt-0.5">Sekolah</span>
                    <div class="inline-flex items-start gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-teal-50 dark:bg-teal-500/10 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-500/25 leading-snug">
                        <i class="fas fa-university text-[10px] mt-0.5 shrink-0"></i>
                        <span class="line-clamp-2" title="{{ $data->user->asal_instansi ?? '-' }}">{{ $data->user->asal_instansi ?? '-' }}</span>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-24 shrink-0 pt-0.5">Dinas</span>
                    <span class="text-xs font-bold text-slate-900 dark:text-white leading-snug line-clamp-2" title="{{ $data->position->instansi->nama_dinas ?? '-' }}">{{ $data->position->instansi->nama_dinas ?? '-' }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-24 shrink-0 pt-0.5">Posisi</span>
                    <span class="text-xs text-teal-600 dark:text-teal-400 font-medium leading-snug line-clamp-2" title="{{ $data->position->judul_posisi ?? '-' }}">{{ $data->position->judul_posisi ?? '-' }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider w-24 shrink-0 pt-0.5">Periode</span>
                    @if($data->tanggal_mulai && $data->tanggal_selesai)
                        <span class="px-2.5 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg font-mono text-[11px] inline-block font-bold text-slate-700 dark:text-slate-300">
                            {{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d M Y') }}
                        </span>
                    @else
                        <span class="text-xs text-slate-400 dark:text-slate-500 italic">Tanggal belum ditentukan</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <x-ui.empty-state
            :mobile="true"
            icon="fas fa-user-circle"
            title="Tidak Ada Data Peserta"
            subtitle="Belum ada data peserta yang sesuai dengan filter pilihan Anda." />
        @endforelse
    </div>

    @if($allInterns instanceof \Illuminate\Pagination\LengthAwarePaginator && $allInterns->hasPages())
        <div class="p-4 border-t border-slate-100 dark:border-slate-800/60 bg-slate-50 dark:bg-slate-900/40">
            {{ $allInterns->links() }}
        </div>
    @endif
</div>
