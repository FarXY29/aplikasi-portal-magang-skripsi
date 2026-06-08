<x-app-layout>
    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col mb-8">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Pusat Laporan</h2>
                <p class="text-sm text-gray-500 mt-2 max-w-2xl">
                    Akses cepat ke seluruh modul analitik dan laporan instansi Anda. 
                    Pilih modul laporan yang ingin Anda tinjau di bawah ini.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Laporan Peserta -->
                <a href="{{ route('dinas.laporan.rekap') }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-teal-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-teal-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Laporan Peserta</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Melihat daftar seluruh peserta magang yang terdaftar dan sedang/telah menjalani magang di instansi ini.</p>
                    </div>
                </a>

                <!-- Analisis Kompetensi -->
                <a href="{{ route('dinas.laporan.grading') }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-purple-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Analisis Kompetensi</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Statistik penilaian mahasiswa secara menyeluruh, grafik distribusi nilai, dan IPK untuk instansi ini.</p>
                    </div>
                </a>

                <!-- Evaluasi Pembimbing -->
                <a href="{{ route('dinas.laporan.evaluasi_pembimbing') }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-blue-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Evaluasi Pembimbing</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Laporan tren penilaian yang diberikan oleh masing-masing pembimbing lapangan kepada pesertanya.</p>
                    </div>
                </a>

                <!-- Kotak Saran Peserta (Anonim) -->
                <a href="{{ route('dinas.laporan.saran_peserta') }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-emerald-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <i class="fas fa-comment-dots"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Kotak Saran (Anonim)</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Kumpulan ulasan dan evaluasi dari peserta magang yang telah lulus untuk instansi Anda.</p>
                    </div>
                </a>

                <!-- Tren Musim Pendaftaran -->
                <a href="{{ route('dinas.laporan.tren_pendaftaran') }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-orange-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                            <i class="fas fa-chart-area"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Tren Pendaftaran</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Melihat pada bulan/tahun berapa jumlah pendaftaran magang paling ramai (musim magang tertinggi).</p>
                    </div>
                </a>

                <!-- Produktivitas Logbook -->
                <a href="{{ route('dinas.laporan.produktivitas_logbook') }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-indigo-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Produktivitas Logbook</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Mengukur persentase keaktifan peserta dalam mengerjakan dan mengumpulkan logbook harian mereka.</p>
                    </div>
                </a>

                <!-- Keterisian Lowongan -->
                <a href="{{ route('dinas.laporan.keterisian_posisi') }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-emerald-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Keterisian Lowongan</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Memeriksa kuota masing-masing posisi magang dan rasio seberapa penuh kuota tersebut (*occupancy rate*).</p>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
