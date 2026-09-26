@if($loker->kuota > 0)
    @php
        $isClosingSoon = false;
        if (!empty($loker->batas_daftar)) {
            $deadline = \Carbon\Carbon::parse($loker->batas_daftar);
            $diffDays = now()->diffInDays($deadline, false);
            $isClosingSoon = ($diffDays >= 0 && $diffDays <= 7);
        }

        $cleanDinas = trim(str_ireplace(['dinas', 'badan', 'kantor', 'bagian', 'sekretariat'], '', $loker->instansi->nama_dinas));
        $initials = strtoupper(substr($cleanDinas, 0, 2));

        $isMatch = false;
        if (($isPeserta ?? false) && auth()->user()) {
            $isMatch = $loker->matchesUser(auth()->user());
        }

        $reqLabel = $loker->requiredMajorCategory?->name ?? $loker->required_major ?? 'Semua Jurusan';

        $jobData = [
            'id' => $loker->id,
            'judul_posisi' => $loker->judul_posisi,
            'status' => $loker->status,
            'kuota' => $loker->kuota,
            'isClosingSoon' => $isClosingSoon,
            'initials' => $initials,
            'instansi_nama' => $loker->instansi->nama_dinas,
            'instansi_alamat' => $loker->instansi->alamat ?? '',
            'category_name' => $loker->requiredMajorCategory?->name ?? '',
            'required_major' => $loker->required_major ?? '',
            'deskripsi' => \App\Services\HtmlSanitizer::clean($loker->deskripsi ?? ''),
            'persyaratan' => \App\Services\HtmlSanitizer::clean($loker->persyaratan ?? ''),
            'batas_daftar_label' => !empty($loker->batas_daftar) ? \Carbon\Carbon::parse($loker->batas_daftar)->translatedFormat('d F Y') : 'Ditentukan Admin',
            'apply_url' => route('peserta.daftar.form', $loker->id),
            'show_url' => route('lowongan.show', $loker->id),
            'is_peserta' => ($isPeserta ?? false),
            'is_match' => $isMatch,
            'req_label' => $reqLabel,
        ];
    @endphp
    <div class="reveal h-full flex flex-col w-full"
         style="--reveal-delay: {{ ($loop->index % 3) * 120 }}ms"
         x-intersect.once="$el.classList.add('revealed')">
        <!-- Job Card Wrapper -->
        <div @click="openJobModal(@js($jobData))"
             class="cursor-pointer group bg-white dark:bg-gray-800 rounded-[2rem] border border-slate-200/80 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:border-teal-300 dark:hover:border-teal-500 hover:-translate-y-1 active:scale-[0.99] transition-all duration-300 flex flex-col h-full relative shadow-xs w-full">

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
                        @if($isMatch)
                            <a @click.stop href="{{ route('peserta.daftar.form', $loker->id) }}" class="w-full bg-teal-600 hover:bg-teal-700 text-white py-3.5 px-4 rounded-2xl font-bold shadow-md active:scale-98 transition text-center text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                                <span>Lamar Posisi Ini</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        @else
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
    </div>
@endif
