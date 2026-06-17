<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-book-open text-purple-600"></i>
                {{ __('Laporan Jurnal / Aktivitas Harian') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden">
            <a href="{{ route('dinas.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-purple-600 transition">
                <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-purple-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Rekapitulasi Kegiatan Logbook Mahasiswa</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            Memantau seluruh aktivitas aktual harian (jurnal) yang dikerjakan oleh mahasiswa magang di instansi Anda.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center gap-3">
                        <form method="GET" action="{{ route('dinas.laporan.jurnal_harian') }}" class="flex items-center gap-2">
                            <select name="filter" onchange="this.form.submit()" class="border-gray-300 focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 rounded-xl shadow-sm text-sm">
                                <option value="semua" {{ $filter == 'semua' ? 'selected' : '' }}>Semua Waktu</option>
                                <option value="hari" {{ $filter == 'hari' ? 'selected' : '' }}>Hari Ini</option>
                                <option value="minggu" {{ $filter == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                                <option value="bulan" {{ $filter == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                            </select>
                        </form>

                        @if($jurnal->count() > 0)
                            <a href="{{ route('dinas.laporan.jurnal_harian.print', ['filter' => $filter]) }}" target="_blank" class="inline-flex items-center px-5 py-2 bg-gray-800 text-white rounded-xl hover:bg-gray-700 shadow-lg transition text-sm font-bold transform hover:-translate-y-0.5">
                                <i class="fas fa-file-pdf mr-2"></i> Download PDF
                            </a>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Mahasiswa & Posisi</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kegiatan / Aktivitas</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Bukti</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status Validasi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($jurnal as $log)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 text-xs text-gray-500 text-center">
                                    {{ $loop->iteration }}
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($log->tanggal)->format('d M Y') }}</div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $log->application->user->name ?? 'User Terhapus' }}</div>
                                    <div class="text-xs text-gray-500">{{ $log->application->position->judul_posisi ?? 'Posisi Terhapus' }}</div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700 whitespace-pre-wrap break-words max-w-sm">{{ Str::limit($log->kegiatan, 100) }}</div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($log->file_bukti)
                                        <a href="{{ Storage::url($log->file_bukti) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition" title="Lihat Bukti">
                                            <i class="fas fa-paperclip"></i>
                                        </a>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($log->status_validasi == 'valid')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Valid
                                        </span>
                                    @elseif($log->status_validasi == 'revisi')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Revisi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                    Belum ada data jurnal harian dari mahasiswa.
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
