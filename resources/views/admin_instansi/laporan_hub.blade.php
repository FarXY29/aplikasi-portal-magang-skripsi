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

                <!-- Kinerja Mahasiswa -->
                <a href="{{ route('dinas.laporan.kinerja_mahasiswa') }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-purple-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Kinerja Mahasiswa</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Scorecard lengkap persentase absensi, keaktifan pengisian logbook, dan nilai akhir tiap mahasiswa.</p>
                    </div>
                </a>

                <!-- Beban & Kinerja Pembimbing -->
                <a href="{{ route('dinas.laporan.beban_pembimbing') }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-blue-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Kinerja Pembimbing</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Memantau beban jumlah mahasiswa bimbingan aktif dan mendeteksi penumpukan logbook yang belum divalidasi.</p>
                    </div>
                </a>

                <!-- Demografi Kampus / Asal Instansi -->
                <a href="{{ route('dinas.laporan.demografi_kampus') }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-orange-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                            <i class="fas fa-university"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Demografi Kampus</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Analisis statistik jumlah pendaftar dan mahasiswa yang diterima berdasarkan asal kampus atau sekolah.</p>
                    </div>
                </a>

                <!-- Jurnal Harian (Baru) -->
                <a href="{{ route('dinas.laporan.jurnal_harian') }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-purple-300 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Jurnal & Aktivitas Harian</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Pantau secara agregat seluruh aktivitas yang diinputkan mahasiswa melalui logbook harian mereka.</p>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
