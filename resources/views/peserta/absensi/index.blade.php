<x-app-layout>
    @push('head')
        @vite('resources/css/peserta.css')
    @endpush
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-history text-teal-600"></i>
                {{ __('Riwayat Kehadiran (Absensi)') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Back Button --}}
            <div class="mb-6 print:hidden">
                <a href="{{ route('peserta.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            {{-- Summary Stats Bar --}}
            @php
                $totalRecords = $attendances->total();
                $hadirCount   = $attendances->getCollection()->where('status', 'hadir')->count();
                $izinCount    = $attendances->getCollection()->whereIn('status', ['izin','sakit'])->count();
                $alpaCount    = $attendances->getCollection()->where('status', 'alpa')->count();
            @endphp
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="stat-summary-card stagger-1 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-900/30 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-day text-teal-600 dark:text-teal-400 text-xl"></i>
                    </div>
                    <div>
                        <div class="stat-number text-gray-900 dark:text-gray-100 stagger-1">{{ $totalRecords }}</div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Hari</div>
                    </div>
                </div>
                <div class="stat-summary-card stagger-2 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user-check text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <div>
                        <div class="stat-number text-green-700 dark:text-green-400 stagger-2">{{ $hadirCount }}</div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Hadir</div>
                    </div>
                </div>
                <div class="stat-summary-card stagger-3 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-yellow-50 dark:bg-yellow-900/30 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-clipboard-list text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                    <div>
                        <div class="stat-number text-yellow-700 dark:text-yellow-400 stagger-3">{{ $izinCount }}</div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Izin/Sakit</div>
                    </div>
                </div>
                <div class="stat-summary-card stagger-4 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user-times text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                    <div>
                        <div class="stat-number text-red-700 dark:text-red-400 stagger-4">{{ $alpaCount }}</div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Alpa</div>
                    </div>
                </div>
            </div>

            {{-- Main Table Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-900 dark:text-gray-100">Histori Absen Saya</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Data absen Anda pada instansi <span class="font-bold text-teal-600">{{ $application->position->instansi->nama_dinas }}</span></p>
                    </div>

                    {{-- Filter Bulan --}}
                    <x-ui.filter-bar :action="route('peserta.absensi.index')" :resetUrl="request()->has('month') ? route('peserta.absensi.index') : null">
                        <div class="flex items-center gap-2 min-w-[200px]">
                            <label for="month" class="text-xs font-bold text-gray-600 dark:text-gray-400 shrink-0"><i class="far fa-calendar-alt text-teal-500 mr-1"></i> Bulan:</label>
                            <input type="month" id="month" name="month" value="{{ request('month') }}" class="w-full text-xs rounded-xl border-gray-200 dark:border-gray-700 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-sm font-medium">
                        </div>
                    </x-ui.filter-bar>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
                            <tr>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Masuk</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Pulang</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Durasi</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Validasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @forelse($attendances as $absen)
                                @php
                                    $durationText = '-';
                                    if ($absen->clock_in && $absen->clock_out) {
                                        $in  = \Carbon\Carbon::parse($absen->clock_in);
                                        $out = \Carbon\Carbon::parse($absen->clock_out);
                                        $diff = $in->diff($out);
                                        $durationText = $diff->h . 'j ' . $diff->i . 'm';
                                    }
                                @endphp
                                <tr class="absensi-row">
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($absen->date)->translatedFormat('l, d M Y') }}</div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @if($absen->status == 'hadir')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 dark:bg-green-950/30 text-green-800 dark:text-green-400 border border-green-200 dark:border-green-900/50"><i class="fas fa-check-circle text-[10px]"></i>Hadir</span>
                                        @elseif($absen->status == 'izin')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 dark:bg-yellow-950/30 text-yellow-800 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-900/50"><i class="fas fa-file-alt text-[10px]"></i>Izin</span>
                                        @elseif($absen->status == 'sakit')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-orange-100 dark:bg-orange-950/30 text-orange-800 dark:text-orange-400 border border-orange-200 dark:border-orange-900/50"><i class="fas fa-thermometer-half text-[10px]"></i>Sakit</span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-950/30 text-red-800 dark:text-red-400 border border-red-200 dark:border-red-900/50"><i class="fas fa-times-circle text-[10px]"></i>Alpa</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm font-bold text-gray-700 dark:text-gray-300">
                                        @if($absen->clock_in)
                                            <span class="inline-flex items-center gap-1.5"><i class="fas fa-sign-in-alt text-teal-500 text-xs"></i>{{ \Carbon\Carbon::parse($absen->clock_in)->format('H:i') }} WIB</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm font-bold text-gray-700 dark:text-gray-300">
                                        @if($absen->clock_out)
                                            <span class="inline-flex items-center gap-1.5"><i class="fas fa-sign-out-alt text-red-400 text-xs"></i>{{ \Carbon\Carbon::parse($absen->clock_out)->format('H:i') }} WIB</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400 font-medium">
                                        @if($durationText !== '-')
                                            <span class="inline-flex items-center gap-1 font-bold text-teal-700 dark:text-teal-400"><i class="fas fa-clock text-xs"></i>{{ $durationText }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        @if($absen->validation_status == 'disetujui')
                                            <span class="inline-flex items-center gap-1 text-green-600 font-bold text-xs"><i class="fas fa-check-circle"></i> Tervalidasi</span>
                                        @elseif($absen->validation_status == 'ditolak')
                                            <span class="inline-flex items-center gap-1 text-red-600 font-bold text-xs"><i class="fas fa-times-circle"></i> Ditolak</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-gray-400 font-bold text-xs"><i class="fas fa-clock"></i> Menunggu</span>
                                        @endif
                                        
                                        @if($absen->description)
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 italic">"{{ $absen->description }}"</p>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="empty-state-svg inline-block mb-5">
                                            <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="15" y="10" width="70" height="80" rx="8" fill="#f1f5f9" stroke="#e2e8f0" stroke-width="2"/>
                                                <rect x="25" y="25" width="50" height="6" rx="3" fill="#cbd5e1"/>
                                                <rect x="25" y="40" width="35" height="6" rx="3" fill="#e2e8f0"/>
                                                <rect x="25" y="55" width="45" height="6" rx="3" fill="#e2e8f0"/>
                                                <circle cx="50" cy="72" r="6" fill="#14b8a6" opacity="0.25"/>
                                                <path d="M47 72 L49.5 74.5 L53 70" stroke="#14b8a6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                        <p class="font-bold text-gray-600 dark:text-gray-400 text-lg">Belum ada data absensi</p>
                                        <p class="text-sm text-gray-400 font-medium mt-1">Tidak ada catatan absensi pada periode yang dipilih.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $attendances->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
