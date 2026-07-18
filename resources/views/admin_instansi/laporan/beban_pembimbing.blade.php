<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-chalkboard-teacher text-teal-600"></i>
                {{ __('Laporan Kinerja Pembimbing') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('dinas.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Statistik Ringkasan --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
                    <div class="w-10 h-10 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center mx-auto mb-3 border border-teal-100">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800">{{ $stats['total_pembimbing'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Pembimbing</p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3 border border-blue-100">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800">{{ $stats['total_bimbingan_aktif'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Bimbingan Aktif</p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 border border-emerald-100">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <p class="text-2xl font-black text-emerald-700">{{ $stats['total_lulus'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Alumni Lulus</p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3 border border-indigo-100">
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-2xl font-black text-indigo-700">{{ $stats['rata_nilai_semua'] > 0 ? round($stats['rata_nilai_semua'], 1) : '-' }}</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Rata-Rata Nilai</p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
                    <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-3 border border-red-100">
                        <i class="fas fa-clock"></i>
                    </div>
                    <p class="text-2xl font-black text-red-700">{{ $stats['total_logbook_tertunda'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Logbook Pending</p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
                    <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center mx-auto mb-3 border border-green-100">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <p class="text-2xl font-black text-green-700">{{ $stats['tertib_validasi'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Tertib Validasi</p>
                </div>
            </div>

            {{-- Highlight Banner --}}
            @if($stats['total_pembimbing'] > 0)
            <div class="bg-gradient-to-r from-teal-600 to-emerald-600 rounded-2xl p-6 text-white shadow-lg shadow-teal-600/20 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fas fa-award"></i>
                </div>
                <div class="text-center sm:text-left flex-grow">
                    <p class="text-xs font-bold uppercase tracking-wider text-teal-100">Pembimbing Lapangan Teraktif (Beban Tertinggi)</p>
                    <p class="text-xl font-black mt-0.5">{{ $stats['pembimbing_teraktif'] }}</p>
                    <p class="text-sm text-teal-100 font-medium">Membimbing {{ $stats['pembimbing_teraktif_jumlah'] }} mahasiswa aktif pada periode saat ini.</p>
                </div>
                @if($beban->count() > 0)
                <div class="sm:ml-auto">
                    <div class="flex gap-2">
                            <a href="{{ route('dinas.laporan.beban_pembimbing.print', ['format' => 'pdf']) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition text-sm font-bold shadow-md border border-white/30 gap-1.5" title="Download PDF">
                                <i class="fas fa-file-pdf text-red-500"></i> PDF
                            </a>
                            <a href="{{ route('dinas.laporan.beban_pembimbing.print', ['format' => 'excel']) }}" class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition text-sm font-bold shadow-md border border-white/30 gap-1.5" title="Download Excel">
                                <i class="fas fa-file-excel text-green-600"></i> Excel
                            </a>
                            <a href="{{ route('dinas.laporan.beban_pembimbing.print', ['format' => 'csv']) }}" class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition text-sm font-bold shadow-md border border-white/30 gap-1.5" title="Download CSV">
                                <i class="fas fa-file-csv text-blue-600"></i> CSV
                            </a>
                        </div>
                </div>
                @endif
            </div>
            @endif

            {{-- Tabel Evaluasi Pembimbing --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-chalkboard-teacher text-teal-500"></i>
                        Evaluasi Beban Kerja & Kinerja Pembimbing Lapangan
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Klik baris pembimbing untuk melihat detail data bimbingan aktif, riwayat lulusan, kepatuhan validasi logbook, serta absensi.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-12 text-center">No</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Pembimbing / Pegawai</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Bimbingan Aktif</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Alumni Lulus</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Logbook Pending</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Rata-rata Nilai</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-12"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50" x-data="{ openRow: null }">
                            @forelse($beban as $pl)
                            <tr class="hover:bg-teal-50/30 transition cursor-pointer" @click="openRow = openRow === {{ $loop->index }} ? null : {{ $loop->index }}">
                                <td class="px-5 py-4 text-xs text-gray-400 text-center font-bold">{{ $loop->iteration }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center text-sm font-black border border-teal-100 flex-shrink-0">
                                            {{ strtoupper(substr($pl->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $pl->name }}</p>
                                            <div class="flex flex-wrap items-center gap-x-2 text-[10px] text-gray-455 font-medium">
                                                <span>NIP/NIK: {{ $pl->nik ?? '-' }}</span>
                                                <span class="text-gray-300">•</span>
                                                <span>{{ $pl->email }}</span>
                                                @if($pl->phone)
                                                <span class="text-gray-300">•</span>
                                                <span>{{ $pl->phone }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                                        {{ $pl->total_bimbingan_aktif }} Orang
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                                        {{ $pl->total_lulus }} Orang
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($pl->logbook_tertunda > 0)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-red-100 text-red-700 animate-pulse">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> {{ $pl->logbook_tertunda }} Pending
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700">
                                        <i class="fas fa-check-circle mr-1"></i> Tuntas
                                    </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($pl->rata_nilai_diberikan > 0)
                                    <span class="text-sm font-black text-gray-800 bg-gray-100 px-2 py-0.5 rounded-full inline-block">{{ round($pl->rata_nilai_diberikan, 1) }}</span>
                                    @else
                                    <span class="text-xs text-gray-400 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" :class="openRow === {{ $loop->index }} ? 'rotate-180 text-teal-500' : ''"></i>
                                </td>
                            </tr>
                            
                            {{-- Row Detail --}}
                            <tr x-show="openRow === {{ $loop->index }}" x-transition.opacity x-cloak>
                                <td colspan="7" class="px-5 py-4 bg-gray-50/50 border-y border-gray-100">
                                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                                        
                                        {{-- Mahasiswa Bimbingan Aktif --}}
                                        <div class="bg-white rounded-xl border border-gray-150 p-5 shadow-sm space-y-4">
                                            <div class="flex items-center justify-between border-b pb-3 border-gray-100">
                                                <h4 class="text-xs font-black text-gray-700 uppercase tracking-wider flex items-center gap-2">
                                                    <i class="fas fa-user-friends text-blue-500"></i>
                                                    Bimbingan Aktif saat ini ({{ count($pl->mahasiswa_aktif) }})
                                                </h4>
                                            </div>
                                            
                                            @if(count($pl->mahasiswa_aktif) > 0)
                                            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                                                @foreach($pl->mahasiswa_aktif as $mhs)
                                                <div class="p-3 bg-gray-50 rounded-xl border border-gray-150 flex flex-col md:flex-row justify-between gap-3 items-start md:items-center">
                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-xs font-black text-gray-900 truncate">{{ $mhs['nama'] }}</p>
                                                        <p class="text-[10px] text-gray-500 font-semibold truncate">{{ $mhs['kampus'] }} — {{ $mhs['jurusan'] }}</p>
                                                        <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                                            <span class="inline-block px-2 py-0.5 bg-teal-50 text-teal-700 text-[9px] font-bold rounded">
                                                                {{ $mhs['posisi'] }}
                                                            </span>
                                                            <span class="text-[9px] text-gray-400">
                                                                {{ \Carbon\Carbon::parse($mhs['mulai'])->format('d M') }} s/d {{ \Carbon\Carbon::parse($mhs['selesai'])->format('d M Y') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="flex md:flex-col justify-between items-end gap-2 text-right flex-shrink-0">
                                                        {{-- Progress Logbook --}}
                                                        <div class="w-full md:w-32">
                                                            <div class="flex items-center justify-between text-[9px] text-gray-400 font-bold mb-1">
                                                                <span>Logbook:</span>
                                                                <span>{{ $mhs['logbook']['disetujui'] }}/{{ $mhs['logbook']['total'] }}</span>
                                                            </div>
                                                            <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                                                <div class="bg-teal-500 h-1.5 rounded-full" style="width: {{ $mhs['logbook']['rate'] }}%"></div>
                                                            </div>
                                                            @if($mhs['logbook']['pending'] > 0 || $mhs['logbook']['revisi'] > 0)
                                                            <div class="flex gap-1.5 justify-end mt-1 text-[8px] font-black">
                                                                @if($mhs['logbook']['pending'] > 0)
                                                                <span class="text-red-500 bg-red-50 px-1 rounded">{{ $mhs['logbook']['pending'] }} Pending</span>
                                                                @endif
                                                                @if($mhs['logbook']['revisi'] > 0)
                                                                <span class="text-yellow-600 bg-yellow-50 px-1 rounded">{{ $mhs['logbook']['revisi'] }} Revisi</span>
                                                                @endif
                                                            </div>
                                                            @endif
                                                        </div>

                                                        {{-- Absensi Pending --}}
                                                        @if($mhs['absensi']['pending'] > 0)
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-black bg-red-100 text-red-700">
                                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $mhs['absensi']['pending'] }} Izin/Sakit Pending
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            @else
                                            <p class="text-xs text-gray-400 italic py-8 text-center">Tidak ada bimbingan aktif saat ini.</p>
                                            @endif
                                        </div>

                                        {{-- Mahasiswa Alumni/Lulus --}}
                                        <div class="bg-white rounded-xl border border-gray-150 p-5 shadow-sm space-y-4">
                                            <div class="flex items-center justify-between border-b pb-3 border-gray-100">
                                                <h4 class="text-xs font-black text-gray-700 uppercase tracking-wider flex items-center gap-2">
                                                    <i class="fas fa-graduation-cap text-emerald-500"></i>
                                                    Alumni Bimbingan Lulus ({{ count($pl->mahasiswa_lulus) }})
                                                </h4>
                                            </div>
                                            
                                            @if(count($pl->mahasiswa_lulus) > 0)
                                            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                                                @foreach($pl->mahasiswa_lulus as $mhs)
                                                <div class="p-3 bg-gray-50 rounded-xl border border-gray-150 flex flex-col justify-between gap-2">
                                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2 border-b pb-2 border-gray-200/50">
                                                        <div>
                                                            <p class="text-xs font-black text-gray-900 truncate">{{ $mhs['nama'] }}</p>
                                                            <p class="text-[10px] text-gray-500 font-semibold truncate">{{ $mhs['kampus'] }} — {{ $mhs['jurusan'] }}</p>
                                                            <p class="text-[9px] text-teal-600 font-bold mt-0.5">Posisi: {{ $mhs['posisi'] }}</p>
                                                        </div>
                                                        <div class="text-right flex flex-row md:flex-col items-center md:items-end gap-1.5 flex-shrink-0">
                                                            <span class="text-xs font-black text-gray-800 bg-gray-200 px-2 py-0.5 rounded">
                                                                Nilai: {{ $mhs['nilai'] }}
                                                            </span>
                                                            <span class="text-[9px] font-black text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">
                                                                {{ $mhs['predikat'] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-1 mt-1">
                                                        @if($mhs['nomor_sertifikat'])
                                                        <p class="text-[9px] text-gray-500 font-bold">
                                                            <i class="fas fa-certificate text-yellow-500 mr-1"></i> No. Sertifikat: <span class="font-mono text-gray-700 bg-white px-1.5 py-0.5 border rounded border-gray-250">{{ $mhs['nomor_sertifikat'] }}</span>
                                                        </p>
                                                        @endif
                                                        <div class="text-[10px] text-gray-600 bg-white p-2 rounded-lg border border-gray-150 mt-1">
                                                            <span class="font-bold text-gray-400 text-[9px] uppercase block mb-0.5">Catatan Evaluasi:</span>
                                                            {{ $mhs['catatan'] ?: 'Tidak ada catatan khusus.' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            @else
                                            <p class="text-xs text-gray-400 italic py-8 text-center">Belum ada mahasiswa bimbingan yang selesai.</p>
                                            @endif
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center text-gray-400">
                                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-chalkboard-teacher text-3xl text-gray-350"></i>
                                        </div>
                                        <p class="font-bold text-gray-500">Belum ada data Pembimbing Lapangan</p>
                                        <p class="text-xs mt-1">Akun pembimbing lapangan perlu dibuat terlebih dahulu di menu manajemen akun.</p>
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
