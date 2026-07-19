<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-university text-orange-600"></i>
                {{ __('Laporan Demografi Kampus') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900/50 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden">
            <a href="{{ route('dinas.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-orange-600 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-orange-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Statistik Ringkasan --}}
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center mx-auto mb-3 border border-orange-100">
                        <i class="fas fa-university"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-200">{{ $stats['total_kampus'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-1">Asal Kampus</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-3 border border-purple-100">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-200">{{ $stats['total_jurusan'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-1">Jurusan</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3 border border-blue-100">
                        <i class="fas fa-users"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-200">{{ $stats['total_pelamar'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-1">Total Pelamar</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center mx-auto mb-3 border border-green-100">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <p class="text-2xl font-black text-green-700">{{ $stats['total_diterima'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-1">Diterima</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 border border-emerald-100">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <p class="text-2xl font-black text-emerald-700">{{ $stats['total_selesai'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-1">Lulus / Selesai</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-3 border border-red-100">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <p class="text-2xl font-black text-red-700">{{ $stats['total_ditolak'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-1">Ditolak</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center mx-auto mb-3 border border-yellow-100">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <p class="text-2xl font-black text-yellow-700">{{ $stats['total_pending'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-1">Pending</p>
                </div>
            </div>

            {{-- Kampus Terbanyak Highlight --}}
            @if($stats['total_kampus'] > 0)
            <div class="bg-gradient-to-r from-orange-500 to-amber-500 rounded-2xl p-6 text-white shadow-lg shadow-orange-500/20 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white dark:bg-gray-800/20 backdrop-blur-sm flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="text-center sm:text-left">
                    <p class="text-xs font-bold uppercase tracking-wider text-orange-100">Kampus Kontributor Terbanyak</p>
                    <p class="text-xl font-black mt-0.5">{{ $stats['kampus_terbanyak'] }}</p>
                    <p class="text-sm text-orange-100 font-bold">{{ $stats['kampus_terbanyak_jumlah'] }} total pelamar</p>
                </div>
                @if($demografi->count() > 0)
                <div class="sm:ml-auto">
                    <div class="flex gap-2">
                            <a href="{{ route('dinas.laporan.demografi_kampus.print', ['format' => 'pdf']) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800/20 backdrop-blur-sm text-white rounded-xl hover:bg-white dark:hover:bg-gray-800/30 transition text-sm font-bold shadow-md border border-white/30 gap-1.5" title="Download PDF">
                                <i class="fas fa-file-pdf text-red-500"></i> PDF
                            </a>
                            <a href="{{ route('dinas.laporan.demografi_kampus.print', ['format' => 'excel']) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800/20 backdrop-blur-sm text-white rounded-xl hover:bg-white dark:hover:bg-gray-800/30 transition text-sm font-bold shadow-md border border-white/30 gap-1.5" title="Download Excel">
                                <i class="fas fa-file-excel text-green-600"></i> Excel
                            </a>
                            <a href="{{ route('dinas.laporan.demografi_kampus.print', ['format' => 'csv']) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800/20 backdrop-blur-sm text-white rounded-xl hover:bg-white dark:hover:bg-gray-800/30 transition text-sm font-bold shadow-md border border-white/30 gap-1.5" title="Download CSV">
                                <i class="fas fa-file-csv text-blue-600"></i> CSV
                            </a>
                        </div>
                </div>
                @endif
            </div>
            @endif

            {{-- Tabel Detail per Kampus --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                        <i class="fas fa-building text-orange-500"></i>
                        Distribusi Pendaftar per Kampus / Sekolah
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Klik baris kampus untuk melihat detail jurusan, posisi, dan daftar peserta yang diterima.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">No</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Asal Kampus / Sekolah</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelamar</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Diterima</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Selesai</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ditolak</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pending</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rasio</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-50" x-data="{ openRow: null }">
                            @forelse($demografi as $kampus => $data)
                            <tr class="hover:bg-orange-50/50 transition cursor-pointer" @click="openRow = openRow === {{ $loop->index }} ? null : {{ $loop->index }}">
                                <td class="px-5 py-4 text-xs text-gray-400 text-center font-bold">{{ $loop->iteration }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center text-sm font-black border border-orange-100 flex-shrink-0">
                                            {{ strtoupper(substr($kampus, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $kampus }}</p>
                                            <p class="text-[10px] text-gray-400 font-medium">{{ $data['jurusan']->count() }} jurusan</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center"><span class="text-sm font-black text-gray-800 dark:text-gray-200">{{ $data['total_pelamar'] }}</span></td>
                                <td class="px-5 py-4 text-center"><span class="text-sm font-bold text-green-600">{{ $data['diterima'] }}</span></td>
                                <td class="px-5 py-4 text-center"><span class="text-sm font-bold text-emerald-600">{{ $data['selesai'] }}</span></td>
                                <td class="px-5 py-4 text-center"><span class="text-sm font-bold text-red-500">{{ $data['ditolak'] }}</span></td>
                                <td class="px-5 py-4 text-center"><span class="text-sm font-bold text-yellow-600">{{ $data['pending'] }}</span></td>
                                <td class="px-5 py-4 text-center">
                                    @php
                                        $rate = $data['acceptance_rate'];
                                        $rateColor = $rate >= 70 ? 'bg-green-100 text-green-700' : ($rate >= 40 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black {{ $rateColor }}">
                                        {{ $rate }}%
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" :class="openRow === {{ $loop->index }} ? 'rotate-180 text-orange-500' : ''"></i>
                                </td>
                            </tr>
                            {{-- Expandable Detail Row --}}
                            <tr x-show="openRow === {{ $loop->index }}" x-transition.opacity x-cloak>
                                <td colspan="9" class="px-5 py-0 bg-gray-50 dark:bg-gray-900/50">
                                    <div class="py-5 grid grid-cols-1 lg:grid-cols-3 gap-5">
                                        {{-- Jurusan Breakdown --}}
                                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm">
                                            <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3"><i class="fas fa-graduation-cap mr-1 text-purple-500"></i> Jurusan</p>
                                            <div class="space-y-2">
                                                @foreach($data['jurusan'] as $jurusan => $count)
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-gray-700 dark:text-gray-300 font-medium truncate mr-2">{{ $jurusan }}</span>
                                                    <span class="text-xs font-black text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full flex-shrink-0">{{ $count }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        {{-- Posisi Breakdown --}}
                                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm">
                                            <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3"><i class="fas fa-briefcase mr-1 text-blue-500"></i> Posisi Diminati</p>
                                            <div class="space-y-2">
                                                @foreach($data['posisi'] as $posisi => $count)
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-gray-700 dark:text-gray-300 font-medium truncate mr-2">{{ $posisi }}</span>
                                                    <span class="text-xs font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full flex-shrink-0">{{ $count }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        {{-- Peserta Diterima --}}
                                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm">
                                            <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3"><i class="fas fa-user-check mr-1 text-green-500"></i> Peserta Diterima ({{ $data['peserta_aktif']->count() }})</p>
                                            @if($data['peserta_aktif']->count() > 0)
                                            <div class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar">
                                                @foreach($data['peserta_aktif'] as $peserta)
                                                <div class="flex items-start gap-2 p-2 bg-green-50/50 rounded-lg border border-green-100/50">
                                                    <div class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-[10px] font-black flex-shrink-0 mt-0.5">
                                                        {{ strtoupper(substr($peserta['nama'], 0, 1)) }}
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate">{{ $peserta['nama'] }}</p>
                                                        <p class="text-[10px] text-gray-500 dark:text-gray-400">{{ $peserta['posisi'] }}</p>
                                                        @if($peserta['mulai'] && $peserta['selesai'])
                                                        <p class="text-[9px] text-gray-400 mt-0.5">
                                                            {{ \Carbon\Carbon::parse($peserta['mulai'])->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($peserta['selesai'])->format('d/m/Y') }}
                                                        </p>
                                                        @endif
                                                    </div>
                                                    <span class="ml-auto text-[9px] font-bold px-1.5 py-0.5 rounded flex-shrink-0 {{ $peserta['status'] === 'selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-green-100 text-green-700' }}">
                                                        {{ $peserta['status'] === 'selesai' ? 'Lulus' : 'Aktif' }}
                                                    </span>
                                                </div>
                                                @endforeach
                                            </div>
                                            @else
                                            <p class="text-xs text-gray-400 italic">Belum ada peserta diterima.</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center text-gray-400">
                                        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-university text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="font-bold text-gray-500 dark:text-gray-400">Belum ada data pendaftaran magang</p>
                                        <p class="text-xs mt-1">Data akan muncul setelah ada pelamar yang mendaftar.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Demografi per Jurusan --}}
            @if($demografiJurusan->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-purple-500"></i>
                        Distribusi Pendaftar per Jurusan / Program Studi
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Menampilkan jurusan atau program studi mana yang paling banyak mendaftar dan diterima.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">No</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jurusan / Program Studi</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Pelamar</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Diterima</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rasio Penerimaan</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Visualisasi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-50">
                            @foreach($demografiJurusan as $jurusan => $data)
                            <tr class="hover:bg-purple-50/30 transition">
                                <td class="px-5 py-4 text-xs text-gray-400 text-center font-bold">{{ $loop->iteration }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-sm font-black border border-purple-100 flex-shrink-0">
                                            <i class="fas fa-book-reader text-xs"></i>
                                        </div>
                                        <span class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $jurusan }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center"><span class="text-sm font-black text-gray-800 dark:text-gray-200">{{ $data['total'] }}</span></td>
                                <td class="px-5 py-4 text-center"><span class="text-sm font-bold text-green-600">{{ $data['diterima'] }}</span></td>
                                <td class="px-5 py-4 text-center">
                                    @php
                                        $rate = $data['acceptance_rate'];
                                        $rateColor = $rate >= 70 ? 'bg-green-100 text-green-700' : ($rate >= 40 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black {{ $rateColor }}">{{ $rate }}%</span>
                                </td>
                                <td class="px-5 py-4">
                                    @php $maxTotal = $demografiJurusan->max('total'); $barWidth = $maxTotal > 0 ? ($data['total'] / $maxTotal) * 100 : 0; @endphp
                                    <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2.5 overflow-hidden">
                                        <div class="bg-gradient-to-r from-purple-500 to-purple-400 h-full rounded-full transition-all duration-500" style="width: {{ $barWidth }}%"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
