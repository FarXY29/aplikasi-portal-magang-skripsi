<x-app-layout>
    <div class="py-8 bg-gray-50 dark:bg-gray-900/50 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden">
                <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Pusat Laporan
                </a>
                <div class="flex gap-2">
                    <a href="{{ route('admin.laporan.penyerapan_kuota.print', ['format' => 'pdf']) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download PDF">
                        <i class="fas fa-file-pdf text-red-500"></i> <span class="hidden sm:inline">PDF</span>
                    </a>
                    <a href="{{ route('admin.laporan.penyerapan_kuota.print', ['format' => 'excel']) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download Excel">
                        <i class="fas fa-file-excel text-green-600"></i> <span class="hidden sm:inline">Excel</span>
                    </a>
                    <a href="{{ route('admin.laporan.penyerapan_kuota.print', ['format' => 'csv']) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download CSV">
                        <i class="fas fa-file-csv text-blue-600"></i> <span class="hidden sm:inline">CSV</span>
                    </a>
                </div>
            </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-black text-gray-900 dark:text-gray-100">Laporan Penyerapan Kuota (City-Wide)</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Evaluasi tingkat keterisian kuota magang pada masing-masing instansi se-kota.</p>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50 dark:bg-gray-900/80">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Nama Instansi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Total Kuota Disediakan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Total Terserap</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Persentase Penyerapan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($penyerapan as $instansi)
                        <tr class="hover:bg-teal-50/30 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $instansi->nama_dinas }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $instansi->total_kuota }} Kursi</td>
                            <td class="px-6 py-4 text-sm text-green-600 font-bold">{{ $instansi->total_terserap }} Orang</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                        <div class="bg-teal-600 h-2.5 rounded-full" style="width: {{ min(100, $instansi->persentase_penyerapan) }}%"></div>
                                    </div>
                                    <span class="text-sm font-bold {{ $instansi->persentase_penyerapan >= 80 ? 'text-teal-600' : 'text-orange-500' }}">
                                        {{ number_format($instansi->persentase_penyerapan, 1) }}%
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
