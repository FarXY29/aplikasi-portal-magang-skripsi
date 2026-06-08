<x-app-layout>
    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden">
            <a href="{{ route('dinas.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                </div>
                Kembali ke Pusat Laporan
                </a>
                <a href="{{ route('dinas.laporan.tren_pendaftaran.print') }}" target="_blank" class="bg-white border border-gray-200 hover:border-teal-500 text-gray-700 hover:text-teal-600 font-bold py-2 px-4 rounded-full shadow-sm flex items-center gap-2 transition text-sm">
                    <i class="fas fa-print text-teal-500"></i> Cetak Laporan PDF
                </a>
            </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">Laporan Tren Pendaftaran</h2>
                    <p class="text-sm text-gray-500 mt-1">Distribusi pendaftaran magang per bulan.</p>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Tahun</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Bulan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Total Pendaftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($tren as $t)
                        <tr class="hover:bg-teal-50/30 transition">
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $t->tahun }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::createFromDate($t->tahun, $t->bulan, 1)->translatedFormat('F') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-teal-600">{{ $t->total }} Orang</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
