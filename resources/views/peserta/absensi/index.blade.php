<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-history text-teal-600"></i>
                {{ __('Riwayat Kehadiran (Absensi)') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900/50 min-h-screen font-sans">
        <div class="mb-6 print:hidden">
                <a href="{{ route('peserta.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Histori Absen Saya</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Data absen Anda pada instansi {{ $application->position->instansi->nama_dinas }}</p>
                    </div>

                    <!-- Filter Bulan -->
                    <x-ui.filter-bar :action="route('peserta.absensi.index')" :resetUrl="request()->has('month') ? route('peserta.absensi.index') : null">
                        <div class="flex items-center gap-2 min-w-[200px]">
                            <label for="month" class="text-xs font-bold text-gray-600 dark:text-gray-400 shrink-0">Pilih Bulan:</label>
                            <input type="month" id="month" name="month" value="{{ request('month') }}" class="w-full text-xs rounded-xl border-gray-200 dark:border-gray-700 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-sm font-medium">
                        </div>
                    </x-ui.filter-bar>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Masuk</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Pulang</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catatan/Validasi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100">
                            @forelse($attendances as $absen)
                                <tr class="table-row hover:bg-gray-50 dark:hover:bg-gray-900/60 group transition duration-150">
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($absen->date)->translatedFormat('l, d M Y') }}</div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
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
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 font-medium">
                                        @if($absen->clock_in)
                                            {{ \Carbon\Carbon::parse($absen->clock_in)->format('H:i') }} WIB
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 font-medium">
                                        @if($absen->clock_out)
                                            {{ \Carbon\Carbon::parse($absen->clock_out)->format('H:i') }} WIB
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        @if($absen->validation_status == 'disetujui')
                                            <span class="text-green-600 font-bold text-xs"><i class="fas fa-check-circle"></i> Tervalidasi</span>
                                        @elseif($absen->validation_status == 'ditolak')
                                            <span class="text-red-600 font-bold text-xs"><i class="fas fa-times-circle"></i> Ditolak</span>
                                        @else
                                            <span class="text-gray-400 font-bold text-xs"><i class="fas fa-clock"></i> Menunggu Validasi</span>
                                        @endif
                                        
                                        @if($absen->description)
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 italic">"{{ $absen->description }}"</p>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
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
