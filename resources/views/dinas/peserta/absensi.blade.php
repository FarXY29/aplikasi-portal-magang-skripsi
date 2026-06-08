<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
                    <i class="fas fa-calendar-alt text-teal-600 mr-2"></i>
                    Riwayat Absensi
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Memantau kehadiran peserta: <span class="font-bold text-gray-800">{{ $app->user->name }}</span>
                </p>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('dinas.peserta.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 text-sm font-bold hover:bg-gray-50 transition shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                
                <a href="{{ route('dinas.peserta.absensi.pdf', $app->id) }}" target="_blank" class="px-4 py-2 bg-red-600 border border-transparent rounded-lg text-white text-sm font-bold hover:bg-red-700 transition shadow-sm flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tepat Waktu</p>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $stats['tepat_waktu'] ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                        <i class="fas fa-check"></i>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Terlambat</p>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $stats['terlambat'] ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Izin / Sakit</p>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $stats['izin'] ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fas fa-file-medical"></i>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Alpha</p>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $stats['alpha'] ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row justify-between md:items-center gap-4 bg-gray-50">
                    <h3 class="font-bold text-gray-700">Daftar Kehadiran</h3>
                    
                    <form action="" method="GET" class="flex items-center gap-2">
                        <div class="relative">
                            <i class="fas fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                            <select name="bulan" onchange="this.form.submit()" class="pl-8 pr-8 py-2 text-sm border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm cursor-pointer">
                                <option value="">Semua Periode</option>
                                <option value="01" {{ request('bulan') == '01' ? 'selected' : '' }}>Januari</option>
                                <option value="02" {{ request('bulan') == '02' ? 'selected' : '' }}>Februari</option>
                                <option value="03" {{ request('bulan') == '03' ? 'selected' : '' }}>Maret</option>
                                <option value="04" {{ request('bulan') == '04' ? 'selected' : '' }}>April</option>
                                <option value="05" {{ request('bulan') == '05' ? 'selected' : '' }}>Mei</option>
                                <option value="06" {{ request('bulan') == '06' ? 'selected' : '' }}>Juni</option>
                                <option value="07" {{ request('bulan') == '07' ? 'selected' : '' }}>Juli</option>
                                <option value="08" {{ request('bulan') == '08' ? 'selected' : '' }}>Agustus</option>
                                <option value="09" {{ request('bulan') == '09' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                                <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Jam Pulang</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Catatan / Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($absensi as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-800">
                                            {{ \Carbon\Carbon::parse($log->date)->isoFormat('dddd, D MMMM Y') }}
                                        </span>
                                        <span class="text-xs text-gray-400">Hari ke-{{ $loop->iteration }}</span>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($log->clock_in)
                                        <span class="px-2 py-1 bg-gray-100 rounded text-sm font-mono font-bold text-gray-700">
                                            {{ \Carbon\Carbon::parse($log->clock_in)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($log->clock_out)
                                        <span class="px-2 py-1 bg-gray-100 rounded text-sm font-mono font-bold text-gray-700">
                                            {{ \Carbon\Carbon::parse($log->clock_out)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum pulang</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($log->status == 'hadir')
                                        @if($log->clock_in > '08:00:00')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                <i class="fas fa-exclamation-triangle mr-1"></i> Terlambat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                <i class="fas fa-check-circle mr-1"></i> Tepat Waktu
                                            </span>
                                        @endif
                                    @elseif($log->status == 'izin')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                            <i class="fas fa-info-circle mr-1"></i> Izin
                                        </span>
                                    @elseif($log->status == 'sakit')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                            <i class="fas fa-procedures mr-1"></i> Sakit
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            <i class="fas fa-times mr-1"></i> Alpha
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">
                                        {{ $log->description ?? '-' }}
                                    </div>
                                    
                                    @if($log->proof_file)
                                        <a href="{{ Storage::url($log->proof_file) }}" target="_blank" class="text-xs text-teal-600 hover:text-teal-800 hover:underline flex items-center mt-1">
                                            <i class="fas fa-paperclip mr-1"></i> Lihat Bukti
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="far fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                                        <p>Belum ada data absensi untuk periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-100 bg-gray-50">
                    {{-- {{ $absensi->links() }} --}}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>