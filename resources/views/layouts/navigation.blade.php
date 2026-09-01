<!-- 1. TOP LOGO & HEADER BRANDING -->
<div class="flex items-center justify-between h-16 min-h-[4rem] max-h-16 px-6 bg-slate-50/90 dark:bg-gray-900/90 text-slate-800 dark:text-white border-b border-slate-200/90 dark:border-gray-700/80 flex-shrink-0 box-border">
    <a href="{{ route('home') }}" class="flex items-center gap-3 group min-w-0">
        <div class="w-9 h-9 rounded-xl bg-teal-700 text-white flex items-center justify-center p-1.5 shadow-2xs group-hover:scale-105 transition-transform duration-200 flex-shrink-0">
            <x-application-logo class="w-full h-full fill-current text-white" />
        </div>
        <div class="flex flex-col min-w-0">
            <span class="text-base font-black tracking-tight leading-tight text-slate-900 dark:text-white truncate">Portal <span class="text-teal-700 dark:text-teal-400">Magang</span></span>
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-gray-400 truncate">Banjarmasin</span>
        </div>
    </a>
    <!-- Tombol Tutup untuk Mode Drawer (Tablet/Mobile) -->
    <button @click="sidebarOpen = false" class="lg:hidden w-8 h-8 rounded-lg bg-slate-200/70 hover:bg-slate-300/70 dark:bg-gray-800 dark:hover:bg-gray-700 flex items-center justify-center text-slate-600 dark:text-gray-300 transition focus:outline-none flex-shrink-0" title="Tutup Sidebar">
        <i class="fas fa-times text-sm"></i>
    </button>
</div>

