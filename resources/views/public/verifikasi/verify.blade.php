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

        <!-- Master Certificate Verification Card -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl border border-slate-200/80 dark:border-gray-700 overflow-hidden relative">
            
            @if(!$isValid)
                <!-- STATE 1: DATA TIDAK DITEMUKAN -->
                <div class="bg-gradient-to-b from-rose-500/15 via-transparent to-transparent dark:from-rose-950/30 p-8 text-center border-b border-rose-100 dark:border-rose-900/40">
                    <div class="w-20 h-20 rounded-3xl bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <i class="fas fa-circle-xmark text-4xl"></i>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-rose-100 dark:bg-rose-950/80 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-[10px] font-black uppercase tracking-widest mb-2">
                        Verifikasi Gagal
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-gray-100 font-display">
                        Data Tidak Ditemukan
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-gray-400 mt-2 max-w-sm mx-auto leading-relaxed">
                        Token verifikasi atau nomor sertifikat yang Anda cari tidak terdaftar pada pangkalan data Pemerintah Kota Banjarmasin.
                    </p>
                </div>

                <div class="p-6 sm:p-8 space-y-6 text-center">
                    @if(!empty($searchedToken))
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-gray-900 border border-slate-200/70 dark:border-gray-800 text-left">
                            <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Kata Kunci / Token yang Dicari:</span>
                            <span class="font-mono text-xs font-bold text-slate-700 dark:text-slate-300 break-all select-all">{{ $searchedToken }}</span>
                        </div>
                    @endif

                    <div class="space-y-3">
                        <p class="text-xs text-slate-500 dark:text-gray-400 leading-relaxed max-w-md mx-auto">
                            Pastikan Anda memindai QR Code asli yang tercantum pada sertifikat fisik/elektronik resmi yang diterbitkan oleh Portal Magang Pemerintah Kota Banjarmasin.
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

            @elseif($app->certificate && $app->certificate->isRevoked())
                <!-- STATE 2: SERTIFIKAT DICABUT / DIBATALKAN -->
                <div class="bg-gradient-to-b from-rose-600/20 via-rose-500/10 to-transparent dark:from-rose-950/40 p-8 text-center border-b border-rose-200 dark:border-rose-900/50">
                    <div class="w-20 h-20 rounded-3xl bg-rose-600 text-white flex items-center justify-center mx-auto mb-4 shadow-lg shadow-rose-600/30 animate-pulse">
                        <i class="fas fa-ban text-3xl"></i>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest mb-2 shadow-xs">
                        Tidak Berlaku
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-rose-700 dark:text-rose-400 font-display">
                        Sertifikat Dibatalkan
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-gray-300 mt-2 max-w-sm mx-auto leading-relaxed font-semibold">
                        Status keabsahan sertifikat ini telah <strong>DICABUT / DIBATALKAN</strong> secara resmi oleh Pemerintah Kota Banjarmasin.
                    </p>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    <!-- Reason Box -->
                    <div class="p-5 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/80 space-y-2">
                        <span class="text-xs font-black text-rose-800 dark:text-rose-300 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-triangle-exclamation"></i> Alasan Pembatalan Resmi:
                        </span>
                        <p class="text-sm font-semibold text-rose-900 dark:text-rose-200 leading-relaxed">
                            {{ $app->certificate->revoked_reason ?? 'Pencabutan status legalitas dan pembatalan hak sertifikat oleh administrator.' }}
                        </p>
                        @if($app->certificate->revoked_at)
                            <span class="block text-[11px] font-mono text-rose-600 dark:text-rose-400 pt-2 border-t border-rose-200/60 dark:border-rose-900/60">
                                Waktu Pencabutan: {{ $app->certificate->revoked_at->translatedFormat('d F Y - H:i') }} WITA
                            </span>
                        @endif
                    </div>

                    <!-- Participant Card Preview -->
                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-gray-900/80 border border-slate-200/80 dark:border-gray-700/80 space-y-3">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Identitas Tercantum:</span>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 dark:text-gray-100">{{ $app->user->name }}</h3>
                            <p class="text-xs text-slate-500 dark:text-gray-400 font-medium">{{ $app->user->asal_instansi ?? 'Institusi Pendidikan' }}</p>
                        </div>
                        <div class="pt-3 border-t border-slate-200/60 dark:border-gray-800 flex justify-between items-center text-xs">
                            <span class="text-slate-500 dark:text-gray-400">Nomor Sertifikat:</span>
                            <span class="font-mono font-bold text-slate-700 dark:text-slate-300 line-through">{{ $app->nomor_sertifikat ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/50 text-center text-xs text-amber-800 dark:text-amber-300">
                        <i class="fas fa-circle-exclamation mr-1"></i>
                        Peringatan: Dokumen ini tidak dapat digunakan untuk keperluan akademik, kedinasan, maupun administrasi lainnya.
                    </div>
                </div>

            @elseif($app->status?->value === 'selesai')
                <!-- STATE 3: SERTIFIKAT SAH & VALID (LULUS MAGANG) -->
                
                <!-- Official Certificate Badge Header -->
                <div class="bg-gradient-to-b from-emerald-500/20 via-teal-500/10 to-transparent dark:from-emerald-950/40 p-8 text-center border-b border-emerald-100 dark:border-emerald-900/40 relative">
                    
                    <!-- Certificate Seal Stamp -->
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center mx-auto mb-4 shadow-xl shadow-emerald-500/30 border-2 border-emerald-300/40">
                        <i class="fas fa-award text-3xl"></i>
                    </div>

                    <span class="inline-flex items-center gap-1.5 px-4 py-1 rounded-full bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest mb-2 shadow-xs">
                        <i class="fas fa-shield-check text-xs"></i> Dokumen Resmi & Terverifikasi
                    </span>

                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-display">
                        Sertifikat Sah & Valid
                    </h1>
                    <p class="text-xs sm:text-sm text-emerald-800 dark:text-emerald-300 mt-1 font-bold">
                        Program Magang Resmi Pemerintah Kota Banjarmasin
                    </p>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    
                    <!-- Identitas Peserta Block -->
                    <div class="text-center pb-6 border-b border-slate-100 dark:border-gray-700/80 space-y-1.5">
                        <span class="text-[10px] font-extrabold text-teal-600 dark:text-teal-400 uppercase tracking-widest block">Diberikan Kepada:</span>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-gray-100 font-display">
                            {{ $app->user->name }}
                        </h2>
                        <p class="text-xs sm:text-sm font-semibold text-slate-500 dark:text-gray-400">
                            {{ $app->user->asal_instansi ?? 'Institusi Pendidikan' }}
                        </p>
                        @if(!empty($app->user->nik))
                            <span class="inline-block font-mono text-[11px] bg-slate-100 dark:bg-gray-900 text-slate-600 dark:text-slate-300 px-3 py-1 rounded-lg mt-1 font-bold">
                                NIK / NIM: {{ $app->user->nik }}
                            </span>
                        @endif
                    </div>

                    <!-- Details Grid 2x2 -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                        <!-- Nomor Sertifikat -->
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-gray-900/60 border border-slate-200/70 dark:border-gray-700/70 space-y-1">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Nomor Registrasi</span>
                            <span class="font-mono font-black text-slate-800 dark:text-gray-200 text-xs sm:text-sm break-all">
                                {{ $app->nomor_sertifikat ?? '-' }}
                            </span>
                        </div>

                        <!-- Predikat Nilai -->
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-gray-900/60 border border-slate-200/70 dark:border-gray-700/70 space-y-1">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Predikat & Nilai Akhir</span>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 dark:text-gray-100 text-xs sm:text-sm">
                                    {{ $app->nilai_angka >= 85 ? 'Sangat Baik (A)' : ($app->nilai_angka >= 70 ? 'Baik (B)' : 'Cukup (C)') }}
                                </span>
                                <span class="font-mono text-xs px-2 py-0.5 rounded-md bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 font-extrabold">
                                    {{ number_format((float) ($app->nilai_angka ?? 0), 1) }}
                                </span>
                            </div>
                        </div>

                        <!-- Instansi Penempatan -->
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-gray-900/60 border border-slate-200/70 dark:border-gray-700/70 space-y-1">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Instansi Penempatan</span>
                            <span class="font-bold text-slate-800 dark:text-gray-200 block">
                                {{ $app->position->instansi->nama_dinas }}
                            </span>
                            <span class="text-[11px] text-slate-500 dark:text-gray-400 block">
                                Posisi: {{ $app->position->judul_posisi }}
                            </span>
                        </div>

                        <!-- Periode Magang -->
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-gray-900/60 border border-slate-200/70 dark:border-gray-700/70 space-y-1">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Periode Magang</span>
                            <span class="font-bold text-slate-800 dark:text-gray-200 block">
                                {{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d M Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Digital Legal Signature & QR Verification Info -->
                    @if($app->certificate)
                        <div class="p-4 sm:p-5 rounded-2xl bg-teal-50/60 dark:bg-teal-950/30 border border-teal-200/70 dark:border-teal-800/50 flex flex-col sm:flex-row items-start sm:items-center gap-4 text-xs">
                            @if($app->certificate->qr_code_path)
                                <div class="w-16 h-16 rounded-xl bg-white dark:bg-gray-900 p-1.5 border border-teal-200 dark:border-teal-800 shadow-2xs shrink-0 mx-auto sm:mx-0">
                                    <img src="{{ Storage::url($app->certificate->qr_code_path) }}" alt="QR Validation" class="w-full h-full object-contain">
                                </div>
                            @endif
                            <div class="space-y-1 flex-grow">
                                <span class="text-[10px] font-extrabold text-teal-700 dark:text-teal-400 uppercase tracking-widest block">Penandatangan Resmi:</span>
                                <p class="font-black text-slate-800 dark:text-gray-100 text-xs sm:text-sm">
                                    {{ $app->certificate->signer_name ?? 'Pemerintah Kota Banjarmasin' }}
                                </p>
                                <span class="text-[10px] text-slate-500 dark:text-gray-400 font-mono block">
                                    Signature Hash: <span class="text-teal-700 dark:text-teal-300 select-all break-all">{{ substr($app->certificate->signature_mock ?? $app->token_verifikasi, 0, 32) }}...</span>
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Action Bar -->
                    <div class="pt-2 flex flex-wrap items-center justify-between gap-3">
                        <!-- Copy Share Link -->
                        <button @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2500)" 
                                type="button" 
                                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl border border-slate-200 dark:border-gray-700 font-bold text-xs transition"
                                :class="copied ? 'bg-emerald-50 text-emerald-700 border-emerald-300 dark:bg-emerald-950/80 dark:text-emerald-300' : 'bg-slate-100 hover:bg-slate-200 dark:bg-gray-900 dark:hover:bg-gray-800 text-slate-700 dark:text-gray-300'">
                            <i :class="copied ? 'fas fa-check text-emerald-600' : 'fas fa-link text-teal-600'"></i>
                            <span x-text="copied ? 'Tautan Tersalin!' : 'Salin Tautan Bukti'">Salin Tautan Bukti</span>
                        </button>

                        <a href="{{ route('qr.scanner') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs uppercase tracking-wider shadow-md hover:shadow-lg transition">
                            <i class="fas fa-qrcode text-xs"></i>
                            <span>Pindai Sertifikat Lain</span>
                        </a>
                    </div>
                </div>

            @else
                <!-- STATE 4: PESERTA SEDANG AKTIF MAGANG -->
                <div class="bg-gradient-to-b from-teal-500/20 via-sky-500/10 to-transparent dark:from-teal-950/40 p-8 text-center border-b border-teal-100 dark:border-teal-900/40">
                    <div class="w-20 h-20 rounded-3xl bg-teal-600 text-white flex items-center justify-center mx-auto mb-4 shadow-xl shadow-teal-600/30">
                        <i class="fas fa-user-clock text-3xl"></i>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-teal-600 text-white text-[10px] font-black uppercase tracking-widest mb-2 shadow-xs">
                        Peserta Aktif
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-display">
                        Peserta Sedang Magang
                    </h1>
                    <p class="text-xs sm:text-sm text-teal-800 dark:text-teal-300 mt-1 font-semibold">
                        Terdaftar Resmi dalam Periode Aktif Pemkot Banjarmasin
                    </p>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    <div class="text-center pb-6 border-b border-slate-100 dark:border-gray-700/80 space-y-1.5">
                        <span class="text-[10px] font-extrabold text-teal-600 dark:text-teal-400 uppercase tracking-widest block">Identitas Peserta:</span>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-gray-100 font-display">
                            {{ $app->user->name }}
                        </h2>
                        <p class="text-xs sm:text-sm font-semibold text-slate-500 dark:text-gray-400">
                            {{ $app->user->asal_instansi ?? 'Institusi Pendidikan' }}
                        </p>
                    </div>

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

                    <div class="p-4 rounded-2xl bg-sky-50 dark:bg-sky-950/30 border border-sky-200 dark:border-sky-900/50 text-center text-xs text-sky-800 dark:text-sky-300">
                        <i class="fas fa-circle-info mr-1"></i>
                        Sertifikat elektronik akan diterbitkan secara otomatis setelah peserta menyelesaikan periode magang dan dinilai oleh pembimbing lapangan.
                    </div>
                </div>
            @endif

            <!-- Footer Card Note -->
            <div class="px-6 sm:px-8 py-4 bg-slate-50 dark:bg-gray-900/60 border-t border-slate-100 dark:border-gray-700/80 text-center text-slate-400 dark:text-slate-500 text-[11px] font-medium flex items-center justify-center gap-2">
                <i class="fas fa-lock text-teal-600 dark:text-teal-400 text-xs"></i>
                <span>Verifikasi Dihasilkan Otomatis oleh Sistem Terpadu Pemerintah Kota Banjarmasin</span>
            </div>
        </div>
    </div>
</x-guest-layout>
