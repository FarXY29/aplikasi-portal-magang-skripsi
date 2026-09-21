@php
    $scope = $scope ?? 'kota';
    $resetRoute = $scope === 'kota' ? route('admin.laporan.pendaftaran') : route('dinas.laporan.pendaftaran');
@endphp

{{-- Interactive Toolbar: Status Pills Tab & Fast Client-Side Search --}}
<div class="bg-white dark:bg-slate-800/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-2xs border border-slate-200 dark:border-slate-700/80 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
    {{-- 1-Click Status Filter Pills --}}
    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 custom-scrollbar text-xs font-bold">
        <button type="button" 
                @click="statusFilter = 'semua'"
                :class="statusFilter === 'semua' ? 'bg-teal-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-3.5 py-2 rounded-xl transition whitespace-nowrap flex items-center gap-1.5">
            <span>Semua</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="statusFilter === 'semua' ? 'bg-teal-700/80 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300'">{{ $stats['total'] ?? 0 }}</span>
        </button>
        <button type="button" 
                @click="statusFilter = 'pending'"
                :class="statusFilter === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-3.5 py-2 rounded-xl transition whitespace-nowrap flex items-center gap-1.5">
            <span>Pending</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="statusFilter === 'pending' ? 'bg-amber-600 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300'">{{ $stats['pending'] ?? 0 }}</span>
        </button>
        <button type="button" 
                @click="statusFilter = 'menunggu'"
                :class="statusFilter === 'menunggu' ? 'bg-yellow-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-3.5 py-2 rounded-xl transition whitespace-nowrap flex items-center gap-1.5">
            <span>Daftar Tunggu</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="statusFilter === 'menunggu' ? 'bg-yellow-600 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300'">{{ $stats['menunggu'] ?? 0 }}</span>
        </button>
        <button type="button" 
                @click="statusFilter = 'diterima'"
                :class="statusFilter === 'diterima' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-3.5 py-2 rounded-xl transition whitespace-nowrap flex items-center gap-1.5">
            <span>Diterima / Aktif</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="statusFilter === 'diterima' ? 'bg-emerald-700 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300'">{{ $stats['diterima'] ?? 0 }}</span>
        </button>
        <button type="button" 
                @click="statusFilter = 'ditolak'"
                :class="statusFilter === 'ditolak' ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-3.5 py-2 rounded-xl transition whitespace-nowrap flex items-center gap-1.5">
            <span>Ditolak</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="statusFilter === 'ditolak' ? 'bg-rose-700 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300'">{{ $stats['ditolak'] ?? 0 }}</span>
        </button>
        <button type="button" 
                @click="statusFilter = 'selesai'"
                :class="statusFilter === 'selesai' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-3.5 py-2 rounded-xl transition whitespace-nowrap flex items-center gap-1.5">
            <span>Selesai</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="statusFilter === 'selesai' ? 'bg-blue-700 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300'">{{ $stats['selesai'] ?? 0 }}</span>
        </button>
    </div>

    {{-- Live Client-Side Table Filter --}}
    <div class="relative w-full md:w-72 flex-shrink-0">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
            <i class="fas fa-filter text-xs"></i>
        </span>
        <input type="text" 
               x-model="clientSearch"
               placeholder="Filter cepat tabel di layar..." 
               class="w-full pl-9 pr-8 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-teal-500 focus:border-teal-500 shadow-2xs font-medium">
        <button x-show="clientSearch.length > 0" 
                @click="clientSearch = ''" 
                type="button" 
                class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>
</div>

