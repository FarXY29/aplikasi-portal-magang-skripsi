<div class="space-y-1.5">
        <div class="px-3 mb-2 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Aktivitas Magang</p>
        </div>

        <a href="{{ route('peserta.logbook.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('peserta.logbook.*') ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-md shadow-teal-600/25' : 'text-gray-600 dark:text-gray-400 hover:bg-teal-50/80 hover:text-teal-700' }}">
           <i class="fas fa-book-open w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('peserta.logbook.*') ? 'text-white' : 'text-gray-400 group-hover:text-teal-600' }}"></i>
           <span>Logbook Harian</span>
        </a>

        <a href="{{ route('peserta.absensi.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('peserta.absensi.*') ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-md shadow-teal-600/25' : 'text-gray-600 dark:text-gray-400 hover:bg-teal-50/80 hover:text-teal-700' }}">
           <i class="fas fa-user-clock w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('peserta.absensi.*') ? 'text-white' : 'text-gray-400 group-hover:text-teal-600' }}"></i>
           <span>Absensi Kehadiran</span>
        </a>

        <a href="{{ route('peserta.sertifikat') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('peserta.sertifikat') ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-md shadow-teal-600/25' : 'text-gray-600 dark:text-gray-400 hover:bg-teal-50/80 hover:text-teal-700' }}">
           <i class="fas fa-award w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('peserta.sertifikat') ? 'text-white' : 'text-gray-400 group-hover:text-teal-600' }}"></i>
           <span>Sertifikat Magang</span>
        </a>
    </div>