<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-file-alt text-teal-600"></i>
                {{ __('Laporan Rekap Peserta') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900/50 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('dinas.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
        </div>
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 items-start">

                {{-- Kolom Kiri: Filter --}}
                <div class="w-full lg:w-1/4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 lg:sticky lg:top-8">
                    <div class="flex justify-between items-center mb-5 border-b border-gray-50 pb-3">
                        <h3 class="text-gray-800 dark:text-gray-200 font-bold text-sm uppercase tracking-wide flex items-center">
                            <i class="fas fa-filter mr-2 text-teal-500"></i> Filter Laporan
                        </h3>
                        @if(request()->anyFilled(['status', 'asal_instansi', 'start_date', 'end_date', 'sort']))
                            <a href="{{ route('dinas.laporan.rekap') }}" class="text-xs text-red-500 hover:text-red-700 font-bold">Reset</a>
                        @endif
                    </div>

                    <form action="{{ route('dinas.laporan.rekap') }}" method="GET" class="space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Status Peserta</label>
                            <select name="status" class="w-full border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm cursor-pointer bg-gray-50 dark:bg-gray-900">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Aktif (Sedang Magang)</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai / Lulus</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Asal Kampus / Sekolah</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <i class="fas fa-university text-xs"></i>
                                </span>
                                <input type="text" name="asal_instansi" value="{{ request('asal_instansi') }}" 
                                    placeholder="Contoh: Universitas..."
                                    class="w-full pl-9 border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Periode Magang</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                    class="w-full border-gray-200 dark:border-gray-700 rounded-xl text-xs focus:ring-teal-500 focus:border-teal-500 shadow-sm" title="Dari Tanggal">
                                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                    class="w-full border-gray-200 dark:border-gray-700 rounded-xl text-xs focus:ring-teal-500 focus:border-teal-500 shadow-sm" title="Sampai Tanggal">
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1 italic">*Menampilkan irisan tanggal.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1.5">Urutkan</label>
                            <select name="sort" class="w-full border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm bg-gray-50 dark:bg-gray-900">
                                <option value="">Terbaru (Default)</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama (A - Z)</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama (Z - A)</option>
                            </select>
                        </div>

                        <x-primary-button class="w-full justify-center">
                            <i class="fas fa-search"></i> Terapkan Filter
                        </x-primary-button>
                    </form>
                </div>

                {{-- Kolom Kanan: Stats & Tabel --}}
                <div class="w-full lg:w-3/4 space-y-6">
                    
                    {{-- Stats Cards Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                            <div class="w-8 h-8 rounded-full bg-teal-50 dark:bg-teal-950/30 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-2 border border-teal-100 dark:border-teal-900/50">
                                <i class="fas fa-file-alt text-xs"></i>
                            </div>
                            <p class="text-xl font-black text-gray-800 dark:text-gray-200">{{ $stats['total'] }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Total Pendaftar</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                            <div class="w-8 h-8 rounded-full bg-green-50 dark:bg-green-950/30 text-green-600 dark:text-green-400 flex items-center justify-center mx-auto mb-2 border border-green-100 dark:border-green-900/50">
                                <i class="fas fa-user-clock text-xs"></i>
                            </div>
                            <p class="text-xl font-black text-green-700 dark:text-green-400">{{ $stats['aktif'] }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Status Aktif</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                            <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-2 border border-blue-100 dark:border-blue-900/50">
                                <i class="fas fa-graduation-cap text-xs"></i>
                            </div>
                            <p class="text-xl font-black text-blue-700 dark:text-blue-400">{{ $stats['selesai'] }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Selesai / Lulus</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                            <div class="w-8 h-8 rounded-full bg-yellow-50 dark:bg-yellow-950/30 text-yellow-600 dark:text-yellow-400 flex items-center justify-center mx-auto mb-2 border border-yellow-100 dark:border-yellow-900/50">
                                <i class="fas fa-hourglass-half text-xs"></i>
                            </div>
                            <p class="text-xl font-black text-yellow-600 dark:text-yellow-400">{{ $stats['pending'] }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Pending</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                            <div class="w-8 h-8 rounded-full bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 flex items-center justify-center mx-auto mb-2 border border-red-100 dark:border-red-900/50">
                                <i class="fas fa-user-times text-xs"></i>
                            </div>
                            <p class="text-xl font-black text-red-700 dark:text-red-400">{{ $stats['ditolak'] }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Ditolak</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                            <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-2 border border-indigo-100">
                                <i class="fas fa-university text-xs"></i>
                            </div>
                            <p class="text-xl font-black text-indigo-700">{{ $stats['total_campuses'] ?? $stats['total_kampus'] }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Kampus Terlibat</p>
                        </div>
                    </div>

                    {{-- Highlight Banner --}}
                    <div class="bg-gradient-to-r from-teal-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-teal-600/20 flex flex-col sm:flex-row items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-white dark:bg-gray-800/20 backdrop-blur-sm flex items-center justify-center text-2xl flex-shrink-0">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div class="text-center sm:text-left flex-grow">
                            <p class="text-xs font-bold uppercase tracking-wider text-teal-100">Rekapitulasi Lamaran Peserta Magang</p>
                            <p class="text-xl font-black mt-0.5">Total {{ $stats['total'] }} Lamaran Masuk</p>
                            <p class="text-sm text-teal-100 font-medium">Terdapat {{ $stats['total_kampus'] }} instansi pendidikan/kampus terdaftar yang telah bermitra.</p>
                        </div>
                        @if($applications->count() > 0)
                        <div class="sm:ml-auto flex-shrink-0 flex gap-2">
                            <a href="{{ route('dinas.laporan.rekap.print', array_merge(request()->query(), ['format' => 'pdf'])) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-400 rounded-xl hover:bg-teal-50 dark:hover:bg-teal-950/20 transition text-sm font-bold shadow-md border border-white/20 dark:border-gray-700" title="Download PDF">
                                <i class="fas fa-file-pdf mr-1.5 text-red-500"></i> PDF
                            </a>
                            <a href="{{ route('dinas.laporan.rekap.print', array_merge(request()->query(), ['format' => 'excel'])) }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-400 rounded-xl hover:bg-teal-50 dark:hover:bg-teal-950/20 transition text-sm font-bold shadow-md border border-white/20 dark:border-gray-700" title="Download Excel">
                                <i class="fas fa-file-excel mr-1.5 text-green-600"></i> Excel
                            </a>
                            <a href="{{ route('dinas.laporan.rekap.print', array_merge(request()->query(), ['format' => 'csv'])) }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-400 rounded-xl hover:bg-teal-50 dark:hover:bg-teal-950/20 transition text-sm font-bold shadow-md border border-white/20 dark:border-gray-700" title="Download CSV">
                                <i class="fas fa-file-csv mr-1.5 text-blue-600"></i> CSV
                            </a>
                        </div>
                        @endif
                    </div>

                    {{-- Card Tabel Utama --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Daftar Rekapitulasi Lamaran</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Menampilkan data pendaftar magang beserta penempatan posisi, pembimbing lapangan, dan statusnya.</p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12 whitespace-nowrap">No</th>
                                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap min-w-[200px]">Peserta & Kampus</th>
                                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap min-w-[200px]">Posisi & Pembimbing</th>
                                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap min-w-[200px]">Periode Magang</th>
                                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28 whitespace-nowrap">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-50">
                                    @forelse($applications as $app)
                                    <tr class="hover:bg-teal-50/10 dark:hover:bg-teal-900/10 transition duration-150">
                                        <td class="px-5 py-4 text-xs text-gray-400 text-center font-bold">
                                            {{ $loop->iteration }}
                                        </td>
                                        
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-teal-50 to-teal-100 flex items-center justify-center text-teal-700 font-bold text-xs border border-teal-200/50 flex-shrink-0">
                                                    {{ strtoupper(substr($app->user->name, 0, 2)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">{{ $app->user->name }}</div>
                                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold truncate">{{ $app->user->asal_instansi ?? '-' }}</p>
                                                    <div class="flex items-center gap-2 text-[9px] text-gray-400 mt-1 flex-wrap font-medium">
                                                        <span>{{ $app->user->email }}</span>
                                                        @if($app->user->phone)
                                                        <span class="text-gray-300">ÔÇó</span>
                                                        <span>{{ $app->user->phone }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4">
                                            <div class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $app->position->judul_posisi }}</div>
                                            @if($app->pembimbing_lapangan)
                                                <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                                                    <i class="fas fa-chalkboard-teacher text-[9px] text-gray-400"></i>
                                                    PL: <span class="font-bold text-gray-600 dark:text-gray-400">{{ $app->pembimbing_lapangan->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-[9px] text-gray-400 italic mt-1 block">PL: Belum ditentukan</span>
                                            @endif
                                        </td>

                                        <td class="px-5 py-4">
                                            <div class="flex flex-col gap-1">
                                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300 flex items-center gap-1.5">
                                                    <i class="far fa-calendar text-gray-400"></i>
                                                    {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} 
                                                    <span class="text-gray-300">Ô×£</span> 
                                                    {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}
                                                </span>
                                                <span class="text-[9px] text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/30 px-2 py-0.5 rounded w-fit font-bold">
                                                    {{ \Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai)) }} Hari
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 text-center">
                                            @php
                                                $statusConfig = [
                                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending'],
                                                    'menunggu' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending'],
                                                    'diterima' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Aktif'],
                                                    'selesai' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Selesai'],
                                                    'ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak'],
                                                    'dikeluarkan' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Dikeluarkan'],
                                                    'dibatalkan' => ['bg' => 'bg-gray-100 dark:bg-gray-800', 'text' => 'text-gray-800 dark:text-gray-200', 'label' => 'Dibatalkan'],
                                                ];
                                                $statusStr = $app->status instanceof \UnitEnum ? $app->status->value : $app->status;
                                                $s = $statusConfig[$statusStr] ?? ['bg' => 'bg-gray-100 dark:bg-gray-800', 'text' => 'text-gray-800 dark:text-gray-200', 'label' => ucfirst($statusStr)];
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-black rounded-full {{ $s['bg'] }} $s['text']">
                                                {{ $s['label'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 text-gray-300">
                                                    <i class="fas fa-search text-2xl"></i>
                                                </div>
                                                <p class="text-gray-900 dark:text-gray-100 font-bold">Data tidak ditemukan</p>
                                                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Coba sesuaikan filter pencarian Anda.</p>
                                                <a href="{{ route('dinas.laporan.rekap') }}" class="mt-4 text-teal-600 hover:text-teal-800 text-sm font-bold hover:underline">
                                                    Reset Semua Filter
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
