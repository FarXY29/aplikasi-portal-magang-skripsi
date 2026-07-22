<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-chart-pie text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Pusat Laporan & Analitik') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400 font-medium bg-white dark:bg-gray-800 px-4 py-1.5 rounded-full shadow-xs border border-gray-100 dark:border-gray-700">
                Modul Laporan: <span class="font-bold text-teal-600 dark:text-teal-400">5 Modul Tersedia</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2 print:hidden">
                <a href="{{ route('dinas.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-xs border border-gray-100 dark:border-gray-700 relative overflow-hidden">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-teal-500/10 dark:bg-teal-400/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="relative z-10 max-w-3xl">
                    <h3 class="text-xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">Pusat Laporan & Data Magang</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                        Akses cepat ke seluruh modul analitik dan laporan instansi Anda. Pilih modul laporan di bawah ini untuk melihat rekapitulasi, mengekspor data (PDF, Excel, CSV), dan menganalisis performa magang.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Laporan Peserta -->
                <a href="{{ route('dinas.laporan.rekap') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-teal-300 dark:hover:border-teal-700/80 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden flex flex-col justify-between">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-teal-50 dark:bg-teal-950/30 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-teal-100 dark:bg-teal-950/80 text-teal-600 dark:text-teal-400 rounded-2xl flex items-center justify-center text-xl mb-4 group-hover:bg-teal-600 group-hover:text-white dark:group-hover:bg-teal-500 transition-colors border border-teal-200/60 dark:border-teal-800/60">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition">Laporan Peserta Magang</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Melihat rekapitulasi seluruh peserta magang yang terdaftar, aktif, dan alumni yang telah menyelesaikan program di instansi ini.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700/60 flex items-center text-xs font-bold text-teal-600 dark:text-teal-400 group-hover:translate-x-1 transition-transform">
                        <span>Buka Laporan</span>
                        <i class="fas fa-arrow-right ml-2 text-[10px]"></i>
                    </div>
                </a>

                <!-- Kinerja Mahasiswa -->
                <a href="{{ route('dinas.laporan.kinerja_mahasiswa') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-purple-300 dark:hover:border-purple-700/80 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden flex flex-col justify-between">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 dark:bg-purple-950/30 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-950/80 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center text-xl mb-4 group-hover:bg-purple-600 group-hover:text-white dark:group-hover:bg-purple-500 transition-colors border border-purple-200/60 dark:border-purple-800/60">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">Kinerja Mahasiswa</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Scorecard lengkap persentase absensi kehadiran, keaktifan logbook harian, dan pencapaian nilai akhir setiap mahasiswa.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700/60 flex items-center text-xs font-bold text-purple-600 dark:text-purple-400 group-hover:translate-x-1 transition-transform">
                        <span>Buka Laporan</span>
                        <i class="fas fa-arrow-right ml-2 text-[10px]"></i>
                    </div>
                </a>

                <!-- Beban & Kinerja Pembimbing -->
                <a href="{{ route('dinas.laporan.beban_pembimbing') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-blue-300 dark:hover:border-blue-700/80 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden flex flex-col justify-between">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 dark:bg-blue-950/30 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-950/80 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center text-xl mb-4 group-hover:bg-blue-600 group-hover:text-white dark:group-hover:bg-blue-500 transition-colors border border-blue-200/60 dark:border-blue-800/60">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">Kinerja Pembimbing</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Memantau distribusikan beban bimbingan, jumlah mahasiswa aktif, serta mendeteksi sisa logbook yang perlu divalidasi.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700/60 flex items-center text-xs font-bold text-blue-600 dark:text-blue-400 group-hover:translate-x-1 transition-transform">
                        <span>Buka Laporan</span>
                        <i class="fas fa-arrow-right ml-2 text-[10px]"></i>
                    </div>
                </a>

                <!-- Demografi Kampus / Asal Instansi -->
                <a href="{{ route('dinas.laporan.demografi_kampus') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-orange-300 dark:hover:border-orange-700/80 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden flex flex-col justify-between">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 dark:bg-orange-950/30 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-950/80 text-orange-600 dark:text-orange-400 rounded-2xl flex items-center justify-center text-xl mb-4 group-hover:bg-orange-600 group-hover:text-white dark:group-hover:bg-orange-500 transition-colors border border-orange-200/60 dark:border-orange-800/60">
                            <i class="fas fa-university"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition">Demografi Kampus</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Analisis statistik jumlah pendaftar dan persebaran mahasiswa yang diterima berdasarkan asal perguruan tinggi / sekolah.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700/60 flex items-center text-xs font-bold text-orange-600 dark:text-orange-400 group-hover:translate-x-1 transition-transform">
                        <span>Buka Laporan</span>
                        <i class="fas fa-arrow-right ml-2 text-[10px]"></i>
                    </div>
                </a>

                <!-- Jurnal Harian -->
                <a href="{{ route('dinas.laporan.jurnal_harian') }}" class="group bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-emerald-300 dark:hover:border-emerald-700/80 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden flex flex-col justify-between">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 dark:bg-emerald-950/30 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center text-xl mb-4 group-hover:bg-emerald-600 group-hover:text-white dark:group-hover:bg-emerald-500 transition-colors border border-emerald-200/60 dark:border-emerald-800/60">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition">Jurnal & Aktivitas Harian</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Pantau secara akumulatif seluruh rekaman kegiatan dan logbook harian peserta magang secara mendalam.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700/60 flex items-center text-xs font-bold text-emerald-600 dark:text-emerald-400 group-hover:translate-x-1 transition-transform">
                        <span>Buka Laporan</span>
                        <i class="fas fa-arrow-right ml-2 text-[10px]"></i>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
