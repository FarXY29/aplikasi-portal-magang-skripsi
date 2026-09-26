<!-- FAQ Section -->
<section id="faq" class="bg-slate-100/40 dark:bg-gray-900/40 border-t border-b border-slate-200/50 dark:border-gray-800 py-20 sm:py-28 scroll-mt-20 w-full">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 w-full">
        <div class="reveal text-center mb-12 sm:mb-20" style="--reveal-delay: 0ms" x-intersect.once="$el.classList.add('revealed')">
            <span class="text-xs font-bold text-teal-600 dark:text-teal-400 tracking-widest uppercase bg-teal-50 dark:bg-teal-950/40 px-4 py-2 rounded-full border border-teal-100 dark:border-teal-900/60">Bantuan Portal</span>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-800 dark:text-white tracking-tight mt-4 font-display">Pertanyaan Populer (FAQ)</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-3 text-sm sm:text-base px-2">Masih bingung? Berikut beberapa jawaban singkat untuk pertanyaan yang sering diajukan seputar magang di Pemkot Banjarmasin.</p>
        </div>

        <!-- FAQ Accordion wrapper -->
        <div x-data="{ activeFaq: null }" class="space-y-4 w-full">
            <!-- FAQ Item 1 -->
            <div class="reveal bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/80 dark:border-gray-700/50 overflow-hidden transition-all duration-300 shadow-sm w-full" style="--reveal-delay: 0ms" x-intersect.once="$el.classList.add('revealed')">
                <button @click="activeFaq = (activeFaq === 1 ? null : 1)" class="w-full text-left p-5 sm:p-6 font-bold text-slate-800 dark:text-slate-200 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-gray-800 active:bg-slate-100 dark:active:bg-gray-800 transition-colors focus:outline-none text-sm sm:text-base cursor-pointer">
                    <span class="pr-6">Siapa saja yang boleh mendaftar magang di Pemkot Banjarmasin?</span>
                    <i class="fas fa-chevron-down shrink-0 transition-transform duration-300" :class="{ 'rotate-180 text-teal-600 dark:text-teal-400': activeFaq === 1, 'text-slate-400': activeFaq !== 1 }"></i>
                </button>
                <div x-show="activeFaq === 1" x-cloak x-collapse class="px-5 sm:px-6 pb-6 border-t border-slate-50 dark:border-gray-700/50 pt-4 text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed bg-slate-50/10 dark:bg-gray-900/10">
                    Siswa aktif SMA/SMK sederajat dan mahasiswa aktif program diploma (D3/D4) maupun sarjana (S1) dari lembaga pendidikan mana pun dipersilakan mendaftar, dengan ketentuan jurusan sesuai kualifikasi lowongan dinas terkait.
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="reveal bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/80 dark:border-gray-700/50 overflow-hidden transition-all duration-300 shadow-sm w-full" style="--reveal-delay: 80ms" x-intersect.once="$el.classList.add('revealed')">
                <button @click="activeFaq = (activeFaq === 2 ? null : 2)" class="w-full text-left p-5 sm:p-6 font-bold text-slate-800 dark:text-slate-200 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-gray-800 active:bg-slate-100 dark:active:bg-gray-800 transition-colors focus:outline-none text-sm sm:text-base cursor-pointer">
                    <span class="pr-6">Bagaimana jika kuota lowongan yang saya inginkan sudah penuh?</span>
                    <i class="fas fa-chevron-down shrink-0 transition-transform duration-300" :class="{ 'rotate-180 text-teal-600 dark:text-teal-400': activeFaq === 2, 'text-slate-400': activeFaq !== 2 }"></i>
                </button>
                <div x-show="activeFaq === 2" x-cloak x-collapse class="px-5 sm:px-6 pb-6 border-t border-slate-50 dark:border-gray-700/50 pt-4 text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed bg-slate-50/10 dark:bg-gray-900/10">
                    Jangan khawatir! Sistem menyediakan opsi <strong>Daftar Tunggu (Waiting List)</strong> pada posisi tersebut jika ada peserta yang membatalkan diri, atau Anda dapat menggunakan fitur <strong>Penempatan Otomatis</strong> untuk dicarikan lowongan relevan lain yang masih memiliki kuota terbuka.
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="reveal bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/80 dark:border-gray-700/50 overflow-hidden transition-all duration-300 shadow-sm w-full" style="--reveal-delay: 160ms" x-intersect.once="$el.classList.add('revealed')">
                <button @click="activeFaq = (activeFaq === 3 ? null : 3)" class="w-full text-left p-5 sm:p-6 font-bold text-slate-800 dark:text-slate-200 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-gray-800 active:bg-slate-100 dark:active:bg-gray-800 transition-colors focus:outline-none text-sm sm:text-base cursor-pointer">
                    <span class="pr-6">Bagaimana cara memantau progres status lamaran saya?</span>
                    <i class="fas fa-chevron-down shrink-0 transition-transform duration-300" :class="{ 'rotate-180 text-teal-600 dark:text-teal-400': activeFaq === 3, 'text-slate-400': activeFaq !== 3 }"></i>
                </button>
                <div x-show="activeFaq === 3" x-cloak x-collapse class="px-5 sm:px-6 pb-6 border-t border-slate-50 dark:border-gray-700/50 pt-4 text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed bg-slate-50/10 dark:bg-gray-900/10">
                    Anda dapat memantau secara publik tanpa login melalui menu <strong>Lacak Permohonan</strong> dengan memasukkan Nomor Registrasi unik (format: <code>REG-YYYYMM-XXXXX</code>) yang diberikan setelah mendaftar, atau masuk ke dashboard peserta untuk melihat riwayat status lengkap.
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="reveal bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/80 dark:border-gray-700/50 overflow-hidden transition-all duration-300 shadow-sm w-full" style="--reveal-delay: 240ms" x-intersect.once="$el.classList.add('revealed')">
                <button @click="activeFaq = (activeFaq === 4 ? null : 4)" class="w-full text-left p-5 sm:p-6 font-bold text-slate-800 dark:text-slate-200 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-gray-800 active:bg-slate-100 dark:active:bg-gray-800 transition-colors focus:outline-none text-sm sm:text-base cursor-pointer">
                    <span class="pr-6">Apakah saya akan mendapatkan sertifikat resmi setelah selesai magang?</span>
                    <i class="fas fa-chevron-down shrink-0 transition-transform duration-300" :class="{ 'rotate-180 text-teal-600 dark:text-teal-400': activeFaq === 4, 'text-slate-400': activeFaq !== 4 }"></i>
                </button>
                <div x-show="activeFaq === 4" x-cloak x-collapse class="px-5 sm:px-6 pb-6 border-t border-slate-50 dark:border-gray-700/50 pt-4 text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed bg-slate-50/10 dark:bg-gray-900/10">
                    Ya. Peserta yang menyelesaikan program magang secara tertib, mengisi jurnal harian (logbook), dan dinilai baik oleh pembimbing lapangan akan memperoleh sertifikat elektronik resmi bertanda tangan digital dan ber-QR code unik yang diakui Pemerintah Kota Banjarmasin.
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="reveal bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/80 dark:border-gray-700/50 overflow-hidden transition-all duration-300 shadow-sm w-full" style="--reveal-delay: 320ms" x-intersect.once="$el.classList.add('revealed')">
                <button @click="activeFaq = (activeFaq === 5 ? null : 5)" class="w-full text-left p-5 sm:p-6 font-bold text-slate-800 dark:text-slate-200 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-gray-800 active:bg-slate-100 dark:active:bg-gray-800 transition-colors focus:outline-none text-sm sm:text-base cursor-pointer">
                    <span class="pr-6">Bagaimana cara memverifikasi keaslian sertifikat magang?</span>
                    <i class="fas fa-chevron-down shrink-0 transition-transform duration-300" :class="{ 'rotate-180 text-teal-600 dark:text-teal-400': activeFaq === 5, 'text-slate-400': activeFaq !== 5 }"></i>
                </button>
                <div x-show="activeFaq === 5" x-cloak x-collapse class="px-5 sm:px-6 pb-6 border-t border-slate-50 dark:border-gray-700/50 pt-4 text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed bg-slate-50/10 dark:bg-gray-900/10">
                    Siapa pun (termasuk kampus atau calon pemberi kerja) dapat memverifikasi keabsahan sertifikat melalui menu <strong>Pindai QR</strong> di portal ini dengan kamera smartphone, mengunggah berkas sertifikat, atau mencari langsung berdasarkan nomor registrasi sertifikat.
                </div>
            </div>
        </div>
    </div>
</section>
