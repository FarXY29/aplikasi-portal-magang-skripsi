<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-3">
                <div>
                    <h2 class="font-black text-xl text-gray-800 dark:text-gray-100 leading-tight flex items-center gap-2">
                        <i class="fas fa-university text-orange-500"></i>
                        {{ __('Laporan Demografi Kampus & Sekolah') }}
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Ringkasan persebaran asal kampus, sekolah, dan jurusan pendaftar magang.</p>
                </div>
            </div>

        </div>
    </x-slot>

    <div class="py-6 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <a href="{{ route('dinas.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                    <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
            {{-- Ringkasan 4 Kartu Utama --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-100 dark:border-gray-700/60 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 dark:bg-orange-950/60 text-orange-600 dark:text-orange-400 flex items-center justify-center text-xl flex-shrink-0 border border-orange-100 dark:border-orange-900/50">
                        <i class="fas fa-university"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900 dark:text-gray-100">{{ $stats['total_kampus'] }}</p>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Asal Kampus/Sekolah</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-100 dark:border-gray-700/60 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl flex-shrink-0 border border-blue-100 dark:border-blue-900/50">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900 dark:text-gray-100">{{ $stats['total_pelamar'] }}</p>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pelamar</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-100 dark:border-gray-700/60 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-950/60 text-green-600 dark:text-green-400 flex items-center justify-center text-xl flex-shrink-0 border border-green-100 dark:border-green-900/50">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-green-600 dark:text-green-400">{{ $stats['total_diterima'] }}</p>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Diterima</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-100 dark:border-gray-700/60 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl flex-shrink-0 border border-purple-100 dark:border-purple-900/50">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-purple-600 dark:text-purple-400">{{ $stats['total_selesai'] }}</p>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Lulus / Selesai</p>
                    </div>
                </div>
            </div>

            {{-- Highlight Kontributor Terbanyak --}}
            @if($stats['total_kampus'] > 0 && !empty($stats['kampus_terbanyak']))
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-orange-200 dark:border-orange-900/50 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-orange-500 text-white flex items-center justify-center text-lg flex-shrink-0 shadow-xs">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-orange-600 dark:text-orange-400">Kontributor Pelamar Terbanyak</span>
                        <h3 class="text-base font-black text-gray-900 dark:text-gray-100">{{ $stats['kampus_terbanyak'] }}</h3>
                    </div>
                </div>

                {{-- Tombol Export --}}
                @if($demografi->count() > 0)
                <div class="flex flex-col sm:flex-row gap-2">
                    <a href="{{ route('dinas.laporan.demografi_kampus.print', ['format' => 'pdf']) }}" target="_blank" class="inline-flex items-center px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-xs transition gap-1.5">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <a href="{{ route('dinas.laporan.demografi_kampus.print', ['format' => 'excel']) }}" class="inline-flex items-center px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition gap-1.5">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a href="{{ route('dinas.laporan.demografi_kampus.print', ['format' => 'csv']) }}" class="inline-flex items-center px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition gap-1.5">
                        <i class="fas fa-file-csv"></i> CSV
                    </a>
                </div>
                @endif
                <div class="px-4 py-1.5 bg-orange-50 dark:bg-orange-950/50 border border-orange-200 dark:border-orange-900/60 rounded-xl text-xs font-bold text-orange-700 dark:text-orange-300">
                    {{ $stats['kampus_terbanyak_jumlah'] }} Pelamar
                </div>
                
            </div>
            @endif

            {{-- Tabel 1: Demografi per Kampus / Sekolah --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <i class="fas fa-building text-orange-500"></i>
                            Pendaftar Berdasarkan Kampus / Sekolah
                        </h3>
                    </div>
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500">{{ $demografi->count() }} Instansi</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 dark:bg-gray-900/80 text-[11px] font-black uppercase text-gray-400 tracking-wider">
                            <tr>
                                <th class="px-5 py-3.5 w-12 text-center">No</th>
                                <th class="px-5 py-3.5">Nama Kampus / Sekolah</th>
                                <th class="px-5 py-3.5 text-center">Pelamar</th>
                                <th class="px-5 py-3.5 text-center">Diterima</th>
                                <th class="px-5 py-3.5 text-center">Selesai</th>
                                <th class="px-5 py-3.5 text-center">Rasio Diterima</th>
                                <th class="px-5 py-3.5 text-center w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 text-xs" x-data="{ openRow: null }">
                            @forelse($demografi as $kampus => $data)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition cursor-pointer" @click="openRow = openRow === {{ $loop->index }} ? null : {{ $loop->index }}">
                                <td class="px-5 py-3.5 text-center font-bold text-gray-400">{{ $loop->iteration }}</td>
                                <td class="px-5 py-3.5 font-bold text-gray-900 dark:text-gray-100">
                                    {{ $kampus }}
                                    <span class="block text-[10px] font-normal text-gray-400 mt-0.5">{{ $data['jurusan']->count() }} Jurusan</span>
                                </td>
                                <td class="px-5 py-3.5 text-center font-black text-gray-800 dark:text-gray-200">{{ $data['total_pelamar'] }}</td>
                                <td class="px-5 py-3.5 text-center font-bold text-green-600 dark:text-green-400">{{ $data['diterima'] }}</td>
                                <td class="px-5 py-3.5 text-center font-bold text-purple-600 dark:text-purple-400">{{ $data['selesai'] }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    @php
                                        $rate = $data['acceptance_rate'];
                                        $badgeClass = $rate >= 60 ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-300' : ($rate >= 30 ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300');
                                    @endphp
                                    <span class="inline-block px-2 py-0.5 rounded-md font-extrabold text-[10px] {{ $badgeClass }}">
                                        {{ $rate }}%
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" :class="openRow === {{ $loop->index }} ? 'rotate-180 text-orange-500' : ''"></i>
                                </td>
                            </tr>
                            {{-- Dropdown Detail --}}
                            <tr x-show="openRow === {{ $loop->index }}" x-transition.opacity x-cloak>
                                <td colspan="7" class="px-5 py-4 bg-gray-50/60 dark:bg-gray-900/50 border-y border-gray-100 dark:border-gray-700">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {{-- Jurusan --}}
                                        <div class="bg-white dark:bg-gray-800 p-3.5 rounded-xl border border-gray-200 dark:border-gray-700">
                                            <p class="text-[10px] font-black uppercase text-gray-400 tracking-wider mb-2"><i class="fas fa-graduation-cap text-purple-500 mr-1"></i> Rincian Jurusan</p>
                                            <div class="space-y-1">
                                                @foreach($data['jurusan'] as $jurusan => $count)
                                                <div class="flex justify-between text-xs">
                                                    <span class="text-gray-700 dark:text-gray-300">{{ $jurusan }}</span>
                                                    <span class="font-bold text-gray-900 dark:text-gray-100">{{ $count }} orang</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        {{-- Peserta Diterima --}}
                                        <div class="bg-white dark:bg-gray-800 p-3.5 rounded-xl border border-gray-200 dark:border-gray-700">
                                            <p class="text-[10px] font-black uppercase text-gray-400 tracking-wider mb-2"><i class="fas fa-user-check text-green-500 mr-1"></i> Peserta Diterima ({{ $data['peserta_aktif']->count() }})</p>
                                            @if($data['peserta_aktif']->count() > 0)
                                            <div class="space-y-1 max-h-36 overflow-y-auto">
                                                @foreach($data['peserta_aktif'] as $peserta)
                                                <div class="flex justify-between items-center text-xs py-0.5">
                                                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $peserta['nama'] }} <span class="text-[10px] font-normal text-gray-400">({{ $peserta['posisi'] }})</span></span>
                                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $peserta['status'] === 'selesai' ? 'bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300' : 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-300' }}">
                                                        {{ $peserta['status'] === 'selesai' ? 'Selesai' : 'Aktif' }}
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
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">Belum ada data pendaftar magang.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel 2: Demografi per Jurusan --}}
            @if($demografiJurusan->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-purple-500"></i>
                        Pendaftar Berdasarkan Jurusan / Program Studi
                    </h3>
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500">{{ $demografiJurusan->count() }} Jurusan</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 dark:bg-gray-900/80 text-[11px] font-black uppercase text-gray-400 tracking-wider">
                            <tr>
                                <th class="px-5 py-3.5 w-12 text-center">No</th>
                                <th class="px-5 py-3.5">Jurusan / Program Studi</th>
                                <th class="px-5 py-3.5 text-center">Total Pelamar</th>
                                <th class="px-5 py-3.5 text-center">Diterima</th>
                                <th class="px-5 py-3.5 text-center">Rasio Diterima</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 text-xs">
                            @foreach($demografiJurusan as $jurusan => $data)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition">
                                <td class="px-5 py-3.5 text-center font-bold text-gray-400">{{ $loop->iteration }}</td>
                                <td class="px-5 py-3.5 font-bold text-gray-900 dark:text-gray-100">{{ $jurusan }}</td>
                                <td class="px-5 py-3.5 text-center font-black text-gray-800 dark:text-gray-200">{{ $data['total'] }}</td>
                                <td class="px-5 py-3.5 text-center font-bold text-green-600 dark:text-green-400">{{ $data['diterima'] }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    @php
                                        $rate = $data['acceptance_rate'];
                                        $badgeClass = $rate >= 60 ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-300' : ($rate >= 30 ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300');
                                    @endphp
                                    <span class="inline-block px-2 py-0.5 rounded-md font-extrabold text-[10px] {{ $badgeClass }}">{{ $rate }}%</span>
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
