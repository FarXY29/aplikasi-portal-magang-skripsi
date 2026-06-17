<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-history text-teal-600"></i>
                {{ __('Riwayat Kehadiran (Absensi)') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="mb-6 print:hidden">
                <a href="{{ route('peserta.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Histori Absen Saya</h3>
                        <p class="text-sm text-gray-500">Data absen Anda pada instansi {{ $application->position->instansi->nama_dinas }}</p>
                    </div>

                    <!-- Filter Bulan -->
                    <form action="{{ route('peserta.absensi.index') }}" method="GET" class="flex gap-2 items-center">
                        <label for="month" class="text-sm font-bold text-gray-600">Pilih Bulan:</label>
                        <input type="month" id="month" name="month" value="{{ request('month') }}" class="rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500 text-sm shadow-sm">
                        <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-xl font-bold text-sm hover:bg-teal-700 transition">Filter</button>
                        @if(request('month'))
                            <a href="{{ route('peserta.absensi.index') }}" class="px-3 py-2 bg-gray-100 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-200 transition"><i class="fas fa-times"></i></a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Jam Pulang</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Catatan/Validasi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($attendances as $absen)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($absen->date)->translatedFormat('l, d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($absen->status == 'hadir')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">Hadir</span>
                                        @elseif($absen->status == 'izin')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">Izin</span>
                                        @elseif($absen->status == 'sakit')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200">Sakit</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">Alpa</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                        @if($absen->clock_in)
                                            {{ \Carbon\Carbon::parse($absen->clock_in)->format('H:i') }} WIB
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                        @if($absen->clock_out)
                                            {{ \Carbon\Carbon::parse($absen->clock_out)->format('H:i') }} WIB
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @if($absen->validation_status == 'disetujui')
                                            <span class="text-green-600 font-bold text-xs"><i class="fas fa-check-circle"></i> Tervalidasi</span>
                                        @elseif($absen->validation_status == 'ditolak')
                                            <span class="text-red-600 font-bold text-xs"><i class="fas fa-times-circle"></i> Ditolak</span>
                                        @else
                                            <span class="text-gray-400 font-bold text-xs"><i class="fas fa-clock"></i> Menunggu Validasi</span>
                                        @endif
                                        
                                        @if($absen->description)
                                            <p class="mt-1 text-xs text-gray-500 italic">"{{ $absen->description }}"</p>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3 block"></i>
                                        Belum ada data absensi yang tercatat pada bulan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $attendances->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