{{-- Card Tabel Utama & Card View Mobile --}}
<div class="bg-white dark:bg-slate-800/90 rounded-2xl sm:rounded-3xl shadow-2xs border border-slate-200 dark:border-slate-700/80 overflow-hidden">
    <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-700/60 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-slate-50/60 dark:bg-slate-900/40">
        <div>
            <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-slate-100">Daftar Pelacakan Permohonan Magang</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Rekapitulasi riwayat pengajuan, nomor registrasi, status verifikasi SKPD, dan masa magang.</p>
        </div>
        <span class="text-xs font-bold text-teal-700 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/60 px-3 py-1 rounded-full border border-teal-200 dark:border-teal-800/60">
            {{ number_format($applications->total()) }} Data Ditemukan
        </span>
    </div>

    {{-- DESKTOP TABULAR VIEW (Hidden on Mobile) --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full divide-y divide-slate-100 dark:divide-slate-700">
            <thead class="bg-slate-50 dark:bg-slate-900/80">
                <tr>
                    <th class="px-4 py-3.5 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-12 whitespace-nowrap">No</th>
                    <th class="px-4 py-3.5 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap min-w-[170px]">No. Registrasi & Tgl</th>
                    <th class="px-4 py-3.5 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap min-w-[220px]">Pemohon & Asal Institusi</th>
                    <th class="px-4 py-3.5 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap min-w-[220px]">
                        @if($scope === 'kota')
                            Dinas & Posisi Magang
                        @else
                            Posisi yang Dilamar & Pembimbing Lapangan
                        @endif
                    </th>
                    <th class="px-4 py-3.5 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap min-w-[170px]">Periode Magang</th>
                    <th class="px-4 py-3.5 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-32 whitespace-nowrap">Status Terkini</th>
                    <th class="px-4 py-3.5 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-24 whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-800/90 divide-y divide-slate-100 dark:divide-slate-700/60 text-sm">
                @forelse($applications as $app)
                @php($appItemJson = $buildAppItem($app))
                <tr x-show="matchesSearch({{ json_encode($appItemJson) }})" 
                    class="hover:bg-teal-50/20 dark:hover:bg-slate-900/60 transition duration-150">
                    <td class="px-4 py-3.5 text-xs text-slate-400 dark:text-slate-500 text-center font-bold">
                        {{ $applications->firstItem() + $loop->index }}
                    </td>
                    
                    {{-- No. Registrasi & Tanggal Lamar --}}
                    <td class="px-4 py-3.5">
                        <div class="flex flex-col gap-1">
                            <span class="px-2.5 py-0.5 rounded-lg text-xs font-black bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-600 font-mono w-fit">
                                {{ $app->nomor_registrasi ?? ('REG-' . $app->id) }}
                            </span>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold flex items-center gap-1">
                                <i class="far fa-calendar-alt text-slate-400"></i>
                                {{ \Carbon\Carbon::parse($app->created_at)->translatedFormat('d M Y, H:i') }}
                            </span>
                            @if($app->is_automatic_placement)
                                <span class="text-[9px] font-bold text-teal-700 dark:text-teal-400 flex items-center gap-1 mt-0.5">
                                    <i class="fas fa-magic text-[8px]"></i> Penempatan Otomatis
                                </span>
                            @endif
                        </div>
                    </td>

                    {{-- Pemohon & Asal Institusi --}}
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-teal-100 to-emerald-200 dark:from-teal-950/60 dark:to-emerald-900/60 flex items-center justify-center text-teal-700 dark:text-teal-300 font-bold text-xs border border-teal-300 dark:border-teal-800/60 flex-shrink-0 shadow-2xs">
                                {{ strtoupper(substr($app->user->name ?? 'P', 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-bold text-slate-900 dark:text-slate-100 truncate">{{ $app->user->name ?? '-' }}</div>
                                <p class="text-[11px] text-teal-700 dark:text-teal-400 font-bold truncate">
                                    {{ $app->user->asal_instansi ?? ($app->user->university->name ?? ($app->user->school->name ?? '-')) }}
                                </p>
                                <div class="flex items-center gap-2 text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 flex-wrap font-medium">
                                    @if($app->user->nim)
                                        <span class="font-mono">NIM/NISN: {{ $app->user->nim }}</span>
                                        <span>•</span>
                                    @endif
                                    <span>{{ $app->user->major ?? ($app->user->jurusan ?? '-') }}</span>
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- Dinas / Posisi yang Dilamar --}}
                    <td class="px-4 py-3.5">
                        @if($scope === 'kota')
                            <div class="text-xs font-bold text-slate-900 dark:text-slate-100">
                                {{ $app->position->instansi->nama_dinas ?? '-' }}
                            </div>
                            <p class="text-[11px] text-teal-700 dark:text-teal-400 font-semibold mt-0.5">
                                {{ $app->position->judul_posisi ?? '-' }}
                            </p>
                        @else
                            <div class="text-xs font-bold text-slate-900 dark:text-slate-100">
                                {{ $app->position->judul_posisi ?? '-' }}
                            </div>
                        @endif

                        @if($app->pembimbing_lapangan)
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1">
                                <i class="fas fa-user-tie text-[9px] text-teal-600"></i>
                                PL: <span class="font-bold text-slate-700 dark:text-slate-300">{{ $app->pembimbing_lapangan->name }}</span>
                            </div>
                        @else
                            <span class="text-[9px] text-slate-400 dark:text-slate-500 italic mt-0.5 block">PL: Belum ditugaskan</span>
                        @endif
                    </td>

                    {{-- Periode Magang --}}
                    <td class="px-4 py-3.5">
                        @if($app->tanggal_mulai && $app->tanggal_selesai)
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-medium text-slate-700 dark:text-slate-300 flex items-center gap-1">
                                    <i class="far fa-calendar-check text-slate-400 dark:text-slate-500"></i>
                                    {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} &rarr; {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}
                                </span>
                                <span class="text-[9px] text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-900/40 px-2 py-0.5 rounded-md w-fit font-bold font-mono">
                                    {{ \Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai)) }} Hari
                                </span>
                            </div>
                        @else
                            <span class="text-xs text-slate-400 dark:text-slate-500 italic">-</span>
                        @endif
                    </td>

                    {{-- Status Terkini --}}
                    <td class="px-4 py-3.5 text-center">
                        <x-ui.badge :status="$app->status" />
                    </td>

                    {{-- Aksi / Detail Pelacakan --}}
                    <td class="px-4 py-3.5 text-center">
                        <button type="button"
                                @click="openDetail({{ json_encode($appItemJson) }})"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 dark:bg-slate-700/80 hover:bg-teal-50 dark:hover:bg-teal-950/60 text-slate-700 dark:text-slate-300 hover:text-teal-700 dark:hover:text-teal-300 border border-slate-200 dark:border-slate-600 rounded-xl text-xs font-bold transition shadow-2xs">
                            <i class="fas fa-search-location text-teal-600 dark:text-teal-400 text-xs"></i>
                            <span>Detail</span>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center justify-center text-slate-400 dark:text-slate-500">
                            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mb-3 border border-slate-200 dark:border-slate-700">
                                <i class="fas fa-inbox text-2xl text-slate-400 dark:text-slate-500"></i>
                            </div>
                            <p class="text-slate-900 dark:text-slate-100 font-bold">Tidak ada permohonan pendaftaran yang ditemukan</p>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Coba sesuaikan kriteria filter, status, atau kata kunci pencarian Anda.</p>
                            <a href="{{ $resetRoute }}" class="mt-4 text-teal-600 dark:text-teal-400 hover:underline text-sm font-bold">
                                Reset Semua Filter
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE CARD VIEW (Visible on Small Screens) --}}
    <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-700/60">
        @forelse($applications as $app)
        @php($appItemJson = $buildAppItem($app))
        <div x-show="matchesSearch({{ json_encode($appItemJson) }})" 
             class="p-4 bg-white dark:bg-slate-800/90 space-y-3">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <span class="px-2 py-0.5 rounded text-[11px] font-black bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-600 font-mono">
                        {{ $app->nomor_registrasi ?? ('REG-' . $app->id) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1">
                        <i class="far fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::parse($app->created_at)->translatedFormat('d M Y, H:i') }}
                    </p>
                </div>
                <div>
                    <x-ui.badge :status="$app->status" />
                </div>
            </div>

            <div>
                <p class="text-sm font-black text-slate-900 dark:text-slate-100">{{ $app->user->name ?? '-' }}</p>
                <p class="text-xs text-teal-700 dark:text-teal-400 font-bold">{{ $app->user->asal_instansi ?? ($app->user->university->name ?? ($app->user->school->name ?? '-')) }}</p>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">NIM: {{ $app->user->nim ?? '-' }} • {{ $app->user->major ?? ($app->user->jurusan ?? '-') }}</p>
            </div>

            <div class="bg-slate-50 dark:bg-slate-900/60 p-2.5 rounded-xl border border-slate-100 dark:border-slate-700/60 text-xs space-y-1">
                @if($scope === 'kota')
                    <p class="text-slate-800 dark:text-slate-200 font-bold flex items-center gap-1.5">
                        <i class="fas fa-building text-teal-600 text-[10px]"></i>
                        {{ $app->position->instansi->nama_dinas ?? '-' }}
                    </p>
                    <p class="text-teal-700 dark:text-teal-400 font-semibold flex items-center gap-1.5">
                        <i class="fas fa-briefcase text-[10px]"></i>
                        {{ $app->position->judul_posisi ?? '-' }}
                    </p>
                @else
                    <p class="text-slate-800 dark:text-slate-200 font-bold flex items-center gap-1.5">
                        <i class="fas fa-briefcase text-teal-600 text-[10px]"></i>
                        {{ $app->position->judul_posisi ?? '-' }}
                    </p>
                    @if($app->pembimbing_lapangan)
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1">
                        <i class="fas fa-user-tie text-[9px] text-teal-600"></i>
                        PL: <span class="font-bold text-slate-700 dark:text-slate-300">{{ $app->pembimbing_lapangan->name }}</span>
                    </p>
                    @endif
                @endif

                @if($app->tanggal_mulai && $app->tanggal_selesai)
                <p class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1 pt-1 border-t border-slate-200 dark:border-slate-700">
                    <i class="far fa-calendar-check text-[10px]"></i>
                    {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}
                </p>
                @endif
            </div>

            <div class="flex items-center justify-end pt-1">
                <button type="button" 
                        @click="openDetail({{ json_encode($appItemJson) }})"
                        class="w-full py-2 bg-teal-50 dark:bg-teal-950/60 hover:bg-teal-100 dark:hover:bg-teal-900/60 text-teal-700 dark:text-teal-300 font-bold text-xs rounded-xl border border-teal-200 dark:border-teal-800/60 flex items-center justify-center gap-1.5 transition">
                    <i class="fas fa-search-location text-xs"></i>
                    <span>Lihat Detail & Pelacakan Status</span>
                </button>
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-slate-400 dark:text-slate-500 text-sm">
            Tidak ada permohonan pendaftaran yang ditemukan.
        </div>
        @endforelse
    </div>

    {{-- Pagination Links --}}
    @if($applications->hasPages())
    <div class="p-5 sm:p-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60">
        {{ $applications->links() }}
    </div>
    @endif
</div>
