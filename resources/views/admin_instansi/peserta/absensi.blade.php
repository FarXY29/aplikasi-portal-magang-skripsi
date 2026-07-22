<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                        <i class="fas fa-calendar-alt text-teal-600 dark:text-teal-400 text-lg"></i>
                    </div>
                    Riwayat Absensi Peserta
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">
                    Memantau rincian kehadiran peserta magang: <span class="font-bold text-gray-800 dark:text-gray-200">{{ $app->user->name }}</span>
                </p>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('dinas.peserta.absensi.pdf', $app->id) }}" target="_blank" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 border border-transparent rounded-xl text-white text-xs font-bold transition shadow-xs flex items-center uppercase tracking-wider">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 print:hidden">
                <a href="{{ route('dinas.peserta.index') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Daftar Peserta
                </a>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Tepat Waktu</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono">{{ $stats['tepat_waktu'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-emerald-800/60 flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-lg shadow-xs">
                        <i class="fas fa-check"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Terlambat</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono">{{ $stats['terlambat'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 border border-amber-100 dark:border-amber-800/60 flex items-center justify-center text-amber-600 dark:text-amber-400 text-lg shadow-xs">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Izin / Sakit</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono">{{ $stats['izin'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/60 border border-blue-100 dark:border-blue-800/60 flex items-center justify-center text-blue-600 dark:text-blue-400 text-lg shadow-xs">
                        <i class="fas fa-file-medical"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Alpha</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 font-mono">{{ $stats['alpha'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-800/60 flex items-center justify-center text-rose-600 dark:text-rose-400 text-lg shadow-xs">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>

            {{-- Main Table Container --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between md:items-center gap-4 bg-gray-50 dark:bg-gray-900">
                    <h3 class="font-bold text-gray-800 dark:text-gray-100 text-base flex items-center gap-2">
                        <i class="fas fa-list-check text-teal-600 dark:text-teal-400"></i> Daftar Kehadiran Peserta
                    </h3>
                    
                    <form action="" method="GET" class="flex items-center gap-2">
                        <div class="relative">
                            <i class="fas fa-calendar absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500 text-xs"></i>
                            <select name="bulan" onchange="this.form.submit()" class="pl-9 pr-8 py-2 text-xs font-bold border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 rounded-xl shadow-xs cursor-pointer [color-scheme:dark]">
                                <option value="" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">Semua Periode</option>
                                <option value="01" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('bulan') == '01' ? 'selected' : '' }}>Januari</option>
                                <option value="02" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('bulan') == '02' ? 'selected' : '' }}>Februari</option>
                                <option value="03" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('bulan') == '03' ? 'selected' : '' }}>Maret</option>
                                <option value="04" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('bulan') == '04' ? 'selected' : '' }}>April</option>
                                <option value="05" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('bulan') == '05' ? 'selected' : '' }}>Mei</option>
                                <option value="06" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('bulan') == '06' ? 'selected' : '' }}>Juni</option>
                                <option value="07" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('bulan') == '07' ? 'selected' : '' }}>Juli</option>
                                <option value="08" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('bulan') == '08' ? 'selected' : '' }}>Agustus</option>
                                <option value="09" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('bulan') == '09' ? 'selected' : '' }}>September</option>
                                <option value="10" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                                <option value="11" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Masuk</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Pulang</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catatan / Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                            @forelse($absensi as $log)
                            <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-950/20 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ \Carbon\Carbon::parse($log->date)->isoFormat('dddd, D MMMM Y') }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-medium">Hari ke-{{ $loop->iteration }}</span>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($log->clock_in)
                                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-mono font-bold text-gray-800 dark:text-gray-200">
                                            {{ \Carbon\Carbon::parse($log->clock_in)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($log->clock_out)
                                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-mono font-bold text-gray-800 dark:text-gray-200">
                                            {{ \Carbon\Carbon::parse($log->clock_out)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="text-xs text-rose-600 dark:text-rose-400 italic bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-900/40 px-2.5 py-0.5 rounded-md font-bold">Belum Pulang</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($log->status == 'hadir')
                                        @if($log->clock_in > '08:00:00')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60 gap-1.5">
                                                <i class="fas fa-exclamation-triangle text-[10px]"></i> Terlambat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 gap-1.5">
                                                <i class="fas fa-check-circle text-[10px]"></i> Tepat Waktu
                                            </span>
                                        @endif
                                    @elseif($log->status == 'izin')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60 gap-1.5">
                                            <i class="fas fa-info-circle text-[10px]"></i> Izin
                                        </span>
                                    @elseif($log->status == 'sakit')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60 gap-1.5">
                                            <i class="fas fa-procedures text-[10px]"></i> Sakit
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60 gap-1.5">
                                            <i class="fas fa-times text-[10px]"></i> Alpha
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-xs sm:text-sm text-gray-800 dark:text-gray-200 font-medium leading-relaxed">
                                        {{ $log->description ?? '-' }}
                                    </div>
                                    
                                    @if($log->proof_file)
                                        <a href="{{ route('storage.access', ['type' => 'attendance', 'filename' => basename($log->proof_file)]) }}" target="_blank" class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300 hover:underline inline-flex items-center gap-1.5 mt-1.5">
                                            <i class="fas fa-paperclip text-teal-500 dark:text-teal-400"></i> Lihat Bukti Pengajuan
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                            <i class="far fa-calendar-times text-3xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <p class="font-bold text-gray-700 dark:text-gray-300 text-sm">Belum Ada Data Absensi</p>
                                        <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">Belum ada catatan kehadiran untuk peserta pada periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    {{ $absensi->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
