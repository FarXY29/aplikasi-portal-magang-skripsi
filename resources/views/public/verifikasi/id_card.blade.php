<x-guest-layout>
    <div class="py-8 sm:py-12 px-4 sm:px-6 lg:px-8 w-full max-w-2xl mx-auto" x-data="{ copied: false }">
        
        <!-- Navigation Back & Quick Actions -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('home') }}" class="group inline-flex items-center text-xs sm:text-sm font-bold text-slate-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                <div class="w-8 h-8 rounded-xl bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 flex items-center justify-center mr-2.5 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-2xs transition">
                    <i class="fas fa-arrow-left text-xs text-slate-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                </div>
                Kembali ke Beranda
            </a>

            <a href="{{ route('qr.scanner') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-slate-600 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 hover:border-teal-400 text-xs font-bold shadow-2xs transition">
                <i class="fas fa-qrcode text-teal-600 dark:text-teal-400 text-xs"></i>
                <span>Pindai Ulang</span>
            </a>
        </div>

        <!-- Master ID Card Verification Card -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl border border-slate-200/80 dark:border-gray-700 overflow-hidden relative">
            
            @if(!$isValid || $idCardStatus === 'invalid')
                <!-- STATE 1: DATA TIDAK DITEMUKAN -->
                <div class="bg-gradient-to-b from-rose-500/15 via-transparent to-transparent dark:from-rose-950/30 p-8 text-center border-b border-rose-100 dark:border-rose-900/40">
                    <div class="w-20 h-20 rounded-3xl bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <i class="fas fa-id-badge text-4xl"></i>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-rose-100 dark:bg-rose-950/80 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-[10px] font-black uppercase tracking-widest mb-2">
                        Verifikasi Gagal
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-gray-100 font-display">
                        ID Card Tidak Ditemukan
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-gray-400 mt-2 max-w-sm mx-auto leading-relaxed">
                        Token verifikasi ID Card tidak terdaftar pada pangkalan data resmi Pemerintah Kota Banjarmasin.
                    </p>
                </div>

                <div class="p-6 sm:p-8 space-y-6 text-center">
                    @if(!empty($searchedToken))
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-gray-900 border border-slate-200/70 dark:border-gray-800 text-left">
                            <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Token yang Dipindai:</span>
                            <span class="font-mono text-xs font-bold text-slate-700 dark:text-slate-300 break-all select-all">{{ $searchedToken }}</span>
                        </div>
                    @endif

                    <div class="space-y-3">
                        <p class="text-xs text-slate-500 dark:text-gray-400 leading-relaxed max-w-md mx-auto">
                            Pastikan Anda memindai QR Code resmi yang tercantum pada ID Card fisik atau elektronik peserta magang Pemerintah Kota Banjarmasin.
                        </p>
                        
                        <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                            <a href="{{ route('qr.scanner') }}" class="bg-teal-600 hover:bg-teal-700 text-white font-extrabold px-6 py-3 rounded-2xl text-xs uppercase tracking-wider shadow-md hover:shadow-lg transition flex items-center gap-2">
                                <i class="fas fa-camera text-xs"></i>
                                <span>Coba Pindai Ulang</span>
                            </a>
                            <a href="{{ route('home') }}" class="bg-slate-100 hover:bg-slate-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-slate-700 dark:text-gray-200 font-bold px-6 py-3 rounded-2xl text-xs uppercase tracking-wider transition">
                                Beranda
                            </a>
                        </div>
                    </div>
                </div>

            @elseif($idCardStatus === 'revoked')
                <!-- STATE 2: KARTU TIDAK BERLAKU / DIBATALKAN / DIKELUARKAN -->
                <div class="bg-gradient-to-b from-rose-600/20 via-rose-500/10 to-transparent dark:from-rose-950/40 p-8 text-center border-b border-rose-200 dark:border-rose-900/50">
                    <div class="w-20 h-20 rounded-3xl bg-rose-600 text-white flex items-center justify-center mx-auto mb-4 shadow-lg shadow-rose-600/30">
                        <i class="fas fa-user-slash text-3xl"></i>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest mb-2 shadow-xs">
                        Tidak Berlaku
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-rose-700 dark:text-rose-400 font-display">
                        ID Card Tidak Berlaku
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-gray-300 mt-2 max-w-sm mx-auto leading-relaxed font-semibold">
                        Status kepesertaan magang ini telah <strong>{{ strtoupper($app->status_value) }}</strong> sehingga kartu pengenal ini tidak dapat digunakan.
                    </p>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    <!-- Participant Identity Preview -->
                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-gray-900/80 border border-slate-200/80 dark:border-gray-700/80 flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-slate-200 dark:bg-gray-800 text-slate-400 flex items-center justify-center overflow-hidden shrink-0 border border-slate-300 dark:border-gray-700">
                            @if($app->user->photo && Storage::disk('public')->exists($app->user->photo))
                                <img src="{{ Storage::disk('public')->url($app->user->photo) }}" class="w-full h-full object-cover grayscale opacity-75" alt="{{ $app->user->name }}">
                            @else
                                <i class="fas fa-user text-xl"></i>
                            @endif
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-base font-black text-slate-800 dark:text-gray-100 line-through">{{ $app->user->name }}</h3>
                            <p class="text-xs text-slate-500 dark:text-gray-400 font-medium">{{ $app->user->asal_instansi ?? 'Institusi Pendidikan' }}</p>
                            <span class="inline-block text-[10px] font-mono text-rose-600 dark:text-rose-400 font-bold">Status: {{ ucfirst($app->status_value) }}</span>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 text-center text-xs text-rose-800 dark:text-rose-300">
                        <i class="fas fa-triangle-exclamation mr-1"></i>
                        Peringatan: Pemilik kartu ini sudah tidak memiliki hak akses kerja maupun kegiatan kedinasan di lingkungan Pemerintah Kota Banjarmasin.
                    </div>
                </div>

            @elseif($idCardStatus === 'finished')
                <!-- STATE 3: MASA MAGANG TELAH SELESAI / BERAKHIR -->
                <div class="bg-gradient-to-b from-sky-500/20 via-teal-500/10 to-transparent dark:from-sky-950/40 p-8 text-center border-b border-sky-100 dark:border-sky-900/40">
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-sky-500 to-teal-600 text-white flex items-center justify-center mx-auto mb-4 shadow-xl shadow-sky-500/30 border-2 border-sky-300/40">
                        <i class="fas fa-user-graduate text-3xl"></i>
                    </div>

                    <span class="inline-flex items-center gap-1.5 px-4 py-1 rounded-full bg-sky-600 text-white text-[10px] font-black uppercase tracking-widest mb-2 shadow-xs">
                        <i class="fas fa-check-circle text-xs"></i> Masa Magang Selesai
                    </span>

                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-display">
                        Periode Magang Berakhir
                    </h1>
                    <p class="text-xs sm:text-sm text-sky-800 dark:text-sky-300 mt-1 font-bold">
                        Peserta Telah Menyelesaikan Program Magang
                    </p>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    <!-- Participant Identity Block -->
                    <div class="flex flex-col sm:flex-row items-center gap-5 p-5 rounded-2xl bg-slate-50 dark:bg-gray-900/60 border border-slate-200/70 dark:border-gray-700/70">
                        <div class="w-20 h-20 rounded-2xl bg-white dark:bg-gray-800 shadow-md border-2 border-teal-500/30 overflow-hidden shrink-0 flex items-center justify-center">
                            @if($app->user->photo && Storage::disk('public')->exists($app->user->photo))
                                <img src="{{ Storage::disk('public')->url($app->user->photo) }}" class="w-full h-full object-cover" alt="{{ $app->user->name }}">
                            @else
                                <i class="fas fa-user text-3xl text-slate-400"></i>
                            @endif
                        </div>
                        <div class="text-center sm:text-left space-y-1">
                            <span class="text-[10px] font-extrabold text-teal-600 dark:text-teal-400 uppercase tracking-widest block">Alumni Peserta Magang</span>
                            <h2 class="text-lg sm:text-xl font-black text-slate-800 dark:text-gray-100 font-display">{{ $app->user->name }}</h2>
                            <p class="text-xs font-semibold text-slate-500 dark:text-gray-400">{{ $app->user->asal_instansi ?? '-' }}</p>
                            @if(!empty($app->user->nik))
                                <span class="inline-block font-mono text-[10px] bg-white dark:bg-gray-800 text-slate-600 dark:text-slate-300 px-2 py-0.5 rounded border border-slate-200 dark:border-gray-700 font-bold">
                                    NIK / NIM: {{ $app->user->masked_nik }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-gray-900/60 border border-slate-200/70 dark:border-gray-700/70 space-y-1">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Instansi Penempatan</span>
                            <span class="font-bold text-slate-800 dark:text-gray-200 block">{{ $app->position->instansi->nama_dinas }}</span>
                            <span class="text-[11px] text-slate-500 dark:text-gray-400 block">{{ $app->position->judul_posisi }}</span>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-gray-900/60 border border-slate-200/70 dark:border-gray-700/70 space-y-1">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Periode Magang</span>
                            <span class="font-bold text-slate-800 dark:text-gray-200 block">
                                {{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d M Y') }}
                            </span>
                        </div>
                    </div>

                    @if($app->nomor_sertifikat || ($app->certificate && $app->certificate->isActive()))
                        <!-- Certificate Redirect Banner -->
                        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                            <div class="space-y-0.5 text-center sm:text-left">
                                <span class="font-black text-emerald-800 dark:text-emerald-300 block">Sertifikat Kelulusan Tersedia</span>
                                <p class="text-[11px] text-emerald-700 dark:text-emerald-400">Nomor: {{ $app->nomor_sertifikat ?? $app->certificate?->nomor_sertifikat }}</p>
                            </div>
                            <a href="{{ route('certificate.verify', $app->token_verifikasi) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs uppercase tracking-wider shadow-sm transition">
                                <i class="fas fa-award text-xs"></i>
                                <span>Lihat Sertifikat</span>
                            </a>
                        </div>
                    @endif

                    <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-gray-900 border border-slate-200/70 dark:border-gray-800 text-center text-xs text-slate-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-1 text-teal-600"></i> Kartu fisik ID Card ini berfungsi sebagai arsip tanda pengenal selama periode magang yang telah diselesaikan.
                    </div>
                </div>

            @else
                <!-- STATE 4: KARTU SAH & PESERTA AKTIF MAGANG -->
                <div class="bg-gradient-to-b from-teal-500/20 via-emerald-500/10 to-transparent dark:from-teal-950/40 p-8 text-center border-b border-teal-100 dark:border-teal-900/40 relative">
                    
                    <!-- Official ID Card Badge Stamp -->
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center mx-auto mb-4 shadow-xl shadow-teal-500/30 border-2 border-teal-300/40">
                        <i class="fas fa-id-card-clip text-3xl"></i>
                    </div>

                    <span class="inline-flex items-center gap-1.5 px-4 py-1 rounded-full bg-teal-600 text-white text-[10px] font-black uppercase tracking-widest mb-2 shadow-xs">
                        <i class="fas fa-shield-check text-xs"></i> Kartu Identitas Resmi & Aktif
                    </span>

                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-display">
                        Peserta Magang Aktif
                    </h1>
                    <p class="text-xs sm:text-sm text-teal-800 dark:text-teal-300 mt-1 font-bold">
                        Pemerintah Kota Banjarmasin
                    </p>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    
                    <!-- Participant Photo & Identity Block -->
                    <div class="flex flex-col sm:flex-row items-center gap-5 p-5 rounded-2xl bg-gradient-to-r from-teal-50/50 via-slate-50 to-white dark:from-gray-900 dark:via-gray-900/80 dark:to-gray-800 border border-teal-100 dark:border-gray-700/80 shadow-2xs">
                        <!-- Photo Frame -->
                        <div class="w-24 h-24 rounded-2xl bg-white dark:bg-gray-800 shadow-md border-2 border-teal-500/30 overflow-hidden shrink-0 flex items-center justify-center relative">
                            @if($app->user->photo && Storage::disk('public')->exists($app->user->photo))
                                <img src="{{ Storage::disk('public')->url($app->user->photo) }}" class="w-full h-full object-cover" alt="{{ $app->user->name }}">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-teal-50 to-slate-100 dark:from-gray-800 dark:to-gray-900 flex flex-col items-center justify-center text-slate-400">
                                    <i class="fas fa-user text-3xl text-teal-600/60 dark:text-teal-400/60 mb-1"></i>
                                    <span class="text-[9px] font-extrabold uppercase tracking-wider text-slate-400">Peserta</span>
                                </div>
                            @endif
                            <div class="absolute bottom-0 inset-x-0 bg-teal-600/90 text-white text-[8px] font-black uppercase text-center py-0.5 tracking-wider">
                                Aktif
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="text-center sm:text-left space-y-1.5">
                            <span class="inline-flex items-center gap-1 text-[10px] font-extrabold text-teal-600 dark:text-teal-400 uppercase tracking-widest">
                                <i class="fas fa-circle-check text-xs"></i> Terdaftar Resmi
                            </span>
                            <h2 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-gray-100 font-display">
                                {{ $app->user->name }}
                            </h2>
                            <p class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-gray-300">
                                {{ $app->user->asal_instansi ?? 'Institusi Pendidikan' }}
                            </p>
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 pt-1">
                                @if(!empty($app->user->nik))
                                    <span class="inline-block font-mono text-[11px] bg-white dark:bg-gray-800 text-slate-700 dark:text-slate-300 px-2.5 py-0.5 rounded-lg border border-slate-200 dark:border-gray-700 font-bold">
                                        NIK/NIM: {{ $app->user->masked_nik }}
                                    </span>
                                @endif
                                @if(!empty($app->user->majorDetail?->name) || !empty($app->user->major))
                                    <span class="inline-block text-[11px] bg-teal-100/60 dark:bg-teal-950/80 text-teal-800 dark:text-teal-300 px-2.5 py-0.5 rounded-lg font-bold">
                                        {{ $app->user->majorDetail?->name ?? $app->user->major }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Placement & Mentoring Details Grid (2x2) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                        <!-- Instansi Penempatan -->
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-gray-900/60 border border-slate-200/70 dark:border-gray-700/70 space-y-1">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Instansi Penempatan</span>
                            <span class="font-bold text-slate-800 dark:text-gray-200 block text-xs sm:text-sm">
                                {{ $app->position->instansi->nama_dinas }}
                            </span>
                            @if(!empty($app->position->instansi->alamat))
                                <span class="text-[11px] text-slate-500 dark:text-gray-400 block leading-tight">
                                    {{ $app->position->instansi->alamat }}
                                </span>
                            @endif
                        </div>

                        <!-- Posisi Magang -->
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-gray-900/60 border border-slate-200/70 dark:border-gray-700/70 space-y-1">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Posisi / Divisi</span>
                            <span class="font-bold text-slate-800 dark:text-gray-100 text-xs sm:text-sm block">
                                {{ $app->position->judul_posisi }}
                            </span>
                            <span class="text-[11px] text-teal-600 dark:text-teal-400 font-semibold block">
                                Kode: {{ $app->position->instansi->kode_unit_kerja ?? '-' }}
                            </span>
                        </div>

                        <!-- Periode Magang -->
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-gray-900/60 border border-slate-200/70 dark:border-gray-700/70 space-y-1">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Periode Pelaksanaan</span>
                            <span class="font-bold text-slate-800 dark:text-gray-200 block">
                                {{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d F Y') }}
                            </span>
                            <span class="text-[11px] text-slate-500 dark:text-gray-400 block">
                                s/d {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d F Y') }}
                            </span>
                        </div>

                        <!-- Pembimbing Lapangan -->
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-gray-900/60 border border-slate-200/70 dark:border-gray-700/70 space-y-1">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Pembimbing Lapangan</span>
                            <span class="font-bold text-slate-800 dark:text-gray-200 block">
                                {{ $app->pembimbing_lapangan->name ?? 'Ditetapkan Dinas' }}
                            </span>
                            @if(!empty($app->pembimbing_lapangan->nip))
                                <span class="text-[10px] font-mono text-slate-500 dark:text-gray-400 block">
                                    NIP: {{ $app->pembimbing_lapangan->nip }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Security & Integrity Notice -->
                    <div class="p-4 rounded-2xl bg-teal-50/60 dark:bg-teal-950/30 border border-teal-200/70 dark:border-teal-800/50 flex items-start gap-3 text-xs">
                        <i class="fas fa-shield-halved text-teal-600 dark:text-teal-400 text-base shrink-0 mt-0.5"></i>
                        <div class="space-y-0.5">
                            <span class="font-bold text-slate-800 dark:text-gray-200 block">Autentikasi Kartu Tanda Pengenal Resmi</span>
                            <p class="text-[11px] text-slate-600 dark:text-gray-400 leading-relaxed">
                                Kartu identitas ini sah digunakan sebagai tanda pengenal resmi selama bertugas di kantor/unit kerja Pemerintah Kota Banjarmasin.
                            </p>
                        </div>
                    </div>

                    <!-- Action Bar -->
                    <div class="pt-2 flex flex-wrap items-center justify-between gap-3">
                        <!-- Copy Share Link -->
                        <button @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2500)" 
                                type="button" 
                                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl border border-slate-200 dark:border-gray-700 font-bold text-xs transition"
                                :class="copied ? 'bg-emerald-50 text-emerald-700 border-emerald-300 dark:bg-emerald-950/80 dark:text-emerald-300' : 'bg-slate-100 hover:bg-slate-200 dark:bg-gray-900 dark:hover:bg-gray-800 text-slate-700 dark:text-gray-300'">
                            <i :class="copied ? 'fas fa-check text-emerald-600' : 'fas fa-link text-teal-600'"></i>
                            <span x-text="copied ? 'Tautan Tersalin!' : 'Salin Tautan ID Card'">Salin Tautan ID Card</span>
                        </button>

                        <a href="{{ route('qr.scanner') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs uppercase tracking-wider shadow-md hover:shadow-lg transition">
                            <i class="fas fa-qrcode text-xs"></i>
                            <span>Pindai Kartu Lain</span>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Footer Card Note -->
            <div class="px-6 sm:px-8 py-4 bg-slate-50 dark:bg-gray-900/60 border-t border-slate-100 dark:border-gray-700/80 text-center text-slate-400 dark:text-slate-500 text-[11px] font-medium flex items-center justify-center gap-2">
                <i class="fas fa-lock text-teal-600 dark:text-teal-400 text-xs"></i>
                <span>Verifikasi Identitas Resmi Pemerintah Kota Banjarmasin</span>
            </div>
        </div>
    </div>
</x-guest-layout>
