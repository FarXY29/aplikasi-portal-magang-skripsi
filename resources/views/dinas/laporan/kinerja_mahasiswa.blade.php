<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-chart-bar text-teal-600"></i>
                {{ __('Laporan Kinerja Mahasiswa') }}
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
                        <h3 class="text-lg font-bold text-gray-800">Scorecard Kinerja Peserta Magang</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            Memantau persentase kehadiran, keaktifan logbook, dan nilai akhir (khusus peserta berstatus Aktif & Selesai).
                        </p>
                    </div>
                    
                    @if($kinerja->count() > 0)
                        <a href="{{ route('dinas.laporan.kinerja_mahasiswa.print') }}" target="_blank" class="inline-flex items-center px-5 py-2 bg-gray-800 text-white rounded-xl hover:bg-gray-700 shadow-lg transition text-sm font-bold transform hover:-translate-y-0.5">
                            <i class="fas fa-file-pdf mr-2"></i> Download PDF
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Posisi Magang</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Kehadiran (Hadir)</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Validasi Logbook</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($kinerja as $index => $app)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 text-xs text-gray-500 text-center">
                                    {{ $loop->iteration }}
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $app->user->name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        {{ $app->user->asal_instansi ?? '-' }}
                                    </div>
                                    <div class="mt-1">
                                        @if($app->status == 'diterima')
                                            <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded font-bold uppercase">Aktif</span>
                                        @elseif($app->status == 'selesai')
                                            <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-bold uppercase">Selesai</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                    {{ $app->position->judul_posisi }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm font-bold {{ $app->attendance_rate >= 80 ? 'text-green-600' : ($app->attendance_rate >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ round($app->attendance_rate, 1) }}%
                                    </div>
                                    <div class="text-[10px] text-gray-400 mt-1">{{ $app->attendances->where('status', 'hadir')->count() }} dari {{ $app->attendances->count() }} hari</div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm font-bold {{ $app->log_rate >= 80 ? 'text-green-600' : ($app->log_rate >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ round($app->log_rate, 1) }}%
                                    </div>
                                    <div class="text-[10px] text-gray-400 mt-1">{{ $app->logs->where('status_validasi', 'disetujui')->count() }} disetujui</div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($app->status == 'selesai' && $app->avg_nilai > 0)
                                        <div class="text-sm font-bold text-gray-900">{{ round($app->avg_nilai, 2) }}</div>
                                        <div class="text-[10px] text-gray-500 mt-1">Rata-rata</div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum Dinilai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                    Belum ada data peserta yang aktif atau selesai.
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
