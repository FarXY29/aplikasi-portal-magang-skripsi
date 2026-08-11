<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-chart-pie text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Laporan Statistik Instansi') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Back Navigation --}}
            <div class="flex justify-between items-center print:hidden">
                <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Pusat Laporan
                </a>
                
                @if($laporan->count() > 0)
                <div class="sm:ml-auto flex-shrink-0 flex flex-wrap gap-2 justify-center">
                    <a href="{{ route('admin.laporan.print', request()->query()) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-teal-900 dark:text-teal-200 rounded-xl hover:bg-teal-50 dark:hover:bg-gray-700 border border-transparent dark:border-gray-700 transition text-xs font-extrabold shadow-xs hover:shadow active:scale-95" title="Download PDF">
                        <i class="fas fa-file-pdf mr-1.5 text-rose-500"></i> PDF
                    </a>
                </div>
                @endif
            </div>

            {{-- Stats Cards Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Total seluruh instansi/dinas Pemerintah Kota Banjarmasin yang terdaftar dalam sistem portal magang.">
                    <div class="w-9 h-9 rounded-2xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-3 border border-teal-100 dark:border-teal-800/60 shadow-xs">
                        <i class="fas fa-building text-xs"></i>
                    </div>
                    <p class="text-xl md:text-2xl font-black text-gray-800 dark:text-gray-100 font-mono tracking-tight">{{ number_format($stats['total_instansi']) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Instansi</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Jumlah posisi lowongan magang berstatus terbuka (buka) yang sedang ditawarkan seluruh instansi.">
                    <div class="w-9 h-9 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-3 border border-blue-100 dark:border-blue-800/60 shadow-xs">
                        <i class="fas fa-briefcase text-xs"></i>
                    </div>
                    <p class="text-xl md:text-2xl font-black text-blue-600 dark:text-blue-400 font-mono tracking-tight">{{ number_format($stats['total_lowongan']) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Lowongan Aktif</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Total akumulasi berkas pendaftaran/lamaran peserta yang masuk ke seluruh instansi Pemko.">
                    <div class="w-9 h-9 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-3 border border-indigo-100 dark:border-indigo-800/60 shadow-xs">
                        <i class="fas fa-users text-xs"></i>
                    </div>
                    <p class="text-xl md:text-2xl font-black text-indigo-600 dark:text-indigo-400 font-mono tracking-tight">{{ number_format($stats['total_pelamar']) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Total Pelamar</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Jumlah peserta magang yang telah diterima (status 'diterima') atau telah lulus/selesai (status 'selesai') secara akumulatif di seluruh instansi.">
                    <div class="w-9 h-9 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-3 border border-emerald-100 dark:border-emerald-800/60 shadow-xs">
                        <i class="fas fa-user-check text-xs"></i>
                    </div>
                    <p class="text-xl md:text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono tracking-tight">{{ number_format($stats['total_diterima']) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Diterima / Lulus</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help bg-gradient-to-br from-teal-50/50 via-white to-indigo-50/30 dark:from-teal-950/20 dark:via-gray-800 dark:to-indigo-950/20" title="Rata-rata persentase kelulusan seleksi tingkat kota. Rumus: (Total Diterima / Total Pelamar) x 100%.">
                    <div class="w-9 h-9 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto mb-3 border border-amber-100 dark:border-amber-800/60 shadow-xs">
                        <i class="fas fa-percentage text-xs"></i>
                    </div>
                    <p class="text-xl md:text-2xl font-black text-amber-600 dark:text-amber-400 font-mono tracking-tight">{{ $stats['avg_seleksi_rate'] }}%</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1.5">Seleksi Kota</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 text-center hover:shadow-md transition cursor-help" title="Instansi/dinas dengan jumlah pendaftar terbanyak se-Kota Banjarmasin.">
                    <div class="w-9 h-9 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-3 border border-rose-100 dark:border-rose-800/60 shadow-xs">
                        <i class="fas fa-award text-xs"></i>
                    </div>
                    <p class="text-xs font-extrabold text-rose-600 dark:text-rose-400 truncate w-full px-1" title="{{ $stats['fav_dinas'] }}">{{ $stats['fav_dinas'] }}</p>
                    <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Instansi Favorit</p>
                </div>
            </div>

            {{-- Highlight Banner --}}
            <div class="bg-gradient-to-r from-teal-800 via-teal-700 to-emerald-700 dark:from-teal-900 dark:via-teal-950 dark:to-emerald-950 rounded-3xl p-6 text-white shadow-md border border-teal-600/30 flex flex-col sm:flex-row items-center gap-6">
                <div class="w-14 h-14 rounded-2xl bg-white/10 dark:bg-gray-800/40 backdrop-blur-md flex items-center justify-center text-3xl flex-shrink-0 border border-white/20 dark:border-gray-700 shadow-md">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="text-center sm:text-left flex-grow space-y-1">
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-teal-200">Statistik Rekapitulasi Program Magang Kota</p>
                    <h2 class="text-xl font-extrabold mt-0.5 tracking-tight">Maju Bersama {{ $stats['total_instansi'] }} Instansi Pemerintahan</h2>
                    <p class="text-xs text-teal-50/90 font-medium">Tingkat seleksi kelulusan peserta kota berada pada kisaran {{ $stats['avg_seleksi_rate'] }}%.</p>
                </div>
            </div>

            {{-- Main Table Card with Integrated Header Filters --}}
            <div class="bg-white dark:bg-gray-800 shadow-xs rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                
                {{-- Card Header: Title & Integrated Right-Side Filter --}}
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-gray-50/50 dark:bg-gray-900/50">
                    <div>
                        <h3 class="font-extrabold text-gray-900 dark:text-gray-100 text-lg flex items-center gap-2.5">
                            <i class="fas fa-building text-teal-600 dark:text-teal-400"></i>
                            Penerimaan & Daya Serap per Instansi
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Rekapitulasi performa daya serap pelamar magang dan efektivitas seleksi untuk masing-masing instansi.</p>
                    </div>

                    {{-- Right-Side Filter Form --}}
                    <form action="{{ route('admin.laporan') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5 items-center w-full lg:w-auto">
                        <div class="relative w-full sm:w-64">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 pointer-events-none">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama dinas..."
                                class="w-full pl-9 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-xl text-xs font-bold focus:ring-teal-500 focus:border-teal-500 shadow-xs">
                        </div>

                        <div class="w-full sm:w-48">
                            <select name="sort" class="w-full py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-100 focus:ring-teal-500 focus:border-teal-500 shadow-xs cursor-pointer [color-scheme:dark]">
                                <option value="pelamar_desc" {{ request('sort') == 'pelamar_desc' ? 'selected' : '' }}>Peminat Terbanyak</option>
                                <option value="pelamar_asc" {{ request('sort') == 'pelamar_asc' ? 'selected' : '' }}>Peminat Tersedikit</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama Instansi (A - Z)</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Instansi (Z - A)</option>
                                <option value="lowongan_desc" {{ request('sort') == 'lowongan_desc' ? 'selected' : '' }}>Lowongan Terbanyak</option>
                                <option value="lowongan_asc" {{ request('sort') == 'lowongan_asc' ? 'selected' : '' }}>Lowongan Tersedikit</option>
                                <option value="seleksi_desc" {{ request('sort') == 'seleksi_desc' ? 'selected' : '' }}>Rasio Kelulusan Tertinggi</option>
                                <option value="seleksi_asc" {{ request('sort') == 'seleksi_asc' ? 'selected' : '' }}>Rasio Kelulusan Terendah</option>
                            </select>
                        </div>

                        <div class="flex gap-2 w-full sm:w-auto">
                            <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 w-full sm:w-auto">
                                <i class="fas fa-filter text-xs"></i> Filter
                            </button>
                            @if(request()->anyFilled(['search', 'sort']))
                                <a href="{{ route('admin.laporan') }}" class="px-3 py-2 border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl font-bold text-xs shadow-xs transition flex items-center justify-center">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Table Content --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
                                <th class="px-6 py-4 w-12 text-center">No</th>
                                <th class="px-6 py-4">Nama Instansi</th>
                                <th class="px-6 py-4 text-center w-36">Lowongan Aktif</th>
                                <th class="px-6 py-4 text-center w-36">Total Pelamar</th>
                                <th class="px-6 py-4 text-center w-36">Diterima / Selesai</th>
                                <th class="px-6 py-4 text-center w-40">Tingkat Seleksi</th>
                                <th class="px-6 py-4 text-center w-44">Rasio Peminat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                            @forelse($laporan as $index => $data)
                            <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-950/20 transition group">
                                <td class="px-6 py-4 text-center text-gray-400 dark:text-gray-500 font-bold text-xs">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-800/60 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            <i class="far fa-building"></i>
                                        </div>
                                        <span class="font-bold text-gray-900 dark:text-gray-100 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition">{{ $data['nama_dinas'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-gray-700 dark:text-gray-300 font-bold bg-gray-100 dark:bg-gray-900 px-3 py-1 rounded-full text-xs border border-gray-200 dark:border-gray-700">
                                        {{ $data['lowongan_aktif'] }} Posisi
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 rounded-full font-bold text-xs border border-blue-200 dark:border-blue-800/60">
                                        {{ $data['total_pelamar'] }} Orang
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 rounded-full font-bold text-xs border border-emerald-200 dark:border-emerald-800/60">
                                        {{ $data['total_magang'] }} Orang
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="font-black text-gray-900 dark:text-gray-100 font-mono">{{ $data['seleksi_rate'] }}%</span>
                                        {{-- Visual progress bar --}}
                                        <div class="w-24 h-1.5 bg-gray-100 dark:bg-gray-900 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                                            <div class="h-full rounded-full bg-gradient-to-r from-teal-500 to-emerald-500" style="width: {{ $data['seleksi_rate'] }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-gray-600 dark:text-gray-400 font-bold italic text-xs">
                                        {{ $data['avg_peminat'] }} <span class="text-[10px] text-gray-400 dark:text-gray-500 font-normal">pelamar/posisi</span>
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-700">
                                            <i class="fas fa-search text-2xl"></i>
                                        </div>
                                        <p class="text-gray-900 dark:text-gray-100 font-bold">Data instansi tidak ditemukan</p>
                                        <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Coba sesuaikan kata kunci pencarian Anda.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($laporan as $index => $data)
                    <div class="p-4 space-y-3.5">
                        {{-- Header: No + Instansi Name --}}
                        <div class="flex items-center gap-3">
                            <span class="text-gray-400 dark:text-gray-500 font-bold text-xs flex-shrink-0">{{ $index + 1 }}</span>
                            <div class="w-8 h-8 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-800/60 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                <i class="far fa-building"></i>
                            </div>
                            <span class="font-bold text-gray-900 dark:text-gray-100 text-sm leading-tight">{{ $data['nama_dinas'] }}</span>
                        </div>

                        {{-- Detail Grid --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">Lowongan Aktif</p>
                                <span class="text-gray-700 dark:text-gray-300 font-bold bg-gray-100 dark:bg-gray-800 px-2.5 py-1 rounded-full text-xs border border-gray-200 dark:border-gray-700">
                                    {{ $data['lowongan_aktif'] }} Posisi
                                </span>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">Total Pelamar</p>
                                <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 rounded-full font-bold text-xs border border-blue-200 dark:border-blue-800/60 inline-block">
                                    {{ $data['total_pelamar'] }} Orang
                                </span>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">Diterima</p>
                                <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 rounded-full font-bold text-xs border border-emerald-200 dark:border-emerald-800/60 inline-block">
                                    {{ $data['total_magang'] }} Orang
                                </span>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">Rasio Peminat</p>
                                <span class="text-gray-600 dark:text-gray-400 font-bold italic text-xs">
                                    {{ $data['avg_peminat'] }} <span class="text-[10px] text-gray-400 dark:text-gray-500 font-normal">pelamar/posisi</span>
                                </span>
                            </div>
                        </div>

                        {{-- Tingkat Seleksi + Progress Bar --}}
                        <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-1.5">
                                <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Tingkat Seleksi</p>
                                <span class="font-black text-gray-900 dark:text-gray-100 font-mono text-sm">{{ $data['seleksi_rate'] }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden border border-transparent dark:border-gray-700">
                                <div class="h-full rounded-full bg-gradient-to-r from-teal-500 to-emerald-500" style="width: {{ $data['seleksi_rate'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-10 flex flex-col items-center justify-center text-center">
                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-700">
                            <i class="fas fa-search text-2xl"></i>
                        </div>
                        <p class="text-gray-900 dark:text-gray-100 font-bold">Data instansi tidak ditemukan</p>
                        <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Coba sesuaikan kata kunci pencarian Anda.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
