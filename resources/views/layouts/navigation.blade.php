<nav class="flex flex-col h-full bg-white text-slate-600 border-r border-slate-200 shadow-xl shadow-slate-200/50 z-50">
   
    <div class="flex-1 overflow-y-auto custom-scrollbar flex flex-col justify-between">
        
        <div class="px-4 py-6 space-y-1">
            <div class="px-4 mb-2 mt-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Menu Utama</p>
            </div>

            <a href="{{ route('dashboard') }}" 
               class="group relative flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 
               {{ request()->routeIs('dashboard') 
                   ? 'bg-gradient-to-r from-teal-50 to-white text-teal-700 shadow-sm border border-teal-100' 
                   : 'text-slate-600 hover:bg-slate-50 hover:text-teal-600' }}">
               
               @if(request()->routeIs('dashboard'))
                   <div class="absolute left-0 h-full w-1 bg-teal-600 rounded-r-full"></div>
               @endif

               <i class="fas fa-th-large w-5 mr-3 text-center transition-colors {{ request()->routeIs('dashboard') ? 'text-teal-600' : 'text-slate-400 group-hover:text-teal-500' }}"></i>
               <span>Dashboard</span>
            </a>

            @if(Auth::user()->role == 'admin_kota')
            <div x-data="{ open: {{ request()->routeIs('admin.*') ? 'true' : 'false' }} }" class="space-y-1 pt-2">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest hover:text-teal-600 transition-colors focus:outline-none group">
                    <span>Super Admin</span>
                    <i class="fas text-[9px] transition-transform duration-200" :class="open ? 'fa-chevron-down text-teal-500' : 'fa-chevron-right text-slate-300 group-hover:text-teal-500'"></i>
                </button>
                
                <div x-show="open" 
                     x-collapse 
                     x-cloak
                     class="space-y-1 pl-3 relative">
                     <div class="absolute left-0 top-2 bottom-2 w-[1px] bg-slate-200"></div>

                    <a href="{{ route('admin.instansi.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl border border-transparent hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-all {{ request()->routeIs('admin.instansi.*') ? 'text-teal-700 border-teal-200 bg-teal-50 shadow-sm' : 'text-slate-500' }}">
                        <i class="fas fa-building w-5 text-center mr-2 {{ request()->routeIs('admin.instansi.*') ? 'text-teal-500' : 'text-slate-400' }}"></i> Kelola Data Instansi
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl border border-transparent hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-all {{ request()->routeIs('admin.users.index') ? 'text-teal-700 border-teal-200 bg-teal-50 shadow-sm' : 'text-slate-500' }}">
                        <i class="fas fa-users-cog w-5 text-center mr-2 {{ request()->routeIs('admin.users.index') ? 'text-teal-500' : 'text-slate-400' }}"></i> Manajemen Pengguna
                    </a>
                    <a href="{{ route('admin.laporan.hub') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl border border-transparent hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-all {{ request()->routeIs('admin.laporan.hub') ? 'text-teal-700 border-teal-200 bg-teal-50 shadow-sm' : 'text-slate-500' }}">
                        <i class="fas fa-chart-pie w-5 text-center mr-2 {{ request()->routeIs('admin.laporan.hub') ? 'text-teal-500' : 'text-slate-400' }}"></i> Pusat Laporan
                    </a>
                     <a href="{{ route('admin.users.logbooks') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl border border-transparent hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-all {{ request()->routeIs('admin.users.logbooks') ? 'text-teal-700 border-teal-200 bg-teal-50 shadow-sm' : 'text-slate-500' }}">
                        <i class="fas fa-book w-5 text-center mr-2 {{ request()->routeIs('admin.users.logbooks') ? 'text-teal-500' : 'text-slate-400' }}"></i> Monitoring Logbook
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl border border-transparent hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-all {{ request()->routeIs('admin.settings.*') ? 'text-teal-700 border-teal-200 bg-teal-50 shadow-sm' : 'text-slate-500' }}">
                        <i class="fas fa-cogs w-5 text-center mr-2 {{ request()->routeIs('admin.settings.*') ? 'text-teal-500' : 'text-slate-400' }}"></i> Pengaturan Sistem
                    </a>
                </div>
            </div>
            @endif

            @if(Auth::user()->role == 'admin_instansi')
            <div x-data="{ open: {{ request()->routeIs('dinas.*') ? 'true' : 'false' }} }" class="space-y-1 pt-2">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest hover:text-teal-600 transition-colors focus:outline-none group">
                    <span>Manajemen Dinas</span>
                    <i class="fas text-[9px] transition-transform duration-200" :class="open ? 'fa-chevron-down text-teal-500' : 'fa-chevron-right text-slate-300 group-hover:text-teal-500'"></i>
                </button>
                <div x-show="open" x-collapse x-cloak class="space-y-1 pl-3 relative">
                     <div class="absolute left-0 top-2 bottom-2 w-[1px] bg-slate-200"></div>
                    <a href="{{ route('dinas.lowongan.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl border border-transparent hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-all {{ request()->routeIs('dinas.lowongan.*') ? 'text-teal-700 border-teal-200 bg-teal-50 shadow-sm' : 'text-slate-500' }}">
                        <i class="fas fa-briefcase w-5 text-center mr-2 {{ request()->routeIs('dinas.lowongan.*') ? 'text-teal-500' : 'text-slate-400' }}"></i> Kelola Lowongan
                    </a>
                    <a href="{{ route('dinas.pelamar') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl border border-transparent hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-all {{ request()->routeIs('dinas.pelamar') ? 'text-teal-700 border-teal-200 bg-teal-50 shadow-sm' : 'text-slate-500' }}">
                        <i class="fas fa-envelope-open-text w-5 text-center mr-2 {{ request()->routeIs('dinas.pelamar') ? 'text-teal-500' : 'text-slate-400' }}"></i> Pelamar Masuk
                    </a>
                    <a href="{{ route('dinas.peserta.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl border border-transparent hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-all {{ request()->routeIs('dinas.peserta.index') ? 'text-teal-700 border-teal-200 bg-teal-50 shadow-sm' : 'text-slate-500' }}">
                        <i class="fas fa-user-check w-5 text-center mr-2 {{ request()->routeIs('dinas.peserta.index') ? 'text-teal-500' : 'text-slate-400' }}"></i> Monitoring Peserta
                    </a>
                    <a href="{{ route('dinas.pembimbing_lapangan.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl border border-transparent hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-all {{ request()->routeIs('dinas.pembimbing_lapangan.*') ? 'text-teal-700 border-teal-200 bg-teal-50 shadow-sm' : 'text-slate-500' }}">
                        <i class="fas fa-chalkboard-teacher w-5 text-center mr-2 {{ request()->routeIs('dinas.pembimbing_lapangan.*') ? 'text-teal-500' : 'text-slate-400' }}"></i> Data Pembimbing
                    </a>
                    <a href="{{ route('dinas.laporan.hub') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl border border-transparent hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-all {{ request()->routeIs('dinas.laporan.hub') ? 'text-teal-700 border-teal-200 bg-teal-50 shadow-sm' : 'text-slate-500' }}">
                        <i class="fas fa-chart-pie w-5 text-center mr-2 {{ request()->routeIs('dinas.laporan.hub') ? 'text-teal-500' : 'text-slate-400' }}"></i> Pusat Laporan
                    </a>
                    <a href="{{ route('dinas.settings') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl border border-transparent hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-all {{ request()->routeIs('dinas.settings') ? 'text-teal-700 border-teal-200 bg-teal-50 shadow-sm' : 'text-slate-500' }}">
                        <i class="fas fa-sliders-h w-5 text-center mr-2 {{ request()->routeIs('dinas.settings') ? 'text-teal-500' : 'text-slate-400' }}"></i> Pengaturan
                    </a>
                </div>
            </div>
            @endif

            @if(Auth::user()->role == 'pembimbing_lapangan')
            <div x-data="{ open: {{ request()->routeIs('pembimbing_lapangan.*') ? 'true' : 'false' }} }" class="space-y-1 pt-2">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest hover:text-teal-600 transition-colors focus:outline-none group">
                    <span>Monitoring</span>
                    <i class="fas text-[9px] transition-transform duration-200" :class="open ? 'fa-chevron-down text-teal-500' : 'fa-chevron-right text-slate-300 group-hover:text-teal-500'"></i>
                </button>
                <div x-show="open" x-collapse x-cloak class="space-y-1 pl-3 relative">
                     <div class="absolute left-0 top-2 bottom-2 w-[1px] bg-slate-200"></div>
                    <a href="{{ route('pembimbing_lapangan.attendance.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl border border-transparent hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-all {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'text-teal-700 border-teal-200 bg-teal-50 shadow-sm' : 'text-slate-500' }}">
                        <i class="fas fa-clipboard-list w-5 text-center mr-2 {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'text-teal-500' : 'text-slate-400' }}"></i> Absensi Peserta
                    </a>
                </div>
            </div>
            @endif

            @if(Auth::user()->role == 'peserta')
            <div x-data="{ open: {{ request()->routeIs('peserta.*') ? 'true' : 'false' }} }" class="space-y-1 pt-2">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest hover:text-teal-600 transition-colors focus:outline-none group">
                    <span>Kegiatan Saya</span>
                    <i class="fas text-[9px] transition-transform duration-200" :class="open ? 'fa-chevron-down text-teal-500' : 'fa-chevron-right text-slate-300 group-hover:text-teal-500'"></i>
                </button>
                <div x-show="open" x-collapse x-cloak class="space-y-1 pl-3 relative">
                     <div class="absolute left-0 top-2 bottom-2 w-[1px] bg-slate-200"></div>
                    <a href="{{ route('peserta.logbook.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl border border-transparent hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-all {{ request()->routeIs('peserta.logbook.index') ? 'text-teal-700 border-teal-200 bg-teal-50 shadow-sm' : 'text-slate-500' }}">
                        <i class="fas fa-book-open w-5 text-center mr-2 {{ request()->routeIs('peserta.logbook.index') ? 'text-teal-500' : 'text-slate-400' }}"></i> Logbook Harian
                    </a>
                    <a href="{{ route('peserta.sertifikat') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl border border-transparent hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-all {{ request()->routeIs('peserta.sertifikat') ? 'text-teal-700 border-teal-200 bg-teal-50 shadow-sm' : 'text-slate-500' }}">
                        <i class="fas fa-award w-5 text-center mr-2 {{ request()->routeIs('peserta.sertifikat') ? 'text-teal-500' : 'text-slate-400' }}"></i> Sertifikat
                    </a>
                </div>
            </div>
            @endif

            @if(Auth::user()->role == 'pembimbing')
            <div x-data="{ open: {{ request()->routeIs('pembimbing.*') ? 'true' : 'false' }} }" class="space-y-1 pt-2">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest hover:text-teal-600 transition-colors focus:outline-none group">
                    <span>Pembimbing Akademik</span>
                    <i class="fas text-[9px] transition-transform duration-200" :class="open ? 'fa-chevron-down text-teal-500' : 'fa-chevron-right text-slate-300 group-hover:text-teal-500'"></i>
                </button>
                <div x-show="open" x-collapse x-cloak class="space-y-1 pl-3 relative">
                     <div class="absolute left-0 top-2 bottom-2 w-[1px] bg-slate-200"></div>
                    <a href="{{ route('pembimbing.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl border border-transparent hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-all {{ request()->routeIs('pembimbing.dashboard') ? 'text-teal-700 border-teal-200 bg-teal-50 shadow-sm' : 'text-slate-500' }}">
                        <i class="fas fa-home w-5 text-center mr-2 {{ request()->routeIs('pembimbing.dashboard') ? 'text-teal-500' : 'text-slate-400' }}"></i> Beranda
                    </a>
                </div>
            </div>
            @endif

        </div>

        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 group hover:shadow-md transition duration-300">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center text-white font-black text-sm shadow-md ring-2 ring-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="ml-3 min-w-0 overflow-hidden">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ Str::limit(Auth::user()->name, 15) }}</p>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-teal-50 text-teal-700 border border-teal-100 uppercase tracking-wide">
                            {{ str_replace('_', ' ', Auth::user()->role) }}
                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-2 mt-3">
                    <a href="{{ route('profile.edit') }}" class="flex items-center justify-center py-2 px-3 text-[10px] font-bold text-slate-600 bg-slate-50 hover:bg-teal-50 hover:text-teal-600 rounded-lg transition duration-200 border border-slate-100">
                        <i class="fas fa-cog mr-2"></i> Setting
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center py-2 px-3 text-[10px] font-bold text-red-500 bg-red-50 hover:bg-red-100 hover:text-red-700 rounded-lg transition duration-200 border border-red-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</nav>

<style>
    /* Custom Scrollbar for Sidebar */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    /* Hide scrollbar for standard browsers but keep functionality */
    .custom-scrollbar { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
</style>