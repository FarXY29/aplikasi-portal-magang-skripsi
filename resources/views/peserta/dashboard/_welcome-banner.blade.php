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
                    Masuk: {{ $jamKerja?->jam_mulai_masuk ? \Carbon\Carbon::parse($jamKerja->jam_mulai_masuk)->format('H:i') : '--:--' }} WITA
                </div>
                <div class="hidden sm:block border-l border-slate-300 dark:border-gray-700 h-4 self-center"></div>
                <div class="flex items-center gap-2 justify-center sm:justify-start">
                    <span class="gps-ping-dot bg-rose-600"></span>
                    Pulang: {{ $jamKerja?->jam_mulai_pulang ? \Carbon\Carbon::parse($jamKerja->jam_mulai_pulang)->format('H:i') : '--:--' }} WITA
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
                @php
                    $qrRequired = (bool) ($jamKerja?->qr_absensi_enabled ?? false);
                @endphp
                <div class="grid grid-cols-2 sm:flex sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
                    <form id="form-absen-masuk" action="{{ route('peserta.absen.masuk') }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <input type="hidden" name="latitude" id="lat-masuk">
                        <input type="hidden" name="longitude" id="lng-masuk">
                        <input type="hidden" name="qr_token" id="qr-token-masuk">
                        @if($qrRequired)
                            <button type="button" id="btn-absen-masuk" onclick="openQrScanner(event, 'form-absen-masuk', 'lat-masuk', 'lng-masuk', 'btn-absen-masuk', 'Absen Datang')" class="w-full sm:w-auto min-h-[44px] justify-center px-5 sm:px-6 py-3 bg-teal-700 hover:bg-teal-800 text-white rounded-2xl font-bold shadow-2xs transition active:scale-95 flex items-center gap-2 text-xs uppercase tracking-wider">
                                <i class="fas fa-qrcode text-sm"></i> Scan QR Masuk
                            </button>
                        @else
                            <button type="submit" id="btn-absen-masuk" onclick="handleAbsenClick(event, 'form-absen-masuk', 'lat-masuk', 'lng-masuk', 'btn-absen-masuk')" class="w-full sm:w-auto min-h-[44px] justify-center px-5 sm:px-6 py-3 bg-teal-700 hover:bg-teal-800 text-white rounded-2xl font-bold shadow-2xs transition active:scale-95 flex items-center gap-2 text-xs uppercase tracking-wider">
                                <i class="fas fa-fingerprint text-sm"></i> Absen Datang
                            </button>
                        @endif
                    </form>
                    
                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'modal-izin')" class="w-full sm:w-auto min-h-[44px] justify-center px-5 sm:px-6 py-3 bg-white dark:bg-gray-800 border-2 border-amber-400 dark:border-amber-500 text-amber-700 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/40 rounded-2xl font-bold transition active:scale-95 flex items-center gap-2 text-xs uppercase tracking-wider">
                        <i class="fas fa-file-medical text-sm"></i> Izin / Sakit
                    </button>
                </div>

             @elseif($attendanceToday->status == 'hadir' && empty($attendanceToday->clock_out))
                @php
                    $qrRequired = (bool) ($jamKerja?->qr_absensi_enabled ?? false);
                @endphp
                <div class="flex flex-col items-center gap-3 w-full sm:w-auto">
                    <div class="text-xs font-bold text-teal-800 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 px-4 py-2 rounded-xl border border-teal-200/80 dark:border-teal-800/60 shadow-2xs">
                        <i class="fas fa-check-circle mr-1"></i> Datang: {{ \Carbon\Carbon::parse($attendanceToday->clock_in)->format('H:i') }}
                    </div>
                    <form id="form-absen-pulang" action="{{ route('peserta.absen.pulang') }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <input type="hidden" name="latitude" id="lat-pulang">
                        <input type="hidden" name="longitude" id="lng-pulang">
                        <input type="hidden" name="qr_token" id="qr-token-pulang">
                        @if($qrRequired)
                            <button type="button" id="btn-absen-pulang" onclick="openQrScanner(event, 'form-absen-pulang', 'lat-pulang', 'lng-pulang', 'btn-absen-pulang', 'Absen Pulang')" class="w-full sm:w-auto min-h-[44px] justify-center px-5 sm:px-6 py-3 bg-rose-700 hover:bg-rose-800 text-white rounded-2xl font-bold shadow-2xs transition active:scale-95 flex items-center gap-2 text-xs uppercase tracking-wider">
                                <i class="fas fa-qrcode text-sm"></i> Scan QR Pulang
                            </button>
                        @else
                            <button type="submit" id="btn-absen-pulang" onclick="handleAbsenClick(event, 'form-absen-pulang', 'lat-pulang', 'lng-pulang', 'btn-absen-pulang')" class="w-full sm:w-auto min-h-[44px] justify-center px-5 sm:px-6 py-3 bg-rose-700 hover:bg-rose-800 text-white rounded-2xl font-bold shadow-2xs transition active:scale-95 flex items-center gap-2 text-xs uppercase tracking-wider">
                                <i class="fas fa-sign-out-alt text-sm"></i> Absen Pulang
                            </button>
                        @endif
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
    @include('peserta.dashboard._qr-scanner-modal')
</div>
