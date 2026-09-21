<div class="space-y-1.5">
    <div class="px-3 mb-2 flex items-center gap-2" :class="sidebarCollapsed ? 'justify-center px-0' : ''">
        <span class="w-1.5 h-1.5 rounded-full bg-teal-600 dark:bg-teal-500 flex-shrink-0"></span>
        <p x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="text-[10px] font-extrabold text-slate-400 dark:text-gray-400 uppercase tracking-widest truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Aktivitas Magang</p>
    </div>

    <!-- Logbook Harian -->
    <a href="{{ route('peserta.logbook.index') }}" 
       @mouseenter="showTooltip($el, 'Logbook Harian')"
       @mouseleave="hideTooltip()"
       class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('peserta.logbook.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
       :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
       <i class="fas fa-book-open w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('peserta.logbook.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
          :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
       <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Logbook Harian</span>
    </a>

    <!-- Absensi Kehadiran -->
    <a href="{{ route('peserta.absensi.index') }}" 
       @mouseenter="showTooltip($el, 'Absensi Kehadiran')"
       @mouseleave="hideTooltip()"
       class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('peserta.absensi.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
       :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
       <i class="fas fa-user-clock w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('peserta.absensi.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
          :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
       <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Absensi Kehadiran</span>
    </a>

    <!-- Sertifikat Magang -->
    <a href="{{ route('peserta.sertifikat') }}" 
       @mouseenter="showTooltip($el, 'Sertifikat Magang')"
       @mouseleave="hideTooltip()"
       class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('peserta.sertifikat') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
       :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
       <i class="fas fa-award w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('peserta.sertifikat') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
          :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
       <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Sertifikat Magang</span>
    </a>
</div>
