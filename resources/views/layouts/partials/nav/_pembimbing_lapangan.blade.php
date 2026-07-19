<div class="space-y-1.5">
        <div class="px-3 mb-2 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Monitoring Lapangan</p>
        </div>

        <a href="{{ route('pembimbing_lapangan.attendance.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-md shadow-teal-600/25' : 'text-gray-600 dark:text-gray-400 hover:bg-teal-50/80 hover:text-teal-700' }}">
           <i class="fas fa-clipboard-list w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'text-white' : 'text-gray-400 group-hover:text-teal-600' }}"></i>
           <span>Absensi Peserta</span>
        </a>
    </div>