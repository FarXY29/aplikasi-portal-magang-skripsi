<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-users-cog text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Rekapitulasi Global Peserta Magang') }}
            </h2>
            <div class="flex items-center gap-2">
                @if(request()->anyFilled(['instansi', 'instansi_id', 'status', 'start_date', 'end_date', 'posisi']))
                    <a href="{{ route('admin.laporan.peserta_global') }}" class="px-3.5 py-1.5 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60 hover:bg-rose-100 dark:hover:bg-rose-900/60 rounded-xl font-bold text-xs transition flex items-center gap-1.5 shadow-xs">
                        <i class="fas fa-redo-alt text-[10px]"></i> Reset Filter
                    </a>
                @endif
                @if($stats['total'] > 0)
                    <a href="{{ route('admin.laporan.peserta_global.print', array_merge(request()->query(), ['format' => 'pdf'])) }}" target="_blank" class="px-3.5 py-1.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-gray-700 rounded-xl font-bold text-xs transition shadow-xs flex items-center gap-1.5" title="Download PDF">
                        <i class="fas fa-file-pdf text-rose-500"></i> PDF
                    </a>
                    <a href="{{ route('admin.laporan.peserta_global.print', array_merge(request()->query(), ['format' => 'excel'])) }}" class="px-3.5 py-1.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 dark:hover:bg-gray-700 rounded-xl font-bold text-xs transition shadow-xs flex items-center gap-1.5" title="Download Excel">
                        <i class="fas fa-file-excel text-emerald-500"></i> Excel
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans" x-data="{ quickSearch: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Back Navigation --}}
            <div class="flex justify-between items-center print:hidden">
                <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Pusat Laporan
                </a>
            </div>

            <!-- Ringkasan Statistik Utama -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Peserta</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-2xl bg-teal-50 dark:bg-teal-950/60 border border-teal-100 dark:border-teal-800/60 flex items-center justify-center text-teal-600 dark:text-teal-400 text-base shadow-xs">
                        <i class="fas fa-users"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Aktif Magang</p>
                        <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1 font-mono">{{ number_format($stats['aktif']) }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-emerald-800/60 flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-base shadow-xs">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Selesai Magang</p>
                        <p class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-1 font-mono">{{ number_format($stats['selesai']) }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-2xl bg-blue-50 dark:bg-blue-950/60 border border-blue-100 dark:border-blue-800/60 flex items-center justify-center text-blue-600 dark:text-blue-400 text-base shadow-xs">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Pending / Menunggu</p>
                        <p class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-1 font-mono">{{ number_format($stats['pending']) }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 dark:bg-amber-950/60 border border-amber-100 dark:border-amber-800/60 flex items-center justify-center text-amber-600 dark:text-amber-400 text-base shadow-xs">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <!-- Toolbar Filter Data -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('admin.laporan.peserta_global') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        
                        <!-- Filter Kampus -->
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Asal Sekolah / Kampus</label>
                            <select name="instansi" class="w-full py-2.5 px-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-100 focus:ring-teal-500 focus:border-teal-500 shadow-xs [color-scheme:dark]">
                                <option value="">Semua Sekolah / Kampus</option>
                                @foreach($listInstansi as $instansi)
                                    <option value="{{ $instansi }}" {{ request('instansi') == $instansi ? 'selected' : '' }}>
                                        {{ Str::limit($instansi, 35) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Dinas -->
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Dinas Penempatan</label>
                            <select name="instansi_id" class="w-full py-2.5 px-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-100 focus:ring-teal-500 focus:border-teal-500 shadow-xs [color-scheme:dark]">
                                <option value="">Semua Dinas Penempatan</option>
                                @foreach($listDinas as $dinas)
                                    <option value="{{ $dinas->id }}" {{ request('instansi_id') == $dinas->id ? 'selected' : '' }}>
                                        {{ Str::limit($dinas->nama_dinas, 35) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Status -->
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Status Magang</label>
                            <select name="status" class="w-full py-2.5 px-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-100 focus:ring-teal-500 focus:border-teal-500 shadow-xs [color-scheme:dark]">
                                <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Aktif Magang</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai Magang</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending / Menunggu</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <!-- Filter Kata Kunci Posisi -->
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Posisi Magang</label>
                            <input type="text" name="posisi" value="{{ request('posisi') }}" placeholder="Cari posisi..."
                                class="w-full py-2.5 px-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-teal-500 focus:border-teal-500 shadow-xs">
                        </div>

                    </div>

                    <div class="flex flex-col sm:flex-row justify-end items-center gap-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        @if(request()->anyFilled(['instansi', 'instansi_id', 'status', 'start_date', 'end_date', 'posisi']))
                            <a href="{{ route('admin.laporan.peserta_global') }}" class="w-full sm:w-auto px-4 py-2 text-xs font-bold text-gray-600 dark:text-gray-400 hover:text-rose-600 bg-gray-100 dark:bg-gray-900 rounded-xl transition text-center border border-gray-200 dark:border-gray-700">
                                <i class="fas fa-times mr-1"></i> Bersihkan Filter
                            </a>
                        @endif
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold shadow-md transition uppercase tracking-wider flex items-center justify-center gap-2">
                            <i class="fas fa-filter text-xs"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Utama Data Peserta -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50 dark:bg-gray-900">
                    <div>
                        <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                            <i class="fas fa-list text-teal-600 dark:text-teal-400"></i> Daftar Peserta Magang
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-medium">Rekapitulasi informasi peserta magang secara menyeluruh.</p>
                    </div>

                    <!-- Client-Side Quick Search Bar -->
                    <div class="w-full sm:w-64 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 pointer-events-none">
                            <i class="fas fa-search text-xs"></i>
                        </span>
                        <input type="text" x-model="quickSearch" placeholder="Cari nama/sekolah..."
                            class="w-full pl-9 pr-8 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-teal-500 focus:border-teal-500 shadow-xs transition">
                        <button type="button" x-show="quickSearch !== ''" @click="quickSearch = ''" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fas fa-times-circle text-xs"></i>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">No</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-[180px]">Nama Peserta</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-[220px]">Asal Sekolah / Kampus</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-[220px]">Dinas & Posisi</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-[190px]">Periode Magang</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                            @forelse($allInterns as $data)
                            <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-950/20 transition duration-150"
                                x-show="quickSearch === '' || 
                                        '{{ strtolower($data->user->name ?? '') }}'.includes(quickSearch.toLowerCase()) || 
                                        '{{ strtolower($data->user->asal_instansi ?? '') }}'.includes(quickSearch.toLowerCase()) || 
                                        '{{ strtolower($data->position->instansi->nama_dinas ?? '') }}'.includes(quickSearch.toLowerCase()) ||
                                        '{{ strtolower($data->position->judul_posisi ?? '') }}'.includes(quickSearch.toLowerCase())">
                                
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-gray-400 dark:text-gray-500">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4 min-w-[180px] max-w-[240px]">
                                    <div class="text-sm font-bold text-gray-900 dark:text-gray-100 leading-snug line-clamp-2" title="{{ $data->user->name ?? '-' }}">{{ $data->user->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500 font-medium truncate mt-0.5">{{ $data->user->email ?? '-' }}</div>
                                </td>

                                <td class="px-6 py-4 min-w-[220px] max-w-[280px]">
                                    <div class="inline-flex items-start gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60 leading-snug">
                                        <i class="fas fa-university text-[10px] mt-0.5 shrink-0"></i>
                                        <span class="line-clamp-2" title="{{ $data->user->asal_instansi ?? '-' }}">{{ $data->user->asal_instansi ?? '-' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 min-w-[220px] max-w-[280px]">
                                    <div class="text-xs font-bold text-gray-900 dark:text-gray-100 leading-snug line-clamp-2" title="{{ $data->position->instansi->nama_dinas ?? '-' }}">{{ $data->position->instansi->nama_dinas ?? '-' }}</div>
                                    <div class="text-xs text-teal-600 dark:text-teal-400 font-medium mt-1 leading-snug line-clamp-2" title="{{ $data->position->judul_posisi ?? '-' }}">{{ $data->position->judul_posisi ?? '-' }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-bold text-gray-700 dark:text-gray-300">
                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl font-mono inline-block">
                                        {{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d M Y') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $statusVal = $data->status instanceof \App\Enums\ApplicationStatus ? $data->status->value : $data->status;
                                        $badgeClass = match($statusVal) {
                                            'diterima' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                                            'selesai' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800/60',
                                            'pending', 'menunggu' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
                                            'ditolak' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
                                            default => 'bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700'
                                        };
                                        $label = match($statusVal) {
                                            'diterima' => 'Aktif',
                                            'selesai' => 'Selesai',
                                            'pending', 'menunggu' => 'Pending',
                                            'ditolak' => 'Ditolak',
                                            default => ucfirst($statusVal)
                                        };
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full border {{ $badgeClass }}">
                                        {{ $label }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                            <i class="far fa-user-circle text-3xl"></i>
                                        </div>
                                        <p class="font-bold text-gray-700 dark:text-gray-300 text-sm">Tidak Ada Data Peserta</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Belum ada data peserta yang sesuai dengan filter pilihan Anda.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($allInterns instanceof \Illuminate\Pagination\LengthAwarePaginator && $allInterns->hasPages())
                    <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        {{ $allInterns->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>