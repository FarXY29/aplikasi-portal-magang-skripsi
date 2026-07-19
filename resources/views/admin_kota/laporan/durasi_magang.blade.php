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
                    <a href="{{ route('admin.laporan.durasi_magang.print', ['format' => 'pdf']) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download PDF">
                        <i class="fas fa-file-pdf text-red-500"></i> <span class="hidden sm:inline">PDF</span>
                    </a>
                    <a href="{{ route('admin.laporan.durasi_magang.print', ['format' => 'excel']) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download Excel">
                        <i class="fas fa-file-excel text-green-600"></i> <span class="hidden sm:inline">Excel</span>
                    </a>
                    <a href="{{ route('admin.laporan.durasi_magang.print', ['format' => 'csv']) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-teal-700 border border-gray-200 dark:border-gray-700 hover:bg-teal-50 rounded-xl font-bold text-xs transition shadow-sm gap-2" title="Download CSV">
                        <i class="fas fa-file-csv text-blue-600"></i> <span class="hidden sm:inline">CSV</span>
                    </a>
                </div>
            </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-black text-gray-900 dark:text-gray-100">Laporan Rata-Rata Durasi Magang</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Mengukur seberapa lama durasi rata-rata peserta melakukan magang di setiap instansi.</p>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50 dark:bg-gray-900/80">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Nama Instansi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Rata-Rata (Hari)</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Rata-Rata (Bulan)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($instansis as $instansi)
                        <tr class="hover:bg-teal-50/30 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $instansi->nama_dinas }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 font-bold">{{ $instansi->avg_durasi_hari }} Hari</td>
                            <td class="px-6 py-4 text-sm text-teal-600 font-bold">{{ number_format($instansi->avg_durasi_bulan, 1) }} Bulan</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
