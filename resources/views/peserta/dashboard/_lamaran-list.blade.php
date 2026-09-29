{{-- Riwayat Lamaran: filter bar, card list, empty state. --}}
<div class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-2xs border border-slate-200/80 dark:border-gray-700 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-slate-200/80 dark:border-gray-700 bg-slate-50/80 dark:bg-gray-900 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h3 class="text-base font-bold text-slate-900 dark:text-gray-100 flex items-center gap-2">
            <i class="fas fa-briefcase text-teal-700 dark:text-teal-400"></i> Riwayat Lamaran
        </h3>

        {{-- Form Filter Riwayat --}}
        <form action="{{ route('peserta.dashboard') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2 sm:gap-3">
            {{-- Filter Status --}}
            <div class="w-full sm:w-auto">
                <select name="status" onchange="this.form.submit()" class="w-full sm:w-auto min-h-[44px] text-xs rounded-xl border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100 focus:border-teal-600 focus:ring-teal-600 py-2 pl-3 pr-8 cursor-pointer shadow-2xs font-bold [color-scheme:dark]">
                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Daftar Tunggu</option>
                    <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-start">
                {{-- Filter Tanggal Awal --}}
                <div class="flex items-center gap-1.5 flex-1 sm:flex-initial">
                    <span class="text-[11px] sm:text-[10px] font-bold text-slate-500 dark:text-gray-400 uppercase">Dari:</span>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()"
                           class="w-full sm:w-auto min-h-[44px] text-xs rounded-xl border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100 focus:border-teal-600 focus:ring-teal-600 py-2 sm:py-1.5 px-2.5 cursor-pointer shadow-2xs font-bold [color-scheme:dark]">
                </div>

                {{-- Filter Tanggal Akhir --}}
                <div class="flex items-center gap-1.5 flex-1 sm:flex-initial">
                    <span class="text-[11px] sm:text-[10px] font-bold text-slate-500 dark:text-gray-400 uppercase">S/D:</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()"
                           class="w-full sm:w-auto min-h-[44px] text-xs rounded-xl border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100 focus:border-teal-600 focus:ring-teal-600 py-2 sm:py-1.5 px-2.5 cursor-pointer shadow-2xs font-bold [color-scheme:dark]">
                </div>

                @if(request('status') || request('start_date') || request('end_date'))
                    <a href="{{ route('peserta.dashboard') }}" class="min-w-[44px] min-h-[44px] flex items-center justify-center p-2 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 hover:bg-rose-100 border border-rose-200/80 dark:border-rose-800/60 rounded-xl transition shrink-0" title="Reset Filter">
                        <i class="fas fa-times text-xs"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="p-4 sm:p-6 space-y-4">
        @php
            $badges = [
                'pending' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200/70 dark:border-amber-800/60',
                'menunggu' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200/70 dark:border-amber-800/60',
                'diterima' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200/70 dark:border-emerald-800/60',
                'belum mulai' => 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-800 dark:text-indigo-300 border-indigo-200/70 dark:border-indigo-800/60',
                'selesai' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border-blue-200/70 dark:border-blue-800/60',
                'ditolak' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border-rose-200/70 dark:border-rose-800/60',
            ];
        @endphp
        @forelse($myApplications as $app)
            @include('peserta.dashboard._lamaran-card', ['app' => $app, 'badges' => $badges])
        @empty
            <x-ui.empty-state
                title="Tidak Ada Lamaran"
                description="Tidak ada lamaran magang yang ditemukan atau sesuai dengan filter pencarian Anda."
                icon="fa-inbox">
                @if(request('status') || request('start_date') || request('end_date'))
                    <a href="{{ route('peserta.dashboard') }}" class="min-h-[44px] inline-flex items-center px-4 py-2 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 hover:bg-rose-100 border border-rose-200/80 dark:border-rose-800/60 rounded-xl text-xs font-bold transition">Reset Filter</a>
                @else
                    <a href="{{ route('home') }}" class="min-h-[44px] inline-flex items-center px-4 py-2.5 bg-teal-700 text-white hover:bg-teal-800 rounded-xl text-xs font-bold transition shadow-2xs">Cari Lowongan Magang &rarr;</a>
                @endif
            </x-ui.empty-state>
        @endforelse
    </div>
</div>
