<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-chart-pie text-teal-600 dark:text-teal-400"></i>
                {{ __('Laporan Statistik Instansi') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-sm">
                    <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats Cards Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-xl bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-3 border border-teal-100 dark:border-teal-900/40 shadow-xs">
                        <i class="fas fa-building text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100 tracking-tight">{{ $stats['total_instansi'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Instansi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-3 border border-blue-100 dark:border-blue-900/40 shadow-xs">
                        <i class="fas fa-briefcase text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-blue-600 dark:text-blue-400 tracking-tight">{{ $stats['total_lowongan'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Lowongan Aktif</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-3 border border-indigo-100 dark:border-indigo-900/40 shadow-xs">
                        <i class="fas fa-users text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400 tracking-tight">{{ $stats['total_pelamar'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Pelamar</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-xl bg-green-50 dark:bg-green-950/40 text-green-600 dark:text-green-400 flex items-center justify-center mx-auto mb-3 border border-green-100 dark:border-green-900/40 shadow-xs">
                        <i class="fas fa-user-check text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-green-600 dark:text-green-400 tracking-tight">{{ $stats['total_diterima'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Diterima / Lulus</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto mb-3 border border-amber-100 dark:border-amber-900/40 shadow-xs">
                        <i class="fas fa-percentage text-xs"></i>
                    </div>
                    <p class="text-2xl font-black text-amber-600 dark:text-amber-400 tracking-tight">{{ $stats['avg_seleksi_rate'] }}%</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Seleksi Kota</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition">
                    <div class="w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-3 border border-rose-100 dark:border-rose-900/40 shadow-xs">
                        <i class="fas fa-award text-xs"></i>
                    </div>
                    <p class="text-xs font-extrabold text-rose-600 dark:text-rose-400 truncate w-full px-1" title="{{ $stats['fav_dinas'] }}">{{ $stats['fav_dinas'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Instansi Favorit</p>
                </div>
            </div>

            {{-- Highlight Banner --}}
            <div class="bg-gradient-to-r from-teal-800 via-teal-700 to-emerald-700 dark:from-teal-900 dark:via-teal-950 dark:to-emerald-950 rounded-3xl p-6 text-white shadow-lg border border-teal-600/30 flex flex-col sm:flex-row items-center gap-6">
                <div class="w-14 h-14 rounded-2xl bg-white/10 dark:bg-gray-800/40 backdrop-blur-md flex items-center justify-center text-3xl flex-shrink-0 border border-white/20 dark:border-gray-700 shadow-md">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="text-center sm:text-left flex-grow space-y-1">
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-teal-200">Statistik Rekapitulasi Program Magang Kota</p>
                    <h2 class="text-xl font-extrabold mt-0.5 tracking-tight">Maju Bersama {{ $stats['total_instansi'] }} Instansi Pemerintahan</h2>
                    <p class="text-xs text-teal-50/90 font-medium">Tingkat seleksi kelulusan peserta kota berada pada kisaran {{ $stats['avg_seleksi_rate'] }}%.</p>
                </div>
                @if($laporan->count() > 0)
                <div class="sm:ml-auto flex-shrink-0 flex gap-2">
                    <a href="{{ route('admin.laporan.print', array_merge(request()->query(), ['format' => 'pdf'])) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-teal-900 dark:text-teal-200 rounded-xl hover:bg-teal-50 dark:hover:bg-gray-700 border border-transparent dark:border-gray-700 transition text-xs font-extrabold shadow-sm hover:shadow active:scale-95" title="Download PDF">
                        <i class="fas fa-file-pdf mr-1.5 text-red-500"></i> PDF
                    </a>
                    <a href="{{ route('admin.laporan.print', array_merge(request()->query(), ['format' => 'excel'])) }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-teal-900 dark:text-teal-200 rounded-xl hover:bg-teal-50 dark:hover:bg-gray-700 border border-transparent dark:border-gray-700 transition text-xs font-extrabold shadow-sm hover:shadow active:scale-95" title="Download Excel">
                        <i class="fas fa-file-excel mr-1.5 text-green-600"></i> Excel
                    </a>
                    <a href="{{ route('admin.laporan.print', array_merge(request()->query(), ['format' => 'csv'])) }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-teal-900 dark:text-teal-200 rounded-xl hover:bg-teal-50 dark:hover:bg-gray-700 border border-transparent dark:border-gray-700 transition text-xs font-extrabold shadow-sm hover:shadow active:scale-95" title="Download CSV">
                        <i class="fas fa-file-csv mr-1.5 text-blue-600"></i> CSV
                    </a>
                </div>
                @endif
            </div>

            {{-- Search & Sorting Panel --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                <form action="{{ route('admin.laporan') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full md:flex-grow">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Pencarian Instansi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama dinas / badan pemerintahan..."
                                class="w-full pl-9 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                        </div>
                    </div>
                    <div class="w-full md:w-64">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Urutkan Data</label>
                        <select name="sort" class="w-full border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-teal-500 focus:border-teal-500 shadow-sm bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 cursor-pointer">
                            <option value="pelamar_desc" {{ request('sort') == 'pelamar_desc' ? 'selected' : '' }}>Peminat Terbanyak (Default)</option>
                            <option value="pelamar_asc" {{ request('sort') == 'pelamar_asc' ? 'selected' : '' }}>Peminat Tersedikit</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama Instansi (A - Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Instansi (Z - A)</option>
                            <option value="lowongan_desc" {{ request('sort') == 'lowongan_desc' ? 'selected' : '' }}>Lowongan Terbanyak</option>
                            <option value="lowongan_asc" {{ request('sort') == 'lowongan_asc' ? 'selected' : '' }}>Lowongan Tersedikit</option>
                            <option value="seleksi_desc" {{ request('sort') == 'seleksi_desc' ? 'selected' : '' }}>Rasio Kelulusan Tertinggi</option>
                            <option value="seleksi_asc" {{ request('sort') == 'seleksi_asc' ? 'selected' : '' }}>Rasio Kelulusan Terendah</option>
                        </select>
                    </div>
                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="submit" class="flex-grow md:flex-none bg-teal-600 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-teal-500/20 hover:bg-teal-700 transition transform active:scale-95 text-sm flex items-center justify-center gap-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        @if(request()->anyFilled(['search', 'sort']))
                        <a href="{{ route('admin.laporan') }}" class="inline-flex items-center justify-center border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition">
                            Reset
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Main Table Card --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-bold text-gray-800 dark:text-gray-200 text-lg">Penerimaan & Daya Serap per Instansi</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Daftar rekapitulasi performa daya serap pelamar magang dan efektivitas seleksi untuk masing-masing instansi.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
                                <th class="px-6 py-4 w-12 text-center">No</th>
                                <th class="px-6 py-4">Nama Instansi</th>
                                <th class="px-6 py-4 text-center w-36">Lowongan Aktif</th>
                                <th class="px-6 py-4 text-center w-36">Total Pelamar</th>
                                <th class="px-6 py-4 text-center w-36">Diterima / Selesai</th>
                                <th class="px-6 py-4 text-center w-40">Tingkat Seleksi</th>
                                <th class="px-6 py-4 text-center w-44">Rasio Peminat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/60 text-sm">
                            @forelse($laporan as $index => $data)
                            <tr class="hover:bg-teal-50/15 dark:hover:bg-gray-900/60 transition group">
                                <td class="px-6 py-4 text-center text-gray-400 dark:text-gray-500 font-bold">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 border border-teal-100 dark:border-teal-900/40 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            <i class="far fa-building"></i>
                                        </div>
                                        <span class="font-bold text-gray-800 dark:text-gray-200 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition">{{ $data['nama_dinas'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-gray-700 dark:text-gray-300 font-semibold bg-gray-100 dark:bg-gray-900 px-3 py-1 rounded-full text-xs border border-gray-200 dark:border-gray-700">
                                        {{ $data['lowongan_aktif'] }} Posisi
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 rounded-full font-bold text-xs border border-blue-100 dark:border-blue-900/40">
                                        {{ $data['total_pelamar'] }} Orang
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-green-50 dark:bg-green-950/40 text-green-700 dark:text-green-400 rounded-full font-bold text-xs border border-green-100 dark:border-green-900/40">
                                        {{ $data['total_magang'] }} Orang
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="font-black text-gray-800 dark:text-gray-200">{{ $data['seleksi_rate'] }}%</span>
                                        {{-- Visual progress bar --}}
                                        <div class="w-24 h-1.5 bg-gray-100 dark:bg-gray-900 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                                            <div class="h-full rounded-full bg-gradient-to-r from-teal-500 to-emerald-500" style="width: {{ $data['seleksi_rate'] }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium italic">
                                        {{ $data['avg_peminat'] }} <span class="text-[10px] text-gray-400 dark:text-gray-500 font-normal">pelamar/posisi</span>
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 text-gray-300 dark:text-gray-600 border border-transparent dark:border-gray-700">
                                            <i class="fas fa-search text-2xl"></i>
                                        </div>
                                        <p class="text-gray-900 dark:text-gray-100 font-bold">Data instansi tidak ditemukan</p>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Coba sesuaikan kata kunci pencarian Anda.</p>
                                    </div>
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