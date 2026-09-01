<x-app-layout>
    @push('head')
        @vite('resources/css/peserta.css')
    @endpush
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-slate-900 dark:text-gray-100 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200/80 dark:border-teal-800/60 shadow-2xs">
                    <i class="fas fa-columns text-teal-700 dark:text-teal-400 text-base"></i>
                </div>
                {{ __('Dashboard Peserta') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <x-ui.alert type="success" class="mb-4">
                    {{ session('success') }}
                </x-ui.alert>
            @endif

            @php
                $profileComplete = !empty(Auth::user()->nik) && !empty(Auth::user()->asal_instansi);
                $hasApplications = $myApplications->count() > 0;
                
                // Determine current active stage
                $currentStage = 1;
                if (!$profileComplete) {
                    $currentStage = 1; // Lengkapi Profil
                } elseif ($profileComplete && !$hasApplications) {
                    $currentStage = 2; // Cari & Lamar Lowongan
                } elseif ($hasApplications && !$activeApp) {
                    $currentStage = 3; // Menunggu Seleksi
                } elseif ($activeApp && $activeApp->display_status === 'belum mulai') {
                    $currentStage = 3; // Menunggu Mulai
                } elseif ($activeApp && $activeApp->status?->value === 'diterima') {
                    $currentStage = 4; // Aktif Magang
                } elseif ($activeApp && $activeApp->status?->value === 'selesai' && empty($activeApp->saran_peserta)) {
                    $currentStage = 5; // Beri Evaluasi / Saran
                } else {
                    $currentStage = 6; // Selesai / Cetak Sertifikat
                }
                
                $stages = [
                    1 => ['name' => 'Profil', 'desc' => 'Lengkapi NIK & Instansi', 'icon' => 'fa-user-edit'],
                    2 => ['name' => 'Lamar', 'desc' => 'Pilih & Lamar Lowongan', 'icon' => 'fa-search'],
                    3 => ['name' => 'Seleksi', 'desc' => 'Proses Peninjauan', 'icon' => 'fa-file-signature'],
                    4 => ['name' => 'Magang', 'desc' => 'Absen & Logbook', 'icon' => 'fa-briefcase'],
                    5 => ['name' => 'Evaluasi', 'desc' => 'Saran & Kuesioner', 'icon' => 'fa-comment-alt'],
                    6 => ['name' => 'Sertifikat', 'desc' => 'Unduh Sertifikat', 'icon' => 'fa-award'],
                ];
            @endphp

            {{-- Horizontal Visual Progress Stepper --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl p-4 sm:p-5 md:p-6 shadow-2xs border border-slate-200/80 dark:border-gray-700 overflow-hidden mb-6">
                <h3 class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-4 sm:mb-6 flex items-center gap-2">
                    <i class="fas fa-route text-teal-700 dark:text-teal-400"></i> Alur Perjalanan Magang Anda
                </h3>
                <div class="relative flex md:flex-row justify-start md:justify-between items-start md:items-center gap-y-3 sm:gap-y-6 gap-x-2 overflow-x-auto md:overflow-visible no-scrollbar pb-2 md:pb-0 -mx-1 px-1">
                    @foreach($stages as $index => $stage)
                        <div class="flex items-center gap-3 md:flex-col md:text-center md:flex-1 relative shrink-0 md:shrink">
                            {{-- Connector Line Horizontal (md+) --}}
                            @if($index < 6)
                                <div class="hidden md:block absolute top-5 left-1/2 right-[-50%] h-[3px] {{ $currentStage > $index ? 'bg-teal-600 dark:bg-teal-400' : 'bg-slate-200 dark:bg-gray-700' }} -z-0"></div>
                            @endif

                            {{-- Circle Badge --}}
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 border-2 font-bold transition duration-300 relative z-10
                                {{ $currentStage > $index 
                                    ? 'bg-teal-700 dark:bg-teal-500 border-teal-700 dark:border-teal-500 text-white shadow-2xs' 
                                    : ($currentStage == $index 
                                        ? 'bg-white dark:bg-gray-800 border-teal-700 text-teal-800 dark:text-teal-400 shadow-2xs ring-4 ring-teal-50 dark:ring-teal-950/40' 
                                        : 'bg-white dark:bg-gray-800 border-slate-200 dark:border-gray-700 text-slate-400 dark:text-gray-500') }}">
                                @if($currentStage > $index)
                                    <i class="fas fa-check text-xs"></i>
                                @else
                                    <i class="fas {{ $stage['icon'] }} text-xs"></i>
                                @endif
                            </div>

                            {{-- Text --}}
                            <div class="md:mt-2 max-w-[140px] sm:max-w-none md:max-w-[120px] md:mx-auto">
                                <h4 class="text-xs font-black {{ $currentStage == $index ? 'text-teal-800 dark:text-teal-300' : ($currentStage > $index ? 'text-slate-800 dark:text-gray-200' : 'text-slate-400 dark:text-gray-500') }}">{{ $stage['name'] }}</h4>
                                <p class="text-[11px] sm:text-[10px] text-slate-400 dark:text-gray-500 font-semibold mt-0.5 leading-tight">{{ $stage['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if(session('error'))
                <x-ui.alert type="error" class="mb-4">
                    {{ session('error') }}
                </x-ui.alert>
            @endif

            {{-- Notifikasi H-7 Magang Berakhir --}}
            @if(isset($daysRemaining) && $daysRemaining >= 0 && $daysRemaining <= 7)
                <div class="notification-banner bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/40 dark:to-indigo-950/40 border-l-4 border-blue-600 p-4 sm:p-5 md:p-6 rounded-r-2xl sm:rounded-r-3xl shadow-2xs border border-blue-200/80 dark:border-blue-900/60 flex gap-3 sm:gap-4 items-start relative overflow-hidden">
                    <div class="absolute right-0 top-0 translate-x-4 -translate-y-4 opacity-5 text-blue-600 pointer-events-none">
                        <i class="fas fa-stopwatch text-7xl sm:text-9xl"></i>
                    </div>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 flex items-center justify-center flex-shrink-0 shadow-inner border border-blue-200 dark:border-blue-800/60">
                        <i class="fas fa-exclamation-triangle text-base sm:text-lg"></i>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-xs font-extrabold text-blue-900 dark:text-blue-300 uppercase tracking-wider mb-1 flex items-center gap-2">
                            Peringatan Berakhirnya Magang
                            <span class="countdown-badge-pulse inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-700 text-white shadow-2xs">
                                <i class="fas fa-clock"></i>
                                {{ $daysRemaining == 0 ? 'HARI INI' : $daysRemaining . ' HARI LAGI' }}
                            </span>
                        </h4>
                        <p class="text-xs sm:text-sm text-blue-950 dark:text-blue-200 font-medium leading-relaxed">
                            Harap segera melengkapi semua logbook, absensi harian, dan memastikan penilaian dari pembimbing lapangan telah diselesaikan sebelum tanggal berakhir.
                        </p>
                    </div>
                </div>
            @endif

            @if(empty(Auth::user()->nik) || empty(Auth::user()->asal_instansi))
                <div class="bg-amber-50/80 dark:bg-amber-950/40 border-l-4 border-amber-500 p-4 sm:p-5 rounded-r-2xl shadow-2xs border border-amber-200/80 dark:border-amber-900/60">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-amber-600 dark:text-amber-400 text-lg mt-0.5"></i>
                        <div>
                            <h3 class="text-xs font-bold text-amber-800 dark:text-amber-300 uppercase tracking-wider">Profil Belum Lengkap</h3>
                            <div class="mt-1 sm:mt-0.5 text-xs text-amber-900 dark:text-amber-200 font-medium">
                                <span>Silakan lengkapi NIK dan Asal Instansi agar sertifikat dapat dicetak.</span> 
                                <a href="{{ route('profile.edit') }}" class="font-bold underline hover:text-amber-950 dark:hover:text-amber-100 mt-2 sm:mt-0 sm:ml-1 inline-block">Lengkapi Sekarang &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($activeApp && in_array($activeApp->status?->value, ['diterima', 'selesai']))
                {{-- Banner Sambutan & Absen Harian --}}
                <div class="welcome-banner bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-2xs border border-slate-200/80 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="p-4 sm:p-6 md:p-8 flex flex-col md:flex-row justify-between items-center gap-4 sm:gap-6">
                        
                        <div class="w-full md:w-auto text-center md:text-left animate-fade-in-up">
                            <div class="flex flex-col md:flex-row items-center md:items-start gap-3 sm:gap-4 mb-4">
                                {{-- Avatar Inisial --}}
                                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 font-black text-xl sm:text-2xl flex items-center justify-center border border-teal-200/80 dark:border-teal-800/60 shadow-2xs flex-shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="text-center md:text-left">
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-bold {{ $activeApp->display_status == 'selesai' ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border border-blue-200/70 dark:border-blue-800/60' : ($activeApp->display_status == 'belum mulai' ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-800 dark:text-indigo-300 border border-indigo-200/70 dark:border-indigo-800/60' : 'bg-teal-50 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/70 dark:border-teal-800/60') }} mb-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $activeApp->display_status == 'selesai' ? 'bg-blue-600' : ($activeApp->display_status == 'belum mulai' ? 'bg-indigo-600' : 'bg-teal-600') }} mr-1.5 animate-ping-slow"></span>
                                        {{ $activeApp->display_status == 'selesai' ? 'Telah Selesai' : ($activeApp->display_status == 'belum mulai' ? 'Belum Mulai' : 'Sedang Magang Aktif') }}
                                    </span>
                                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-gray-100">Halo, {{ Auth::user()->name }}! 👋</h3>
                                    <p class="text-xs sm:text-sm text-slate-500 dark:text-gray-400 mt-0.5 font-medium">{{ $activeApp->display_status == 'selesai' ? 'Program magang Anda telah berakhir. Selamat!' : ($activeApp->display_status == 'belum mulai' ? 'Magang Anda akan segera dimulai. Persiapkan diri Anda!' : 'Pastikan mengisi logbook dan absensi setiap hari kerja.') }}</p>
                                </div>
                            </div>
                            
                            <div class="inline-flex flex-col sm:flex-row gap-2 sm:gap-3 text-xs font-bold text-slate-700 dark:text-gray-300 bg-slate-50 dark:bg-gray-900 p-2.5 sm:p-3 rounded-2xl border border-slate-200/80 dark:border-gray-700 w-full sm:w-auto shadow-2xs">
                                <div class="flex items-center gap-2 justify-center sm:justify-start">
                                    <span class="gps-ping-dot bg-emerald-600"></span>
                                    Masuk: {{ \Carbon\Carbon::parse($jamKerja->jam_mulai_masuk)->format('H:i') }} WITA
                                </div>
                                <div class="hidden sm:block border-l border-slate-300 dark:border-gray-700 h-4 self-center"></div>
                                <div class="flex items-center gap-2 justify-center sm:justify-start">
                                    <span class="gps-ping-dot bg-rose-600"></span>
                                    Pulang: {{ \Carbon\Carbon::parse($jamKerja->jam_mulai_pulang)->format('H:i') }} WITA
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap justify-center gap-3 w-full md:w-auto">
                            @if($activeApp->display_status == 'selesai')
                                <div class="px-5 py-3 sm:px-6 min-h-[44px] bg-blue-50 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 rounded-2xl border border-blue-200/80 dark:border-blue-800/60 font-bold flex items-center gap-2 shadow-2xs text-xs">
                                    <i class="fas fa-flag-checkered text-blue-600"></i> Magang Selesai
                                </div>
                            @elseif($activeApp->display_status == 'belum mulai')
                                <div class="px-5 py-3 sm:px-6 min-h-[44px] bg-indigo-50 dark:bg-indigo-950/60 text-indigo-800 dark:text-indigo-300 rounded-2xl border border-indigo-200/80 dark:border-indigo-800/60 font-bold flex items-center gap-2 shadow-2xs text-xs">
                                    <i class="fas fa-hourglass-start text-indigo-600"></i> Magang Belum Dimulai
                                </div>
                            @elseif(!$attendanceToday)
                                <div class="grid grid-cols-2 sm:flex sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
                                    <form id="form-absen-masuk" action="{{ route('peserta.absen.masuk') }}" method="POST" class="w-full sm:w-auto">
                                        @csrf
                                        <input type="hidden" name="latitude" id="lat-masuk">
                                        <input type="hidden" name="longitude" id="lng-masuk">
                                        <button type="submit" id="btn-absen-masuk" onclick="handleAbsenClick(event, 'form-absen-masuk', 'lat-masuk', 'lng-masuk', 'btn-absen-masuk')" class="w-full sm:w-auto min-h-[44px] justify-center px-5 sm:px-6 py-3 bg-teal-700 hover:bg-teal-800 text-white rounded-2xl font-bold shadow-2xs transition active:scale-95 flex items-center gap-2 text-xs uppercase tracking-wider">
                                            <i class="fas fa-fingerprint text-sm"></i> Absen Datang
                                        </button>
                                    </form>
                                    
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'modal-izin')" class="w-full sm:w-auto min-h-[44px] justify-center px-5 sm:px-6 py-3 bg-white dark:bg-gray-800 border-2 border-amber-400 dark:border-amber-500 text-amber-700 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/40 rounded-2xl font-bold transition active:scale-95 flex items-center gap-2 text-xs uppercase tracking-wider">
                                        <i class="fas fa-file-medical text-sm"></i> Izin / Sakit
                                    </button>
                                </div>

                             @elseif($attendanceToday->status == 'hadir' && empty($attendanceToday->clock_out))
                                <div class="flex flex-col items-center gap-3 w-full sm:w-auto">
                                    <div class="text-xs font-bold text-teal-800 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 px-4 py-2 rounded-xl border border-teal-200/80 dark:border-teal-800/60 shadow-2xs">
                                        <i class="fas fa-check-circle mr-1"></i> Datang: {{ \Carbon\Carbon::parse($attendanceToday->clock_in)->format('H:i') }}
                                    </div>
                                    <form id="form-absen-pulang" action="{{ route('peserta.absen.pulang') }}" method="POST" class="w-full sm:w-auto">
                                        @csrf
                                        <input type="hidden" name="latitude" id="lat-pulang">
                                        <input type="hidden" name="longitude" id="lng-pulang">
                                        <button type="submit" id="btn-absen-pulang" onclick="handleAbsenClick(event, 'form-absen-pulang', 'lat-pulang', 'lng-pulang', 'btn-absen-pulang')" class="w-full sm:w-auto min-h-[44px] justify-center px-5 sm:px-6 py-3 bg-rose-700 hover:bg-rose-800 text-white rounded-2xl font-bold shadow-2xs transition active:scale-95 flex items-center gap-2 text-xs uppercase tracking-wider">
                                            <i class="fas fa-sign-out-alt text-sm"></i> Absen Pulang
                                        </button>
                                    </form>
                                </div>

                            @else
                                <div class="text-center w-full sm:w-auto">
                                    @if($attendanceToday->status == 'hadir')
                                        <div class="px-5 py-4 sm:px-6 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 rounded-2xl border border-emerald-200/80 dark:border-emerald-800/60 font-bold flex flex-col items-center shadow-2xs text-xs">
                                            <span class="flex items-center gap-1"><i class="fas fa-check-double text-emerald-600 dark:text-emerald-400"></i> Kehadiran Terekam</span>
                                            <span class="text-[11px] font-medium mt-1 text-emerald-700 dark:text-emerald-400">
                                                {{ \Carbon\Carbon::parse($attendanceToday->clock_in)->format('H:i') }} - 
                                                {{ $attendanceToday->clock_out ? \Carbon\Carbon::parse($attendanceToday->clock_out)->format('H:i') : '?' }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="px-5 py-3 sm:px-6 min-h-[44px] bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 rounded-2xl border border-amber-200/80 dark:border-amber-800/60 font-bold flex items-center gap-2 shadow-2xs text-xs">
                                            <i class="fas fa-info-circle text-amber-600"></i> Status Absen: {{ ucfirst($attendanceToday->status) }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                    </div>
                    @include('peserta.dashboard._gps-widget')
                </div>

                {{-- Card Input Saran / Evaluasi --}}
                @if($activeApp && $activeApp->status?->value === 'selesai' && empty($activeApp->saran_peserta))
                    <div class="bg-indigo-50/60 dark:bg-indigo-950/40 rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 border border-indigo-200/80 dark:border-indigo-800/60 shadow-2xs mb-6">
                        <div class="flex flex-col md:flex-row gap-4 sm:gap-6 items-start justify-between">
                            <div class="flex gap-3 sm:gap-4 items-start">
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 border border-indigo-200/80 dark:border-indigo-800/60 shadow-inner">
                                    <i class="fas fa-comment-alt text-lg sm:text-xl"></i>
                                </div>
                                <div class="space-y-1">
                                    <h3 class="text-slate-900 dark:text-gray-100 font-extrabold text-base sm:text-lg md:text-xl tracking-tight flex flex-wrap items-center gap-2">
                                        Isi Evaluasi & Saran Magang
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-indigo-700 text-white shadow-2xs">
                                            WAJIB
                                        </span>
                                    </h3>
                                    <p class="text-slate-600 dark:text-gray-400 text-xs md:text-sm font-medium leading-relaxed max-w-3xl">
                                        Selamat! Masa magang Anda telah selesai. Mohon berikan saran dan evaluasi konstruktif untuk <strong>{{ $activeApp->position->instansi->nama_dinas }}</strong>. Evaluasi Anda bersifat <strong>anonim</strong> dan wajib diisi sebelum mengunduh Sertifikat & Transkrip Nilai Anda.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('peserta.saran.store', $activeApp->id) }}" method="POST" class="mt-4 sm:mt-6 space-y-4">
                            @csrf
                            <div>
                                <textarea name="saran_peserta" rows="4" required class="w-full border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100 rounded-2xl shadow-2xs focus:border-indigo-600 focus:ring-indigo-600 text-xs sm:text-sm font-medium" placeholder="Tuliskan evaluasi, kritik, atau saran perbaikan untuk instansi tempat magang Anda..."></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="w-full sm:w-auto min-h-[44px] px-5 sm:px-6 py-3 bg-indigo-700 hover:bg-indigo-800 text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-2xs active:scale-95 transition flex items-center justify-center gap-2">
                                    <i class="fas fa-paper-plane"></i> Kirim Evaluasi & Buka Sertifikat
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                {{-- Grid Dashboard Stats & Detail Magang --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
                    
                    @include('peserta.dashboard._stats')

                    @include('peserta.dashboard._absensi-card')

                    @include('peserta.dashboard._logbook-card')

                </div>

                <x-modal name="modal-izin" focusable>
                    <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 text-slate-900 dark:text-gray-100">
                        <h2 class="text-base font-bold text-slate-900 dark:text-gray-100 mb-4 pb-3 border-b border-slate-100 dark:border-gray-700 flex items-center gap-2">
                            <i class="fas fa-file-alt text-amber-600"></i> Form Pengajuan Izin / Sakit
                        </h2>
                        <form action="{{ route('peserta.absen.izin') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-gray-300 uppercase mb-2">Jenis Keterangan</label>
                                    <select name="status" class="w-full min-h-[44px] border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100 rounded-xl shadow-2xs focus:border-teal-600 focus:ring-teal-600 text-xs font-bold [color-scheme:dark]">
                                        <option value="sakit">Sakit (Upload Surat Dokter)</option>
                                        <option value="izin">Izin (Keperluan Mendesak)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-gray-300 uppercase mb-2">Alasan Detail</label>
                                    <textarea name="description" rows="3" class="w-full border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100 rounded-xl shadow-2xs focus:border-teal-600 focus:ring-teal-600 text-xs font-medium" required placeholder="Jelaskan alasan pengajuan Anda..."></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-gray-300 uppercase mb-2">Bukti Foto / Surat (PNG / JPG / PDF)</label>
                                    <input type="file" name="proof_file" class="w-full text-xs text-slate-500 dark:text-gray-400 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 dark:file:bg-teal-950/60 file:text-teal-800 dark:file:text-teal-300 hover:file:bg-teal-100/80 border border-slate-300 dark:border-gray-700 rounded-xl p-1 bg-white dark:bg-gray-900" required>
                                </div>
                            </div>

                            <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3">
                                <button type="button" x-on:click.prevent="$dispatch('close')" class="min-h-[44px] px-5 py-2.5 bg-white dark:bg-gray-800 border border-slate-300 dark:border-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-gray-900 rounded-xl font-bold text-xs transition uppercase tracking-wider">Batal</button>
                                <button type="submit" class="min-h-[44px] px-5 py-2.5 bg-teal-700 hover:bg-teal-800 text-white rounded-xl font-bold text-xs shadow-2xs transition uppercase tracking-wider">Kirim Pengajuan</button>
                            </div>
                        </form>
                    </div>
                </x-modal>
            @else
                {{-- Banner Penempatan Otomatis --}}
                <div class="bg-gradient-to-r from-teal-800 via-teal-900 to-emerald-900 rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 text-white shadow-xl shadow-teal-950/20 mb-6 overflow-hidden relative border border-teal-700/50">
                    <div class="absolute right-0 top-0 translate-x-6 -translate-y-6 opacity-10 text-white pointer-events-none">
                        <i class="fas fa-magic text-7xl sm:text-9xl"></i>
                    </div>
                    <div class="flex flex-col md:flex-row gap-4 sm:gap-6 items-center justify-between relative z-10">
                        <div class="flex gap-3 sm:gap-4 items-start">
                            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-teal-500/20 text-teal-300 flex items-center justify-center flex-shrink-0 shadow-inner border border-teal-500/30">
                                <i class="fas fa-magic text-lg sm:text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-extrabold text-base sm:text-lg md:text-xl leading-snug">Bingung Memilih Instansi Magang?</h3>
                                <p class="text-teal-100/90 text-xs sm:text-sm mt-1 max-w-2xl font-medium leading-relaxed">
                                    Gunakan fitur <strong>Penempatan Otomatis</strong>! Sistem akan menyalurkan Anda secara otomatis ke instansi yang masih kuota tersedia sesuai dengan kualifikasi jurusan Anda ({{ Auth::user()->major ?? '-' }}).
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('peserta.apply_automatic.form') }}" class="shrink-0 w-full sm:w-auto min-h-[44px] text-center bg-white dark:bg-gray-800 text-teal-800 dark:text-teal-300 hover:bg-teal-50 px-5 sm:px-6 py-3 rounded-xl font-bold shadow-2xs hover:shadow-xs transition transform hover:-translate-y-0.5 active:scale-95 text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                            <i class="fas fa-magic"></i> Daftar Penempatan Otomatis
                        </a>
                    </div>
                </div>
            @endif

            @if($hasApplications)
                <div class="bg-teal-50/70 dark:bg-teal-950/30 border border-teal-200/80 dark:border-teal-800/60 rounded-2xl sm:rounded-3xl p-4 sm:p-5 flex items-start gap-3 mb-6 shadow-2xs">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl bg-teal-100/80 dark:bg-teal-900/60 text-teal-700 dark:text-teal-300 flex items-center justify-center flex-shrink-0 border border-teal-200/80 dark:border-teal-800/60">
                        <i class="fas fa-file-download text-base sm:text-lg"></i>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-xs font-black text-teal-800 dark:text-teal-300 uppercase tracking-wider mb-1">Unduh Dokumen Lamaran</h4>
                        <p class="text-xs sm:text-sm text-teal-900 dark:text-teal-200 font-medium leading-relaxed">
                            Klik tombol <strong>Detail</strong> pada setiap lamaran untuk mengunduh dokumen seperti <strong>ID Card</strong>, <strong>Surat Balasan</strong>, <strong>Rekap Logbook</strong>, <strong>Sertifikat</strong>, dan <strong>Transkrip</strong>.
                        </p>
                    </div>
                </div>
            @endif

            @include('peserta.dashboard._lamaran-list')

        </div>
    </div>

    @push('scripts')
    <script>
    let latestGpsPosition = null;
    let latestGpsTimestamp = 0;

    function updateGpsStatusUI(lat, lng) {
        const banner = document.getElementById('gps-status-banner');
        if (!banner) return;

        const officeLat = parseFloat(banner.dataset.lat);
        const officeLng = parseFloat(banner.dataset.lng);
        const radius = parseInt(banner.dataset.radius) || 100;

        const iconWrapper = document.getElementById('gps-icon-wrapper');
        const title = document.getElementById('gps-title');
        const desc = document.getElementById('gps-desc');
        const badge = document.getElementById('gps-badge');

        const R = 6371000;
        const dLat = (officeLat - lat) * Math.PI / 180;
        const dLon = (officeLng - lng) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat * Math.PI / 180) * Math.cos(officeLat * Math.PI / 180) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        const distance = Math.round(R * c);

        if (distance <= radius) {
            banner.className = "px-4 py-3 sm:px-6 sm:py-4 bg-emerald-50/80 dark:bg-emerald-950/20 border-t border-emerald-200/60 dark:border-emerald-900/50 flex items-center justify-between flex-wrap gap-3 transition-all duration-300";
            if (iconWrapper) {
                iconWrapper.className = "w-11 h-11 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-lg shadow-md shadow-emerald-500/20 shrink-0";
                iconWrapper.innerHTML = '<i class="fas fa-map-marker-alt"></i>';
            }
            if (title) {
                title.className = "text-xs font-extrabold text-emerald-900 dark:text-emerald-300 uppercase tracking-wider";
                title.innerText = "Lokasi Terverifikasi (Dalam Radius)";
            }
            if (desc) {
                desc.className = "text-xs text-emerald-700 dark:text-emerald-400 font-medium";
                desc.innerText = `Jarak Anda: ${distance} meter dari kantor (Batas maksimal ${radius}m). Anda siap melakukan absensi!`;
            }
            if (badge) {
                badge.className = "min-h-[44px] px-3.5 py-2 sm:py-1.5 rounded-xl bg-white dark:bg-gray-800 text-emerald-700 dark:text-emerald-400 text-xs font-extrabold shadow-sm border border-emerald-200/60 dark:border-emerald-900/50 flex items-center gap-1.5 shrink-0";
                badge.innerHTML = '<i class="fas fa-check-circle text-emerald-500"></i> Siap Absen';
                badge.onclick = null;
            }
        } else {
            banner.className = "px-4 py-3 sm:px-6 sm:py-4 bg-amber-50/80 dark:bg-amber-950/20 border-t border-amber-200/60 dark:border-amber-900/50 flex items-center justify-between flex-wrap gap-3 transition-all duration-300";
            if (iconWrapper) {
                iconWrapper.className = "w-11 h-11 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-lg shadow-md shadow-amber-500/20 shrink-0";
                iconWrapper.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
            }
            if (title) {
                title.className = "text-xs font-extrabold text-amber-900 dark:text-amber-300 uppercase tracking-wider";
                title.innerText = "Di Luar Radius Kantor";
            }
            if (desc) {
                desc.className = "text-xs text-amber-700 dark:text-amber-400 font-medium";
                desc.innerText = `Jarak Anda: ${distance} meter dari kantor (Batas maksimal ${radius}m). Mendekatlah ke kantor untuk absen.`;
            }
            if (badge) {
                badge.className = "min-h-[44px] px-3.5 py-2 sm:py-1.5 rounded-xl bg-white dark:bg-gray-800 text-amber-700 dark:text-amber-400 text-xs font-extrabold shadow-sm border border-amber-200/60 dark:border-amber-900/50 flex items-center gap-1.5 shrink-0";
                badge.innerHTML = `<i class="fas fa-ruler-horizontal text-amber-500"></i> Jarak: ${distance}m`;
                badge.onclick = null;
            }
        }
    }

    function autoDetectGPS() {
        const banner = document.getElementById('gps-status-banner');
        if (!banner) return;

        const iconWrapper = document.getElementById('gps-icon-wrapper');
        const title = document.getElementById('gps-title');
        const desc = document.getElementById('gps-desc');
        const badge = document.getElementById('gps-badge');

        if (!navigator.geolocation) {
            banner.className = "px-4 py-3 sm:px-6 sm:py-4 bg-rose-50/60 dark:bg-rose-950/20 border-t border-rose-100 dark:border-rose-900/50 flex items-center justify-between flex-wrap gap-3 transition-all duration-300";
            if (iconWrapper) {
                iconWrapper.className = "w-11 h-11 rounded-2xl bg-rose-500 text-white flex items-center justify-center text-lg shadow-md shadow-rose-500/20 shrink-0";
                iconWrapper.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
            }
            if (title) {
                title.className = "text-xs font-extrabold text-rose-900 dark:text-rose-300 uppercase tracking-wider";
                title.innerText = "GPS Tidak Didukung";
            }
            if (desc) {
                desc.className = "text-xs text-rose-600 dark:text-rose-400 font-medium";
                desc.innerText = "Browser Anda tidak mendukung fitur geolokasi GPS.";
            }
            if (badge) {
                badge.className = "min-h-[44px] px-3.5 py-2 sm:py-1.5 rounded-xl bg-white dark:bg-gray-800 text-rose-700 dark:text-rose-400 text-xs font-extrabold shadow-sm border border-rose-200/60 dark:border-rose-900/50 flex items-center gap-1.5 shrink-0";
                badge.innerHTML = '<i class="fas fa-times"></i> Gagal';
                badge.onclick = null;
            }
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                latestGpsPosition = position;
                latestGpsTimestamp = Date.now();

                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                const latMasuk = document.getElementById('lat-masuk');
                const lngMasuk = document.getElementById('lng-masuk');
                const latPulang = document.getElementById('lat-pulang');
                const lngPulang = document.getElementById('lng-pulang');

                if (latMasuk && lngMasuk) {
                    latMasuk.value = lat.toFixed(6);
                    lngMasuk.value = lng.toFixed(6);
                }
                if (latPulang && lngPulang) {
                    latPulang.value = lat.toFixed(6);
                    lngPulang.value = lng.toFixed(6);
                }

                updateGpsStatusUI(lat, lng);
            },
            function(error) {
                banner.className = "px-4 py-3 sm:px-6 sm:py-4 bg-rose-50/80 dark:bg-rose-950/20 border-t border-rose-200/60 dark:border-rose-900/50 flex items-center justify-between flex-wrap gap-3 transition-all duration-300";
                if (iconWrapper) {
                    iconWrapper.className = "w-11 h-11 rounded-2xl bg-rose-500 text-white flex items-center justify-center text-lg shadow-md shadow-rose-500/20 shrink-0";
                    iconWrapper.innerHTML = '<i class="fas fa-map-pin"></i>';
                }
                if (title) {
                    title.className = "text-xs font-extrabold text-rose-900 dark:text-rose-300 uppercase tracking-wider";
                    title.innerText = "Izin Lokasi (GPS) Diperlukan";
                }
                
                let errorMsg = "Sistem gagal mengambil lokasi GPS Anda. Pastikan GPS aktif.";
                if (error.code === 1) errorMsg = "Akses lokasi ditolak! Silakan izinkan akses lokasi (GPS) pada browser/HP Anda.";
                else if (error.code === 2) errorMsg = "Sinyal GPS tidak ditemukan. Pastikan GPS HP/perangkat Anda aktif.";
                else if (error.code === 3) errorMsg = "Waktu permintaan lokasi habis (timeout). Silakan coba lagi.";

                if (desc) {
                    desc.className = "text-xs text-rose-700 dark:text-rose-400 font-medium";
                    desc.innerText = errorMsg;
                }
                if (badge) {
                    badge.className = "min-h-[44px] px-3.5 py-2 sm:py-1.5 rounded-xl bg-white dark:bg-gray-800 text-rose-700 dark:text-rose-400 text-xs font-extrabold shadow-sm border border-rose-200/60 dark:border-rose-900/50 flex items-center gap-1.5 cursor-pointer hover:bg-rose-50 dark:hover:bg-rose-950/20 shrink-0";
                    badge.innerHTML = '<i class="fas fa-sync-alt mr-1"></i> Coba Deteksi Ulang';
                    badge.onclick = autoDetectGPS;
                }
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }

    document.addEventListener("DOMContentLoaded", autoDetectGPS);
    window.addEventListener("turbo:load", autoDetectGPS);

    // Fungsi ripple effect
    function addRipple(btn, event) {
        const circle = document.createElement('span');
        circle.classList.add('ripple-circle');
        const rect = btn.getBoundingClientRect();
        circle.style.left = (event.clientX - rect.left) + 'px';
        circle.style.top  = (event.clientY - rect.top) + 'px';
        btn.appendChild(circle);
        setTimeout(() => circle.remove(), 650);
    }

    function handleAbsenClick(event, formId, latId, lngId, btnId) {
        event.preventDefault();
        const btn = document.getElementById(btnId);
        if (btn) addRipple(btn, event);
        const originalHtml = btn ? btn.innerHTML : '';
        const form = document.getElementById(formId);

        if (!form) return;

        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung fitur geolokasi GPS. Silakan gunakan browser/HP lain.');
            return;
        }

        if (btn) {
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> <span>Menyiapkan Sesi Aman...</span>';
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
        }

        function restoreButton() {
            if (btn) {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        }

        function submitWithPosition(position, challenge) {
            latestGpsPosition = position;
            latestGpsTimestamp = Date.now();
            fillAntiFraudFields(form, latId, lngId, position, challenge);
            updateGpsStatusUI(position.coords.latitude, position.coords.longitude);
            if (btn) {
                btn.innerHTML = '<i class="fas fa-check-circle"></i> <span>Lokasi Terkunci! Mengirim...</span>';
            }
            setTimeout(() => form.submit(), 300);
        }

        // Anti-fraud: minta challenge nonce dari server SEBELUM mengirim absensi.
        // Nonce single-use & berumur pendek → mencegah replay request absensi.
        fetch('{{ route('peserta.absensi.challenge') }}', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(res) {
            if (!res.ok) throw new Error('challenge_failed_' + res.status);
            return res.json();
        })
        .then(function(challenge) {
            // Jika posisi GPS baru saja dideteksi (< 20 detik yang lalu), langsung gunakan
            if (latestGpsPosition && (Date.now() - latestGpsTimestamp) < 20000) {
                submitWithPosition(latestGpsPosition, challenge);
                return;
            }

            if (btn) {
                btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> <span>Mendeteksi GPS...</span>';
            }

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    submitWithPosition(position, challenge);
                },
                function(error) {
                    restoreButton();
                    let msg = 'Gagal mengambil lokasi GPS Anda.';
                    if (error.code === 1) msg = 'Akses Lokasi (GPS) ditolak! Izinkan akses lokasi pada browser Anda.';
                    else if (error.code === 2) msg = 'Sinyal GPS tidak ditemukan. Pastikan GPS aktif.';
                    else if (error.code === 3) msg = 'Waktu permintaan lokasi habis. Silakan coba lagi.';
                    alert(msg);
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 10000 }
            );
        })
        .catch(function() {
            restoreButton();
            alert('Gagal menyiapkan sesi keamanan absensi. Periksa koneksi internet Anda lalu coba lagi.');
        });
    }

    function formatNumericCoord(val) {
        if (val === null || val === undefined || isNaN(val)) return '';
        return String(Math.round(val));
    }

    // Isi hidden fields anti-fraud: koordinat + metadata GPS + nonce +
    // idempotency key (UUID per klik tombol — duplicate click tidak
    // menghasilkan absensi kedua).
    function fillAntiFraudFields(form, latId, lngId, position, challenge) {
        const c = position.coords;
        if (typeof c.latitude === 'number' && !isNaN(c.latitude)) {
            const latEl = document.getElementById(latId);
            if (latEl) latEl.value = c.latitude.toFixed(6);
        }
        if (typeof c.longitude === 'number' && !isNaN(c.longitude)) {
            const lngEl = document.getElementById(lngId);
            if (lngEl) lngEl.value = c.longitude.toFixed(6);
        }

        setOrCreateHidden(form, 'nonce', challenge.nonce);
        setOrCreateHidden(form, 'idempotency_key',
            (window.crypto && crypto.randomUUID) ? crypto.randomUUID() : 'abs-' + Date.now() + '-' + Math.random().toString(36).slice(2));
        
        const ts = (position.timestamp && !isNaN(position.timestamp)) ? Math.round(position.timestamp) : Date.now();
        setOrCreateHidden(form, 'client_timestamp', String(ts));
        setOrCreateHidden(form, 'accuracy', formatNumericCoord(c.accuracy));
        setOrCreateHidden(form, 'altitude', formatNumericCoord(c.altitude));
        setOrCreateHidden(form, 'speed', formatNumericCoord(c.speed));
        setOrCreateHidden(form, 'heading', formatNumericCoord(c.heading));
    }

    function setOrCreateHidden(form, name, value) {
        let input = form.querySelector('input[name="' + name + '"]');
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            form.appendChild(input);
        }
        input.value = value;
    }
    </script>
    @endpush
</x-app-layout>