<!-- 2. MAIN SCROLLABLE MENU ITEMS -->
<div class="flex-1 overflow-y-auto custom-scrollbar px-4 py-5 space-y-6">
    
    <!-- SECTION: MENU UTAMA -->
    <div class="space-y-1.5">
        <div class="px-3 mb-2 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-600 dark:bg-teal-500"></span>
            <p class="text-[10px] font-extrabold text-slate-400 dark:text-gray-400 uppercase tracking-widest">Menu Utama</p>
        </div>

        <a href="{{ route('dashboard') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-th-large w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dashboard') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Dashboard Beranda</span>
        </a>
    </div>

    <!-- SECTION: ROLE-SPECIFIC NAVIGATION -->
    @if(Auth::user()->role == 'peserta')
    <div class="space-y-1.5">
        <div class="px-3 mb-2 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-600 dark:bg-teal-500"></span>
            <p class="text-[10px] font-extrabold text-slate-400 dark:text-gray-400 uppercase tracking-widest">Aktivitas Magang</p>
        </div>

        <a href="{{ route('peserta.logbook.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('peserta.logbook.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-book-open w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('peserta.logbook.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Logbook Harian</span>
        </a>

        <a href="{{ route('peserta.absensi.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('peserta.absensi.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-user-clock w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('peserta.absensi.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Absensi Kehadiran</span>
        </a>

        <a href="{{ route('peserta.sertifikat') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('peserta.sertifikat') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-award w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('peserta.sertifikat') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Sertifikat Magang</span>
        </a>
    </div>
    @elseif(Auth::user()->role == 'admin_kota')
    <div class="space-y-1.5">
        <div class="px-3 mb-2 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-600 dark:bg-teal-500"></span>
            <p class="text-[10px] font-extrabold text-slate-400 dark:text-gray-400 uppercase tracking-widest">Super Admin</p>
        </div>

        <a href="{{ route('admin.instansi.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.instansi.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-building w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.instansi.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Kelola Data Instansi</span>
        </a>

        <a href="{{ route('admin.master.majors.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.master.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-graduation-cap w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.master.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Master Jurusan & Rumpun</span>
        </a>

        <a href="{{ route('admin.users.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.users.index') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-users-cog w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.users.index') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Manajemen Pengguna</span>
        </a>

        <a href="{{ route('admin.certificates.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.certificates.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-certificate w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.certificates.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Registri Sertifikat</span>
        </a>

        <a href="{{ route('admin.laporan.hub') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.laporan.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-chart-pie w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.laporan.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Pusat Laporan</span>
        </a>

        <a href="{{ route('admin.users.logbooks') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.users.logbooks*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-book w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.users.logbooks*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Monitoring Logbook</span>
        </a>

        <a href="{{ route('admin.settings.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-cogs w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('admin.settings.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Pengaturan Sistem</span>
        </a>
    </div>
    @elseif(Auth::user()->role == 'admin_instansi')
    <div class="space-y-1.5">
        <div class="px-3 mb-2 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-600 dark:bg-teal-500"></span>
            <p class="text-[10px] font-extrabold text-slate-400 dark:text-gray-400 uppercase tracking-widest">Manajemen Dinas</p>
        </div>

        <a href="{{ route('dinas.lowongan.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.lowongan.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-briefcase w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.lowongan.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Kelola Lowongan</span>
        </a>

        <a href="{{ route('dinas.pelamar') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.pelamar') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-envelope-open-text w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.pelamar') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Pelamar Masuk</span>
        </a>

        <a href="{{ route('dinas.peserta.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.peserta.index') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-user-check w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.peserta.index') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Monitoring Peserta</span>
        </a>

        <a href="{{ route('dinas.pembimbing_lapangan.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.pembimbing_lapangan.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-chalkboard-teacher w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.pembimbing_lapangan.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Data Pembimbing</span>
        </a>

        <a href="{{ route('dinas.laporan.hub') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.laporan.hub') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-chart-pie w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.laporan.hub') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Pusat Laporan</span>
        </a>

        <a href="{{ route('dinas.settings') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('dinas.settings') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-sliders-h w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('dinas.settings') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Pengaturan</span>
        </a>
    </div>
    @elseif(Auth::user()->role == 'pembimbing_lapangan')
    <div class="space-y-1.5">
        <div class="px-3 mb-2 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-600 dark:bg-teal-500"></span>
            <p class="text-[10px] font-extrabold text-slate-400 dark:text-gray-400 uppercase tracking-widest">Monitoring Lapangan</p>
        </div>

        <a href="{{ route('pembimbing_lapangan.logbook.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('pembimbing_lapangan.logbook*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-book-open w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('pembimbing_lapangan.logbook*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Validasi Logbook</span>
        </a>

        <a href="{{ route('pembimbing_lapangan.attendance.index') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-clipboard-list w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('pembimbing_lapangan.attendance.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Absensi Peserta</span>
        </a>
    </div>
    @elseif(Auth::user()->role == 'pembimbing')
    <div class="space-y-1.5">
        <div class="px-3 mb-2 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-600 dark:bg-teal-500"></span>
            <p class="text-[10px] font-extrabold text-slate-400 dark:text-gray-400 uppercase tracking-widest">Pembimbing Sekolah</p>
        </div>

        <a href="{{ route('pembimbing.dashboard') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('pembimbing.dashboard') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-user-graduate w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('pembimbing.dashboard') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Daftar Mahasiswa</span>
        </a>
    </div>
    @endif

    <!-- SECTION: PENGATURAN & SISTEM -->
    <div class="space-y-1.5 pt-3 border-t border-slate-200/80 dark:border-gray-700/50">
        <div class="px-3 mb-2 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
            <p class="text-[10px] font-extrabold text-slate-400 dark:text-gray-400 uppercase tracking-widest">Akun & Sistem</p>
        </div>

        <a href="{{ route('profile.edit') }}" 
           class="group relative flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200 {{ request()->routeIs('profile.*') ? 'bg-teal-50/90 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 shadow-2xs' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100/80 dark:hover:bg-gray-700 hover:text-teal-800 dark:hover:text-white' }}">
           <i class="fas fa-user-circle w-5 mr-3.5 text-center transition-transform group-hover:scale-110 {{ request()->routeIs('profile.*') ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-gray-500 group-hover:text-teal-700 dark:group-hover:text-white' }}"></i>
           <span>Pengaturan Profil</span>
        </a>

    </div>
</div>

<!-- 3. BOTTOM USER PROFILE & LOGOUT CARD -->
<div class="p-3.5 border-t border-slate-200/90 dark:border-gray-700/80 bg-slate-50/90 dark:bg-gray-900/80 flex-shrink-0">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-3 shadow-2xs border border-slate-200/80 dark:border-gray-700/80 hover:border-slate-300 dark:hover:border-teal-500 transition-all duration-300">
        <div class="flex items-center justify-between gap-2.5">
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
                <x-theme-toggle class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-300 hover:bg-slate-200 hover:text-slate-900 dark:hover:bg-gray-600 dark:hover:text-white transition-all duration-200 active:scale-95 shadow-2xs border border-slate-200 dark:border-gray-600" />
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
    </div>
</div>
