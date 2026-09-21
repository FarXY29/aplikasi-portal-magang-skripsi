<!-- 1. TOP LOGO & HEADER BRANDING -->
<div class="flex items-center h-16 min-h-[4rem] max-h-16 bg-slate-50/90 dark:bg-gray-900/90 text-slate-800 dark:text-white border-b border-slate-200/90 dark:border-gray-700/80 flex-shrink-0 box-border transition-all duration-300 overflow-x-hidden"
     :class="sidebarCollapsed ? 'justify-center px-2' : 'justify-between px-5'">
    
    <a href="{{ route('home') }}" 
       class="flex items-center gap-3 group min-w-0" 
       :class="sidebarCollapsed ? 'justify-center' : ''"
       @mouseenter="showTooltip($el, '{{ config('app.name', 'Portal Magang') }}', 'Banjarmasin')"
       @mouseleave="hideTooltip()">
        <div class="w-9 h-9 rounded-xl bg-teal-700 text-white flex items-center justify-center p-1.5 shadow-2xs group-hover:scale-105 transition-transform duration-200 flex-shrink-0">
            <x-ui.application-logo class="w-full h-full fill-current text-white" />
        </div>
        <div x-show="!sidebarCollapsed" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-x-2"
             x-transition:enter-end="opacity-100 translate-x-0"
             class="flex flex-col min-w-0" 
             :class="sidebarCollapsed ? 'lg:hidden' : ''">
            <span class="text-base font-black tracking-tight leading-tight text-slate-900 dark:text-white truncate">Portal <span class="text-teal-700 dark:text-teal-400">Magang</span></span>
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-gray-400 truncate">Banjarmasin</span>
        </div>
    </a>

    <!-- Tombol Collapse/Expand di Header Sidebar (Desktop lg) -->
    <button @click="toggleSidebarCollapse()" 
            x-show="!sidebarCollapsed"
            class="hidden lg:flex w-8 h-8 rounded-lg bg-slate-200/60 hover:bg-slate-300/70 dark:bg-gray-800 dark:hover:bg-gray-700 items-center justify-center text-slate-500 dark:text-gray-400 hover:text-teal-700 dark:hover:text-teal-400 transition focus:outline-none flex-shrink-0" 
            title="Kecilkan Sidebar">
        <i class="fas fa-angle-left text-sm"></i>
    </button>

    <!-- Tombol Tutup untuk Mode Drawer (Tablet/Mobile) -->
    <button @click="sidebarOpen = false" class="lg:hidden w-8 h-8 rounded-lg bg-slate-200/70 hover:bg-slate-300/70 dark:bg-gray-800 dark:hover:bg-gray-700 flex items-center justify-center text-slate-600 dark:text-gray-300 transition focus:outline-none flex-shrink-0" title="Tutup Sidebar">
        <i class="fas fa-times text-sm"></i>
    </button>
</div>

<!-- 2. MAIN SCROLLABLE MENU ITEMS -->
<div class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar transition-all duration-300"
     :class="sidebarCollapsed ? 'px-2 py-4 space-y-4' : 'px-4 py-5 space-y-6'">
    
    <!-- SECTION: MENU UTAMA -->
    <div class="space-y-1.5">
        <div class="px-3 mb-2 flex items-center gap-2" :class="sidebarCollapsed ? 'justify-center px-0' : ''">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-600 dark:bg-teal-500 flex-shrink-0"></span>
            <p x-show="!sidebarCollapsed" 
               x-transition.opacity.duration.150ms 
               class="text-[10px] font-extrabold text-slate-400 dark:text-gray-400 uppercase tracking-widest truncate" 
               :class="sidebarCollapsed ? 'lg:hidden' : ''">Menu Utama</p>
        </div>

        <a href="{{ route('dashboard') }}" 
           @mouseenter="showTooltip($el, 'Dashboard Beranda')"
           @mouseleave="hideTooltip()"
           class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
           :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
           <i class="fas fa-th-large w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dashboard') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
              :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
           <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Dashboard Beranda</span>
        </a>
    </div>

    <!-- SECTION: ROLE-SPECIFIC NAVIGATION -->
    @if(Auth::user()->role == 'peserta')
        @include('layouts.partials.nav._peserta')
    @elseif(Auth::user()->role == 'admin_kota')
        @include('layouts.partials.nav._admin_kota')
    @elseif(Auth::user()->role == 'admin_instansi')
        @include('layouts.partials.nav._admin_instansi')
    @elseif(Auth::user()->role == 'pembimbing_lapangan')
        @include('layouts.partials.nav._pembimbing_lapangan')
    @elseif(Auth::user()->role == 'pembimbing')
        @include('layouts.partials.nav._pembimbing')
    @endif

    <!-- SECTION: PENGATURAN & SISTEM -->
    <div class="space-y-1.5 pt-3 border-t border-slate-200/80 dark:border-gray-700/50">
        <div class="px-3 mb-2 flex items-center gap-2" :class="sidebarCollapsed ? 'justify-center px-0' : ''">
            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 flex-shrink-0"></span>
            <p x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="text-[10px] font-extrabold text-slate-400 dark:text-gray-400 uppercase tracking-widest truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Akun & Sistem</p>
        </div>

        <!-- Pengaturan Profil -->
        <a href="{{ route('profile.edit') }}" 
           @mouseenter="showTooltip($el, 'Pengaturan Profil')"
           @mouseleave="hideTooltip()"
           class="group relative flex items-center text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('profile.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}"
           :class="sidebarCollapsed ? 'px-0 py-3 justify-center' : 'px-4 py-3'">
           <i class="fas fa-user-circle w-5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('profile.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"
              :class="sidebarCollapsed ? 'mr-0' : 'mr-3.5'"></i>
           <span x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms class="truncate" :class="sidebarCollapsed ? 'lg:hidden' : ''">Pengaturan Profil</span>
        </a>
    </div>
