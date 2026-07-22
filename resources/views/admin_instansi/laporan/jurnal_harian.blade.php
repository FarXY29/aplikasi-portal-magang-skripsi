<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-950/60 flex items-center justify-center border border-purple-200 dark:border-purple-800/60">
                    <i class="fas fa-book-open text-purple-600 dark:text-purple-400 text-lg"></i>
                </div>
                {{ __('Laporan Jurnal / Aktivitas Harian') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('dinas.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-purple-500 dark:group-hover:border-purple-400 shadow-xs">
                    <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-purple-600 dark:group-hover:text-purple-400"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
        </div>
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Statistik Ringkasan --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center mx-auto mb-2.5 border border-purple-100 dark:border-purple-900/50">
                        <i class="fas fa-book"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100">{{ $stats['total_jurnal'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Total Jurnal</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-green-50 dark:bg-green-950/60 text-green-600 dark:text-green-400 flex items-center justify-center mx-auto mb-2.5 border border-green-100 dark:border-green-900/50">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <p class="text-2xl font-black text-green-700 dark:text-green-400">{{ $stats['disetujui'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Disetujui</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto mb-2.5 border border-amber-100 dark:border-amber-900/50">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <p class="text-2xl font-black text-amber-600 dark:text-amber-400">{{ $stats['pending'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Pending</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-950/60 text-red-600 dark:text-red-400 flex items-center justify-center mx-auto mb-2.5 border border-red-100 dark:border-red-900/50">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <p class="text-2xl font-black text-red-700 dark:text-red-400">{{ $stats['revisi'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Revisi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-2.5 border border-blue-100 dark:border-blue-900/50">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100">{{ $stats['total_peserta_aktif'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Peserta Aktif</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-2.5 border border-indigo-100 dark:border-indigo-900/50">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <p class="text-2xl font-black text-indigo-700 dark:text-indigo-400">{{ $stats['rasio_validasi'] }}%</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Rasio Validasi</p>
                </div>
            </div>

            {{-- Highlight Banner --}}
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-3xl p-6 text-white shadow-lg shadow-purple-600/20 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 dark:bg-gray-800/30 backdrop-blur-sm flex items-center justify-center text-2xl flex-shrink-0 border border-white/20">
                    <i class="fas fa-book-reader"></i>
                </div>
                <div class="text-center sm:text-left flex-grow">
                    <p class="text-xs font-bold uppercase tracking-wider text-purple-100">Rekapitulasi Jurnal Periode: <span class="underline font-bold text-white">{{ $filter == 'semua' ? 'Semua Waktu' : ($filter == 'hari' ? 'Hari Ini' : ($filter == 'minggu' ? 'Minggu Ini' : 'Bulan Ini')) }}</span></p>
                    <p class="text-xl font-black mt-0.5">Terdapat {{ $stats['total_jurnal'] }} Entri Jurnal</p>
                    <p class="text-sm text-purple-100 font-medium">
                        Ada <span class="font-bold text-white bg-white/20 dark:bg-gray-800/30 px-2.5 py-0.5 rounded-lg border border-white/20">{{ $stats['pending'] }} jurnal</span> menunggu validasi. 
                        Rasio jurnal tuntas disetujui mencapai <span class="font-bold text-white text-md">{{ $stats['rasio_validasi'] }}%</span>.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-3 sm:ml-auto">
                    <form method="GET" action="{{ route('dinas.laporan.jurnal_harian') }}" class="flex-shrink-0">
                        <select name="filter" onchange="this.form.submit()" class="bg-purple-700/60 dark:bg-gray-800 border border-purple-400/80 dark:border-gray-700 text-white rounded-xl text-xs font-bold focus:ring-purple-300 focus:border-purple-300 block w-full p-2.5 transition cursor-pointer">
                            <option value="semua" {{ $filter == 'semua' ? 'selected' : '' }} class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">Semua Waktu</option>
                            <option value="hari" {{ $filter == 'hari' ? 'selected' : '' }} class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">Hari Ini</option>
                            <option value="minggu" {{ $filter == 'minggu' ? 'selected' : '' }} class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">Minggu Ini</option>
                            <option value="bulan" {{ $filter == 'bulan' ? 'selected' : '' }} class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">Bulan Ini</option>
                        </select>
                    </form>

                    @if($jurnal->count() > 0)
                        <div class="flex gap-2">
                            <a href="{{ route('dinas.laporan.jurnal_harian.print', array_merge(['filter' => $filter], ['format' => 'pdf'])) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-purple-700 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 rounded-xl hover:bg-purple-50 dark:hover:bg-gray-700 transition text-xs font-bold shadow-md border border-white/20 dark:border-gray-700" title="Download PDF">
                                <i class="fas fa-file-pdf mr-1.5 text-red-500"></i> PDF
                            </a>
                            <a href="{{ route('dinas.laporan.jurnal_harian.print', array_merge(['filter' => $filter], ['format' => 'excel'])) }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-purple-700 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 rounded-xl hover:bg-purple-50 dark:hover:bg-gray-700 transition text-xs font-bold shadow-md border border-white/20 dark:border-gray-700" title="Download Excel">
                                <i class="fas fa-file-excel mr-1.5 text-green-600"></i> Excel
                            </a>
                            <a href="{{ route('dinas.laporan.jurnal_harian.print', array_merge(['filter' => $filter], ['format' => 'csv'])) }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-purple-700 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 rounded-xl hover:bg-purple-50 dark:hover:bg-gray-700 transition text-xs font-bold shadow-md border border-white/20 dark:border-gray-700" title="Download CSV">
                                <i class="fas fa-file-csv mr-1.5 text-blue-600"></i> CSV
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tabel Jurnal Harian --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-list-alt text-purple-500 dark:text-purple-400"></i>
                        Daftar Jurnal Aktivitas Harian Mahasiswa
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Uraian kegiatan harian beserta status persetujuan dari masing-masing pembimbing lapangan dinas.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">No</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28">Tanggal</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mahasiswa & Posisi</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Uraian Kegiatan</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-20">Bukti</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-36">Status Validasi</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pembimbing & Komentar</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                            @forelse($jurnal as $log)
                            <tr class="hover:bg-purple-50/15 dark:hover:bg-purple-950/20 transition duration-150">
                                <td class="px-5 py-4 text-xs text-gray-400 dark:text-gray-500 text-center font-bold">
                                    {{ $loop->iteration }}
                                </td>
                                
                                <td class="px-5 py-4">
                                    <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($log->tanggal)->format('d M Y') }}</div>
                                    <span class="text-[9px] font-bold text-gray-400 dark:text-gray-500 block mt-0.5">{{ \Carbon\Carbon::parse($log->tanggal)->isoFormat('dddd') }}</span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60 flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                                            {{ strtoupper(substr($log->application->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-black text-gray-900 dark:text-gray-100 truncate">{{ $log->application->user->name ?? 'User Terhapus' }}</p>
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold truncate">{{ $log->application->position->judul_posisi ?? 'Posisi Terhapus' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="text-xs text-gray-800 dark:text-gray-200 whitespace-pre-wrap break-words max-w-xs md:max-w-sm">{{ $log->kegiatan }}</div>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    @if($log->bukti_foto_path)
                                        <a href="{{ route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]) }}" target="_blank" class="inline-block relative group" title="Lihat Bukti Foto">
                                            <img src="{{ route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]) }}" class="w-10 h-10 rounded-xl object-cover border border-gray-200 dark:border-gray-700 hover:scale-110 transition shadow-xs">
                                            <span class="absolute -bottom-1 -right-1 bg-purple-600 dark:bg-purple-500 text-white w-4 h-4 rounded-full flex items-center justify-center text-[8px] shadow-xs"><i class="fas fa-search-plus"></i></span>
                                        </a>
                                    @else
                                        <span class="inline-block p-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-400 dark:text-gray-500" title="Tidak ada bukti foto">
                                            <i class="fas fa-image-slash text-xs"></i>
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-center">
                                    @if($log->status_validasi == 'disetujui')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 gap-1">
                                            <i class="fas fa-check-circle text-[9px]"></i> Disetujui
                                        </span>
                                    @elseif($log->status_validasi == 'revisi')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60 gap-1 animate-pulse">
                                            <i class="fas fa-exclamation-circle text-[9px]"></i> Revisi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60 gap-1">
                                            <i class="fas fa-clock text-[9px]"></i> Pending
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    @if($log->application->pembimbing_lapangan)
                                        <div class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $log->application->pembimbing_lapangan->name }}</div>
                                        @if($log->komentar_pembimbing_lapangan)
                                            <div class="mt-1 bg-gray-50 dark:bg-gray-900 text-[10px] text-gray-700 dark:text-gray-300 p-2.5 rounded-xl border border-gray-200 dark:border-gray-700 italic relative max-w-xs">
                                                <span class="font-bold text-gray-400 dark:text-gray-500 text-[8px] uppercase block not-italic mb-0.5">Catatan Pembimbing:</span>
                                                "{{ $log->komentar_pembimbing_lapangan }}"
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">Belum ditentukan</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                            <i class="fas fa-book-open text-3xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <p class="font-bold text-gray-700 dark:text-gray-300">Belum ada data jurnal harian</p>
                                        <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">Logbook aktivitas mahasiswa dalam periode filter ini belum tercatat.</p>
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
</x-app-layout>
