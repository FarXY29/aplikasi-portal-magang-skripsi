<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-950/60 flex items-center justify-center border border-emerald-200 dark:border-emerald-800/60">
                    <i class="fas fa-clipboard-check text-emerald-600 dark:text-emerald-400 text-lg"></i>
                </div>
                {{ __('Laporan Pendaftaran & Pelacakan Permohonan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('dinas.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-emerald-500 dark:group-hover:border-emerald-400 shadow-xs">
                    <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-emerald-600 dark:group-hover:text-emerald-400"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
        </div>
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 6 Stats Cards Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                {{-- Total Pendaftar --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Total seluruh berkas permohonan pendaftaran magang yang masuk ke instansi.">
                    <div class="w-8 h-8 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-2 border border-emerald-100 dark:border-emerald-900/50">
                        <i class="fas fa-file-signature text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-gray-800 dark:text-gray-100">{{ $stats['total'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Total Pendaftar</p>
                </div>

                {{-- Status Pending --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Permohonan baru yang masih menantikan proses peninjauan / verifikasi awal.">
                    <div class="w-8 h-8 rounded-full bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto mb-2 border border-amber-100 dark:border-amber-900/50">
                        <i class="fas fa-hourglass-start text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-amber-600 dark:text-amber-400">{{ $stats['pending'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Pending (Baru)</p>
                </div>

                {{-- Status Menunggu / Waiting List --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Permohonan yang dimasukkan ke dalam daftar tunggu (waiting list) kuota magang.">
                    <div class="w-8 h-8 rounded-full bg-yellow-50 dark:bg-yellow-950/60 text-yellow-600 dark:text-yellow-400 flex items-center justify-center mx-auto mb-2 border border-yellow-100 dark:border-yellow-900/50">
                        <i class="fas fa-clock text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-yellow-600 dark:text-yellow-400">{{ $stats['menunggu'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Daftar Tunggu</p>
                </div>

                {{-- Status Diterima --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Permohonan yang telah disetujui / diterima oleh instansi dan aktif melaksanakan magang.">
                    <div class="w-8 h-8 rounded-full bg-green-50 dark:bg-green-950/60 text-green-600 dark:text-green-400 flex items-center justify-center mx-auto mb-2 border border-green-100 dark:border-green-900/50">
                        <i class="fas fa-check-circle text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-green-700 dark:text-green-400">{{ $stats['diterima'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Diterima / Aktif</p>
                </div>

                {{-- Status Ditolak --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Permohonan yang tidak disetujui atau ditolak karena kuota penuh atau kualifikasi.">
                    <div class="w-8 h-8 rounded-full bg-red-50 dark:bg-red-950/60 text-red-600 dark:text-red-400 flex items-center justify-center mx-auto mb-2 border border-red-100 dark:border-red-900/50">
                        <i class="fas fa-times-circle text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-red-700 dark:text-red-400">{{ $stats['ditolak'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Ditolak</p>
                </div>

                {{-- Status Selesai --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center cursor-help transition hover:shadow-md" title="Peserta yang telah menuntaskan seluruh periode pelaksanaan program magang.">
                    <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-2 border border-blue-100 dark:border-blue-900/50">
                        <i class="fas fa-graduation-cap text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-blue-700 dark:text-blue-400">{{ $stats['selesai'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Selesai Magang</p>
                </div>
            </div>

            {{-- Highlight Action Banner --}}
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl p-6 text-white shadow-lg shadow-emerald-600/20 flex flex-col sm:flex-row items-center gap-4 cursor-help" title="Ringkasan pelacakan status permohonan magang dan tombol cetak PDF resmi.">
                <div class="w-14 h-14 rounded-2xl bg-white/20 dark:bg-gray-800/30 backdrop-blur-sm flex items-center justify-center text-2xl flex-shrink-0 border border-white/20">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="text-center sm:text-left flex-grow">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-100">Pelacakan & Rekapitulasi Pendaftaran</p>
                    <p class="text-xl font-black mt-0.5">Total {{ $stats['total'] }} Berkas Permohonan Terdata</p>
                    <p class="text-sm text-emerald-100 font-medium">Monitoring pergerakan status pelamar secara real-time di lingkungan dinas/instansi Anda.</p>
                </div>
                @if($applications->count() > 0)
                <div class="sm:ml-auto flex-shrink-0 flex gap-2">
                    <a href="{{ route('dinas.laporan.pendaftaran.print', request()->query()) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 rounded-xl hover:bg-emerald-50 dark:hover:bg-gray-700 transition text-xs font-bold shadow-md border border-white/20 dark:border-gray-700" title="Cetak Berkas Laporan PDF">
                        <i class="fas fa-file-pdf mr-1.5 text-red-500 text-sm"></i> Cetak PDF
                    </a>
                </div>
                @endif
            </div>

            {{-- Card Filter Multi-Parameter --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
                    <h3 class="text-gray-800 dark:text-gray-200 font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                        <i class="fas fa-filter text-emerald-600 dark:text-emerald-400"></i> Filter & Parameter Laporan
                    </h3>
                    @if(request()->anyFilled(['status', 'posisi_id', 'start_date', 'end_date', 'search']))
                        <a href="{{ route('dinas.laporan.pendaftaran') }}" class="text-xs text-red-600 dark:text-red-400 hover:underline font-bold flex items-center gap-1">
                            <i class="fas fa-redo text-[10px]"></i> Reset Filter
                        </a>
                    @endif
                </div>

                <form action="{{ route('dinas.laporan.pendaftaran') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    {{-- Filter Status --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5">Status Permohonan</label>
                        <select name="status" class="w-full border border-gray-300 dark:border-gray-700 rounded-xl text-xs focus:ring-emerald-500 focus:border-emerald-500 shadow-xs cursor-pointer bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 py-2">
                            <option value="" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">Semua Status</option>
                            <option value="pending" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu Review)</option>
                            <option value="menunggu" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Daftar Tunggu (Waiting List)</option>
                            <option value="diterima" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima / Aktif</option>
                            <option value="ditolak" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="selesai" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai / Lulus</option>
                        </select>
                    </div>

                    {{-- Filter Posisi Magang --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5">Posisi Magang</label>
                        <select name="posisi_id" class="w-full border border-gray-300 dark:border-gray-700 rounded-xl text-xs focus:ring-emerald-500 focus:border-emerald-500 shadow-xs cursor-pointer bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 py-2">
                            <option value="" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">Semua Posisi Lowongan</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id }}" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('posisi_id') == $pos->id ? 'selected' : '' }}>{{ $pos->judul_posisi }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Rentang Tanggal Pengajuan --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5">Tgl Pendaftaran</label>
                        <div class="grid grid-cols-2 gap-1.5">
                            <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-xl text-[11px] focus:ring-emerald-500 focus:border-emerald-500 shadow-xs px-2 py-1.5 [color-scheme:light] dark:[color-scheme:dark]" title="Dari Tanggal Pendaftaran">
                            <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-xl text-[11px] focus:ring-emerald-500 focus:border-emerald-500 shadow-xs px-2 py-1.5 [color-scheme:light] dark:[color-scheme:dark]" title="Sampai Tanggal Pendaftaran">
                        </div>
                    </div>

                    {{-- Kolom Pencarian Cepat --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5">Pencarian Cepat</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Nama, NIM, No. Registrasi..."
                                class="w-full pl-9 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-xl text-xs focus:ring-emerald-500 focus:border-emerald-500 shadow-xs py-2">
                        </div>
                    </div>

                    {{-- Tombol Terapkan --}}
                    <div>
                        <x-primary-button class="w-full justify-center py-2 text-xs bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 focus:ring-emerald-500">
                            <i class="fas fa-search mr-1.5"></i> Terapkan Filter
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- Card Tabel Utama --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Daftar Pelacakan Permohonan Magang</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Menampilkan seluruh riwayat pengajuan, nomor registrasi, status verifikasi, dan masa magang.</p>
                    </div>
                    <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-3 py-1 rounded-full border border-emerald-200 dark:border-emerald-800/60">
                        {{ $applications->total() }} Data Ditemukan
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12 whitespace-nowrap">No</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap min-w-[170px]">No. Registrasi & Tgl</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap min-w-[220px]">Pemohon & Asal Institusi</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap min-w-[200px]">Posisi yang Dilamar</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap min-w-[180px]">Periode Magang</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32 whitespace-nowrap">Status Terkini</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                            @forelse($applications as $app)
                            @php
                                $appStatus = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->value : $app->status;
                            @endphp
                            <tr class="hover:bg-emerald-50/15 dark:hover:bg-gray-900/60 transition duration-150">
                                <td class="px-5 py-4 text-xs text-gray-400 dark:text-gray-500 text-center font-bold">
                                    {{ $applications->firstItem() + $loop->index }}
                                </td>
                                
                                {{-- No. Registrasi & Tanggal Lamar --}}
                                <td class="px-5 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-black bg-slate-100 dark:bg-gray-700 text-slate-800 dark:text-gray-200 border border-slate-200 dark:border-gray-600 font-mono w-fit">
                                            {{ $app->nomor_registrasi ?? ('REG-' . $app->id) }}
                                        </span>
                                        <span class="text-[11px] text-gray-500 dark:text-gray-400 font-semibold flex items-center gap-1">
                                            <i class="far fa-calendar-alt text-gray-400"></i>
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
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-full bg-gradient-to-br from-emerald-100 to-teal-200 dark:from-emerald-950/60 dark:to-teal-900/60 flex items-center justify-center text-emerald-700 dark:text-emerald-300 font-bold text-xs border border-emerald-300 dark:border-emerald-800/60 flex-shrink-0 shadow-xs">
                                            {{ strtoupper(substr($app->user->name ?? 'P', 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">{{ $app->user->name ?? '-' }}</div>
                                            <p class="text-[11px] text-emerald-700 dark:text-emerald-400 font-bold truncate">
                                                {{ $app->user->asal_instansi ?? ($app->user->university->name ?? ($app->user->school->name ?? '-')) }}
                                            </p>
                                            <div class="flex items-center gap-2 text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 flex-wrap font-medium">
                                                @if($app->user->nim)
                                                    <span class="font-mono">NIM/NISN: {{ $app->user->nim }}</span>
                                                    <span>•</span>
                                                @endif
                                                <span>{{ $app->user->major ?? ($app->user->jurusan ?? '-') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Posisi yang Dilamar --}}
                                <td class="px-5 py-4">
                                    <div class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $app->position->judul_posisi ?? '-' }}</div>
                                    @if($app->pembimbing_lapangan)
                                        <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                                            <i class="fas fa-user-tie text-[9px] text-teal-600"></i>
                                            PL: <span class="font-bold text-gray-700 dark:text-gray-300">{{ $app->pembimbing_lapangan->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-[9px] text-gray-400 dark:text-gray-500 italic mt-0.5 block">PL: Belum ditugaskan</span>
                                    @endif
                                </td>

                                {{-- Periode Magang --}}
                                <td class="px-5 py-4">
                                    @if($app->tanggal_mulai && $app->tanggal_selesai)
                                        <div class="flex flex-col gap-1">
                                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1">
                                                <i class="far fa-calendar-check text-gray-400 dark:text-gray-500"></i>
                                                {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} &rarr; {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}
                                            </span>
                                            <span class="text-[9px] text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-emerald-900/40 px-2 py-0.5 rounded-md w-fit font-bold font-mono">
                                                {{ \Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai)) }} Hari
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">-</span>
                                    @endif
                                </td>

                                {{-- Status Terkini --}}
                                <td class="px-5 py-4 text-center">
                                    <x-ui.badge :status="$app->status" />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                            <i class="fas fa-inbox text-2xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <p class="text-gray-900 dark:text-gray-100 font-bold">Tidak ada permohonan pendaftaran yang ditemukan</p>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Coba ubah kriteria filter status atau kata kunci pencarian Anda.</p>
                                        <a href="{{ route('dinas.laporan.pendaftaran') }}" class="mt-4 text-emerald-600 dark:text-emerald-400 hover:underline text-sm font-bold">
                                            Reset Semua Filter
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($applications->hasPages())
                <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/60">
                    {{ $applications->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

