<div class="space-y-1.5">
    <div class="px-3 mb-2 flex items-center gap-2" :class="sidebarCollapsed ? 'justify-center px-0' : ''">
        <span class="w-1.5 h-1.5 rounded-full bg-teal-600 dark:bg-teal-500 flex-shrink-0"></span>
        <p x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="text-[10px] font-extrabold text-slate-400 dark:text-gray-400 uppercase tracking-widest truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Manajemen Dinas</p>
    </div>

    <!-- Kelola Lowongan -->
    <a href="{{ route('dinas.lowongan.index') }}" 
       @mouseenter="showTooltip($el, 'Kelola Lowongan')"
       @mouseleave="hideTooltip()"
       class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.lowongan.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
       :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
       <i class="fas fa-briefcase w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.lowongan.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
          :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
       <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Kelola Lowongan</span>
    </a>

    <!-- Pelamar Masuk -->
    <a href="{{ route('dinas.pelamar') }}" 
       @mouseenter="showTooltip($el, 'Pelamar Masuk')"
       @mouseleave="hideTooltip()"
       class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.pelamar') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
       :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
       <i class="fas fa-envelope-open-text w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.pelamar') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
          :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
       <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Pelamar Masuk</span>
    </a>

    <!-- Monitoring Peserta -->
    <a href="{{ route('dinas.peserta.index') }}" 
       @mouseenter="showTooltip($el, 'Monitoring Peserta')"
       @mouseleave="hideTooltip()"
       class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.peserta.index') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
       :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
       <i class="fas fa-user-check w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.peserta.index') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
          :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
       <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Monitoring Peserta</span>
    </a>

    <!-- Data Pembimbing -->
    <a href="{{ route('dinas.pembimbing_lapangan.index') }}" 
       @mouseenter="showTooltip($el, 'Data Pembimbing')"
       @mouseleave="hideTooltip()"
       class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.pembimbing_lapangan.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
       :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
       <i class="fas fa-chalkboard-teacher w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.pembimbing_lapangan.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
          :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
       <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Data Pembimbing</span>
    </a>

    <!-- Pusat Laporan -->
    <a href="{{ route('dinas.laporan.hub') }}" 
       @mouseenter="showTooltip($el, 'Pusat Laporan')"
       @mouseleave="hideTooltip()"
       class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.laporan.hub') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
       :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
       <i class="fas fa-chart-pie w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.laporan.hub') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
          :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
       <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Pusat Laporan</span>
    </a>

    <!-- Pengaturan -->
    <a href="{{ route('dinas.settings') }}" 
       @mouseenter="showTooltip($el, 'Pengaturan')"
       @mouseleave="hideTooltip()"
       class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.settings') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
       :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
       <i class="fas fa-sliders-h w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.settings') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
          :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
       <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Pengaturan</span>
    </a>
</div>
