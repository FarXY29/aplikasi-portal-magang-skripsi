<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-university text-teal-600"></i>
                {{ __('Laporan Demografi Kampus') }}
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
                        <h3 class="text-lg font-bold text-gray-800">Distribusi Pendaftar Berdasarkan Asal Kampus/Sekolah</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            Memetakan dari instansi pendidikan mana saja yang paling banyak berkontribusi mengirim pelamar dan mahasiswa magang.
                        </p>
                    </div>
                    
                    @if($demografi->count() > 0)
                        <a href="{{ route('dinas.laporan.demografi_kampus.print') }}" target="_blank" class="inline-flex items-center px-5 py-2 bg-gray-800 text-white rounded-xl hover:bg-gray-700 shadow-lg transition text-sm font-bold transform hover:-translate-y-0.5">
                            <i class="fas fa-file-pdf mr-2"></i> Download PDF
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Asal Kampus / Sekolah</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Total Pelamar</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Diterima (Aktif & Lulus)</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Ditolak</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Proses Seleksi (Pending)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @php $total_pelamar = 0; $total_diterima = 0; $total_ditolak = 0; $total_pending = 0; @endphp
                            @forelse($demografi as $kampus => $data)
                            @php 
                                $total_pelamar += $data['total_pelamar'];
                                $total_diterima += $data['diterima'];
                                $total_ditolak += $data['ditolak'];
                                $total_pending += $data['pending'];
                            @endphp
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 text-xs text-gray-500 text-center">
                                    {{ $loop->iteration }}
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $kampus }}</div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm font-bold text-gray-900">{{ $data['total_pelamar'] }}</div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-bold text-green-600">{{ $data['diterima'] }}</span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-bold text-red-600">{{ $data['ditolak'] }}</span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-bold text-yellow-600">{{ $data['pending'] }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                    Belum ada data pendaftaran magang yang masuk.
                                </td>
                            </tr>
                            @endforelse
                            @if($demografi->count() > 0)
                            <tr class="bg-gray-100/50 border-t-2 border-gray-200">
                                <td colspan="2" class="px-6 py-4 text-right text-sm font-black text-gray-800">TOTAL KESELURUHAN</td>
                                <td class="px-6 py-4 text-center text-sm font-black text-gray-900">{{ $total_pelamar }}</td>
                                <td class="px-6 py-4 text-center text-sm font-black text-green-700">{{ $total_diterima }}</td>
                                <td class="px-6 py-4 text-center text-sm font-black text-red-700">{{ $total_ditolak }}</td>
                                <td class="px-6 py-4 text-center text-sm font-black text-yellow-700">{{ $total_pending }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
