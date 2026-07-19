<x-app-layout>
    <div class="py-8 bg-gray-50 dark:bg-gray-900/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col mb-8">
                <h2 class="text-3xl font-black text-gray-900 dark:text-gray-100 tracking-tight">Pusat Laporan</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 max-w-2xl">
                    Akses cepat ke seluruh modul laporan statistik dan analitik Pemerintah Kota Banjarmasin. 
                    Pilih modul laporan yang ingin Anda lihat di bawah ini.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Laporan Instansi -->
                <a href="{{ route('admin.laporan') }}" class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-teal-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-teal-50 dark:bg-teal-900/20 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Master Instansi</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Melihat daftar seluruh instansi (Dinas/Badan) beserta status jam kerja dan detail profilnya.</p>
                    </div>
                </a>

                <!-- Laporan Global Peserta -->
                <a href="{{ route('admin.laporan.peserta_global') }}" class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:teal-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 dark:bg-blue-900/20 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Peserta Global</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Rekapitulasi seluruh peserta magang dari seluruh universitas dan instansi di lingkungan pemkot.</p>
                    </div>
                </a>

                <!-- Analisis Kompetensi -->
                <a href="{{ route('admin.laporan.grading') }}" class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-purple-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 dark:bg-purple-900/20 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Analisis Kompetensi</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Laporan distribusi penilaian mahasiswa, mencakup IPK, nilai teknis, disiplin, dan perilaku secara global.</p>
                    </div>
                </a>

                <!-- Instansi Paling Disiplin -->
                <a href="{{ route('admin.laporan.instansi_disiplin') }}" class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-orange-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 dark:bg-orange-900/20 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                            <i class="fas fa-medal"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Instansi Disiplin</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Pemeringkatan (ranking) instansi berdasarkan kepatuhan absensi *clock-in* / *clock-out* dari peserta magangnya.</p>
                    </div>
                </a>

                <!-- Durasi Magang -->
                <a href="{{ route('admin.laporan.durasi_magang') }}" class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-indigo-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 dark:bg-indigo-900/20 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Durasi Rata-Rata Magang</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Menghitung seberapa lama durasi (dalam hari dan bulan) rata-rata setiap peserta melakukan magang.</p>
                    </div>
                </a>

                <!-- Demografi Jurusan -->
                <a href="{{ route('admin.laporan.demografi_jurusan') }}" class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-pink-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-pink-50 dark:bg-pink-900/20 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-pink-100 text-pink-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-pink-600 group-hover:text-white transition-colors">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Demografi Jurusan</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Melihat jurusan kuliah apa saja yang paling banyak dibutuhkan oleh instansi pemerintahan.</p>
                    </div>
                </a>

                <!-- Penyerapan Kuota -->
                <a href="{{ route('admin.laporan.penyerapan_kuota') }}" class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-emerald-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 dark:bg-emerald-900/20 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Kinerja Penyerapan Kuota</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Mengukur efisiensi *recruitment* dengan membandingkan kuota magang tersedia vs kuota yang terisi.</p>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
