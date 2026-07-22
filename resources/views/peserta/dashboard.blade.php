<x-app-layout>
    @push('head')
        @vite('resources/css/peserta.css')
    @endpush
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-columns text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Dashboard Peserta') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @php
                $globalAnnouncement = \App\Models\Setting::where('key', 'announcement')->value('value');
            @endphp
            @if(!empty($globalAnnouncement))
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-950/40 dark:to-orange-950/40 border-l-4 border-amber-500 p-6 rounded-r-3xl shadow-xs border border-amber-200 dark:border-amber-900/60 flex gap-4 items-start relative overflow-hidden">
                    <div class="absolute right-0 top-0 translate-x-4 -translate-y-4 opacity-5 text-amber-500 pointer-events-none">
                        <i class="fas fa-bullhorn text-9xl"></i>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300 flex items-center justify-center flex-shrink-0 shadow-inner border border-amber-200 dark:border-amber-800/60">
                        <i class="fas fa-bullhorn text-lg"></i>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-xs font-black text-amber-800 dark:text-amber-400 uppercase tracking-wider mb-1">Pengumuman Penting</h4>
                        <div class="text-xs sm:text-sm text-amber-950 dark:text-amber-200 font-medium leading-relaxed prose prose-amber max-w-none">
                            {!! nl2br(e($globalAnnouncement)) !!}
                        </div>
                    </div>
                </div>
            @endif

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
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
                <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                    <i class="fas fa-route text-teal-600 dark:text-teal-400"></i> Alur Perjalanan Magang Anda
                </h3>
                <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center gap-y-6 gap-x-2">
                    @foreach($stages as $index => $stage)
                        <div class="flex items-center gap-3 md:flex-col md:text-center md:flex-1 relative">
                            {{-- Connector Line --}}
                            @if($index < 6)
                                <div class="hidden md:block absolute top-5 left-1/2 right-[-50%] h-[3px] {{ $currentStage > $index ? 'bg-teal-500 dark:bg-teal-400' : 'bg-gray-200 dark:bg-gray-700' }} -z-0"></div>
                            @endif

                            {{-- Circle Badge --}}
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 border-2 font-bold transition duration-300 relative z-10
                                {{ $currentStage > $index 
                                    ? 'bg-teal-600 dark:bg-teal-500 border-teal-600 dark:border-teal-500 text-white shadow-xs' 
                                    : ($currentStage == $index 
                                        ? 'bg-white dark:bg-gray-800 border-teal-500 text-teal-600 dark:text-teal-400 shadow-xs ring-4 ring-teal-50 dark:ring-teal-950/40' 
                                        : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-400 dark:text-gray-500') }}">
                                @if($currentStage > $index)
                                    <i class="fas fa-check text-xs"></i>
                                @else
                                    <i class="fas {{ $stage['icon'] }} text-xs"></i>
                                @endif
                            </div>

                            {{-- Text --}}
                            <div class="md:mt-2">
                                <h4 class="text-xs font-black {{ $currentStage == $index ? 'text-teal-600 dark:text-teal-400' : ($currentStage > $index ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400 dark:text-gray-500') }}">{{ $stage['name'] }}</h4>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 font-semibold mt-0.5 leading-tight md:max-w-[120px] md:mx-auto">{{ $stage['desc'] }}</p>
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
                <div class="notification-banner bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/40 dark:to-indigo-950/40 border-l-4 border-blue-500 p-6 rounded-r-3xl shadow-xs border border-blue-200 dark:border-blue-900/60 flex gap-4 items-start relative overflow-hidden">
                    <div class="absolute right-0 top-0 translate-x-4 -translate-y-4 opacity-5 text-blue-500 pointer-events-none">
                        <i class="fas fa-stopwatch text-9xl"></i>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-blue-100 dark:bg-blue-900/60 text-blue-600 dark:text-blue-300 flex items-center justify-center flex-shrink-0 shadow-inner border border-blue-200 dark:border-blue-800/60">
                        <i class="fas fa-exclamation-triangle text-lg"></i>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-xs font-extrabold text-blue-800 dark:text-blue-300 uppercase tracking-wider mb-1 flex items-center gap-2">
                            Peringatan Berakhirnya Magang
                            <span class="countdown-badge-pulse inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-600 text-white shadow-xs">
                                <i class="fas fa-clock"></i>
                                {{ $daysRemaining == 0 ? 'HARI INI' : $daysRemaining . ' HARI LAGI' }}
                            </span>
                        </h4>
                        <p class="text-xs sm:text-sm text-blue-900 dark:text-blue-200 font-semibold leading-relaxed">
                            Harap segera melengkapi semua logbook, absensi harian, dan memastikan penilaian dari pembimbing lapangan telah diselesaikan sebelum tanggal berakhir.
                        </p>
                    </div>
                </div>
            @endif

            @if(empty(Auth::user()->nik) || empty(Auth::user()->asal_instansi))
                <div class="bg-amber-50/80 dark:bg-amber-950/40 border-l-4 border-amber-500 p-4 rounded-r-2xl shadow-xs border border-amber-200 dark:border-amber-900/60">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-amber-600 dark:text-amber-400 text-lg mt-0.5"></i>
                        <div>
                            <h3 class="text-xs font-bold text-amber-800 dark:text-amber-300 uppercase tracking-wider">Profil Belum Lengkap</h3>
                            <div class="mt-0.5 text-xs text-amber-900 dark:text-amber-200 font-medium">
                                Silakan lengkapi NIK dan Asal Instansi agar sertifikat dapat dicetak. 
                                <a href="{{ route('profile.edit') }}" class="font-bold underline hover:text-amber-950 dark:hover:text-amber-100 ml-1">Lengkapi Sekarang &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($activeApp && in_array($activeApp->status?->value, ['diterima', 'selesai']))
                {{-- Banner Sambutan & Absen Harian --}}
                <div class="welcome-banner bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between items-center gap-6">
                        
                        <div class="w-full md:w-auto text-center md:text-left animate-fade-in-up">
                            <div class="flex flex-col md:flex-row items-center md:items-start gap-4 mb-4">
                                {{-- Avatar Inisial --}}
                                <div class="w-16 h-16 rounded-2xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-300 font-black text-2xl flex items-center justify-center border border-teal-200 dark:border-teal-800/60 shadow-xs flex-shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="text-center md:text-left">
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-bold {{ $activeApp->display_status == 'selesai' ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60' : ($activeApp->display_status == 'belum mulai' ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60' : 'bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60') }} mb-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $activeApp->display_status == 'selesai' ? 'bg-blue-500' : ($activeApp->display_status == 'belum mulai' ? 'bg-indigo-500' : 'bg-teal-500') }} mr-1.5 animate-ping-slow"></span>
                                        {{ $activeApp->display_status == 'selesai' ? 'Telah Selesai' : ($activeApp->display_status == 'belum mulai' ? 'Belum Mulai' : 'Sedang Magang Aktif') }}
                                    </span>
                                    <h3 class="text-2xl font-black text-gray-900 dark:text-gray-100">Halo, {{ Auth::user()->name }}! 👋</h3>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5 font-medium">{{ $activeApp->display_status == 'selesai' ? 'Program magang Anda telah berakhir. Selamat!' : ($activeApp->display_status == 'belum mulai' ? 'Magang Anda akan segera dimulai. Persiapkan diri Anda!' : 'Pastikan mengisi logbook dan absensi setiap hari kerja.') }}</p>
                                </div>
                            </div>
                            
                            <div class="inline-flex flex-col sm:flex-row gap-3 text-xs font-bold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 p-3 rounded-2xl border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <span class="gps-ping-dot bg-emerald-500"></span>
                                    Masuk: {{ \Carbon\Carbon::parse($jamKerja->jam_mulai_masuk)->format('H:i') }} WIB
                                </div>
                                <div class="hidden sm:block border-l border-gray-300 dark:border-gray-700 h-4 self-center"></div>
                                <div class="flex items-center gap-2">
                                    <span class="gps-ping-dot bg-rose-500"></span>
                                    Pulang: {{ \Carbon\Carbon::parse($jamKerja->jam_mulai_pulang)->format('H:i') }} WIB
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap justify-center gap-3 w-full md:w-auto">
                            @if($activeApp->display_status == 'selesai')
                                <div class="px-6 py-3 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 rounded-2xl border border-blue-200 dark:border-blue-800/60 font-bold flex items-center gap-2 shadow-xs text-xs">
                                    <i class="fas fa-flag-checkered text-blue-500"></i> Magang Selesai
                                </div>
                            @elseif($activeApp->display_status == 'belum mulai')
                                <div class="px-6 py-3 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 rounded-2xl border border-indigo-200 dark:border-indigo-800/60 font-bold flex items-center gap-2 shadow-xs text-xs">
                                    <i class="fas fa-hourglass-start text-indigo-500"></i> Magang Belum Dimulai
                                </div>
                            @elseif(!$attendanceToday)
                                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                                    <form id="form-absen-masuk" action="{{ route('peserta.absen.masuk') }}" method="POST" class="w-full sm:w-auto">
                                        @csrf
                                        <input type="hidden" name="latitude" id="lat-masuk">
                                        <input type="hidden" name="longitude" id="lng-masuk">
                                        <button type="submit" id="btn-absen-masuk" onclick="handleAbsenClick(event, 'form-absen-masuk', 'lat-masuk', 'lng-masuk', 'btn-absen-masuk')" class="w-full sm:w-auto justify-center px-6 py-3.5 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-bold shadow-md transition active:scale-95 flex items-center gap-2 text-xs uppercase tracking-wider">
                                            <i class="fas fa-fingerprint text-sm"></i> Absen Datang
                                        </button>
                                    </form>
                                    
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'modal-izin')" class="w-full sm:w-auto justify-center px-6 py-3.5 bg-white dark:bg-gray-800 border-2 border-amber-400 dark:border-amber-500 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/40 rounded-2xl font-bold transition active:scale-95 flex items-center gap-2 text-xs uppercase tracking-wider">
                                        <i class="fas fa-file-medical text-sm"></i> Izin / Sakit
                                    </button>
                                </div>

                             @elseif($attendanceToday->status == 'hadir' && empty($attendanceToday->clock_out))
                                <div class="flex flex-col items-center gap-3 w-full sm:w-auto">
                                    <div class="text-xs font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 px-4 py-2 rounded-xl border border-teal-200 dark:border-teal-800/60">
                                        <i class="fas fa-check-circle mr-1"></i> Datang: {{ \Carbon\Carbon::parse($attendanceToday->clock_in)->format('H:i') }}
                                    </div>
                                    <form id="form-absen-pulang" action="{{ route('peserta.absen.pulang') }}" method="POST" class="w-full sm:w-auto">
                                        @csrf
                                        <input type="hidden" name="latitude" id="lat-pulang">
                                        <input type="hidden" name="longitude" id="lng-pulang">
                                        <button type="submit" id="btn-absen-pulang" onclick="handleAbsenClick(event, 'form-absen-pulang', 'lat-pulang', 'lng-pulang', 'btn-absen-pulang')" class="w-full sm:w-auto justify-center px-6 py-3.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold shadow-md transition active:scale-95 flex items-center gap-2 text-xs uppercase tracking-wider">
                                            <i class="fas fa-sign-out-alt text-sm"></i> Absen Pulang
                                        </button>
                                    </form>
                                </div>

                            @else
                                <div class="text-center">
                                    @if($attendanceToday->status == 'hadir')
                                        <div class="px-6 py-4 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 rounded-2xl border border-emerald-200 dark:border-emerald-800/60 font-bold flex flex-col items-center shadow-xs text-xs">
                                            <span class="flex items-center gap-1"><i class="fas fa-check-double text-emerald-600 dark:text-emerald-400"></i> Kehadiran Terekam</span>
                                            <span class="text-[11px] font-normal mt-1 text-emerald-600 dark:text-emerald-400">
                                                {{ \Carbon\Carbon::parse($attendanceToday->clock_in)->format('H:i') }} - 
                                                {{ $attendanceToday->clock_out ? \Carbon\Carbon::parse($attendanceToday->clock_out)->format('H:i') : '?' }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="px-6 py-3 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 rounded-2xl border border-amber-200 dark:border-amber-800/60 font-bold flex items-center gap-2 shadow-xs text-xs">
                                            <i class="fas fa-info-circle text-amber-500"></i> Status Absen: {{ ucfirst($attendanceToday->status) }}
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
                    <div class="bg-indigo-50/60 dark:bg-indigo-950/40 rounded-3xl p-6 md:p-8 border border-indigo-200 dark:border-indigo-800/60 shadow-xs mb-6">
                        <div class="flex flex-col md:flex-row gap-6 items-start justify-between">
                            <div class="flex gap-4 items-start">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 border border-indigo-200 dark:border-indigo-800/60 shadow-inner">
                                    <i class="fas fa-comment-alt text-xl"></i>
                                </div>
                                <div class="space-y-1">
                                    <h3 class="text-gray-900 dark:text-gray-100 font-extrabold text-lg md:text-xl tracking-tight flex items-center gap-2">
                                        Isi Evaluasi & Saran Magang
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-indigo-600 text-white shadow-xs">
                                            WAJIB
                                        </span>
                                    </h3>
                                    <p class="text-gray-600 dark:text-gray-400 text-xs md:text-sm font-medium leading-relaxed max-w-3xl">
                                        Selamat! Masa magang Anda telah selesai. Mohon berikan saran dan evaluasi konstruktif untuk <strong>{{ $activeApp->position->instansi->nama_dinas }}</strong>. Evaluasi Anda bersifat <strong>anonim</strong> dan wajib diisi sebelum mengunduh Sertifikat & Transkrip Nilai Anda.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('peserta.saran.store', $activeApp->id) }}" method="POST" class="mt-6 space-y-4">
                            @csrf
                            <div>
                                <textarea name="saran_peserta" rows="4" required class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-2xl shadow-xs focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm font-medium" placeholder="Tuliskan evaluasi, kritik, atau saran perbaikan untuk instansi tempat magang Anda..."></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-md active:scale-95 transition flex items-center gap-2">
                                    <i class="fas fa-paper-plane"></i> Kirim Evaluasi & Buka Sertifikat
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                {{-- Grid Dashboard Stats & Detail Magang --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    
                    @include('peserta.dashboard._stats')

                    @include('peserta.dashboard._absensi-card')

                    @include('peserta.dashboard._logbook-card')

                </div>

                <x-modal name="modal-izin" focusable>
                    <div class="p-6 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        <h2 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-4 pb-3 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                            <i class="fas fa-file-alt text-amber-500"></i> Form Pengajuan Izin / Sakit
                        </h2>
                        <form action="{{ route('peserta.absen.izin') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Jenis Keterangan</label>
                                    <select name="status" class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-xl shadow-xs focus:border-teal-500 focus:ring-teal-500 text-xs font-bold [color-scheme:dark]">
                                        <option value="sakit">Sakit (Upload Surat Dokter)</option>
                                        <option value="izin">Izin (Keperluan Mendesak)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Alasan Detail</label>
                                    <textarea name="description" rows="3" class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-xl shadow-xs focus:border-teal-500 focus:ring-teal-500 text-xs font-medium" required placeholder="Jelaskan alasan pengajuan Anda..."></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Bukti Foto / Surat (PNG / JPG / PDF)</label>
                                    <input type="file" name="proof_file" class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 dark:file:bg-teal-950/60 file:text-teal-700 dark:file:text-teal-300 hover:file:bg-teal-100 border border-gray-300 dark:border-gray-700 rounded-xl p-1 bg-white dark:bg-gray-900" required>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" x-on:click.prevent="$dispatch('close')" class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 rounded-xl font-bold text-xs transition uppercase tracking-wider">Batal</button>
                                <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-xs shadow-md transition uppercase tracking-wider">Kirim Pengajuan</button>
                            </div>
                        </form>
                    </div>
                </x-modal>
            @else
                {{-- Banner Penempatan Otomatis --}}
                <div class="bg-gradient-to-r from-teal-800 via-teal-900 to-emerald-900 rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-teal-950/20 mb-6 overflow-hidden relative border border-teal-700/50">
                    <div class="absolute right-0 top-0 translate-x-6 -translate-y-6 opacity-10 text-white pointer-events-none">
                        <i class="fas fa-magic text-9xl"></i>
                    </div>
                    <div class="flex flex-col md:flex-row gap-6 items-center justify-between relative z-10">
                        <div class="flex gap-4 items-start">
                            <div class="w-12 h-12 rounded-2xl bg-teal-500/20 text-teal-300 flex items-center justify-center flex-shrink-0 shadow-inner border border-teal-500/30">
                                <i class="fas fa-magic text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-extrabold text-lg md:text-xl leading-snug">Bingung Memilih Instansi Magang?</h3>
                                <p class="text-teal-100/90 text-xs sm:text-sm mt-1 max-w-2xl font-medium leading-relaxed">
                                    Gunakan fitur <strong>Penempatan Otomatis</strong>! Sistem akan menyalurkan Anda secara otomatis ke instansi yang masih kuota tersedia sesuai dengan kualifikasi jurusan Anda ({{ Auth::user()->major ?? '-' }}).
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('peserta.apply_automatic.form') }}" class="shrink-0 bg-white dark:bg-gray-800 text-teal-800 dark:text-teal-300 hover:bg-teal-50 px-6 py-3 rounded-xl font-bold shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 active:scale-95 text-xs uppercase tracking-wider">
                            Daftar Penempatan Otomatis
                        </a>
                    </div>
                </div>
            @endif

            @include('peserta.dashboard._lamaran-list')

        </div>
    </div>

    @push('scripts')
    <script>
    function autoDetectGPS() {
        const banner = document.getElementById('gps-status-banner');
        if (!banner) return;

        const officeLat = parseFloat(banner.dataset.lat);
        const officeLng = parseFloat(banner.dataset.lng);
        const radius = parseInt(banner.dataset.radius) || 100;

        const iconWrapper = document.getElementById('gps-icon-wrapper');
        const title = document.getElementById('gps-title');
        const desc = document.getElementById('gps-desc');
        const badge = document.getElementById('gps-badge');

        if (!navigator.geolocation) {
            banner.className = "px-6 py-4 bg-rose-50/60 dark:bg-rose-950/20 border-t border-rose-100 dark:border-rose-900/50 flex items-center justify-between flex-wrap gap-3 transition-all duration-300";
            iconWrapper.className = "w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center text-lg shadow-md shadow-rose-500/20";
            iconWrapper.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
            title.className = "text-xs font-extrabold text-rose-900 dark:text-rose-300 uppercase tracking-wider";
            title.innerText = "GPS Tidak Didukung";
            desc.className = "text-xs text-rose-600 dark:text-rose-400 font-medium";
            desc.innerText = "Browser Anda tidak mendukung fitur geolokasi GPS.";
            badge.className = "px-3.5 py-1.5 rounded-xl bg-white dark:bg-gray-800 text-rose-700 dark:text-rose-400 text-xs font-extrabold shadow-sm border border-rose-200/60 dark:border-rose-900/50 flex items-center gap-1.5";
            badge.innerHTML = '<i class="fas fa-times"></i> Gagal';
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
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

                const R = 6371000;
                const dLat = (officeLat - lat) * Math.PI / 180;
                const dLon = (officeLng - lng) * Math.PI / 180;
                const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                          Math.cos(lat * Math.PI / 180) * Math.cos(officeLat * Math.PI / 180) *
                          Math.sin(dLon/2) * Math.sin(dLon/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                const distance = Math.round(R * c);

                if (distance <= radius) {
                    banner.className = "px-6 py-4 bg-emerald-50/80 dark:bg-emerald-950/20 border-t border-emerald-200/60 dark:border-emerald-900/50 flex items-center justify-between flex-wrap gap-3 transition-all duration-300";
                    iconWrapper.className = "w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-lg shadow-md shadow-emerald-500/20";
                    iconWrapper.innerHTML = '<i class="fas fa-map-marker-alt"></i>';
                    title.className = "text-xs font-extrabold text-emerald-900 dark:text-emerald-300 uppercase tracking-wider";
                    title.innerText = "Lokasi Terverifikasi (Dalam Radius)";
                    desc.className = "text-xs text-emerald-700 dark:text-emerald-400 font-medium";
                    desc.innerText = `Jarak Anda: ${distance} meter dari kantor (Batas maksimal ${radius}m). Anda siap melakukan absensi!`;
                    badge.className = "px-3.5 py-1.5 rounded-xl bg-white dark:bg-gray-800 text-emerald-700 dark:text-emerald-400 text-xs font-extrabold shadow-sm border border-emerald-200/60 dark:border-emerald-900/50 flex items-center gap-1.5";
                    badge.innerHTML = '<i class="fas fa-check-circle text-emerald-500"></i> Siap Absen';
                } else {
                    banner.className = "px-6 py-4 bg-amber-50/80 dark:bg-amber-950/20 border-t border-amber-200/60 dark:border-amber-900/50 flex items-center justify-between flex-wrap gap-3 transition-all duration-300";
                    iconWrapper.className = "w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg shadow-md shadow-amber-500/20";
                    iconWrapper.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
                    title.className = "text-xs font-extrabold text-amber-900 dark:text-amber-300 uppercase tracking-wider";
                    title.innerText = "Di Luar Radius Kantor";
                    desc.className = "text-xs text-amber-700 dark:text-amber-400 font-medium";
                    desc.innerText = `Jarak Anda: ${distance} meter dari kantor (Batas maksimal ${radius}m). Mendekatlah ke kantor untuk absen.`;
                    badge.className = "px-3.5 py-1.5 rounded-xl bg-white dark:bg-gray-800 text-amber-700 dark:text-amber-400 text-xs font-extrabold shadow-sm border border-amber-200/60 dark:border-amber-900/50 flex items-center gap-1.5";
                    badge.innerHTML = `<i class="fas fa-ruler-horizontal text-amber-500"></i> Jarak: ${distance}m`;
                }
            },
            function(error) {
                banner.className = "px-6 py-4 bg-rose-50/80 dark:bg-rose-950/20 border-t border-rose-200/60 dark:border-rose-900/50 flex items-center justify-between flex-wrap gap-3 transition-all duration-300";
                iconWrapper.className = "w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center text-lg shadow-md shadow-rose-500/20";
                iconWrapper.innerHTML = '<i class="fas fa-map-pin"></i>';
                title.className = "text-xs font-extrabold text-rose-900 dark:text-rose-300 uppercase tracking-wider";
                title.innerText = "Izin Lokasi (GPS) Diperlukan";
                
                let errorMsg = "Sistem gagal mengambil lokasi GPS Anda. Pastikan GPS aktif.";
                if (error.code === 1) errorMsg = "Akses lokasi ditolak! Silakan izinkan akses lokasi (GPS) pada browser/HP Anda.";
                else if (error.code === 2) errorMsg = "Sinyal GPS tidak ditemukan. Pastikan GPS HP/perangkat Anda aktif.";
                else if (error.code === 3) errorMsg = "Waktu permintaan lokasi habis (timeout). Silakan coba lagi.";

                desc.className = "text-xs text-rose-700 dark:text-rose-400 font-medium";
                desc.innerText = errorMsg;
                badge.className = "px-3.5 py-1.5 rounded-xl bg-white dark:bg-gray-800 text-rose-700 dark:text-rose-400 text-xs font-extrabold shadow-sm border border-rose-200/60 dark:border-rose-900/50 flex items-center gap-1.5 cursor-pointer hover:bg-rose-50 dark:hover:bg-rose-950/20";
                badge.innerHTML = '<i class="fas fa-sync-alt mr-1"></i> Coba Deteksi Ulang';
                badge.onclick = autoDetectGPS;
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
        addRipple(btn, event);
        const originalHtml = btn.innerHTML;
        const form = document.getElementById(formId);
        const latVal = document.getElementById(latId)?.value;
        const lngVal = document.getElementById(lngId)?.value;

        if (latVal && lngVal && latVal !== '' && lngVal !== '') {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Mengirim Absensi...</span>';
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            form.submit();
            return;
        }

        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung fitur geolokasi GPS. Silakan gunakan browser/HP lain.');
            return;
        }

        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> <span>Mendeteksi GPS...</span>';
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                document.getElementById(latId).value = lat.toFixed(6);
                document.getElementById(lngId).value = lng.toFixed(6);
                btn.innerHTML = '<i class="fas fa-check-circle"></i> <span>Lokasi Terkunci! Mengirim...</span>';
                setTimeout(() => form.submit(), 400);
            },
            function(error) {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-not-allowed');
                let msg = 'Gagal mengambil lokasi GPS Anda.';
                if (error.code === 1) msg = 'Akses Lokasi (GPS) ditolak! Izinkan akses lokasi pada browser Anda.';
                else if (error.code === 2) msg = 'Sinyal GPS tidak ditemukan. Pastikan GPS aktif.';
                else if (error.code === 3) msg = 'Waktu permintaan lokasi habis. Silakan coba lagi.';
                alert(msg);
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }
    </script>
    @endpush
</x-app-layout>
