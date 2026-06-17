<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-chalkboard-teacher text-teal-600"></i>
                {{ __('Laporan Kinerja Pembimbing') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden">
            <a href="{{ route('dinas.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Evaluasi Beban & Kinerja Pembimbing Lapangan</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            Memantau jumlah mahasiswa bimbingan, tunggakan validasi logbook, dan rata-rata nilai yang diberikan.
                        </p>
                    </div>
                    
                    @if($beban->count() > 0)
                        <a href="{{ route('dinas.laporan.beban_pembimbing.print') }}" target="_blank" class="inline-flex items-center px-5 py-2 bg-gray-800 text-white rounded-xl hover:bg-gray-700 shadow-lg transition text-sm font-bold transform hover:-translate-y-0.5">
                            <i class="fas fa-file-pdf mr-2"></i> Download PDF
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Pembimbing</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Bimbingan Aktif</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Selesai / Lulus</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Logbook Menunggu Validasi</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Rata-rata Nilai Diberikan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($beban as $index => $pl)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 text-xs text-gray-500 text-center">
                                    {{ $loop->iteration }}
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $pl->name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        NIP: {{ $pl->nik ?? '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-bold {{ $pl->total_bimbingan_aktif > 5 ? 'text-orange-600' : 'text-gray-900' }}">
                                        {{ $pl->total_bimbingan_aktif }}
                                    </span>
                                    <div class="text-[10px] text-gray-400 mt-1">Mahasiswa</div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-bold text-blue-600">
                                        {{ $pl->total_lulus }}
                                    </span>
                                    <div class="text-[10px] text-gray-400 mt-1">Mahasiswa</div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($pl->logbook_tertunda > 0)
                                        <div class="text-sm font-bold text-red-600 bg-red-50 px-3 py-1 rounded-full inline-block">
                                            {{ $pl->logbook_tertunda }}
                                        </div>
                                        <div class="text-[10px] text-red-400 mt-1">Perlu Divalidasi</div>
                                    @else
                                        <span class="text-xs font-bold text-green-600"><i class="fas fa-check-circle mr-1"></i> Tuntas</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($pl->rata_nilai_diberikan > 0)
                                        <div class="text-sm font-bold text-gray-900">{{ round($pl->rata_nilai_diberikan, 2) }}</div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                    Belum ada data pembimbing lapangan.
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
