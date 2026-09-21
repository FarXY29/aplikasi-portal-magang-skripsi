{{-- MODAL DETAIL PELACAKAN PERMOHONAN MAGANG --}}
<div x-cloak 
     x-show="activeApp !== null" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        {{-- Backdrop --}}
        <div x-show="activeApp !== null" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="closeDetail()"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" 
             aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        {{-- Modal Panel --}}
        <div x-show="activeApp !== null" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl sm:rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full border border-slate-200 dark:border-slate-700">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/80 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                        <i class="fas fa-clipboard-list text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-slate-100" id="modal-title">
                            Detail & Pelacakan Permohonan
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-mono" x-text="activeApp ? activeApp.nomor_reg : ''"></p>
                    </div>
                </div>
                <button type="button" 
                        @click="closeDetail()" 
                        class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700/60 hover:bg-rose-50 dark:hover:bg-rose-500/10 text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 transition flex items-center justify-center">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            {{-- Modal Content Body --}}
            <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto custom-scrollbar" x-if="activeApp">
                
                {{-- Status Summary Card --}}
                <div class="p-4 rounded-2xl bg-teal-50/50 dark:bg-teal-950/20 border border-teal-200/70 dark:border-teal-900/40 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <p class="text-[11px] font-black text-teal-700 dark:text-teal-400 uppercase tracking-wider">Status Permohonan Terkini</p>
                        <p class="text-lg font-black text-slate-900 dark:text-slate-100 mt-0.5" x-text="activeApp.status_label"></p>
                        <p class="text-xs text-slate-500 dark:text-slate-400" x-text="'Diajukan pada: ' + activeApp.tgl_lamar"></p>
                    </div>
                    <div x-show="activeApp.is_auto" class="px-3 py-1 rounded-full text-xs font-bold bg-teal-100 dark:bg-teal-900/60 text-teal-800 dark:text-teal-200 border border-teal-300 dark:border-teal-700">
                        <i class="fas fa-magic mr-1"></i> Penempatan Otomatis
                    </div>
                </div>

                {{-- Catatan Penolakan (jika ditolak) --}}
                <div x-show="activeApp.rejected_reason" class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 text-xs">
                    <p class="font-bold text-rose-800 dark:text-rose-300 flex items-center gap-1.5 mb-1">
                        <i class="fas fa-exclamation-circle"></i> Alasan Penolakan:
                    </p>
                    <p class="text-rose-700 dark:text-rose-200" x-text="activeApp.rejected_reason"></p>
                </div>

                {{-- Profil Pemohon --}}
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-3 flex items-center gap-1.5">
                        <i class="fas fa-user-graduate text-teal-500"></i> Identitas Pemohon
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-50 dark:bg-slate-900/60 p-4 rounded-2xl border border-slate-200 dark:border-slate-700/60 text-xs">
                        <div>
                            <span class="text-slate-400 block font-medium">Nama Lengkap:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100" x-text="activeApp.nama"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">NIM / NISN:</span>
                            <span class="font-mono font-bold text-slate-800 dark:text-slate-100" x-text="activeApp.nim"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Asal Instansi Pendidikan:</span>
                            <span class="font-bold text-teal-700 dark:text-teal-400" x-text="activeApp.kampus"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Program Studi / Jurusan:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100" x-text="activeApp.jurusan"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Email:</span>
                            <span class="text-slate-800 dark:text-slate-100 font-medium" x-text="activeApp.email"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">No. Telepon / WhatsApp:</span>
                            <span class="text-slate-800 dark:text-slate-100 font-medium" x-text="activeApp.phone"></span>
                        </div>
                    </div>
                </div>

                {{-- Info Penempatan & Formasi --}}
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-3 flex items-center gap-1.5">
                        <i class="fas fa-building text-teal-500"></i> SKPD & Formasi Magang
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-50 dark:bg-slate-900/60 p-4 rounded-2xl border border-slate-200 dark:border-slate-700/60 text-xs">
                        <div>
                            <span class="text-slate-400 block font-medium">Dinas / Instansi:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100" x-text="activeApp.dinas"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Posisi / Jabatan:</span>
                            <span class="font-bold text-teal-700 dark:text-teal-400" x-text="activeApp.posisi"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Periode Kegiatan:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100" x-text="(activeApp.tgl_mulai && activeApp.tgl_selesai) ? (activeApp.tgl_mulai + ' s.d ' + activeApp.tgl_selesai) : 'Belum ditetapkan'"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Pembimbing Lapangan:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100" x-text="activeApp.pembimbing ? activeApp.pembimbing : 'Belum ditugaskan'"></span>
                        </div>
                    </div>
                </div>

                {{-- Berkas & Dokumen Terlampir --}}
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-3 flex items-center gap-1.5">
                        <i class="fas fa-file-alt text-teal-500"></i> Berkas Persyaratan
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        {{-- Surat Pengantar --}}
                        <div class="p-3 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2.5 truncate">
                                <i class="fas fa-file-pdf text-rose-500 text-base"></i>
                                <div class="truncate">
                                    <p class="font-bold text-slate-800 dark:text-slate-200 truncate">Surat Pengantar</p>
                                    <p class="text-[10px] text-slate-400">Dari Institusi Pendidikan</p>
                                </div>
                            </div>
                            <template x-if="activeApp.surat_url">
                                <a :href="activeApp.surat_url" target="_blank" 
                                   class="px-2.5 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1">
                                    <i class="fas fa-external-link-alt text-[10px]"></i> Buka
                                </a>
                            </template>
                            <template x-if="!activeApp.surat_url">
                                <span class="text-slate-400 italic text-[11px]">Tidak Ada</span>
                            </template>
                        </div>

                        {{-- Dokumen CV --}}
                        <div class="p-3 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2.5 truncate">
                                <i class="fas fa-id-card text-blue-500 text-base"></i>
                                <div class="truncate">
                                    <p class="font-bold text-slate-800 dark:text-slate-200 truncate">Curriculum Vitae (CV)</p>
                                    <p class="text-[10px] text-slate-400">Biodata Peserta</p>
                                </div>
                            </div>
                            <template x-if="activeApp.cv_url">
                                <a :href="activeApp.cv_url" target="_blank" 
                                   class="px-2.5 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1">
                                    <i class="fas fa-external-link-alt text-[10px]"></i> Buka
                                </a>
                            </template>
                            <template x-if="!activeApp.cv_url">
                                <span class="text-slate-400 italic text-[11px]">Tidak Ada</span>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Visual Timeline Riwayat Pelacakan Status --}}
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-3 flex items-center gap-1.5">
                        <i class="fas fa-history text-teal-500"></i> Riwayat Mutasi Status & Pelacakan
                    </h4>
                    <div class="bg-slate-50 dark:bg-slate-900/60 p-4 rounded-2xl border border-slate-200 dark:border-slate-700/60">
                        <template x-if="activeApp.timelines && activeApp.timelines.length > 0">
                            <div class="relative border-l-2 border-teal-500/40 ml-3 pl-5 space-y-4">
                                <template x-for="(item, idx) in activeApp.timelines" :key="idx">
                                    <div class="relative">
                                        <div class="absolute -left-[27px] top-0.5 w-3.5 h-3.5 rounded-full bg-teal-600 border-2 border-white dark:border-slate-800 shadow-2xs"></div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold text-slate-900 dark:text-slate-100" x-text="item.event"></span>
                                                <span class="text-[10px] text-slate-400 font-mono" x-text="item.date"></span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5" x-text="'Oleh: ' + item.actor"></p>
                                            <p x-show="item.notes" class="text-xs text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 p-2 rounded-lg border border-slate-200 dark:border-slate-700 mt-1 font-medium" x-text="item.notes"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="!activeApp.timelines || activeApp.timelines.length === 0">
                            <div class="text-center py-4 text-slate-400 text-xs italic">
                                Belum ada riwayat mutasi status yang tercatat secara spesifik untuk berkas ini.
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-3.5 bg-slate-50 dark:bg-slate-900/80 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                <button type="button" 
                        @click="closeDetail()"
                        class="px-5 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-200 text-xs font-bold rounded-xl transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>
