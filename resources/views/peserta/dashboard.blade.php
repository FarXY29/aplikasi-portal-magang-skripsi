<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-columns text-teal-600"></i>
                {{ __('Dashboard Peserta') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @php
                $globalAnnouncement = \App\Models\Setting::where('key', 'announcement')->value('value');
            @endphp
            @if(!empty($globalAnnouncement))
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 p-6 rounded-r-2xl shadow-sm border border-amber-100 flex gap-4 items-start relative overflow-hidden">
                    <div class="absolute right-0 top-0 translate-x-4 -translate-y-4 opacity-5 text-amber-500 pointer-events-none">
                        <i class="fas fa-bullhorn text-9xl"></i>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 shadow-inner">
                        <i class="fas fa-bullhorn text-lg"></i>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-sm font-extrabold text-amber-800 uppercase tracking-wider mb-1">Pengumuman Penting</h4>
                        <div class="text-sm text-amber-950 font-semibold leading-relaxed prose prose-amber max-w-none">
                            {!! nl2br(e($globalAnnouncement)) !!}
                        </div>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center p-4 mb-4 text-green-800 rounded-xl bg-green-50 border border-green-100 shadow-sm relative">
                    <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 mr-3 text-green-600"></i>
                    <div class="text-sm font-bold">{{ session('success') }}</div>
                    <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center p-4 mb-4 text-red-800 rounded-xl bg-red-50 border border-red-100 shadow-sm relative">
                    <i class="fas fa-exclamation-triangle flex-shrink-0 w-5 h-5 mr-3 text-red-600"></i>
                    <div class="text-sm font-bold">{{ session('error') }}</div>
                    <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            {{-- Notifikasi H-7 Magang Berakhir --}}
            @if(isset($daysRemaining) && $daysRemaining >= 0 && $daysRemaining <= 7)
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 p-6 rounded-r-2xl shadow-sm border border-blue-100 flex gap-4 items-start relative overflow-hidden animate-pulse">
                    <div class="absolute right-0 top-0 translate-x-4 -translate-y-4 opacity-5 text-blue-500 pointer-events-none">
                        <i class="fas fa-stopwatch text-9xl"></i>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 shadow-inner">
                        <i class="fas fa-info-circle text-lg"></i>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-sm font-extrabold text-blue-800 uppercase tracking-wider mb-1">Peringatan Berakhirnya Magang</h4>
                        <p class="text-sm text-blue-900 font-semibold leading-relaxed">
                            Masa magang Anda akan berakhir dalam <span class="text-red-600 text-base">{{ $daysRemaining == 0 ? 'hari ini' : $daysRemaining . ' hari lagi' }}</span>. Harap segera melengkapi semua logbook, absensi harian, dan memastikan penilaian dari pembimbing lapangan telah diselesaikan sebelum tanggal tersebut.
                        </p>
                    </div>
                </div>
            @endif

            @if(empty(Auth::user()->nik) || empty(Auth::user()->asal_instansi))
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-xl shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-yellow-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-yellow-800">Profil Belum Lengkap</h3>
                            <div class="mt-1 text-sm text-yellow-700">
                                Silakan lengkapi NIK dan Asal Instansi agar sertifikat dapat dicetak. 
                                <a href="{{ route('profile.edit') }}" class="font-bold underline hover:text-yellow-900">Lengkapi Sekarang &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($activeApp && in_array($activeApp->status, ['diterima', 'selesai']))
                <!-- 1. Banner Sambutan & Absen Harian -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                    <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between items-center gap-6 bg-gradient-to-r from-teal-50/50 via-white to-teal-50/20">
                        
                        <div class="w-full md:w-auto text-center md:text-left">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $activeApp->display_status == 'selesai' ? 'bg-blue-100 text-blue-800 border-blue-200' : ($activeApp->display_status == 'belum mulai' ? 'bg-indigo-100 text-indigo-800 border-indigo-200' : 'bg-teal-100 text-teal-800 border-teal-200') }} mb-2">
                                <i class="fas fa-check-circle mr-1"></i> Status: {{ $activeApp->display_status == 'selesai' ? 'Telah Selesai' : ($activeApp->display_status == 'belum mulai' ? 'Belum Mulai' : 'Sedang Magang Aktif') }}
                            </span>
                            <h3 class="text-2xl font-extrabold text-gray-900 mb-1">Halo, {{ Auth::user()->name }}!</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ $activeApp->display_status == 'selesai' ? 'Program magang Anda telah berakhir.' : ($activeApp->display_status == 'belum mulai' ? 'Magang Anda akan segera dimulai. Persiapkan diri Anda!' : 'Pastikan untuk mengisi logbook dan melakukan absensi setiap hari kerja.') }}</p>
                            
                            <div class="inline-flex flex-col sm:flex-row gap-3 text-xs font-bold text-gray-600 bg-white p-3 rounded-xl shadow-sm border border-gray-150">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                                    Jam Masuk: {{ \Carbon\Carbon::parse($jamKerja->jam_mulai_masuk)->format('H:i') }} WIB
                                </div>
                                <div class="hidden sm:block border-l border-gray-300 h-4 self-center"></div>
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                                    Jam Pulang: {{ \Carbon\Carbon::parse($jamKerja->jam_mulai_pulang)->format('H:i') }} WIB
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap justify-center gap-3 w-full md:w-auto">
                            @if($activeApp->display_status == 'selesai')
                                <div class="px-6 py-3 bg-blue-50 text-blue-700 rounded-xl border border-blue-200 font-bold flex items-center gap-2 shadow-sm">
                                    <i class="fas fa-flag-checkered text-blue-500"></i> Magang Selesai
                                </div>
                            @elseif($activeApp->display_status == 'belum mulai')
                                <div class="px-6 py-3 bg-indigo-50 text-indigo-700 rounded-xl border border-indigo-200 font-bold flex items-center gap-2 shadow-sm">
                                    <i class="fas fa-hourglass-start text-indigo-500"></i> Magang Belum Dimulai
                                </div>
                            @elseif(!$attendanceToday)
                                <form action="{{ route('peserta.absen.masuk') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold shadow-lg shadow-teal-200 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                                        <i class="fas fa-fingerprint"></i> Absen Datang
                                    </button>
                                </form>
                                
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'modal-izin')" class="px-6 py-3 bg-white border border-yellow-400 text-yellow-600 hover:bg-yellow-50 rounded-xl font-bold transition flex items-center gap-2">
                                    <i class="fas fa-file-medical"></i> Izin / Sakit
                                </button>

                            @elseif($attendanceToday->status == 'hadir' && empty($attendanceToday->clock_out))
                                <div class="flex flex-col items-center gap-3">
                                    <div class="text-xs font-bold text-teal-700 bg-teal-50 px-4 py-2 rounded-lg border border-teal-150">
                                        <i class="fas fa-check-circle mr-1"></i> Datang: {{ \Carbon\Carbon::parse($attendanceToday->clock_in)->format('H:i') }}
                                    </div>
                                    <form action="{{ route('peserta.absen.pulang') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold shadow-lg shadow-red-200 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                                            <i class="fas fa-sign-out-alt"></i> Absen Pulang
                                        </button>
                                    </form>
                                </div>

                            @else
                                <div class="text-center">
                                    @if($attendanceToday->status == 'hadir')
                                        <div class="px-6 py-4 bg-green-50 text-green-700 rounded-xl border border-green-200 font-bold flex flex-col items-center shadow-sm">
                                            <span class="flex items-center gap-1"><i class="fas fa-check-double text-green-600"></i> Kehadiran Terekam</span>
                                            <span class="text-xs font-normal mt-1 text-green-600">
                                                {{ \Carbon\Carbon::parse($attendanceToday->clock_in)->format('H:i') }} - 
                                                {{ $attendanceToday->clock_out ? \Carbon\Carbon::parse($attendanceToday->clock_out)->format('H:i') : '?' }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="px-6 py-3 bg-yellow-50 text-yellow-700 rounded-xl border border-yellow-200 font-bold flex items-center gap-2 shadow-sm">
                                            <i class="fas fa-info-circle text-yellow-500"></i> Status Absen: {{ ucfirst($attendanceToday->status) }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                <!-- 2. Grid Dashboard Stats & Detail Magang -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    
                    <!-- Progress Card -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Progress Magang</h4>
                                <span class="p-2 bg-teal-50 rounded-xl text-teal-600"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                            <div class="text-2xl font-black text-gray-900 mb-1">
                                {{ $stats['elapsed_days'] }} <span class="text-sm font-normal text-gray-500">dari {{ $stats['total_days'] }} Hari</span>
                            </div>
                            <p class="text-xs text-gray-500 mb-4">Internship timeline completion rate.</p>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold text-gray-700 mb-1.5">
                                <span>Persentase</span>
                                <span>{{ $stats['progress_percent'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-teal-600 h-2 rounded-full transition-all duration-500" style="width: {{ $stats['progress_percent'] }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Stats Card -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Statistik Absensi</h4>
                                <span class="p-2 bg-purple-50 rounded-xl text-purple-600"><i class="fas fa-calendar-check"></i></span>
                            </div>
                            <div class="grid grid-cols-3 gap-2 text-center">
                                <div class="bg-green-50/50 p-3 rounded-xl border border-green-100">
                                    <span class="block text-xl font-bold text-green-700">{{ $stats['attendance']['hadir'] }}</span>
                                    <span class="text-[10px] font-bold text-green-600 uppercase">Hadir</span>
                                </div>
                                <div class="bg-yellow-50/50 p-3 rounded-xl border border-yellow-100">
                                    <span class="block text-xl font-bold text-yellow-700">{{ $stats['attendance']['izin'] }}</span>
                                    <span class="text-[10px] font-bold text-yellow-600 uppercase">Izin</span>
                                </div>
                                <div class="bg-red-50/50 p-3 rounded-xl border border-red-100">
                                    <span class="block text-xl font-bold text-red-700">{{ $stats['attendance']['alpa'] }}</span>
                                    <span class="text-[10px] font-bold text-red-600 uppercase">Alpa</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-3 text-center">Data absensi kumulatif selama masa magang.</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100 text-center">
                            <a href="{{ route('peserta.absensi.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-600 hover:text-teal-800 transition">
                                <i class="fas fa-history"></i> Lihat Riwayat Absen
                            </a>
                        </div>
                    </div>

                    <!-- Logbook & Pembimbing Lapangan Card -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Logbook Harian</h4>
                                <span class="p-2 bg-blue-50 rounded-xl text-blue-600"><i class="fas fa-book"></i></span>
                            </div>
                            <div class="flex justify-between items-center gap-4 bg-gray-50 p-3 rounded-xl border border-gray-150 mb-3">
                                <div>
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Total Log</span>
                                    <span class="text-lg font-bold text-gray-800">{{ $stats['logs_count'] }} Entri</span>
                                </div>
                                <div class="w-px h-8 bg-gray-200"></div>
                                <div>
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Valid</span>
                                    <span class="text-lg font-bold text-green-600">{{ $stats['logs_validated'] }} Entri</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pembimbing Lapangan Contact Details -->
                        <div class="border-t border-gray-100 pt-3 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 font-bold border border-teal-100 text-xs">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="block text-[10px] font-bold text-gray-400 uppercase leading-none">Pembimbing Lapangan</span>
                                <span class="text-xs font-bold text-gray-800 truncate block">{{ $activeApp->pembimbing_lapangan->name ?? 'Belum Ditunjuk' }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                <x-modal name="modal-izin" focusable>
                    <div class="p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-file-alt text-yellow-500"></i> Form Izin / Sakit
                        </h2>
                        <form action="{{ route('peserta.absen.izin') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Keterangan</label>
                                    <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                        <option value="sakit">Sakit (Upload Surat Dokter)</option>
                                        <option value="izin">Izin (Keperluan Mendesak)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Alasan Detail</label>
                                    <textarea name="description" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500" required placeholder="Jelaskan alasan Anda..."></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Bukti Foto / Surat</label>
                                    <input type="file" name="proof_file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-gray-300 rounded-lg" required>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50 transition">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg font-bold hover:bg-teal-700 transition shadow-md">Kirim Pengajuan</button>
                            </div>
                        </form>
                    </div>
                </x-modal>
            @else
                <!-- Banner Penempatan Otomatis -->
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
                                <p class="text-teal-100/90 text-sm mt-1 max-w-2xl font-medium leading-relaxed">
                                    Gunakan fitur **Penempatan Otomatis**! Sistem akan menyalurkan Anda secara otomatis ke instansi yang masih longgar dan membutuhkan peserta sesuai dengan kualifikasi jurusan Anda ({{ Auth::user()->major ?? '-' }}).
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('peserta.apply_automatic.form') }}" class="shrink-0 bg-white text-teal-800 hover:bg-teal-50 px-6 py-3 rounded-xl font-extrabold shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 active:scale-95 text-sm">
                            Daftar Penempatan Otomatis
                        </a>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-briefcase text-teal-500"></i> Riwayat Lamaran
                    </h3>
                    
                    <!-- Form Filter Riwayat -->
                    <form action="{{ route('peserta.dashboard') }}" method="GET" class="w-full md:w-auto flex flex-wrap items-center gap-3">
                        <!-- Filter Status -->
                        <div>
                            <select name="status" onchange="this.form.submit()" class="text-xs rounded-xl border-gray-200 focus:border-teal-500 focus:ring-teal-500 py-1.5 pl-3 pr-8 cursor-pointer shadow-sm font-bold text-gray-600">
                                <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Daftar Tunggu</option>
                                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        
                        <!-- Filter Tanggal Awal -->
                        <div class="flex items-center gap-1.5">
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Dari:</span>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()" 
                                   class="text-xs rounded-xl border-gray-200 focus:border-teal-500 focus:ring-teal-500 py-1 px-2 cursor-pointer shadow-sm text-gray-600 font-medium">
                        </div>

                        <!-- Filter Tanggal Akhir -->
                        <div class="flex items-center gap-1.5">
                            <span class="text-[10px] font-bold text-gray-400 uppercase">S/D:</span>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()" 
                                   class="text-xs rounded-xl border-gray-200 focus:border-teal-500 focus:ring-teal-500 py-1 px-2 cursor-pointer shadow-sm text-gray-600 font-medium">
                        </div>

                        @if(request('status') || request('start_date') || request('end_date'))
                            <a href="{{ route('peserta.dashboard') }}" class="p-1.5 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-lg transition" title="Reset Filter">
                                <i class="fas fa-times text-xs"></i>
                            </a>
                        @endif
                    </form>
                </div>
                
                <div class="p-6 space-y-4">
                    @forelse($myApplications as $app)
                        <div x-data class="flex flex-col lg:flex-row justify-between items-start lg:items-center p-5 rounded-xl border transition hover:shadow-md cursor-pointer gap-4
                            {{ $app->status == 'diterima' ? 'bg-teal-50/50 border-teal-100 hover:border-teal-300' : ($app->status == 'selesai' ? 'bg-blue-50/50 border-blue-100 hover:border-blue-300' : ($app->status == 'menunggu' ? 'bg-orange-50/50 border-orange-100' : 'bg-white border-gray-100 hover:border-teal-200')) }}"
                            x-on:click="$dispatch('open-modal', 'modal-lamaran-{{ $app->id }}')">
                            
                            <div class="w-full lg:flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                    <h4 class="font-bold text-gray-900 text-lg leading-tight">{{ $app->position->instansi->nama_dinas }}</h4>
                                    @php
                                        $badges = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'menunggu' => 'bg-orange-100 text-orange-800',
                                            'diterima' => 'bg-green-100 text-green-800',
                                            'belum mulai' => 'bg-indigo-100 text-indigo-800',
                                            'selesai' => 'bg-blue-100 text-blue-800',
                                            'ditolak' => 'bg-red-100 text-red-800'
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase whitespace-nowrap {{ $badges[$app->display_status] ?? 'bg-gray-100' }}">
                                        {{ $app->display_status }}
                                    </span>
                                    @if($app->is_automatic_placement)
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200 flex items-center gap-1 whitespace-nowrap">
                                            <i class="fas fa-magic text-[10px]"></i> Penempatan Otomatis
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 font-medium">{{ $app->position->judul_posisi }}</p>
                                
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-xs text-gray-400 font-medium">
                                    <span class="flex items-center gap-1.5">
                                        <i class="far fa-paper-plane text-gray-300"></i>
                                        Tgl Lamar: <span class="text-gray-600 font-bold">{{ \Carbon\Carbon::parse($app->created_at)->translatedFormat('d M Y') }}</span>
                                    </span>
                                    @if(in_array($app->status, ['diterima', 'selesai']) && $app->tanggal_mulai)
                                    <span class="flex items-center gap-1.5">
                                        <i class="far fa-calendar-alt text-gray-300"></i>
                                        Periode Magang: <span class="text-gray-600 font-bold">{{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d M Y') }}</span>
                                    </span>
                                    @endif
                                </div>
                                
                                @if($app->nilai_angka)
                                    <div class="mt-2 inline-flex items-center px-3 py-1 bg-white rounded border border-blue-200 text-xs font-bold text-blue-700 shadow-sm">
                                        <i class="fas fa-star mr-1 text-yellow-400"></i> Nilai Akhir: {{ $app->nilai_angka }} ({{ $app->predikat }})
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-wrap gap-2 justify-start lg:justify-end w-full lg:w-auto shrink-0 mt-2 lg:mt-0" x-on:click.stop>
                                @if($app->display_status == 'diterima')
                                    <a href="{{ route('peserta.id_card.download', $app->id) }}" target="_blank" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-bold hover:bg-emerald-700 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-id-card"></i> ID Card
                                    </a>
                                    <a href="{{ route('peserta.loa.download', $app->id) }}" target="_blank" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-file-contract"></i> Surat Balasan
                                    </a>

                                    <a href="{{ route('peserta.logbook.index') }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg text-sm font-bold hover:bg-teal-700 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-book-open"></i> Logbook
                                    </a>

                                @elseif($app->display_status == 'belum mulai')
                                    <a href="{{ route('peserta.id_card.download', $app->id) }}" target="_blank" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-bold hover:bg-emerald-700 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-id-card"></i> ID Card
                                    </a>
                                    <a href="{{ route('peserta.loa.download', $app->id) }}" target="_blank" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-file-contract"></i> Surat Balasan
                                    </a>
                                @elseif($app->display_status == 'selesai')
                                    <a href="{{ route('peserta.id_card.download', $app->id) }}" target="_blank" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-bold hover:bg-emerald-700 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-id-card"></i> ID Card
                                    </a>
                                    <a href="{{ route('peserta.loa.download', $app->id) }}" target="_blank" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-file-contract"></i> Surat Balasan
                                    </a>
                                    <a href="{{ route('peserta.sertifikat') }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-certificate"></i> Sertifikat
                                    </a>
                                    <a href="{{ route('peserta.download.nilai', $app->id) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-file-alt"></i> Transkrip
                                    </a>
                                    

                                @endif

                                @if(in_array($app->status, ['pending', 'menunggu']) || ($app->status === 'diterima' && $app->display_status === 'belum mulai'))
                                    <form action="{{ route('peserta.lamaran.batal', $app->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan lamaran magang ini? Tindakan ini tidak dapat dikembalikan.');">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 transition shadow-sm flex items-center gap-2">
                                            <i class="fas fa-times-circle"></i> Batalkan
                                        </button>
                                    </form>
                                @endif

                                <button type="button" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-200 transition shadow-sm flex items-center gap-2" x-on:click.prevent="$dispatch('open-modal', 'modal-lamaran-{{ $app->id }}')">
                                    <i class="fas fa-info-circle"></i> Detail
                                </button>
                            </div>
                        </div>

                        <!-- Modal Detail Lamaran -->
                        <x-modal name="modal-lamaran-{{ $app->id }}" focusable>
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4 border-b border-gray-100 pb-4">
                                    <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-clipboard-list text-teal-600"></i> Detail Lamaran Magang
                                    </h2>
                                    <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 p-2 rounded-full transition"><i class="fas fa-times"></i></button>
                                </div>
                                
                                <div class="mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <h4 class="font-bold text-gray-900 text-lg mb-1">{{ $app->position->instansi->nama_dinas }}</h4>
                                    <p class="text-sm text-teal-700 font-bold mb-4">{{ $app->position->judul_posisi }}</p>
                                    
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-500 mb-1 text-xs font-bold uppercase">Status Akhir</p>
                                            <p><span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase {{ $badges[$app->display_status] ?? 'bg-gray-200' }}">{{ $app->display_status }}</span></p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 mb-1 text-xs font-bold uppercase">Tanggal Daftar</p>
                                            <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($app->created_at)->translatedFormat('d M Y') }}</p>
                                        </div>
                                        @if(in_array($app->status, ['diterima', 'selesai']) && $app->tanggal_mulai)
                                            <div class="col-span-2">
                                                <p class="text-gray-500 mb-1 text-xs font-bold uppercase">Periode Pelaksanaan</p>
                                                <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d M Y') }} <span class="text-gray-400 mx-2">&rarr;</span> {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d M Y') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if($app->catatan_pembimbing_lapangan)
                                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 p-5 rounded-xl shadow-sm mb-6">
                                        <h3 class="font-bold text-amber-800 text-sm flex items-center gap-2 mb-3">
                                            <i class="fas fa-lightbulb text-amber-500"></i> Pesan & Saran dari Pembimbing Lapangan
                                        </h3>
                                        <p class="text-sm text-amber-900 italic font-medium leading-relaxed">"{{ $app->catatan_pembimbing_lapangan }}"</p>
                                    </div>
                                @endif

                                @if($app->status == 'selesai')
                                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                                        <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                                            <i class="fas fa-star text-teal-500"></i> Evaluasi & Saran untuk Instansi
                                        </h3>
                                        @if($app->saran_peserta)
                                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                                <p class="text-sm text-gray-700 italic">"{{ $app->saran_peserta }}"</p>
                                                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-200">
                                                    <i class="fas fa-check-circle text-green-500"></i>
                                                    <p class="text-xs text-gray-600 font-bold">Evaluasi telah dikirimkan secara anonim.</p>
                                                </div>
                                            </div>
                                        @else
                                            <form action="{{ route('peserta.saran.store', $app->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-4">
                                                    <label class="block text-xs font-bold text-gray-600 mb-2">Beri Masukan Pembangun (Kerahasiaan Terjamin)</label>
                                                    <textarea name="saran_peserta" rows="3" class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm text-sm" placeholder="Tuliskan saran atau kritik membangun Anda mengenai instansi maupun pembimbing..." required></textarea>
                                                </div>
                                                <div class="flex justify-end">
                                                    <button type="submit" class="px-5 py-2.5 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 shadow-md transition transform active:scale-95 text-sm flex items-center gap-2">
                                                        <i class="fas fa-paper-plane"></i> Kirim Evaluasi
                                                    </button>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="mt-6 flex justify-end">
                                    <button x-on:click="$dispatch('close')" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold rounded-xl transition">Tutup</button>
                                </div>
                            </div>
                        </x-modal>
                    @empty
                        <div class="text-center py-10">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                                <i class="fas fa-inbox text-3xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Tidak ada lamaran yang sesuai filter.</p>
                            @if(request('status') || request('start_date') || request('end_date'))
                                <a href="{{ route('peserta.dashboard') }}" class="mt-2 inline-block text-teal-600 font-bold hover:underline">Reset Filter</a>
                            @else
                                <a href="{{ route('home') }}" class="mt-2 inline-block text-teal-600 font-bold hover:underline">Cari Lowongan Magang &rarr;</a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>