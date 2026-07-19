<div class="space-y-1.5">
        <div class="px-3 mb-2 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Manajemen Dinas</p>
        </div>

        <a href="{{ route('dinas.lowongan.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.lowongan.*') ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-md shadow-teal-600/25' : 'text-gray-600 dark:text-gray-400 hover:bg-teal-50/80 hover:text-teal-700' }}">
           <i class="fas fa-briefcase w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.lowongan.*') ? 'text-white' : 'text-gray-400 group-hover:text-teal-600' }}"></i>
           <span>Kelola Lowongan</span>
        </a>

        <a href="{{ route('dinas.pelamar') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.pelamar') ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-md shadow-teal-600/25' : 'text-gray-600 dark:text-gray-400 hover:bg-teal-50/80 hover:text-teal-700' }}">
           <i class="fas fa-envelope-open-text w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.pelamar') ? 'text-white' : 'text-gray-400 group-hover:text-teal-600' }}"></i>
           <span>Pelamar Masuk</span>
        </a>

        <a href="{{ route('dinas.peserta.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.peserta.index') ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-md shadow-teal-600/25' : 'text-gray-600 dark:text-gray-400 hover:bg-teal-50/80 hover:text-teal-700' }}">
           <i class="fas fa-user-check w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.peserta.index') ? 'text-white' : 'text-gray-400 group-hover:text-teal-600' }}"></i>
           <span>Monitoring Peserta</span>
        </a>

        <a href="{{ route('dinas.pembimbing_lapangan.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.pembimbing_lapangan.*') ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-md shadow-teal-600/25' : 'text-gray-600 dark:text-gray-400 hover:bg-teal-50/80 hover:text-teal-700' }}">
           <i class="fas fa-chalkboard-teacher w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.pembimbing_lapangan.*') ? 'text-white' : 'text-gray-400 group-hover:text-teal-600' }}"></i>
           <span>Data Pembimbing</span>
        </a>

        <a href="{{ route('dinas.laporan.hub') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.laporan.hub') ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-md shadow-teal-600/25' : 'text-gray-600 dark:text-gray-400 hover:bg-teal-50/80 hover:text-teal-700' }}">
           <i class="fas fa-chart-pie w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.laporan.hub') ? 'text-white' : 'text-gray-400 group-hover:text-teal-600' }}"></i>
           <span>Pusat Laporan Hub</span>
        </a>

        <a href="{{ route('dinas.settings') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.settings') ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-md shadow-teal-600/25' : 'text-gray-600 dark:text-gray-400 hover:bg-teal-50/80 hover:text-teal-700' }}">
           <i class="fas fa-sliders-h w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.settings') ? 'text-white' : 'text-gray-400 group-hover:text-teal-600' }}"></i>
           <span>Pengaturan</span>
        </a>
    </div>