<x-guest-layout>
    @php
        $cleanDinas = trim(str_ireplace(['dinas', 'badan', 'kantor', 'bagian', 'sekretariat'], '', $position->instansi->nama_dinas ?? ''));
        $initials = strtoupper(substr($cleanDinas, 0, 2));
        $isOpen = $position->status === 'buka' && $position->kuota > 0;
    @endphp

    <div class="w-full max-w-4xl mx-auto py-6 pb-28 md:pb-8 px-4 sm:px-6">
        <!-- Breadcrumb / Back button -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('home') }}#lowongan" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 bg-teal-50 dark:bg-teal-950/60 px-4 py-2 rounded-xl border border-teal-200 dark:border-teal-800/60 transition shadow-2xs">
                <i class="fas fa-arrow-left text-xs"></i>
                <span>Kembali ke Daftar Lowongan</span>
            </a>
            <span class="text-xs font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest hidden sm:inline">
                Detail Lowongan
            </span>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl border border-slate-200/80 dark:border-gray-700 overflow-hidden">
            <!-- Header Card -->
            <div class="p-6 sm:p-8 border-b border-slate-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40">
                <div class="flex flex-col sm:flex-row items-start gap-5">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center font-black text-2xl shadow-lg shadow-teal-500/20 shrink-0">
                        {{ $initials }}
                    </div>
                    <div class="min-w-0 flex-grow space-y-2">
                        <div class="flex flex-wrap items-center gap-2">
                            @if($isOpen)
                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60 text-[10px] px-2.5 py-1 rounded-lg font-black uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
                                    Buka
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/60 text-[10px] px-2.5 py-1 rounded-lg font-black uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 dark:bg-rose-400"></span>
                                    Tutup
                                </span>
                            @endif
                            @if($isOpen && $position->kuota < 3)
                                <span class="inline-flex items-center gap-1 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/60 text-[10px] px-2.5 py-1 rounded-lg font-black uppercase tracking-wider">
                                    🔥 Sisa {{ $position->kuota }} Kursi
                                </span>
                            @endif
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-gray-100 leading-tight">
                            {{ $position->judul_posisi }}
                        </h1>
                        <p class="text-sm font-bold text-teal-600 dark:text-teal-400 flex items-center gap-2">
                            <i class="fas fa-building text-xs"></i>
                            <span>{{ $position->instansi->nama_dinas ?? 'Instansi Tidak Diketahui' }}</span>
                        </p>
                        @if(!empty($position->instansi->alamat))
                            <p class="text-xs text-slate-500 dark:text-gray-400 flex items-start gap-2 pt-1 leading-relaxed font-medium">
                                <i class="fas fa-map-marker-alt text-rose-500 shrink-0 mt-0.5 animate-bounce"></i>
                                <span>{{ $position->instansi->alamat }}</span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Info Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-6 sm:p-8 bg-white dark:bg-gray-800 border-b border-slate-100 dark:border-gray-700/60">
                <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-gray-700/80 rounded-2xl p-4 flex flex-col justify-between shadow-2xs">
                    <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Kapasitas Kursi</span>
                    <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-gray-100 mt-1.5 flex items-center gap-1.5">
                        <i class="fas fa-users text-teal-600 dark:text-teal-400 text-xs"></i>
                        <span>{{ $position->kuota }} Posisi</span>
                    </span>
                </div>

                <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-gray-700/80 rounded-2xl p-4 flex flex-col justify-between shadow-2xs">
                    <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Kualifikasi</span>
                    <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-gray-100 mt-1.5 truncate flex items-center gap-1.5" title="{{ $position->required_major ?? 'Semua Jurusan' }}">
                        <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400 text-xs"></i>
                        <span class="truncate">{{ $position->required_major ?? 'Semua Jurusan' }}</span>
                    </span>
                </div>

                <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-gray-700/80 rounded-2xl p-4 flex flex-col justify-between shadow-2xs">
                    <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Batas Pendaftaran</span>
                    <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-gray-100 mt-1.5 flex items-center gap-1.5 truncate" title="{{ $position->batas_daftar ? \Carbon\Carbon::parse($position->batas_daftar)->translatedFormat('d F Y') : 'Ditentukan Admin' }}">
                        <i class="fas fa-calendar-alt text-teal-600 dark:text-teal-400 text-xs"></i>
                        <span class="truncate">{{ $position->batas_daftar ? \Carbon\Carbon::parse($position->batas_daftar)->translatedFormat('d M Y') : 'Ditentukan Admin' }}</span>
                    </span>
                </div>

                <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-gray-700/80 rounded-2xl p-4 flex flex-col justify-between shadow-2xs">
                    <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Status Lowongan</span>
                    <span class="text-xs sm:text-sm font-bold {{ $isOpen ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} mt-1.5 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ $isOpen ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                        {{ $isOpen ? 'Buka' : 'Tutup' }}
                    </span>
                </div>
            </div>

            <!-- Content Details -->
            <div class="p-6 sm:p-8 space-y-6">
                <!-- Deskripsi Pekerjaan -->
                <div class="space-y-3">
                    <h3 class="text-xs sm:text-sm font-extrabold text-slate-800 dark:text-gray-100 uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-file-lines text-teal-600 dark:text-teal-400"></i> Deskripsi Pekerjaan & Persyaratan
                    </h3>
                    <div class="prose prose-sm dark:prose-invert max-w-none text-slate-600 dark:text-gray-300 bg-slate-50 dark:bg-gray-900/50 p-6 rounded-2xl border border-slate-100 dark:border-gray-700/70 text-xs sm:text-sm font-medium leading-relaxed">
                        {!! $position->deskripsi ? (str_contains($position->deskripsi, '<') ? $position->deskripsi : nl2br(e($position->deskripsi))) : '<p>Tidak ada deskripsi rinci.</p>' !!}
                    </div>
                </div>

                <!-- Informasi Penempatan & Kantor -->
                @if($position->instansi)
                    <div class="space-y-3">
                        <h3 class="text-xs sm:text-sm font-extrabold text-slate-800 dark:text-gray-100 uppercase tracking-wider flex items-center gap-2">
                            <i class="fas fa-building-circle-check text-teal-600 dark:text-teal-400"></i> Informasi Kantor & Penempatan
                        </h3>
                        
                        <div class="bg-slate-50 dark:bg-gray-900/50 border border-slate-200/80 dark:border-gray-700/70 rounded-2xl p-5 space-y-4 text-xs sm:text-sm">
                            @if(!empty($position->instansi->nama_pejabat))
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 mt-0.5 border border-teal-200 dark:border-teal-800/60">
                                        <i class="fas fa-user-tie text-xs"></i>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-wider">Pejabat Penanggung Jawab</span>
                                        <span class="font-bold text-slate-800 dark:text-gray-200">{{ $position->instansi->nama_pejabat }}</span>
                                        <span class="block text-[11px] text-slate-500 dark:text-gray-400 mt-0.5">{{ $position->instansi->jabatan_pejabat }} (NIP: {{ $position->instansi->nip_pejabat }})</span>
                                    </div>
                                </div>
                            @endif

                            @if(!empty($position->instansi->jam_mulai_masuk) && !empty($position->instansi->jam_mulai_pulang))
                                <div class="flex items-start gap-3 border-t border-slate-100 dark:border-gray-800 pt-3">
                                    <div class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 mt-0.5 border border-teal-200 dark:border-teal-800/60">
                                        <i class="fas fa-clock text-xs"></i>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-wider">Jam Absensi Kerja Dinas</span>
                                        <span class="font-bold text-slate-800 dark:text-gray-200 font-mono">{{ substr($position->instansi->jam_mulai_masuk, 0, 5) }} s/d {{ substr($position->instansi->jam_mulai_pulang, 0, 5) }} WITA</span>
                                        <span class="block text-[10px] text-slate-400 dark:text-gray-500 mt-0.5 font-medium">Wajib absen masuk dan pulang tepat waktu sesuai radius jangkauan dinas.</span>
                                    </div>
                                </div>
                            @endif

                            @if(!empty($position->instansi->latitude) && !empty($position->instansi->longitude))
                                <div class="flex items-start gap-3 border-t border-slate-100 dark:border-gray-800 pt-3">
                                    <div class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 mt-0.5 border border-teal-200 dark:border-teal-800/60">
                                        <i class="fas fa-map-marked-alt text-xs"></i>
                                    </div>
                                    <div class="flex-grow">
                                        <span class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-wider">Titik Koordinat Absensi</span>
                                        <span class="text-slate-800 dark:text-gray-200 block font-bold text-xs mt-0.5">Radius: {{ $position->instansi->radius_absen ?? '100' }} meter dari kantor</span>
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $position->instansi->latitude }},{{ $position->instansi->longitude }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-teal-600 dark:text-teal-400 font-bold hover:underline mt-2 text-xs">
                                            <span>Buka Google Maps</span>
                                            <i class="fas fa-external-link-alt text-[10px]"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Desktop Footer Actions -->
            <div class="px-6 sm:px-8 py-5 border-t border-slate-100 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-900/80 hidden md:flex items-center justify-between gap-4">
                <a href="{{ route('home') }}#lowongan" class="px-5 py-3 bg-white hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-200 border border-slate-200 dark:border-gray-700 rounded-xl font-bold transition text-xs uppercase tracking-wider shadow-2xs">
                    <i class="fas fa-arrow-left mr-1.5"></i> Kembali
                </a>

                @auth
                    @if(auth()->user()->hasPortalRole('peserta'))
                        @php
                            $isMatch = $position->matchesUser(auth()->user());
                        @endphp
                        @if($isOpen)
                            @if($isMatch)
                                <a href="{{ route('peserta.daftar.form', $position->id) }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl font-bold shadow-md transition text-xs uppercase tracking-wider flex items-center gap-2">
                                    <span>Ajukan Lamaran</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            @else
                                <button disabled class="bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-6 py-3 rounded-xl font-bold cursor-not-allowed text-xs uppercase tracking-wider">
                                    <i class="fas fa-lock text-xs mr-1"></i> Syarat Tidak Sesuai
                                </button>
                            @endif
                        @else
                            <button disabled class="bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-6 py-3 rounded-xl font-bold cursor-not-allowed text-xs uppercase tracking-wider">
                                Lowongan Ditutup
                            </button>
                        @endif
                    @elseif(auth()->user()->hasPortalRole(['admin_kota', 'admin_instansi']))
                        <button disabled class="px-5 py-3 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 rounded-xl font-bold text-xs uppercase tracking-wider">
                            Pratinjau Admin
                        </button>
                    @endif
                @else
                    @if($isOpen)
                        <a href="{{ route('peserta.daftar.form', $position->id) }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl font-bold shadow-md transition text-xs uppercase tracking-wider flex items-center gap-2">
                            <span>Masuk & Lamar</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    @else
                        <button disabled class="bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-6 py-3 rounded-xl font-bold cursor-not-allowed text-xs uppercase tracking-wider">
                            Lowongan Ditutup
                        </button>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Mobile Sticky Bottom Action Bar -->
        <div class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 dark:bg-gray-900/95 backdrop-blur-2xl border-t border-slate-200/80 dark:border-gray-800 p-4 pb-[calc(1rem+env(safe-area-inset-bottom,0px))] shadow-2xl flex items-center justify-between gap-3">
            <a href="{{ route('home') }}#lowongan" class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 flex items-center justify-center text-slate-600 dark:text-slate-300 shrink-0 active:scale-95 transition" title="Kembali">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>

            <div class="flex-grow">
                @auth
                    @if(auth()->user()->hasPortalRole('peserta'))
                        @php
                            $isMatch = $position->matchesUser(auth()->user());
                        @endphp
                        @if($isOpen)
                            @if($isMatch)
                                <a href="{{ route('peserta.daftar.form', $position->id) }}" class="w-full bg-teal-600 hover:bg-teal-700 text-white h-12 px-5 rounded-2xl font-black shadow-lg shadow-teal-600/30 transition text-xs uppercase tracking-wider flex items-center justify-center gap-2 active:scale-95">
                                    <span>Ajukan Lamaran</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            @else
                                <button disabled class="w-full bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 h-12 px-4 rounded-2xl font-bold cursor-not-allowed text-xs uppercase tracking-wider">
                                    Syarat Tidak Sesuai
                                </button>
                            @endif
                        @else
                            <button disabled class="w-full bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 h-12 px-4 rounded-2xl font-bold cursor-not-allowed text-xs uppercase tracking-wider">
                                Lowongan Ditutup
                            </button>
                        @endif
                    @elseif(auth()->user()->hasPortalRole(['admin_kota', 'admin_instansi']))
                        <button disabled class="w-full h-12 px-4 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 rounded-2xl font-bold text-xs uppercase tracking-wider">
                            Pratinjau Admin
                        </button>
                    @endif
                @else
                    @if($isOpen)
                        <a href="{{ route('peserta.daftar.form', $position->id) }}" class="w-full bg-teal-600 hover:bg-teal-700 text-white h-12 px-5 rounded-2xl font-black shadow-lg shadow-teal-600/30 transition text-xs uppercase tracking-wider flex items-center justify-center gap-2 active:scale-95">
                            <span>Masuk & Lamar</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    @else
                        <button disabled class="w-full bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 h-12 px-4 rounded-2xl font-bold cursor-not-allowed text-xs uppercase tracking-wider">
                            Lowongan Ditutup
                        </button>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</x-guest-layout>