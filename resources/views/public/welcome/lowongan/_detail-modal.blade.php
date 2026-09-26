<!-- Detail Lowongan Pop-Up Modal (Centralized Dynamic Instance & Adaptive Mobile Bottom Sheet) -->
<template x-teleport="body">
    <div x-show="modalOpen"
         x-cloak
         @keydown.escape.window="closeJobModal()"
         class="fixed inset-0 z-[9999] overflow-y-auto overscroll-contain"
         role="dialog"
         aria-modal="true"
         aria-labelledby="modal-title-central">

        <!-- Backdrop overlay -->
        <div x-show="modalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-950/70 backdrop-blur-md transition-opacity"
             @click="closeJobModal()"
             aria-hidden="true"></div>

        <!-- Pop Up Modal Center Container (Adaptive Bottom Sheet on Mobile) -->
        <div class="min-h-full flex items-center justify-center p-3 sm:p-6 text-center max-sm:p-0 max-sm:items-end">
            <div x-show="modalOpen"
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
                            <h3 id="modal-title-central" class="text-sm sm:text-lg font-black text-slate-800 dark:text-gray-100 leading-tight">
                                Detail Lowongan Magang
                            </h3>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Copy / Web Share API Button -->
                        <button @click="shareJob()"
                                type="button"
                                class="h-9 px-3 flex items-center gap-1.5 text-xs font-bold rounded-xl transition duration-200 border shadow-2xs"
                                :class="copied ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-700 border-slate-200 dark:border-gray-700'"
                                title="Bagikan atau salin tautan lowongan">
                            <i :class="copied ? 'fas fa-check text-emerald-500' : 'fas fa-share-nodes text-teal-600 dark:text-teal-400'"></i>
                            <span x-text="copied ? 'Tersalin!' : 'Bagikan'" class="hidden sm:inline">Bagikan</span>
                        </button>

                        <button @click="closeJobModal()" type="button" class="w-9 h-9 flex items-center justify-center text-slate-400 dark:text-gray-500 hover:text-slate-700 dark:hover:text-gray-200 bg-white dark:bg-gray-800 hover:bg-slate-100 dark:hover:bg-gray-700 border border-slate-200 dark:border-gray-700 rounded-xl transition duration-200 shadow-xs" title="Tutup">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Body Pop Up Content -->
                <template x-if="selectedJob">
                    <div class="px-6 sm:px-8 pt-6 pb-8 overflow-y-auto flex-grow space-y-6 custom-scrollbar overscroll-contain">

                        <!-- Branding & Instansi Block -->
                        <div class="flex flex-col sm:flex-row items-start gap-4 pb-2 border-b border-slate-100 dark:border-gray-700/60">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-teal-500/20 shrink-0"
                                 x-text="selectedJob.initials">
                            </div>
                            <div class="space-y-1 flex-grow">
                                <h4 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-gray-100 leading-tight"
                                    x-text="selectedJob.judul_posisi">
                                </h4>
                                <p class="text-xs sm:text-sm font-bold text-teal-600 dark:text-teal-400 flex items-center gap-1.5">
                                    <i class="fas fa-building text-xs"></i>
                                    <span x-text="selectedJob.instansi_nama"></span>
                                </p>
                                <template x-if="selectedJob.instansi_alamat">
                                    <p class="text-xs text-slate-500 dark:text-gray-400 flex items-start gap-2 pt-1 leading-relaxed font-medium">
                                        <i class="fas fa-map-marker-alt text-rose-500 shrink-0 mt-0.5"></i>
                                        <span x-text="selectedJob.instansi_alamat"></span>
                                    </p>
                                </template>
                            </div>
                        </div>

                        <!-- Quick Info Grid Cards -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <!-- Status Card -->
                            <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-gray-700/80 rounded-2xl p-3.5 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Status</span>
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-1 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-lg border border-emerald-200 dark:border-emerald-800/60 w-fit">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
                                    <span x-text="selectedJob.status" class="uppercase"></span>
                                </span>
                            </div>

                            <!-- Kuota Card -->
                            <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-gray-700/80 rounded-2xl p-3.5 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Kapasitas Kursi</span>
                                <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-gray-100 mt-1 flex items-center gap-1.5">
                                    <i class="fas fa-users text-teal-600 dark:text-teal-400 text-xs"></i>
                                    <span x-text="selectedJob.kuota + ' Posisi'"></span>
                                </span>
                            </div>

                            <!-- Kualifikasi Card -->
                            <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-gray-700/80 rounded-2xl p-3.5 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Kualifikasi</span>
                                <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-gray-100 mt-1 truncate flex items-center gap-1.5" :title="selectedJob.req_label">
                                    <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400 text-xs"></i>
                                    <span class="truncate" x-text="selectedJob.req_label"></span>
                                </span>
                            </div>

                            <!-- Batas Waktu Card -->
                            <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-gray-700/80 rounded-2xl p-3.5 flex flex-col justify-between shadow-2xs">
                                <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Batas Pendaftaran</span>
                                <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-gray-100 mt-1 flex items-center gap-1.5 truncate" :title="selectedJob.batas_daftar_label">
                                    <i class="fas fa-calendar-alt text-teal-600 dark:text-teal-400 text-xs"></i>
                                    <span class="truncate" x-text="selectedJob.batas_daftar_label"></span>
                                </span>
                            </div>
                        </div>

                        <!-- Detail Tabs / Content Sections -->
                        <div class="space-y-4">
                            <!-- Deskripsi Section -->
                            <div class="space-y-2">
                                <h5 class="text-xs font-black text-slate-700 dark:text-gray-200 uppercase tracking-wider flex items-center gap-2">
                                    <i class="fas fa-file-lines text-teal-600 dark:text-teal-400"></i> Deskripsi Pekerjaan
                                </h5>
                                <div class="prose prose-sm dark:prose-invert max-w-none text-slate-600 dark:text-gray-300 bg-slate-50 dark:bg-gray-900/60 p-4 sm:p-5 rounded-2xl border border-slate-100 dark:border-gray-700/60 text-xs sm:text-sm leading-relaxed"
                                     x-html="selectedJob.deskripsi || '<p>Tidak ada rincian deskripsi pekerjaan.</p>'">
                                </div>
                            </div>

                            <!-- Persyaratan Section -->
                            <template x-if="selectedJob.persyaratan && selectedJob.persyaratan.trim().length > 0">
                                <div class="space-y-2 pt-2">
                                    <h5 class="text-xs font-black text-slate-700 dark:text-gray-200 uppercase tracking-wider flex items-center gap-2">
                                        <i class="fas fa-list-check text-teal-600 dark:text-teal-400"></i> Persyaratan Khusus
                                    </h5>
                                    <div class="prose prose-sm dark:prose-invert max-w-none text-slate-600 dark:text-gray-300 bg-slate-50 dark:bg-gray-900/60 p-4 sm:p-5 rounded-2xl border border-slate-100 dark:border-gray-700/60 text-xs sm:text-sm leading-relaxed"
                                         x-html="selectedJob.persyaratan">
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Footer Pop Up Action Bar -->
                <div class="px-6 sm:px-8 py-4 sm:py-5 border-t border-slate-100 dark:border-gray-700/80 bg-gray-50/80 dark:bg-gray-900/80 shrink-0 flex items-center justify-between gap-3">
                    <button @click="closeJobModal()"
                            type="button"
                            class="px-5 py-3 bg-white hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-200 border border-slate-200 dark:border-gray-700 rounded-xl font-bold transition text-xs uppercase tracking-wider shadow-2xs">
                        Tutup
                    </button>

                    <template x-if="selectedJob">
                        <div class="flex items-center gap-2">
                            <!-- Direct Page Link -->
                            <a :href="selectedJob.show_url"
                               class="hidden sm:inline-flex items-center gap-1.5 px-4 py-3 bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 border border-slate-200 dark:border-gray-700 rounded-xl text-xs font-bold transition shadow-2xs uppercase tracking-wider">
                                <i class="fas fa-arrow-up-right-from-square text-xs"></i>
                                <span>Halaman Penuh</span>
                            </a>

                            <!-- Apply Action Button -->
                            <template x-if="selectedJob.is_peserta">
                                <div>
                                    <template x-if="selectedJob.is_match">
                                        <a :href="selectedJob.apply_url"
                                           class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl font-black shadow-md transition text-xs uppercase tracking-wider flex items-center gap-2 active:scale-95">
                                            <span>Lamar Posisi Ini</span>
                                            <i class="fas fa-arrow-right text-xs"></i>
                                        </a>
                                    </template>
                                    <template x-if="!selectedJob.is_match">
                                        <button disabled
                                                :title="'Kualifikasi: ' + selectedJob.req_label"
                                                class="bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-5 py-3 rounded-xl font-bold cursor-not-allowed text-xs uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="fas fa-lock text-xs"></i>
                                            <span>Syarat Tidak Sesuai</span>
                                        </button>
                                    </template>
                                </div>
                            </template>

                            <template x-if="!selectedJob.is_peserta">
                                <a :href="selectedJob.apply_url"
                                   class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl font-black shadow-md transition text-xs uppercase tracking-wider flex items-center gap-2 active:scale-95">
                                    <span>Masuk & Lamar</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
