<div class="space-y-1.5">
    <div class="px-3 mb-2 flex items-center gap-2" :class="sidebarCollapsed ? 'justify-center px-0' : ''">
        <span class="w-1.5 h-1.5 rounded-full bg-teal-600 dark:bg-teal-500 flex-shrink-0"></span>
        <p x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="text-[10px] font-extrabold text-slate-400 dark:text-gray-400 uppercase tracking-widest truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Super Admin</p>
    </div>

    <!-- Kelola Data Instansi -->
    <a href="{{ route('admin.instansi.index') }}" 
       @mouseenter="showTooltip($el, 'Kelola Data Instansi')"
       @mouseleave="hideTooltip()"
       class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.instansi.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
       :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
       <i class="fas fa-building w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.instansi.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
          :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
       <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Kelola Data Instansi</span>
    </a>

    <!-- Master Jurusan & Rumpun -->
    <a href="{{ route('admin.master.majors.index') }}" 
       @mouseenter="showTooltip($el, 'Master Jurusan & Rumpun')"
       @mouseleave="hideTooltip()"
       class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.master.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
       :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
       <i class="fas fa-graduation-cap w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.master.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
          :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
       <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Master Jurusan & Rumpun</span>
    </a>

    <!-- Manajemen Pengguna -->
    <a href="{{ route('admin.users.index') }}" 
       @mouseenter="showTooltip($el, 'Manajemen Pengguna')"
       @mouseleave="hideTooltip()"
       class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.users.index') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
       :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
       <i class="fas fa-users-cog w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.users.index') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
          :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
       <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Manajemen Pengguna</span>
    </a>

    <!-- Registri Sertifikat -->
    <a href="{{ route('admin.certificates.index') }}" 
       @mouseenter="showTooltip($el, 'Registri Sertifikat')"
       @mouseleave="hideTooltip()"
       class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.certificates.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
       :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
       <i class="fas fa-certificate w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.certificates.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
          :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
       <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Registri Sertifikat</span>
    </a>

    <!-- Pusat Laporan -->
    <a href="{{ route('admin.laporan.hub') }}" 
       @mouseenter="showTooltip($el, 'Pusat Laporan')"
       @mouseleave="hideTooltip()"
       class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.laporan.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
       :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
       <i class="fas fa-chart-pie w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.laporan.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
          :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
       <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Pusat Laporan</span>
    </a>

    <!-- Monitoring Logbook -->
    <a href="{{ route('admin.users.logbooks') }}" 
       @mouseenter="showTooltip($el, 'Monitoring Logbook')"
       @mouseleave="hideTooltip()"
       class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.users.logbooks*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
       :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
       <i class="fas fa-book w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.users.logbooks*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
          :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
       <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Monitoring Logbook</span>
    </a>

    <!-- Pengaturan Sistem -->
    <a href="{{ route('admin.settings.index') }}" 
       @mouseenter="showTooltip($el, 'Pengaturan Sistem')"
       @mouseleave="hideTooltip()"
       class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
       :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
       <i class="fas fa-cogs w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.settings.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
          :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
       <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Pengaturan Sistem</span>
    </a>
</div>
