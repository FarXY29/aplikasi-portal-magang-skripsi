@if($loker->kuota > 0)
    @php
        $isClosingSoon = false;
        if (!empty($loker->batas_daftar)) {
            $deadline = \Carbon\Carbon::parse($loker->batas_daftar);
            $diffDays = now()->diffInDays($deadline, false);
            $isClosingSoon = ($diffDays >= 0 && $diffDays <= 7);
        }
    @endphp
    <div x-data="{ showModal: false, copied: false }" 
         x-init="$watch('showModal', value => { document.body.classList.toggle('overflow-hidden', value) })"
         class="reveal h-full flex flex-col w-full" 
         style="--reveal-delay: {{ ($loop->index % 3) * 120 }}ms" 
         x-intersect.once="$el.classList.add('revealed')">
        <!-- Job Card Wrapper -->
        <div @click="showModal = true" class="cursor-pointer group bg-white dark:bg-gray-800 rounded-[2rem] border border-slate-200/80 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:border-teal-300 dark:hover:border-teal-500 hover:-translate-y-1 active:scale-[0.99] transition-all duration-300 flex flex-col h-full relative shadow-xs w-full">
        
            <!-- Card Header Bar -->
            <div class="px-6 pt-6 pb-4 flex items-center justify-between gap-3 border-b border-slate-50 dark:border-gray-700/60 shrink-0">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60 text-[10px] px-2.5 py-1 rounded-lg font-black uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
                        {{ $loker->status }}
                    </span>

                    @if($isClosingSoon)
                        <span class="inline-flex items-center gap-1 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/60 text-[10px] px-2.5 py-1 rounded-lg font-black uppercase tracking-wider animate-pulse">
                            ⏰ Segera Ditutup
                        </span>
                    @endif

                    @if($loker->kuota < 3)
                        <span class="inline-flex items-center gap-1 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/60 text-[10px] px-2.5 py-1 rounded-lg font-black uppercase tracking-wider">
                            🔥 Sisa {{ $loker->kuota }} Kursi
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 bg-slate-100 dark:bg-gray-900 text-slate-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 text-[10px] px-2.5 py-1 rounded-lg font-bold">
                            💺 Kuota: {{ $loker->kuota }}
                        </span>
                    @endif
                </div>
                <div class="text-slate-300 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors shrink-0">
                    <i class="fas fa-chevron-right text-xs"></i>
                </div>
            </div>

            <!-- Job Title & Icon Info -->
            <div class="p-6 pt-5 pb-4 flex items-start gap-4 shrink-0">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center shadow-md shadow-teal-500/20 shrink-0 font-bold text-lg">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="min-w-0 flex-grow">
                    <h3 class="text-base sm:text-lg font-black text-slate-800 dark:text-gray-100 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors duration-300 line-clamp-1 leading-snug" title="{{ $loker->judul_posisi }}">
                        {{ $loker->judul_posisi }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-gray-400 flex items-center font-bold mt-1.5">
                        <i class="fas fa-building text-teal-600 dark:text-teal-400 mr-2 shrink-0"></i>
                        <span class="truncate">{{ $loker->instansi->nama_dinas }}</span>
                    </p>
                </div>
            </div>

            <!-- Major Requirement tags -->
            <div class="px-6 py-2 flex-grow flex flex-col justify-start">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    @if($loker->requiredMajorCategory)
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-[11px] font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60 max-w-full">
                            <i class="fas fa-layer-group mr-2 text-teal-600 dark:text-teal-400 shrink-0"></i>
                            <span class="truncate">{{ $loker->requiredMajorCategory->name }}</span>
                        </span>
                    @endif
                    @if($loker->required_major && (!$loker->requiredMajorCategory || $loker->required_major !== $loker->requiredMajorCategory->name))
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-[11px] font-bold bg-slate-100 dark:bg-gray-900 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-700 max-w-full">
                            <i class="fas fa-graduation-cap mr-2 text-teal-600 dark:text-teal-400 shrink-0"></i>
                            <span class="truncate" title="{{ $loker->required_major }}">{{ $loker->required_major }}</span>
                        </span>
                    @endif
                    @if(preg_match('/SMA|SMK/i', (string) $loker->required_major))
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-black bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60 shrink-0">
                            🎒 SMA/SMK
                        </span>
                    @endif
                </div>

                <!-- Excerpt description -->
                <div class="prose prose-sm text-slate-500 dark:text-gray-400 text-xs sm:text-sm leading-relaxed line-clamp-2 mb-6 font-medium">
                    {{ strip_tags($loker->deskripsi) }}
                </div>
            </div>

            <!-- Action buttons footer -->
            <div class="p-5 pt-3 bg-slate-50 dark:bg-gray-900 border-t border-slate-100 dark:border-gray-700/60 mt-auto shrink-0 flex items-center justify-between gap-3">
                @auth
                    @if(auth()->user()->hasPortalRole('peserta'))
                        @php
                            $isMatch = $loker->matchesUser(auth()->user());
                        @endphp

                        @if($isMatch)
                            <a @click.stop href="{{ route('peserta.daftar.form', $loker->id) }}" class="w-full bg-teal-600 hover:bg-teal-700 text-white py-3.5 px-4 rounded-2xl font-bold shadow-md active:scale-98 transition text-center text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                                <span>Lamar Posisi Ini</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        @else
                            @php
                                $reqLabel = $loker->requiredMajorCategory?->name ?? $loker->required_major;
                            @endphp
                            <div class="w-full" title="Kualifikasi yang dibutuhkan: {{ $reqLabel }}">
                                <button disabled class="w-full bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 py-3 px-3 rounded-2xl font-bold cursor-not-allowed text-xs flex items-center justify-center gap-1.5 uppercase tracking-wider">
                                    <i class="fas fa-lock text-xs"></i>
                                    <span>Syarat Tidak Sesuai</span>
                                </button>
                                <p class="text-[10px] text-center text-slate-400 dark:text-gray-500 mt-1 truncate px-1 font-medium">
                                    Khusus: {{ Str::limit($reqLabel, 26) }}
                                </p>
                            </div>
                        @endif
                    @elseif(auth()->user()->hasPortalRole(['admin_kota', 'admin_instansi']))
                        <button disabled class="w-full text-center text-xs font-bold text-gray-500 dark:text-gray-400 py-3.5 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl uppercase tracking-wider">Pratinjau Admin</button>
                    @endif
                @else
                    <a @click.stop href="{{ route('peserta.daftar.form', $loker->id) }}" class="w-full bg-slate-900 dark:bg-teal-600 hover:bg-teal-600 dark:hover:bg-teal-500 text-white py-3.5 px-4 rounded-2xl font-bold shadow-md active:scale-98 transition text-center text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                        <span>Masuk & Lamar</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                @endauth
            </div>
        </div>

        <!-- Detail Loker Pop-Up Modal -->
        <template x-teleport="body">
            <div x-show="showModal" 
                 x-cloak
                 @keydown.escape.window="showModal = false"
                 class="fixed inset-0 z-[9999] overflow-y-auto overscroll-contain" 
                 role="dialog" 
                 aria-modal="true"
                 aria-labelledby="modal-title-{{ $loker->id }}">
                
                <!-- Backdrop overlay -->
                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100" 
                     x-transition:leave-end="opacity-0" 
                     class="fixed inset-0 bg-slate-950/70 backdrop-blur-md transition-opacity" 
                     @click="showModal = false"
                     aria-hidden="true"></div>

                <!-- Pop Up Modal Center Container (Adaptive Bottom Sheet on Mobile) -->
                <div class="min-h-full flex items-center justify-center p-3 sm:p-6 text-center max-sm:p-0 max-sm:items-end">
                    <div x-show="showModal" 
                         x-transition:enter="transition ease-out duration-300 transform" 
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4 max-sm:translate-y-full max-sm:scale-100" 
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                         x-transition:leave="transition ease-in duration-200 transform" 
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                         x-transition:leave-end="opacity-0 scale-95 translate-y-4 max-sm:translate-y-full max-sm:scale-100"
                         @click.stop
                         class="relative bg-white dark:bg-gray-800 rounded-[2rem] sm:rounded-[2.5rem] max-sm:rounded-t-[2.5rem] max-sm:rounded-b-none shadow-2xl w-full max-w-2xl max-h-[90vh] max-sm:max-h-[92vh] overflow-hidden flex flex-col z-10 transition-all text-left border border-slate-200/80 dark:border-gray-700 overscroll-contain">
                        
                        <!-- Mobile Bottom Sheet Grab Handle -->
                        <div class="w-12 h-1.5 bg-slate-300 dark:bg-gray-600 rounded-full mx-auto mt-3 mb-1 sm:hidden"></div>

                        <!-- Header Pop Up -->
                        <div class="px-5 sm:px-8 py-3.5 sm:py-5 border-b border-slate-100 dark:border-gray-700/80 bg-gray-50/80 dark:bg-gray-900/80 backdrop-blur-md shrink-0 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl bg-teal-500/10 dark:bg-teal-500/20 text-teal-600 dark:text-teal-400 flex items-center justify-center border border-teal-500/20 shadow-xs shrink-0">
                                    <i class="fas fa-briefcase text-xs sm:text-sm"></i>
                                </div>
                                <div>
                                    <span class="text-[9px] sm:text-[10px] font-extrabold text-teal-600 dark:text-teal-400 uppercase tracking-widest block">Informasi Lowongan</span>
                                    <h3 id="modal-title-{{ $loker->id }}" class="text-sm sm:text-lg font-black text-slate-800 dark:text-gray-100 leading-tight">
                                        Detail Lowongan Magang
                                    </h3>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <!-- Copy / Share Link Button -->
                                <button @click="navigator.clipboard.writeText('{{ route('lowongan.show', $loker->id) }}'); copied = true; window.dispatchEvent(new CustomEvent('notify', { detail: 'Tautan lowongan berhasil disalin ke clipboard!' })); setTimeout(() => copied = false, 2500)" 
                                        type="button" 
                                        class="h-9 px-3 flex items-center gap-1.5 text-xs font-bold rounded-xl transition duration-200 border shadow-2xs"
                                        :class="copied ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-700 border-slate-200 dark:border-gray-700'"
                                        title="Salin tautan lowongan">
                                    <i :class="copied ? 'fas fa-check text-emerald-500' : 'fas fa-share-nodes text-teal-600 dark:text-teal-400'"></i>
                                    <span x-text="copied ? 'Tersalin' : 'Bagikan'" class="hidden sm:inline">Bagikan</span>
                                </button>

                                <button @click="showModal = false" type="button" class="w-9 h-9 flex items-center justify-center text-slate-400 dark:text-gray-500 hover:text-slate-700 dark:hover:text-gray-200 bg-white dark:bg-gray-800 hover:bg-slate-100 dark:hover:bg-gray-700 border border-slate-200 dark:border-gray-700 rounded-xl transition duration-200 shadow-xs" title="Tutup">
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Body Pop Up Content -->
                        <div class="px-6 sm:px-8 pt-6 pb-8 overflow-y-auto flex-grow space-y-6 custom-scrollbar overscroll-contain">
                            
                            <!-- Branding & Instansi Block -->
                            @php
                                $cleanDinas = trim(str_ireplace(['dinas', 'badan', 'kantor', 'bagian', 'sekretariat'], '', $loker->instansi->nama_dinas));
                                $initials = strtoupper(substr($cleanDinas, 0, 2));
                            @endphp
                            <div class="flex flex-col sm:flex-row items-start gap-4 pb-2 border-b border-slate-100 dark:border-gray-700/60">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-teal-500/20 shrink-0">
                                    {{ $initials }}
                                </div>
                                <div class="space-y-1 flex-grow">
                                    <h4 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-gray-100 leading-tight">
                                        {{ $loker->judul_posisi }}
                                    </h4>
                                    <p class="text-xs sm:text-sm font-bold text-teal-600 dark:text-teal-400 flex items-center gap-1.5">
                                        <i class="fas fa-building text-xs"></i>
                                        <span>{{ $loker->instansi->nama_dinas }}</span>
                                    </p>
                                    @if(!empty($loker->instansi->alamat))
                                        <p class="text-xs text-slate-500 dark:text-gray-400 flex items-start gap-2 pt-1 leading-relaxed font-medium">
                                            <i class="fas fa-map-marker-alt text-rose-500 shrink-0 mt-0.5 animate-bounce"></i>
                                            <span>{{ $loker->instansi->alamat }}</span>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Quick Info Grid Cards -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <!-- Status Card -->
                                <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-gray-700/80 rounded-2xl p-3.5 flex flex-col justify-between shadow-2xs">
                                    <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Status</span>
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-1 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-lg border border-emerald-200 dark:border-emerald-800/60 w-fit">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
                                        {{ $loker->status }}
                                    </span>
                                </div>

                                <!-- Kuota Card -->
                                <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-gray-700/80 rounded-2xl p-3.5 flex flex-col justify-between shadow-2xs">
                                    <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Kapasitas Kursi</span>
                                    <span class="text-xs font-bold text-slate-800 dark:text-gray-100 mt-1 flex items-center gap-1.5">
                                        <i class="fas fa-users text-teal-600 dark:text-teal-400 text-[10px]"></i>
                                        <span>{{ $loker->kuota }} Posisi</span>
                                    </span>
                                </div>

                                <!-- Deadline Card -->
                                <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-gray-700/80 rounded-2xl p-3.5 flex flex-col justify-between shadow-2xs">
                                    <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Batas Daftar</span>
                                    <span class="text-xs font-bold text-slate-800 dark:text-gray-100 mt-1 flex items-center gap-1.5 truncate" title="{{ \Carbon\Carbon::parse($loker->batas_daftar)->translatedFormat('d F Y') }}">
                                        <i class="fas fa-calendar-alt text-teal-600 dark:text-teal-400 text-[10px]"></i>
                                        <span class="truncate">{{ \Carbon\Carbon::parse($loker->batas_daftar)->translatedFormat('d M Y') }}</span>
                                    </span>
                                </div>

                                <!-- Kualifikasi Card -->
                                <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-gray-700/80 rounded-2xl p-3.5 flex flex-col justify-between shadow-2xs">
                                    <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Kualifikasi</span>
                                    <span class="text-xs font-bold text-slate-800 dark:text-gray-100 mt-1 truncate flex items-center gap-1.5" title="{{ $loker->required_major ?? 'Semua Jurusan' }}">
                                        <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400 text-[10px]"></i>
                                        <span class="truncate">{{ $loker->required_major ?? 'Semua' }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Detail Job Description -->
                            <div class="space-y-3">
                                <h5 class="text-xs font-extrabold text-slate-800 dark:text-gray-100 uppercase tracking-wider flex items-center gap-2">
                                    <i class="fas fa-file-lines text-teal-600 dark:text-teal-400"></i> Deskripsi Pekerjaan & Persyaratan
                                </h5>
                                <div class="prose prose-sm dark:prose-invert max-w-none text-slate-600 dark:text-gray-300 bg-slate-50 dark:bg-gray-900/50 p-5 rounded-2xl border border-slate-100 dark:border-gray-700/70 text-xs sm:text-sm font-medium leading-relaxed">
                                    {!! \App\Services\HtmlSanitizer::clean($loker->deskripsi) ?: '<p class="text-slate-400 italic">Tidak ada deskripsi rinci.</p>' !!}
                                </div>
                            </div>

                            <!-- Detailed Office & Penanggung Jawab Section -->
                            <div class="space-y-3">
                                <h5 class="text-xs font-extrabold text-slate-800 dark:text-gray-100 uppercase tracking-wider flex items-center gap-2">
                                    <i class="fas fa-building-circle-check text-teal-600 dark:text-teal-400"></i> Informasi Kantor & Penempatan
                                </h5>
                                
                                <div class="bg-slate-50 dark:bg-gray-900/50 border border-slate-200/80 dark:border-gray-700/70 rounded-2xl p-4 sm:p-5 space-y-3.5 text-xs sm:text-sm">
                                    @if(!empty($loker->instansi->nama_pejabat))
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 mt-0.5 border border-teal-200 dark:border-teal-800/60">
                                                <i class="fas fa-user-tie text-xs"></i>
                                            </div>
                                            <div>
                                                <span class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-wider">Pejabat Penanggung Jawab</span>
                                                <span class="font-bold text-slate-800 dark:text-gray-200">{{ $loker->instansi->nama_pejabat }}</span>
                                                <span class="block text-[11px] text-slate-500 dark:text-gray-400 mt-0.5">{{ $loker->instansi->jabatan_pejabat }} (NIP: {{ $loker->instansi->nip_pejabat }})</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if(!empty($loker->instansi->jam_mulai_masuk) && !empty($loker->instansi->jam_mulai_pulang))
                                        <div class="flex items-start gap-3 border-t border-slate-100 dark:border-gray-800 pt-3">
                                            <div class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 mt-0.5 border border-teal-200 dark:border-teal-800/60">
                                                <i class="fas fa-clock text-xs"></i>
                                            </div>
                                            <div>
                                                <span class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-wider">Jam Absensi Kerja Dinas</span>
                                                <span class="font-bold text-slate-800 dark:text-gray-200 font-mono">{{ substr($loker->instansi->jam_mulai_masuk, 0, 5) }} s/d {{ substr($loker->instansi->jam_mulai_pulang, 0, 5) }} WITA</span>
                                                <span class="block text-[10px] text-slate-400 dark:text-gray-500 mt-0.5 font-medium">Wajib absen masuk dan pulang tepat waktu sesuai radius jangkauan dinas.</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if(!empty($loker->instansi->latitude) && !empty($loker->instansi->longitude))
                                        <div class="flex items-start gap-3 border-t border-slate-100 dark:border-gray-800 pt-3">
                                            <div class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 mt-0.5 border border-teal-200 dark:border-teal-800/60">
                                                <i class="fas fa-map-marked-alt text-xs"></i>
                                            </div>
                                            <div class="flex-grow">
                                                <span class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-wider">Titik Koordinat Absensi</span>
                                                <span class="text-slate-800 dark:text-gray-200 block font-bold text-xs mt-0.5">Radius: {{ $loker->instansi->radius_absen ?? '100' }} meter dari kantor</span>
                                                <a href="https://www.google.com/maps/search/?api=1&query={{ $loker->instansi->latitude }},{{ $loker->instansi->longitude }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-teal-600 dark:text-teal-400 font-bold hover:underline mt-2 text-xs">
                                                    <span>Buka Google Maps</span>
                                                    <i class="fas fa-external-link-alt text-[10px]"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Footer Pop Up (Sticky Bottom on Mobile with iOS Safe Area) -->
                        <div class="px-5 sm:px-8 py-3.5 sm:py-4 border-t border-slate-100 dark:border-gray-700 bg-gray-50/95 dark:bg-gray-900/95 backdrop-blur-md flex items-center justify-between gap-2.5 shrink-0 z-20 pb-[calc(0.875rem+env(safe-area-inset-bottom,0px))]">
                            <a href="{{ route('lowongan.show', $loker->id) }}" class="text-slate-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 text-xs font-bold transition flex items-center gap-1.5 shrink-0" title="Buka tautan langsung lowongan ini">
                                <i class="fas fa-arrow-up-right-from-square text-[11px]"></i>
                                <span class="hidden sm:inline">Halaman Penuh</span>
                            </a>

                            <div class="flex items-center gap-2 sm:gap-3 flex-grow sm:flex-grow-0 justify-end">
                                <button @click="showModal = false" type="button" class="px-4 sm:px-5 py-3 bg-white hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-200 border border-slate-200 dark:border-gray-700 rounded-xl font-bold transition text-xs uppercase tracking-wider active:scale-95">
                                    Tutup
                                </button>
                                
                                @auth
                                    @if(auth()->user()->hasPortalRole('peserta'))
                                        @if($isMatch ?? true)
                                            <a href="{{ route('peserta.daftar.form', $loker->id) }}" class="flex-1 sm:flex-none bg-teal-600 hover:bg-teal-700 text-white px-5 sm:px-6 py-3 rounded-xl font-bold shadow-md transition text-xs uppercase tracking-wider flex items-center justify-center gap-2 active:scale-95">
                                                <span>Ajukan Lamaran</span>
                                                <i class="fas fa-arrow-right text-xs"></i>
                                            </a>
                                        @else
                                             @php
                                                 $reqLabel = $loker->requiredMajorCategory?->name ?? $loker->required_major;
                                             @endphp
                                             <button disabled title="Kualifikasi yang dibutuhkan: {{ $reqLabel }}" class="flex-1 sm:flex-none bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-4 sm:px-5 py-3 rounded-xl font-bold cursor-not-allowed text-xs uppercase tracking-wider flex items-center justify-center gap-1.5">
                                                 <i class="fas fa-lock text-xs"></i>
                                                 <span>Syarat Tidak Sesuai (Khusus: {{ Str::limit($reqLabel, 22) }})</span>
                                             </button>
                                         @endif
                                    @elseif(auth()->user()->hasPortalRole(['admin_kota', 'admin_instansi']))
                                        <button disabled class="px-4 sm:px-5 py-3 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 rounded-xl font-bold text-xs uppercase tracking-wider">Pratinjau Admin</button>
                                    @endif
                                @else
                                    <a href="{{ route('peserta.daftar.form', $loker->id) }}" class="flex-1 sm:flex-none bg-teal-600 hover:bg-teal-700 text-white px-5 sm:px-6 py-3 rounded-xl font-bold shadow-md transition text-xs uppercase tracking-wider flex items-center justify-center gap-2 active:scale-95">
                                        <span>Masuk & Lamar</span>
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
@endif
