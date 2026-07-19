<div class="space-y-1.5">
        <div class="px-3 mb-2 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Super Admin Kota</p>
        </div>

        <a href="{{ route('admin.instansi.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.instansi.*') ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-md shadow-teal-600/25' : 'text-gray-600 dark:text-gray-400 hover:bg-teal-50/80 hover:text-teal-700' }}">
           <i class="fas fa-building w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.instansi.*') ? 'text-white' : 'text-gray-400 group-hover:text-teal-600' }}"></i>
           <span>Kelola Data Instansi</span>
        </a>

        <a href="{{ route('admin.users.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.users.index') ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-md shadow-teal-600/25' : 'text-gray-600 dark:text-gray-400 hover:bg-teal-50/80 hover:text-teal-700' }}">
           <i class="fas fa-users-cog w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.users.index') ? 'text-white' : 'text-gray-400 group-hover:text-teal-600' }}"></i>
           <span>Manajemen Pengguna</span>
        </a>

        <a href="{{ route('admin.audit_trail') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.audit_trail') ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-md shadow-teal-600/25' : 'text-gray-600 dark:text-gray-400 hover:bg-teal-50/80 hover:text-teal-700' }}">
           <i class="fas fa-shield-alt w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.audit_trail') ? 'text-white' : 'text-gray-400 group-hover:text-teal-600' }}"></i>
           <span>Audit Trail</span>
        </a>

        <a href="{{ route('admin.laporan.hub') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.laporan.*') ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-md shadow-teal-600/25' : 'text-gray-600 dark:text-gray-400 hover:bg-teal-50/80 hover:text-teal-700' }}">
           <i class="fas fa-chart-pie w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.laporan.*') ? 'text-white' : 'text-gray-400 group-hover:text-teal-600' }}"></i>
           <span>Pusat Laporan Hub</span>
        </a>

        <a href="{{ route('admin.users.logbooks') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.users.logbooks*') ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-md shadow-teal-600/25' : 'text-gray-600 dark:text-gray-400 hover:bg-teal-50/80 hover:text-teal-700' }}">
           <i class="fas fa-book w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.users.logbooks*') ? 'text-white' : 'text-gray-400 group-hover:text-teal-600' }}"></i>
           <span>Monitoring Logbook</span>
        </a>

        <a href="{{ route('admin.settings.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-md shadow-teal-600/25' : 'text-gray-600 dark:text-gray-400 hover:bg-teal-50/80 hover:text-teal-700' }}">
           <i class="fas fa-cogs w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-gray-400 group-hover:text-teal-600' }}"></i>
           <span>Pengaturan Sistem</span>
        </a>
    </div>