</div>

<!-- 3. BOTTOM USER PROFILE & LOGOUT CARD -->
<div class="border-t border-slate-200/90 dark:border-gray-700/80 bg-slate-50/90 dark:bg-gray-900/80 flex-shrink-0 transition-all duration-300 overflow-x-hidden"
     :class="sidebarCollapsed ? 'p-2' : 'p-3.5'">
    
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-gray-700/80 hover:border-slate-300 dark:hover:border-teal-500 transition-all duration-300"
         :class="sidebarCollapsed ? 'p-2 flex flex-col items-center gap-2.5' : 'p-3'">
        
        <!-- Expanded Mode: Horizontal Info + Controls -->
        <div class="flex items-center justify-between gap-2.5 w-full"
             :class="sidebarCollapsed ? 'lg:hidden' : ''">
            <div class="flex items-center gap-3 min-w-0 flex-1">
                <div class="w-10 h-10 rounded-xl bg-teal-700 text-white flex items-center justify-center font-black text-sm shadow-2xs flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-black text-slate-800 dark:text-gray-100 truncate leading-tight">{{ Auth::user()->name }}</p>
                    <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-md text-[9px] font-extrabold bg-teal-50 dark:bg-teal-900/30 text-teal-800 dark:text-teal-400 border border-teal-200/70 dark:border-teal-800 uppercase tracking-wider truncate max-w-full">
                        {{ str_replace('_', ' ', Auth::user()->role) }}
                    </span>
                </div>
            </div>
            
            <div class="flex items-center gap-1.5 flex-shrink-0">
                <x-ui.theme-toggle class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-300 hover:bg-slate-200 hover:text-slate-900 dark:hover:bg-gray-600 dark:hover:text-white transition-all duration-200 active:scale-95 shadow-2xs border border-slate-200 dark:border-gray-600" />
                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" 
                            title="Keluar / Logout"
                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white dark:bg-rose-900/30 dark:text-rose-400 dark:hover:bg-rose-600 dark:hover:text-white transition-all duration-200 active:scale-95 shadow-2xs border border-rose-200/60 dark:border-rose-900/50">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Collapsed Mode (Desktop Only): Compact Vertical Stack -->
        <div x-show="sidebarCollapsed" 
             class="hidden lg:flex flex-col items-center gap-2.5 w-full">
            
            <!-- User Avatar Icon with Hover Tooltip -->
            <div class="flex items-center justify-center"
                 @mouseenter="showTooltip($el, '{{ Auth::user()->name }}', '{{ str_replace('_', ' ', Auth::user()->role) }}')"
                 @mouseleave="hideTooltip()">
                <div class="w-10 h-10 rounded-xl bg-teal-700 text-white flex items-center justify-center font-black text-sm shadow-2xs cursor-pointer hover:scale-105 transition-transform duration-150">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>

            <!-- Divider -->
            <div class="w-8 h-px bg-slate-200 dark:bg-gray-700"></div>

            <!-- Controls: Theme Toggle & Logout -->
            <div class="flex flex-col items-center gap-2">
                <div @mouseenter="showTooltip($el, 'Ganti Tema')"
                     @mouseleave="hideTooltip()">
                    <x-ui.theme-toggle class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-300 hover:bg-slate-200 hover:text-slate-900 dark:hover:bg-gray-600 dark:hover:text-white transition-all duration-200 active:scale-95 shadow-2xs border border-slate-200 dark:border-gray-600" />
                </div>

                <div @mouseenter="showTooltip($el, 'Logout')"
                     @mouseleave="hideTooltip()">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white dark:bg-rose-900/30 dark:text-rose-400 dark:hover:bg-rose-600 dark:hover:text-white transition-all duration-200 active:scale-95 shadow-2xs border border-rose-200/60 dark:border-rose-900/50">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
