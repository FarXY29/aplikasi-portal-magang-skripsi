<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-chart-line text-blue-600"></i>
                {{ __('Laporan Kinerja Mahasiswa') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('dinas.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-blue-600 transition">
                <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-blue-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Statistik Ringkasan --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3 border border-blue-100">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800">{{ $stats['total_peserta'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Total Peserta</p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
                    <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center mx-auto mb-3 border border-green-100">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <p class="text-2xl font-black text-green-700">{{ $stats['aktif'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Peserta Aktif</p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3 border border-indigo-100">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <p class="text-2xl font-black text-indigo-700">{{ $stats['selesai'] }}</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Selesai / Lulus</p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
                    <div class="w-10 h-10 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center mx-auto mb-3 border border-teal-100">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <p class="text-2xl font-black text-teal-700">{{ $stats['avg_kehadiran'] }}%</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Avg Kehadiran</p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
                    <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-3 border border-purple-100">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <p class="text-2xl font-black text-purple-700">{{ $stats['avg_logbook'] }}%</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Avg Logbook</p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
                    <div class="w-10 h-10 rounded-full bg-orange-50 text-orange-650 flex items-center justify-center mx-auto mb-3 border border-orange-100" style="color: #d97706; background-color: #fef3c7;">
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-800" style="color: #d97706;">{{ $stats['avg_nilai'] > 0 ? $stats['avg_nilai'] : '-' }}</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Avg Nilai Lulus</p>
                </div>
            </div>

            {{-- Highlight Banner --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-650 rounded-2xl p-6 text-white shadow-lg shadow-blue-600/20 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="text-center sm:text-left flex-grow">
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-100">Scorecard Kinerja Mahasiswa Dinas</p>
                    <p class="text-xl font-black mt-0.5">Rata-rata Kehadiran {{ $stats['avg_kehadiran'] }}%</p>
                    <p class="text-sm text-blue-100 font-medium">Tingkat kepatuhan validasi logbook mahasiswa mencapai {{ $stats['avg_logbook'] }}% dari keseluruhan mahasiswa aktif/selesai.</p>
                </div>
                @if($kinerja->count() > 0)
                <div class="sm:ml-auto">
                    <a href="{{ route('dinas.laporan.kinerja_mahasiswa.print') }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition text-sm font-bold border border-white/30">
                        <i class="fas fa-file-pdf mr-2"></i> Download PDF
                    </a>
                </div>
                @endif
            </div>

            {{-- Tabel Kinerja Mahasiswa --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-user-graduate text-blue-500"></i>
                        Scorecard Performa Peserta Magang
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Klik baris peserta untuk melihat rincian absensi harian, daftar logbook, serta rincian penilaian akhir.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Mahasiswa & Kampus</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Posisi Magang</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Kehadiran</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Validasi Logbook</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-12"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50" x-data="{ openRow: null }">
                            @forelse($kinerja as $app)
                            <tr class="hover:bg-blue-50/20 transition cursor-pointer" @click="openRow = openRow === {{ $loop->index }} ? null : {{ $loop->index }}">
                                <td class="px-5 py-4 text-xs text-gray-400 text-center font-bold">{{ $loop->iteration }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-black border border-blue-100 flex-shrink-0">
                                            {{ strtoupper(substr($app->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $app->user->name }}</p>
                                            <div class="flex flex-wrap items-center gap-x-2 text-[10px] text-gray-400 font-semibold mt-0.5">
                                                <span>{{ $app->user->asal_instansi ?? '-' }}</span>
                                                <span class="text-gray-300">•</span>
                                                @if($app->status == 'diterima')
                                                    <span class="inline-flex px-1.5 py-0.5 rounded text-[8px] font-black bg-green-100 text-green-700 uppercase">Aktif</span>
                                                @elseif($app->status == 'selesai')
                                                    <span class="inline-flex px-1.5 py-0.5 rounded text-[8px] font-black bg-blue-100 text-blue-700 uppercase">Selesai</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-xs text-gray-700 font-bold">
                                    {{ $app->position->judul_posisi }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @php
                                        $attRate = $app->attendance_rate;
                                        $attColor = $attRate >= 80 ? 'text-green-600 bg-green-50' : ($attRate >= 50 ? 'text-yellow-600 bg-yellow-50' : 'text-red-600 bg-red-50');
                                    @endphp
                                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-black {{ $attColor }}">
                                        {{ round($attRate, 1) }}%
                                    </span>
                                    <div class="text-[9px] text-gray-400 mt-1 font-semibold">{{ $app->attendances->where('status', 'hadir')->count() }}/{{ $app->attendances->count() }} hari</div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @php
                                        $logRate = $app->log_rate;
                                        $logColor = $logRate >= 80 ? 'text-green-600 bg-green-50' : ($logRate >= 50 ? 'text-yellow-600 bg-yellow-50' : 'text-red-600 bg-red-50');
                                    @endphp
                                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-black {{ $logColor }}">
                                        {{ round($logRate, 1) }}%
                                    </span>
                                    <div class="text-[9px] text-gray-400 mt-1 font-semibold">{{ $app->logs->where('status_validasi', 'disetujui')->count() }} disetujui</div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($app->avg_nilai > 0)
                                        <span class="text-sm font-black text-gray-800 bg-gray-100 px-2.5 py-0.5 rounded-full inline-block">
                                            {{ round($app->avg_nilai, 1) }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum dinilai</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" :class="openRow === {{ $loop->index }} ? 'rotate-180 text-blue-500' : ''"></i>
                                </td>
                            </tr>
                            
                            {{-- Row Detail --}}
                            <tr x-show="openRow === {{ $loop->index }}" x-transition.opacity x-cloak>
                                <td colspan="7" class="px-5 py-4 bg-gray-50/50 border-y border-gray-100">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                        
                                        {{-- Detail Absensi --}}
                                        <div class="bg-white rounded-xl border border-gray-150 p-4 shadow-sm space-y-3">
                                            <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-wider flex items-center gap-2 border-b pb-2 border-gray-100">
                                                <i class="fas fa-calendar-alt text-teal-500"></i> Rincian Absensi
                                            </h4>
                                            <div class="space-y-1.5 text-xs text-gray-700">
                                                <div class="flex justify-between">
                                                    <span>Hadir:</span>
                                                    <span class="font-bold text-gray-800">{{ $app->attendances->where('status', 'hadir')->count() }} hari</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span>Sakit:</span>
                                                    <span class="font-bold text-gray-800">{{ $app->attendances->where('status', 'sakit')->count() }} hari</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span>Izin:</span>
                                                    <span class="font-bold text-gray-800">{{ $app->attendances->where('status', 'izin')->count() }} hari</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span>Tanpa Keterangan (Alfa):</span>
                                                    <span class="font-bold text-gray-800">{{ $app->attendances->where('status', 'alfa')->count() }} hari</span>
                                                </div>
                                                <div class="flex justify-between border-t pt-1.5 border-gray-100 mt-1 font-bold">
                                                    <span>Izin/Sakit Pending:</span>
                                                    @php $pendAtt = $app->attendances->where('validation_status', 'pending')->count(); @endphp
                                                    <span class="{{ $pendAtt > 0 ? 'text-red-650' : 'text-gray-800' }}">{{ $pendAtt }} hari</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Detail Logbook --}}
                                        <div class="bg-white rounded-xl border border-gray-150 p-4 shadow-sm space-y-3">
                                            <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-wider flex items-center gap-2 border-b pb-2 border-gray-100">
                                                <i class="fas fa-book text-purple-500"></i> Kepatuhan Logbook
                                            </h4>
                                            <div class="space-y-1.5 text-xs text-gray-700">
                                                <div class="flex justify-between">
                                                    <span>Total Jurnal Harian:</span>
                                                    <span class="font-bold text-gray-800">{{ $app->logs->count() }} entri</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span>Disetujui Pembimbing:</span>
                                                    <span class="font-bold text-green-600">{{ $app->logs->where('status_validasi', 'disetujui')->count() }} entri</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span>Ditolak / Perlu Revisi:</span>
                                                    <span class="font-bold text-red-600">{{ $app->logs->where('status_validasi', 'revisi')->count() }} entri</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span>Belum Divalidasi:</span>
                                                    @php $pendLogs = $app->logs->where('status_validasi', 'pending')->count(); @endphp
                                                    <span class="font-bold {{ $pendLogs > 0 ? 'text-red-650' : 'text-gray-800' }}">{{ $pendLogs }} entri</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Detail Penilaian Akhir --}}
                                        <div class="bg-white rounded-xl border border-gray-150 p-4 shadow-sm space-y-3">
                                            <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-wider flex items-center gap-2 border-b pb-2 border-gray-100">
                                                <i class="fas fa-award text-orange-500"></i> Penilaian Akhir & Sertifikat
                                            </h4>
                                            
                                            @if($app->status === 'selesai')
                                                <div class="space-y-2 text-xs">
                                                    <div class="grid grid-cols-2 gap-x-2 gap-y-1 bg-gray-50 p-2 rounded-lg border border-gray-200 text-[10px]">
                                                        <div>Kerajinan: <span class="font-bold text-gray-800">{{ $app->nilai_kerajinan ?? '-' }}</span></div>
                                                        <div>Disiplin: <span class="font-bold text-gray-800">{{ $app->nilai_disiplin ?? '-' }}</span></div>
                                                        <div>Adaptasi: <span class="font-bold text-gray-800">{{ $app->nilai_adaptasi ?? '-' }}</span></div>
                                                        <div>Kreatifitas: <span class="font-bold text-gray-800">{{ $app->nilai_kreatifitas ?? '-' }}</span></div>
                                                        <div class="col-span-2">Skill & Pengetahuan: <span class="font-bold text-gray-800">{{ $app->nilai_skill_pengetahuan ?? '-' }}</span></div>
                                                    </div>
                                                    
                                                    <div class="space-y-1 mt-2 text-gray-700">
                                                        <div class="flex justify-between">
                                                            <span>Nilai Rata-rata:</span>
                                                            <span class="font-black text-gray-900">{{ round($app->avg_nilai, 1) }} ({{ $app->predikat ?? '-' }})</span>
                                                        </div>
                                                        @if($app->nomor_sertifikat)
                                                        <div class="flex justify-between">
                                                            <span>Sertifikat:</span>
                                                            <span class="font-mono text-gray-800 font-bold bg-yellow-50 px-1 rounded border border-yellow-250 text-[9px]">{{ $app->nomor_sertifikat }}</span>
                                                        </div>
                                                        @endif
                                                        <div class="flex justify-between">
                                                            <span>Pembimbing:</span>
                                                            <span class="font-bold text-gray-800">{{ $app->pembimbing_lapangan->name ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    @if($app->catatan_pembimbing_lapangan)
                                                    <div class="text-[9px] text-gray-600 bg-gray-50 p-2 rounded-lg border border-gray-150 italic mt-2">
                                                        <span class="font-bold text-gray-400 text-[8px] uppercase block not-italic mb-0.5">Catatan Pembimbing:</span>
                                                        "{{ $app->catatan_pembimbing_lapangan }}"
                                                    </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-xs text-gray-500 py-2">
                                                    <p class="font-bold text-gray-700 flex items-center gap-1"><i class="fas fa-info-circle text-blue-500"></i> Magang Belum Selesai</p>
                                                    <p class="mt-1 text-[10px]">Penilaian akhir akan diisi oleh Pembimbing Lapangan <strong>({{ $app->pembimbing_lapangan->name ?? 'Belum ditentukan' }})</strong> ketika peserta menyelesaikan periode magang.</p>
                                                </div>
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
                                            <i class="fas fa-graduation-cap text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="font-bold text-gray-500">Belum ada data Mahasiswa</p>
                                        <p class="text-xs mt-1">Data mahasiswa magang dengan status aktif atau selesai belum terdaftar di instansi Anda.</p>
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
