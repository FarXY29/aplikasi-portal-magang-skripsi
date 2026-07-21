<!-- FAQ Section -->
<section id="faq" class="bg-slate-100/40 dark:bg-slate-900/40 border-t border-b border-slate-200/50 dark:border-slate-800 py-24 scroll-mt-20 w-full">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 w-full">
        <div class="text-center mb-16">
            <span class="text-xs font-bold text-teal-600 dark:text-teal-400 tracking-widest uppercase bg-teal-50 dark:bg-teal-950/40 px-4 py-2 rounded-full border border-teal-100 dark:border-teal-900/60">Bantuan Portal</span>
            <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight mt-4">Pertanyaan Populer (FAQ)</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-3 text-sm sm:text-base">Masih bingung? Berikut beberapa jawaban singkat untuk pertanyaan yang sering diajukan.</p>
        </div>

        <!-- FAQ Accordion wrapper -->
        <div x-data="{ activeFaq: null }" class="space-y-4 w-full">
            <!-- FAQ Item 1 -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700/50 overflow-hidden transition-all duration-300 shadow-sm w-full">
                <button @click="activeFaq = (activeFaq === 1 ? null : 1)" class="w-full text-left p-6 font-bold text-slate-800 dark:text-slate-200 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-slate-800/50 active:bg-slate-105 transition-colors focus:outline-none text-sm sm:text-base">
                    <span class="pr-6">Siapa saja yang boleh mendaftar magang di Pemkot Banjarmasin?</span>
                    <i class="fas shrink-0 transition-transform duration-300" :class="activeFaq === 1 ? 'fa-chevron-up text-teal-600 dark:text-teal-400 rotate-180' : 'fa-chevron-down text-slate-400 dark:text-slate-400'"></i>
                </button>
                <div x-show="activeFaq === 1" x-cloak x-collapse class="px-6 pb-6 border-t border-slate-50 dark:border-slate-700/50 pt-4 text-xs sm:text-sm text-slate-550 dark:text-slate-400 leading-relaxed bg-slate-50/10">
                    Siswa aktif SMA/SMK sederajat dan mahasiswa aktif program diploma (D3/D4) maupun sarjana (S1) dari lembaga pendidikan mana pun dipersilakan mendaftar, dengan ketentuan jurusan sesuai kualifikasi lowongan dinas terkait.
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700/50 overflow-hidden transition-all duration-300 shadow-sm w-full">
                <button @click="activeFaq = (activeFaq === 2 ? null : 2)" class="w-full text-left p-6 font-bold text-slate-800 dark:text-slate-200 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-slate-800/50 active:bg-slate-105 transition-colors focus:outline-none text-sm sm:text-base">
                    <span class="pr-6">Bagaimana sistem validasi kuota magang dilakukan?</span>
                    <i class="fas shrink-0 transition-transform duration-300" :class="activeFaq === 2 ? 'fa-chevron-up text-teal-600 dark:text-teal-400 rotate-180' : 'fa-chevron-down text-slate-400 dark:text-slate-400'"></i>
                </button>
                <div x-show="activeFaq === 2" x-cloak x-collapse class="px-6 pb-6 border-t border-slate-50 dark:border-slate-700/50 pt-4 text-xs sm:text-sm text-slate-550 dark:text-slate-400 leading-relaxed bg-slate-50/10">
                    Sistem pendaftaran menghitung kuota berdasarkan jadwal masuk dan keluar peserta magang secara dinamis (seperti sistem booking kamar). Jika kuota penuh pada tanggal yang Anda pilih, Anda akan diminta mengisi slot tanggal alternatif.
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700/50 overflow-hidden transition-all duration-300 shadow-sm w-full">
                <button @click="activeFaq = (activeFaq === 3 ? null : 3)" class="w-full text-left p-6 font-bold text-slate-800 dark:text-slate-200 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-slate-800/50 active:bg-slate-105 transition-colors focus:outline-none text-sm sm:text-base">
                    <span class="pr-6">Apakah saya akan mendapatkan sertifikat setelah magang selesai?</span>
                    <i class="fas shrink-0 transition-transform duration-300" :class="activeFaq === 3 ? 'fa-chevron-up text-teal-600 dark:text-teal-400 rotate-180' : 'fa-chevron-down text-slate-400 dark:text-slate-400'"></i>
                </button>
                <div x-show="activeFaq === 3" x-cloak x-collapse class="px-6 pb-6 border-t border-slate-50 dark:border-slate-700/50 pt-4 text-xs sm:text-sm text-slate-550 dark:text-slate-400 leading-relaxed bg-slate-50/10">
                    Ya. Peserta yang menyelesaikan program magang secara tertib, mengisi laporan kinerja harian, dan dinilai baik oleh pembimbing lapangan akan memperoleh sertifikat elektronik resmi bertanda tangan digital yang dapat diunduh di dashboard masing-masing.
                </div>
            </div>
        </div>
    </div>
</section>