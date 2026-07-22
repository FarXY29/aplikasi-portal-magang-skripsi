<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-950/60 flex items-center justify-center border border-orange-200 dark:border-orange-800/60">
                    <i class="fas fa-university text-orange-600 dark:text-orange-400 text-lg"></i>
                </div>
                {{ __('Laporan Demografi Kampus') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('dinas.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-orange-500 dark:group-hover:border-orange-400 shadow-xs">
                    <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-orange-600 dark:group-hover:text-orange-400"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Statistik Ringkasan --}}
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-orange-50 dark:bg-orange-950/60 text-orange-600 dark:text-orange-400 flex items-center justify-center mx-auto mb-2.5 border border-orange-100 dark:border-orange-900/50">
                        <i class="fas fa-university"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100">{{ $stats['total_kampus'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Asal Kampus</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center mx-auto mb-2.5 border border-purple-100 dark:border-purple-900/50">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100">{{ $stats['total_jurusan'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Jurusan</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-2.5 border border-blue-100 dark:border-blue-900/50">
                        <i class="fas fa-users"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100">{{ $stats['total_pelamar'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Total Pelamar</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-green-50 dark:bg-green-950/60 text-green-600 dark:text-green-400 flex items-center justify-center mx-auto mb-2.5 border border-green-100 dark:border-green-900/50">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <p class="text-2xl font-black text-green-700 dark:text-green-400">{{ $stats['total_diterima'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Diterima</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-2.5 border border-emerald-100 dark:border-emerald-900/50">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <p class="text-2xl font-black text-emerald-700 dark:text-emerald-400">{{ $stats['total_selesai'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Lulus / Selesai</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-950/60 text-red-600 dark:text-red-400 flex items-center justify-center mx-auto mb-2.5 border border-red-100 dark:border-red-900/50">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <p class="text-2xl font-black text-red-700 dark:text-red-400">{{ $stats['total_ditolak'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Ditolak</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-yellow-50 dark:bg-yellow-950/60 text-yellow-600 dark:text-yellow-400 flex items-center justify-center mx-auto mb-2.5 border border-yellow-100 dark:border-yellow-900/50">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <p class="text-2xl font-black text-yellow-700 dark:text-yellow-400">{{ $stats['total_pending'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Pending</p>
                </div>
            </div>

            {{-- Kampus Terbanyak Highlight --}}
            @if($stats['total_kampus'] > 0)
            <div class="bg-gradient-to-r from-orange-500 to-amber-500 rounded-3xl p-6 text-white shadow-lg shadow-orange-500/20 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 dark:bg-gray-800/30 backdrop-blur-sm flex items-center justify-center text-2xl flex-shrink-0 border border-white/20">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="text-center sm:text-left flex-grow">
                    <p class="text-xs font-bold uppercase tracking-wider text-orange-100">Kampus Kontributor Terbanyak</p>
                    <p class="text-xl font-black mt-0.5">{{ $stats['kampus_terbanyak'] }}</p>
                    <p class="text-sm text-orange-100 font-bold">{{ $stats['kampus_terbanyak_jumlah'] }} total pelamar</p>
                </div>
                @if($demografi->count() > 0)
                <div class="sm:ml-auto flex-shrink-0 flex gap-2">
                    <a href="{{ route('dinas.laporan.demografi_kampus.print', ['format' => 'pdf']) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-orange-700 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 rounded-xl hover:bg-orange-50 dark:hover:bg-gray-700 transition text-xs font-bold shadow-md border border-white/20 dark:border-gray-700" title="Download PDF">
                        <i class="fas fa-file-pdf mr-1.5 text-red-500"></i> PDF
                    </a>
                    <a href="{{ route('dinas.laporan.demografi_kampus.print', ['format' => 'excel']) }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-orange-700 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 rounded-xl hover:bg-orange-50 dark:hover:bg-gray-700 transition text-xs font-bold shadow-md border border-white/20 dark:border-gray-700" title="Download Excel">
                        <i class="fas fa-file-excel mr-1.5 text-green-600"></i> Excel
                    </a>
                    <a href="{{ route('dinas.laporan.demografi_kampus.print', ['format' => 'csv']) }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-orange-700 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 rounded-xl hover:bg-orange-50 dark:hover:bg-gray-700 transition text-xs font-bold shadow-md border border-white/20 dark:border-gray-700" title="Download CSV">
                        <i class="fas fa-file-csv mr-1.5 text-blue-600"></i> CSV
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- Tabel Detail per Kampus --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-building text-orange-500 dark:text-orange-400"></i>
                        Distribusi Pendaftar per Kampus / Sekolah
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Klik baris kampus untuk melihat detail jurusan, posisi, dan daftar peserta yang diterima.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">No</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Asal Kampus / Sekolah</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelamar</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Diterima</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Selesai</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ditolak</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pending</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rasio</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm" x-data="{ openRow: null }">
                            @forelse($demografi as $kampus => $data)
                            <tr class="hover:bg-orange-50/15 dark:hover:bg-orange-950/20 transition cursor-pointer" @click="openRow = openRow === {{ $loop->index }} ? null : {{ $loop->index }}">
                                <td class="px-5 py-4 text-xs text-gray-400 dark:text-gray-500 text-center font-bold">{{ $loop->iteration }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-orange-50 dark:bg-orange-950/60 text-orange-600 dark:text-orange-300 flex items-center justify-center text-sm font-black border border-orange-200 dark:border-orange-800/60 flex-shrink-0 shadow-xs">
                                            {{ strtoupper(substr($kampus, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $kampus }}</p>
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">{{ $data['jurusan']->count() }} jurusan</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center"><span class="text-sm font-black text-gray-800 dark:text-gray-100">{{ $data['total_pelamar'] }}</span></td>
                                <td class="px-5 py-4 text-center"><span class="text-sm font-bold text-green-600 dark:text-green-400">{{ $data['diterima'] }}</span></td>
                                <td class="px-5 py-4 text-center"><span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ $data['selesai'] }}</span></td>
                                <td class="px-5 py-4 text-center"><span class="text-sm font-bold text-red-500 dark:text-red-400">{{ $data['ditolak'] }}</span></td>
                                <td class="px-5 py-4 text-center"><span class="text-sm font-bold text-yellow-600 dark:text-yellow-400">{{ $data['pending'] }}</span></td>
                                <td class="px-5 py-4 text-center">
                                    @php
                                        $rate = $data['acceptance_rate'];
                                        $rateColor = $rate >= 70 ? 'bg-green-50 dark:bg-green-950/60 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800/60' : ($rate >= 40 ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60' : 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60');
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black {{ $rateColor }}">
                                        {{ $rate }}%
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 text-xs transition-transform duration-200" :class="openRow === {{ $loop->index }} ? 'rotate-180 text-orange-600 dark:text-orange-400' : ''"></i>
                                </td>
                            </tr>
                            {{-- Expandable Detail Row --}}
                            <tr x-show="openRow === {{ $loop->index }}" x-transition.opacity x-cloak>
                                <td colspan="9" class="px-5 py-4 bg-gray-50/60 dark:bg-gray-900/60 border-y border-gray-100 dark:border-gray-700">
                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                                        {{-- Jurusan Breakdown --}}
                                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 shadow-xs">
                                            <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3"><i class="fas fa-graduation-cap mr-1 text-purple-500 dark:text-purple-400"></i> Jurusan</p>
                                            <div class="space-y-2">
                                                @foreach($data['jurusan'] as $jurusan => $count)
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-gray-700 dark:text-gray-300 font-medium truncate mr-2">{{ $jurusan }}</span>
                                                    <span class="text-xs font-black text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-950/60 border border-purple-100 dark:border-purple-900/40 px-2 py-0.5 rounded-full flex-shrink-0">{{ $count }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        {{-- Posisi Breakdown --}}
                                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 shadow-xs">
                                            <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3"><i class="fas fa-briefcase mr-1 text-blue-500 dark:text-blue-400"></i> Posisi Diminati</p>
                                            <div class="space-y-2">
                                                @foreach($data['posisi'] as $posisi => $count)
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-gray-700 dark:text-gray-300 font-medium truncate mr-2">{{ $posisi }}</span>
                                                    <span class="text-xs font-black text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/60 border border-blue-100 dark:border-blue-900/40 px-2 py-0.5 rounded-full flex-shrink-0">{{ $count }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        {{-- Peserta Diterima --}}
                                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 shadow-xs">
                                            <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3"><i class="fas fa-user-check mr-1 text-green-500 dark:text-green-400"></i> Peserta Diterima ({{ $data['peserta_aktif']->count() }})</p>
                                            @if($data['peserta_aktif']->count() > 0)
                                            <div class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar">
                                                @foreach($data['peserta_aktif'] as $peserta)
                                                <div class="flex items-start gap-2 p-2 bg-green-50/50 dark:bg-green-950/30 rounded-xl border border-green-100/50 dark:border-green-900/40">
                                                    <div class="w-6 h-6 rounded-full bg-green-100 dark:bg-green-950/80 text-green-700 dark:text-green-300 flex items-center justify-center text-[10px] font-black flex-shrink-0 mt-0.5">
                                                        {{ strtoupper(substr($peserta['nama'], 0, 1)) }}
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate">{{ $peserta['nama'] }}</p>
                                                        <p class="text-[10px] text-gray-500 dark:text-gray-400">{{ $peserta['posisi'] }}</p>
                                                        @if($peserta['mulai'] && $peserta['selesai'])
                                                        <p class="text-[9px] text-gray-400 dark:text-gray-500 mt-0.5">
                                                            {{ \Carbon\Carbon::parse($peserta['mulai'])->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($peserta['selesai'])->format('d/m/Y') }}
                                                        </p>
                                                        @endif
                                                    </div>
                                                    <span class="ml-auto text-[9px] font-bold px-1.5 py-0.5 rounded flex-shrink-0 {{ $peserta['status'] === 'selesai' ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60' : 'bg-green-100 dark:bg-green-950/60 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800/60' }}">
                                                        {{ $peserta['status'] === 'selesai' ? 'Lulus' : 'Aktif' }}
                                                    </span>
                                                </div>
                                                @endforeach
                                            </div>
                                            @else
                                            <p class="text-xs text-gray-400 dark:text-gray-500 italic">Belum ada peserta diterima.</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                            <i class="fas fa-university text-3xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <p class="font-bold text-gray-700 dark:text-gray-300">Belum ada data pendaftaran magang</p>
                                        <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">Data akan muncul setelah ada pelamar yang mendaftar.</p>
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
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-purple-500 dark:text-purple-400"></i>
                        Distribusi Pendaftar per Jurusan / Program Studi
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Menampilkan jurusan atau program studi mana yang paling banyak mendaftar dan diterima.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">No</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jurusan / Program Studi</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Pelamar</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Diterima</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rasio Penerimaan</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Visualisasi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                            @foreach($demografiJurusan as $jurusan => $data)
                            <tr class="hover:bg-purple-50/15 dark:hover:bg-purple-950/20 transition">
                                <td class="px-5 py-4 text-xs text-gray-400 dark:text-gray-500 text-center font-bold">{{ $loop->iteration }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-300 flex items-center justify-center text-sm font-black border border-purple-200 dark:border-purple-800/60 flex-shrink-0 shadow-xs">
                                            <i class="fas fa-book-reader text-xs"></i>
                                        </div>
                                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $jurusan }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center"><span class="text-sm font-black text-gray-800 dark:text-gray-100">{{ $data['total'] }}</span></td>
                                <td class="px-5 py-4 text-center"><span class="text-sm font-bold text-green-600 dark:text-green-400">{{ $data['diterima'] }}</span></td>
                                <td class="px-5 py-4 text-center">
                                    @php
                                        $rate = $data['acceptance_rate'];
                                        $rateColor = $rate >= 70 ? 'bg-green-50 dark:bg-green-950/60 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800/60' : ($rate >= 40 ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60' : 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60');
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black {{ $rateColor }}">{{ $rate }}%</span>
                                </td>
                                <td class="px-5 py-4">
                                    @php $maxTotal = $demografiJurusan->max('total'); $barWidth = $maxTotal > 0 ? ($data['total'] / $maxTotal) * 100 : 0; @endphp
                                    <div class="w-full bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-full h-2.5 overflow-hidden">
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
