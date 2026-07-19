<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-book-open text-purple-600"></i>
                {{ __('Laporan Jurnal / Aktivitas Harian') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900/50 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('dinas.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-purple-600 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-purple-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Statistik Ringkasan --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-3 border border-purple-100">
                        <i class="fas fa-book"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-200">{{ $stats['total_jurnal'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-1">Total Jurnal</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center mx-auto mb-3 border border-green-100">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <p class="text-2xl font-black text-green-700">{{ $stats['disetujui'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-1">Disetujui</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center mx-auto mb-3 border border-yellow-100">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <p class="text-2xl font-black text-yellow-600" style="color: #d97706;">{{ $stats['pending'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-1">Pending</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-3 border border-red-100">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <p class="text-2xl font-black text-red-700">{{ $stats['revisi'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-1">Revisi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3 border border-blue-100">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-200">{{ $stats['total_peserta_aktif'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-1">Peserta Aktif</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3 border border-indigo-100">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <p class="text-2xl font-black text-indigo-700" style="color: #4f46e5;">{{ $stats['rasio_validasi'] }}%</p>
                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-1">Rasio Validasi</p>
                </div>
            </div>

            {{-- Highlight Banner --}}
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-purple-600/20 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white dark:bg-gray-800/20 backdrop-blur-sm flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fas fa-book-reader"></i>
                </div>
                <div class="text-center sm:text-left flex-grow">
                    <p class="text-xs font-bold uppercase tracking-wider text-purple-100">Rekapitulasi Jurnal Periode: <span class="underline font-bold text-white">{{ $filter == 'semua' ? 'Semua Waktu' : ($filter == 'hari' ? 'Hari Ini' : ($filter == 'minggu' ? 'Minggu Ini' : 'Bulan Ini')) }}</span></p>
                    <p class="text-xl font-black mt-0.5">Terdapat {{ $stats['total_jurnal'] }} Entri Jurnal</p>
                    <p class="text-sm text-purple-100 font-medium">
                        Ada <span class="font-bold text-white bg-white dark:bg-gray-800/20 px-2.5 py-0.5 rounded">{{ $stats['pending'] }} jurnal</span> menunggu validasi. 
                        Rasio jurnal tuntas disetujui mencapai <span class="font-bold text-white text-md">{{ $stats['rasio_validasi'] }}%</span>.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-3 sm:ml-auto">
                    <form method="GET" action="{{ route('dinas.laporan.jurnal_harian') }}" class="flex-shrink-0">
                        <select name="filter" onchange="this.form.submit()" class="bg-purple-700/50 border border-purple-400 text-white rounded-xl text-xs font-bold focus:ring-purple-300 focus:border-purple-300 block w-full p-2.5 transition cursor-pointer">
                            <option value="semua" {{ $filter == 'semua' ? 'selected' : '' }} class="text-gray-800 dark:text-gray-200">Semua Waktu</option>
                            <option value="hari" {{ $filter == 'hari' ? 'selected' : '' }} class="text-gray-800 dark:text-gray-200">Hari Ini</option>
                            <option value="minggu" {{ $filter == 'minggu' ? 'selected' : '' }} class="text-gray-800 dark:text-gray-200">Minggu Ini</option>
                            <option value="bulan" {{ $filter == 'bulan' ? 'selected' : '' }} class="text-gray-800 dark:text-gray-200">Bulan Ini</option>
                        </select>
                    </form>

                    @if($jurnal->count() > 0)
                        <div class="flex gap-2">
                            <a href="{{ route('dinas.laporan.jurnal_harian.print', array_merge(['filter' => $filter], ['format' => 'pdf'])) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-teal-700 rounded-xl hover:bg-teal-50 transition text-sm font-bold shadow-md border border-white/20" title="Download PDF">
                                <i class="fas fa-file-pdf text-red-500"></i> PDF
                            </a>
                            <a href="{{ route('dinas.laporan.jurnal_harian.print', array_merge(['filter' => $filter], ['format' => 'excel'])) }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-teal-700 rounded-xl hover:bg-teal-50 transition text-sm font-bold shadow-md border border-white/20" title="Download Excel">
                                <i class="fas fa-file-excel text-green-600"></i> Excel
                            </a>
                            <a href="{{ route('dinas.laporan.jurnal_harian.print', array_merge(['filter' => $filter], ['format' => 'csv'])) }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-teal-700 rounded-xl hover:bg-teal-50 transition text-sm font-bold shadow-md border border-white/20" title="Download CSV">
                                <i class="fas fa-file-csv text-blue-600"></i> CSV
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tabel Jurnal Harian --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                        <i class="fas fa-list-alt text-purple-500"></i>
                        Daftar Jurnal Aktivitas Harian Mahasiswa
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Uraian kegiatan harian beserta status persetujuan dari masing-masing pembimbing lapangan dinas.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">No</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28">Tanggal</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mahasiswa & Posisi</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Uraian Kegiatan</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-20">Bukti</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-36">Status Validasi</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pembimbing & Komentar</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-50">
                            @forelse($jurnal as $log)
                            <tr class="hover:bg-purple-50/10 transition duration-150">
                                <td class="px-5 py-4 text-xs text-gray-400 text-center font-bold">
                                    {{ $loop->iteration }}
                                </td>
                                
                                <td class="px-5 py-4">
                                    <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($log->tanggal)->format('d M Y') }}</div>
                                    <span class="text-[9px] font-bold text-gray-400 block mt-0.5">{{ \Carbon\Carbon::parse($log->tanggal)->isoFormat('dddd') }}</span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            {{ strtoupper(substr($log->application->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-black text-gray-900 dark:text-gray-100 truncate">{{ $log->application->user->name ?? 'User Terhapus' }}</p>
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold truncate">{{ $log->application->position->judul_posisi ?? 'Posisi Terhapus' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="text-xs text-gray-700 dark:text-gray-300 whitespace-pre-wrap break-words max-w-xs md:max-w-sm">{{ $log->kegiatan }}</div>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    @if($log->bukti_foto_path)
                                        <a href="{{ asset('storage/' . $log->bukti_foto_path) }}" target="_blank" class="inline-block relative group" title="Lihat Bukti Foto">
                                            <img src="{{ asset('storage/' . $log->bukti_foto_path) }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200 dark:border-gray-700 hover:scale-110 transition shadow-sm">
                                            <span class="absolute -bottom-1 -right-1 bg-purple-600 text-white w-4 h-4 rounded-full flex items-center justify-center text-[8px] shadow-sm"><i class="fas fa-search-plus"></i></span>
                                        </a>
                                    @else
                                        <span class="inline-block p-1 bg-gray-50 dark:bg-gray-900 border border-gray-150 rounded text-gray-400" title="Tidak ada bukti foto">
                                            <i class="fas fa-image-slash text-xs"></i>
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-center">
                                    @if($log->status_validasi == 'disetujui')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-green-100 text-green-700">
                                            <i class="fas fa-check-circle mr-1"></i> Disetujui
                                        </span>
                                    @elseif($log->status_validasi == 'revisi')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-red-100 text-red-700 animate-pulse">
                                            <i class="fas fa-exclamation-circle mr-1"></i> Revisi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-yellow-100 text-yellow-700">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    @if($log->application->pembimbing_lapangan)
                                        <div class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $log->application->pembimbing_lapangan->name }}</div>
                                        @if($log->komentar_pembimbing_lapangan)
                                            <div class="mt-1 bg-gray-50 dark:bg-gray-900 text-[10px] text-gray-600 dark:text-gray-400 p-2 rounded-lg border border-gray-150 italic relative max-w-xs">
                                                <span class="font-bold text-gray-400 text-[8px] uppercase block not-italic mb-0.5">Catatan Pembimbing:</span>
                                                "{{ $log->komentar_pembimbing_lapangan }}"
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum ditentukan</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center text-gray-400">
                                        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-book-open text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="font-bold text-gray-500 dark:text-gray-400">Belum ada data jurnal harian</p>
                                        <p class="text-xs mt-1">Logbook aktivitas mahasiswa dalam periode filter ini belum tercatat.</p>
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
