<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-chalkboard-teacher text-indigo-600 dark:text-indigo-400"></i>
                {{ __('Dashboard Pembimbing Lapangan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @php
                $globalAnnouncement = \App\Models\Setting::where('key', 'announcement')->value('value');
            @endphp
            @if(!empty($globalAnnouncement))
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-950/30 dark:to-orange-950/20 border-l-4 border-amber-500 p-6 rounded-r-2xl shadow-sm border border-amber-100 dark:border-amber-900/40 flex gap-4 items-start relative overflow-hidden">
                    <div class="absolute right-0 top-0 translate-x-4 -translate-y-4 opacity-5 text-amber-500 pointer-events-none">
                        <i class="fas fa-bullhorn text-9xl"></i>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0 shadow-inner">
                        <i class="fas fa-bullhorn text-lg"></i>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-sm font-extrabold text-amber-800 dark:text-amber-300 uppercase tracking-wider mb-1">Pengumuman Kota Banjarmasin</h4>
                        <div class="text-sm text-amber-950 dark:text-amber-100 font-semibold leading-relaxed prose prose-amber max-w-none">
                            {!! nl2br(e($globalAnnouncement)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-indigo-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-indigo-500 dark:text-indigo-400 uppercase tracking-widest mb-1">Total Bimbingan</p>
                            <h3 class="text-3xl font-black text-gray-800 dark:text-gray-200">{{ $interns->count() }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-sm">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-teal-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-teal-500 dark:text-teal-400 uppercase tracking-widest mb-1">Sedang Magang</p>
                            <h3 class="text-3xl font-black text-gray-800 dark:text-gray-200">{{ $interns->filter(fn($i) => $i->status_value === 'diterima')->count() }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-950/40 flex items-center justify-center text-teal-600 dark:text-teal-400 shadow-sm">
                            <i class="fas fa-user-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-blue-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-1">Selesai / Lulus</p>
                            <h3 class="text-3xl font-black text-gray-800 dark:text-gray-200">{{ $interns->filter(fn($i) => $i->status_value === 'selesai')->count() }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-sm">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" class="mb-4">
                    {{ session('success') }}
                </x-ui.alert>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                            <i class="fas fa-list-ul text-indigo-500 dark:text-indigo-400"></i> Daftar Mahasiswa Bimbingan
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pantau aktivitas, validasi kehadiran, dan berikan penilaian.</p>
                    </div>
                </div>
                
                <!-- Desktop Table View (md and above) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Profil Peserta</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Periode & Status</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kehadiran (Valid)</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nilai Akhir</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-50 dark:divide-gray-700/60">
                            @forelse($interns as $mhs)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 dark:from-indigo-900 dark:to-indigo-800 flex items-center justify-center text-indigo-700 dark:text-indigo-200 font-bold text-sm border border-indigo-200 dark:border-indigo-700 shadow-sm">
                                            {{ strtoupper(substr($mhs->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">{{ $mhs->user->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $mhs->user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        @if($mhs->tanggal_mulai)
                                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400 flex items-center gap-1">
                                                <i class="far fa-calendar-alt text-indigo-500"></i>
                                                {{ \Carbon\Carbon::parse($mhs->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($mhs->tanggal_selesai)->format('d M Y') }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Jadwal belum diset</span>
                                        @endif

                                        <div>
                                            @if($mhs->status_value === 'diterima')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 dark:bg-green-950/40 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span> AKTIF
                                                </span>
                                            @elseif($mhs->status_value === 'selesai')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                                    <i class="fas fa-check-circle mr-1 text-[8px]"></i> SELESAI
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $validLogs = $mhs->logs->where('status_validasi', 'disetujui')->count();
                                    @endphp
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-lg font-black text-gray-800 dark:text-gray-200">{{ $validLogs }}</span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">Hari Valid</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if(!is_null($mhs->nilai_angka) || !is_null($mhs->nilai_rata_rata))
                                        @php
                                            $finalScore = $mhs->nilai_angka ?? $mhs->nilai_rata_rata;
                                        @endphp
                                        <div class="inline-flex flex-col items-center bg-indigo-50 dark:bg-indigo-950/40 px-3 py-1 rounded-lg border border-indigo-100 dark:border-indigo-800/50">
                                            <span class="text-lg font-black text-indigo-600 dark:text-indigo-400">{{ $finalScore }}</span>
                                            <span class="text-[10px] font-bold text-indigo-400 dark:text-indigo-300 uppercase">{{ $mhs->predikat ?? '-' }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic bg-gray-100 dark:bg-gray-900 px-2.5 py-1 rounded-md border border-gray-200 dark:border-gray-700">Belum dinilai</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2 flex-wrap sm:flex-nowrap">
                                        <a href="{{ route('pembimbing_lapangan.logbook', $mhs->id) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-600 dark:text-gray-400 text-xs font-bold hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 transition shadow-sm" 
                                           title="Periksa Logbook">
                                            <i class="fas fa-book-open mr-1.5"></i> Logbook
                                        </a>

                                        <a href="{{ route('pembimbing_lapangan.attendance.index', $mhs->id) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-600 dark:text-gray-400 text-xs font-bold hover:text-teal-600 dark:hover:text-teal-400 hover:border-teal-300 hover:bg-teal-50 dark:hover:bg-teal-950/30 transition shadow-sm" 
                                           title="Riwayat Absensi">
                                            <i class="fas fa-clock mr-1.5"></i> Absensi
                                        </a>

                                        @if($mhs->status_value === 'diterima' || $mhs->status_value === 'selesai')
                                            @php
                                                $hasGrade = !is_null($mhs->nilai_angka) || !is_null($mhs->nilai_rata_rata);
                                            @endphp
                                            <a href="{{ route('pembimbing_lapangan.penilaian', $mhs->id) }}" 
                                               class="inline-flex items-center px-3 py-1.5 border rounded-lg text-xs font-bold transition shadow-sm {{ $hasGrade ? 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800 hover:bg-amber-100 dark:hover:bg-amber-900/60' : 'bg-indigo-600 text-white border-transparent hover:bg-indigo-700' }}">
                                                <i class="fas fa-star mr-1.5"></i> {{ $hasGrade ? 'Edit Nilai' : 'Input Nilai' }}
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-3">
                                            <i class="fas fa-users-slash text-3xl text-gray-300 dark:text-gray-600"></i>
                                        </div>
                                        <p class="font-bold text-gray-600 dark:text-gray-400">Belum ada mahasiswa bimbingan</p>
                                        <p class="text-xs mt-1">Anda belum ditugaskan untuk membimbing peserta magang manapun.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View (< md) -->
                <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($interns as $mhs)
                    <div class="p-5 space-y-4 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition">
                        <!-- Header Card: Foto, Nama, Email -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 dark:from-indigo-900 dark:to-indigo-800 flex items-center justify-center text-indigo-700 dark:text-indigo-200 font-bold text-base border border-indigo-200 dark:border-indigo-700 shadow-sm shrink-0">
                                    {{ strtoupper(substr($mhs->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-base font-bold text-gray-900 dark:text-gray-100">{{ $mhs->user->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $mhs->user->email }}</div>
                                </div>
                            </div>
                            <div>
                                @if($mhs->status_value === 'diterima')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 dark:bg-green-950/40 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span> AKTIF
                                    </span>
                                @elseif($mhs->status_value === 'selesai')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                        <i class="fas fa-check-circle mr-1 text-[8px]"></i> SELESAI
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Periode & Stats Grid -->
                        <div class="grid grid-cols-2 gap-2 bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-100 dark:border-gray-700 text-xs">
                            <div class="col-span-2 flex items-center gap-1.5 text-gray-600 dark:text-gray-400 font-medium pb-2 border-b border-gray-200 dark:border-gray-700/60">
                                <i class="far fa-calendar-alt text-indigo-500"></i>
                                @if($mhs->tanggal_mulai)
                                    <span>{{ \Carbon\Carbon::parse($mhs->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($mhs->tanggal_selesai)->format('d M Y') }}</span>
                                @else
                                    <span class="text-gray-400 italic">Jadwal belum diset</span>
                                @endif
                            </div>
                            <div class="pt-1 flex flex-col">
                                <span class="text-[10px] text-gray-400 font-bold uppercase">Kehadiran Valid</span>
                                @php
                                    $validLogs = $mhs->logs->where('status_validasi', 'disetujui')->count();
                                @endphp
                                <span class="text-base font-black text-gray-800 dark:text-gray-200">{{ $validLogs }} <span class="text-xs font-normal text-gray-500 dark:text-gray-400">Hari</span></span>
                            </div>
                            <div class="pt-1 flex flex-col border-l border-gray-200 dark:border-gray-700/60 pl-3">
                                <span class="text-[10px] text-gray-400 font-bold uppercase">Nilai Akhir</span>
                                @if(!is_null($mhs->nilai_angka) || !is_null($mhs->nilai_rata_rata))
                                    @php
                                        $finalScore = $mhs->nilai_angka ?? $mhs->nilai_rata_rata;
                                    @endphp
                                    <span class="text-base font-black text-indigo-600 dark:text-indigo-400">{{ $finalScore }} <span class="text-xs font-bold text-indigo-400">({{ $mhs->predikat ?? '-' }})</span></span>
                                @else
                                    <span class="text-xs text-gray-400 italic pt-0.5">Belum dinilai</span>
                                @endif
                            </div>
                        </div>

                        <!-- Tombol Aksi Full-Width untuk HP -->
                        <div class="flex flex-col sm:flex-row gap-2 pt-1">
                            <a href="{{ route('pembimbing_lapangan.logbook', $mhs->id) }}" 
                               class="w-full sm:flex-1 py-2.5 px-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 text-xs font-bold hover:bg-indigo-50 dark:hover:bg-indigo-950/30 hover:text-indigo-600 transition shadow-sm flex items-center justify-center gap-2">
                                <i class="fas fa-book-open text-indigo-500"></i> Cek Logbook
                            </a>

                            <a href="{{ route('pembimbing_lapangan.attendance.index', $mhs->id) }}" 
                               class="w-full sm:flex-1 py-2.5 px-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 text-xs font-bold hover:bg-teal-50 dark:hover:bg-teal-950/30 hover:text-teal-600 transition shadow-sm flex items-center justify-center gap-2">
                                <i class="fas fa-clock text-teal-500"></i> Cek Absensi
                            </a>

                            @if($mhs->status_value === 'diterima' || $mhs->status_value === 'selesai')
                                @php
                                    $hasGrade = !is_null($mhs->nilai_angka) || !is_null($mhs->nilai_rata_rata);
                                @endphp
                                <a href="{{ route('pembimbing_lapangan.penilaian', $mhs->id) }}" 
                                   class="w-full sm:flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition shadow-sm flex items-center justify-center gap-2 {{ $hasGrade ? 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-300 dark:border-amber-800 hover:bg-amber-100 dark:hover:bg-amber-900/60' : 'bg-indigo-600 text-white hover:bg-indigo-700' }}">
                                    <i class="fas fa-star text-yellow-400"></i> {{ $hasGrade ? 'Edit Nilai' : 'Input Nilai' }}
                                </a>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-400">
                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-users-slash text-3xl text-gray-300 dark:text-gray-600"></i>
                        </div>
                        <p class="font-bold text-gray-600 dark:text-gray-400">Belum ada mahasiswa bimbingan</p>
                        <p class="text-xs mt-1">Anda belum ditugaskan untuk membimbing peserta magang manapun.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>