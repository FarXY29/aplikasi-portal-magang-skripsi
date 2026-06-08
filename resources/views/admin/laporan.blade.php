<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-chart-pie text-teal-600"></i>
                {{ __('Laporan Statistik Instansi') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Pusat Laporan
                </a>
            </div>

            <div class="bg-gradient-to-r from-teal-700 to-emerald-600 rounded-3xl p-8 shadow-lg text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h3 class="text-2xl font-black mb-2">Statistik Rekapitulasi Magang</h3>
                        <p class="text-teal-50 text-sm max-w-2xl leading-relaxed">
                            Memonitor daya serap dan ketertarikan calon pelamar terhadap setiap Instansi Pemerintahan, lengkap dengan rasio seleksi peserta.
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <i class="fas fa-chart-area text-6xl text-teal-200 opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-3xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Penerimaan per Instansi</h3>
                        <p class="text-xs text-gray-500 mt-1">Rekapitulasi total pelamar, tingkat seleksi, dan rasio peminat.</p>
                    </div>
                    <a href="{{ route('admin.laporan.print') }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-xl hover:bg-gray-800 transition font-bold text-sm shadow-md">
                        <i class="fas fa-file-pdf text-red-400"></i> Unduh PDF
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">
                                <th class="px-6 py-4 w-12 text-center">No</th>
                                <th class="px-6 py-4">Nama Instansi</th>
                                <th class="px-6 py-4 text-center">Lowongan Aktif</th>
                                <th class="px-6 py-4 text-center">Total Pelamar</th>
                                <th class="px-6 py-4 text-center">Diterima / Selesai</th>
                                <th class="px-6 py-4 text-center">Tingkat Seleksi</th>
                                <th class="px-6 py-4 text-center">Rasio Peminat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm">
                            @forelse($laporan as $index => $data)
                            <tr class="hover:bg-teal-50/30 transition group">
                                <td class="px-6 py-4 text-center text-gray-400 font-bold">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-xs">
                                            <i class="far fa-building"></i>
                                        </div>
                                        <span class="font-bold text-gray-800 group-hover:text-teal-700 transition">{{ $data['nama_dinas'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-gray-600 font-medium">{{ $data['lowongan_aktif'] }} Posisi</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full font-bold text-xs border border-blue-100">
                                        {{ $data['total_pelamar'] }} Orang
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-green-50 text-green-700 rounded-full font-bold text-xs border border-green-100">
                                        {{ $data['total_magang'] }} Orang
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-gray-700">
                                    {{ $data['seleksi_rate'] }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-500 italic">
                                    {{ $data['avg_peminat'] }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400 text-sm italic">
                                    Belum ada data statistik magang yang tersedia.
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