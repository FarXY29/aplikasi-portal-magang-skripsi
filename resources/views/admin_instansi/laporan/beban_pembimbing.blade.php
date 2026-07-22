<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-chalkboard-teacher text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Laporan Kinerja Pembimbing') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('dinas.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                    <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Statistik Ringkasan --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-2.5 border border-teal-100 dark:border-teal-900/50">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100">{{ $stats['total_pembimbing'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Pembimbing</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-2.5 border border-blue-100 dark:border-blue-900/50">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800 dark:text-gray-100">{{ $stats['total_bimbingan_aktif'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Bimbingan Aktif</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-2.5 border border-emerald-100 dark:border-emerald-900/50">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <p class="text-2xl font-black text-emerald-700 dark:text-emerald-400">{{ $stats['total_lulus'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Alumni Lulus</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-2.5 border border-indigo-100 dark:border-indigo-900/50">
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-2xl font-black text-indigo-700 dark:text-indigo-400">{{ $stats['rata_nilai_semua'] > 0 ? round($stats['rata_nilai_semua'], 1) : '-' }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Rata-Rata Nilai</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-950/60 text-red-600 dark:text-red-400 flex items-center justify-center mx-auto mb-2.5 border border-red-100 dark:border-red-900/50">
                        <i class="fas fa-clock"></i>
                    </div>
                    <p class="text-2xl font-black text-red-700 dark:text-red-400">{{ $stats['total_logbook_tertunda'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Logbook Pending</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 shadow-xs border border-gray-100 dark:border-gray-700 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-green-50 dark:bg-green-950/60 text-green-600 dark:text-green-400 flex items-center justify-center mx-auto mb-2.5 border border-green-100 dark:border-green-900/50">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <p class="text-2xl font-black text-green-700 dark:text-green-400">{{ $stats['tertib_validasi'] }}</p>
                    <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">Tertib Validasi</p>
                </div>
            </div>

            {{-- Highlight Banner --}}
            @if($stats['total_pembimbing'] > 0)
            <div class="bg-gradient-to-r from-teal-600 to-emerald-600 rounded-3xl p-6 text-white shadow-lg shadow-teal-600/20 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 dark:bg-gray-800/30 backdrop-blur-sm flex items-center justify-center text-2xl flex-shrink-0 border border-white/20">
                    <i class="fas fa-award"></i>
                </div>
                <div class="text-center sm:text-left flex-grow">
                    <p class="text-xs font-bold uppercase tracking-wider text-teal-100">Pembimbing Lapangan Teraktif (Beban Tertinggi)</p>
                    <p class="text-xl font-black mt-0.5">{{ $stats['pembimbing_teraktif'] }}</p>
                    <p class="text-sm text-teal-100 font-medium">Membimbing {{ $stats['pembimbing_teraktif_jumlah'] }} mahasiswa aktif pada periode saat ini.</p>
                </div>
                @if($beban->count() > 0)
                <div class="sm:ml-auto flex-shrink-0 flex gap-2">
                    <a href="{{ route('dinas.laporan.beban_pembimbing.print', ['format' => 'pdf']) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300 rounded-xl hover:bg-teal-50 dark:hover:bg-gray-700 transition text-xs font-bold shadow-md border border-white/20 dark:border-gray-700" title="Download PDF">
                        <i class="fas fa-file-pdf mr-1.5 text-red-500"></i> PDF
                    </a>
                    <a href="{{ route('dinas.laporan.beban_pembimbing.print', ['format' => 'excel']) }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300 rounded-xl hover:bg-teal-50 dark:hover:bg-gray-700 transition text-xs font-bold shadow-md border border-white/20 dark:border-gray-700" title="Download Excel">
                        <i class="fas fa-file-excel mr-1.5 text-green-600"></i> Excel
                    </a>
                    <a href="{{ route('dinas.laporan.beban_pembimbing.print', ['format' => 'csv']) }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 text-teal-700 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300 rounded-xl hover:bg-teal-50 dark:hover:bg-gray-700 transition text-xs font-bold shadow-md border border-white/20 dark:border-gray-700" title="Download CSV">
                        <i class="fas fa-file-csv mr-1.5 text-blue-600"></i> CSV
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- Tabel Evaluasi Pembimbing --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-chalkboard-teacher text-teal-600 dark:text-teal-400"></i>
                        Evaluasi Beban Kerja & Kinerja Pembimbing Lapangan
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Klik baris pembimbing untuk melihat detail data bimbingan aktif, riwayat lulusan, kepatuhan validasi logbook, serta absensi.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">No</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Pembimbing / Pegawai</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bimbingan Aktif</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alumni Lulus</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Logbook Pending</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rata-rata Nilai</th>
                                <th class="px-5 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/60 text-sm" x-data="{ openRow: null }">
                            @forelse($beban as $pl)
                            <tr class="hover:bg-teal-50/15 dark:hover:bg-teal-900/20 transition cursor-pointer" @click="openRow = openRow === {{ $loop->index }} ? null : {{ $loop->index }}">
                                <td class="px-5 py-4 text-xs text-gray-400 dark:text-gray-500 text-center font-bold">{{ $loop->iteration }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-300 flex items-center justify-center text-sm font-black border border-teal-200 dark:border-teal-800/60 flex-shrink-0 shadow-xs">
                                            {{ strtoupper(substr($pl->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $pl->name }}</p>
                                            <div class="flex flex-wrap items-center gap-x-2 text-[10px] text-gray-500 dark:text-gray-400 font-medium mt-0.5">
                                                <span>NIP/NIK: {{ $pl->nik ?? '-' }}</span>
                                                <span>•</span>
                                                <span>{{ $pl->email }}</span>
                                                @if($pl->phone)
                                                <span>•</span>
                                                <span>{{ $pl->phone }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-800/60">
                                        {{ $pl->total_bimbingan_aktif }} Orang
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-800/60">
                                        {{ $pl->total_lulus }} Orang
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($pl->logbook_tertunda > 0)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60 animate-pulse">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> {{ $pl->logbook_tertunda }} Pending
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-800/60">
                                        <i class="fas fa-check-circle mr-1"></i> Tuntas
                                    </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($pl->rata_nilai_diberikan > 0)
                                    <span class="text-xs font-black text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 px-3 py-1 rounded-full inline-block">{{ round($pl->rata_nilai_diberikan, 1) }}</span>
                                    @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 text-xs transition-transform duration-200" :class="openRow === {{ $loop->index }} ? 'rotate-180 text-teal-600 dark:text-teal-400' : ''"></i>
                                </td>
                            </tr>
                            
                            {{-- Row Detail --}}
                            <tr x-show="openRow === {{ $loop->index }}" x-transition.opacity x-cloak>
                                <td colspan="7" class="px-5 py-4 bg-gray-50/60 dark:bg-gray-900/60 border-y border-gray-100 dark:border-gray-700">
                                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                                        
                                        {{-- Mahasiswa Bimbingan Aktif --}}
                                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-xs space-y-4">
                                            <div class="flex items-center justify-between border-b pb-3 border-gray-100 dark:border-gray-700">
                                                <h4 class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                                    <i class="fas fa-user-friends text-blue-500 dark:text-blue-400"></i>
                                                    Bimbingan Aktif saat ini ({{ count($pl->mahasiswa_aktif) }})
                                                </h4>
                                            </div>
                                            
                                            @if(count($pl->mahasiswa_aktif) > 0)
                                            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                                                @foreach($pl->mahasiswa_aktif as $mhs)
                                                <div class="p-3 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between gap-3 items-start md:items-center">
                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-xs font-black text-gray-900 dark:text-gray-100 truncate">{{ $mhs['nama'] }}</p>
                                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold truncate">{{ $mhs['kampus'] }} — {{ $mhs['jurusan'] }}</p>
                                                        <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                                            <span class="inline-block px-2 py-0.5 bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-100 dark:border-teal-900/40 text-[9px] font-bold rounded-md">
                                                                {{ $mhs['posisi'] }}
                                                            </span>
                                                            <span class="text-[9px] text-gray-400 dark:text-gray-500 font-medium">
                                                                {{ \Carbon\Carbon::parse($mhs['mulai'])->format('d M') }} s/d {{ \Carbon\Carbon::parse($mhs['selesai'])->format('d M Y') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="flex md:flex-col justify-between items-end gap-2 text-right flex-shrink-0">
                                                        {{-- Progress Logbook --}}
                                                        <div class="w-full md:w-32">
                                                            <div class="flex items-center justify-between text-[9px] text-gray-400 dark:text-gray-500 font-bold mb-1">
                                                                <span>Logbook:</span>
                                                                <span>{{ $mhs['logbook']['disetujui'] }}/{{ $mhs['logbook']['total'] }}</span>
                                                            </div>
                                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 overflow-hidden">
                                                                <div class="bg-teal-500 h-1.5 rounded-full" style="width: {{ $mhs['logbook']['rate'] }}%"></div>
                                                            </div>
                                                            @if($mhs['logbook']['pending'] > 0 || $mhs['logbook']['revisi'] > 0)
                                                            <div class="flex gap-1.5 justify-end mt-1 text-[8px] font-black">
                                                                @if($mhs['logbook']['pending'] > 0)
                                                                <span class="text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/60 px-1 py-0.5 rounded">{{ $mhs['logbook']['pending'] }} Pending</span>
                                                                @endif
                                                                @if($mhs['logbook']['revisi'] > 0)
                                                                <span class="text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/60 px-1 py-0.5 rounded">{{ $mhs['logbook']['revisi'] }} Revisi</span>
                                                                @endif
                                                            </div>
                                                            @endif
                                                        </div>

                                                        {{-- Absensi Pending --}}
                                                        @if($mhs['absensi']['pending'] > 0)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60">
                                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $mhs['absensi']['pending'] }} Izin/Sakit Pending
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            @else
                                            <p class="text-xs text-gray-400 dark:text-gray-500 italic py-8 text-center">Tidak ada bimbingan aktif saat ini.</p>
                                            @endif
                                        </div>

                                        {{-- Mahasiswa Alumni/Lulus --}}
                                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-xs space-y-4">
                                            <div class="flex items-center justify-between border-b pb-3 border-gray-100 dark:border-gray-700">
                                                <h4 class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                                    <i class="fas fa-graduation-cap text-emerald-600 dark:text-emerald-400"></i>
                                                    Alumni Bimbingan Lulus ({{ count($pl->mahasiswa_lulus) }})
                                                </h4>
                                            </div>
                                            
                                            @if(count($pl->mahasiswa_lulus) > 0)
                                            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                                                @foreach($pl->mahasiswa_lulus as $mhs)
                                                <div class="p-3 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 flex flex-col justify-between gap-2">
                                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2 border-b pb-2 border-gray-200 dark:border-gray-700">
                                                        <div>
                                                            <p class="text-xs font-black text-gray-900 dark:text-gray-100 truncate">{{ $mhs['nama'] }}</p>
                                                            <p class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold truncate">{{ $mhs['kampus'] }} — {{ $mhs['jurusan'] }}</p>
                                                            <p class="text-[9px] text-teal-600 dark:text-teal-400 font-bold mt-0.5">Posisi: {{ $mhs['posisi'] }}</p>
                                                        </div>
                                                        <div class="text-right flex flex-row md:flex-col items-center md:items-end gap-1.5 flex-shrink-0">
                                                            <span class="text-xs font-black text-gray-800 dark:text-gray-200 bg-gray-200 dark:bg-gray-800 px-2 py-0.5 rounded border border-gray-300 dark:border-gray-700">
                                                                Nilai: {{ $mhs['nilai'] }}
                                                            </span>
                                                            <span class="text-[9px] font-black text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-emerald-800/60 px-1.5 py-0.5 rounded">
                                                                {{ $mhs['predikat'] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-1 mt-1">
                                                        @if($mhs['nomor_sertifikat'])
                                                        <p class="text-[9px] text-gray-500 dark:text-gray-400 font-bold">
                                                            <i class="fas fa-certificate text-yellow-500 mr-1"></i> No. Sertifikat: <span class="font-mono text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 px-1.5 py-0.5 border rounded border-gray-300 dark:border-gray-700">{{ $mhs['nomor_sertifikat'] }}</span>
                                                        </p>
                                                        @endif
                                                        <div class="text-[10px] text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 p-2 rounded-xl border border-gray-200 dark:border-gray-700">
                                                            <span class="font-bold text-gray-400 dark:text-gray-500 text-[9px] uppercase block mb-0.5">Catatan Evaluasi:</span>
                                                            {{ $mhs['catatan'] ?: 'Tidak ada catatan khusus.' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            @else
                                            <p class="text-xs text-gray-400 dark:text-gray-500 italic py-8 text-center">Belum ada mahasiswa bimbingan yang selesai.</p>
                                            @endif
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3 border border-gray-200 dark:border-gray-700">
                                            <i class="fas fa-chalkboard-teacher text-3xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <p class="font-bold text-gray-700 dark:text-gray-300">Belum ada data Pembimbing Lapangan</p>
                                        <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">Akun pembimbing lapangan perlu dibuat terlebih dahulu di menu manajemen akun.</p>
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
