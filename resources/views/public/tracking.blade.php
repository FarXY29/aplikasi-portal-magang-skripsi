<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#0f766e">
    <title>Lacak Status Permohonan Magang - SiMagang Kota Banjarmasin</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800|outfit:300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }
        body { 
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; 
            -webkit-tap-highlight-color: transparent;
        }
        h1, h2, h3, h4, .font-display {
            font-family: 'Outfit', sans-serif;
        }
        .bg-sasirangan-premium {
            background-color: #042f2e !important;
            background-image: 
                radial-gradient(circle at 80% 20%, rgba(20, 184, 166, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 15% 80%, rgba(16, 185, 129, 0.12) 0%, transparent 50%),
                linear-gradient(to bottom right, rgba(4, 47, 46, 0.95), rgba(6, 78, 59, 0.98)),
                url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2314b8a6' fill-opacity='0.05'%3E%3Cpath d='M40 38v-8h-4v8h-8v4h8v8h4v-8h8v-4h-8zm0-36V0h-4v2h-8v4h8v8h4V6h8V2h-8zM8 38v-8H4v8H0v4h4v8h4v-8h8v-4H8zM8 2V0H4v2H0v4h4v8h4V6h8V2H8z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") !important;
            background-size: 100% 100%, 100% 100%, cover, auto !important;
        }
    </style>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-slate-50 dark:bg-gray-900 text-slate-600 dark:text-slate-400 min-h-screen flex flex-col antialiased" 
      x-data="publicTrackingApp()" 
      x-init="initApp()">

    <!-- Header Navigation -->
    <header class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-slate-200/80 dark:border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group focus:outline-none" data-turbo="false">
                <div class="bg-teal-700 text-white rounded-xl p-2 shadow-2xs flex items-center justify-center">
                    <x-application-logo class="w-6 h-6 fill-current text-white" />
                </div>
                <div class="flex flex-col">
                    <span class="text-base font-black text-slate-900 dark:text-white uppercase leading-none font-display">SiMagang</span>
                    <span class="text-[9px] font-extrabold text-teal-600 dark:text-teal-400 uppercase tracking-widest mt-0.5">Kota Banjarmasin</span>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400 transition" data-turbo="false">
                    <i class="fas fa-arrow-left text-[10px]"></i> Beranda
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-teal-700 hover:bg-teal-800 text-white text-xs font-extrabold rounded-xl shadow-2xs transition flex items-center gap-1.5" data-turbo="false">
                        <i class="fas fa-columns"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-teal-700 hover:bg-teal-800 text-white text-xs font-extrabold rounded-xl shadow-2xs transition flex items-center gap-1.5" data-turbo="false">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </a>
                @endauth
                <x-theme-toggle class="p-2 text-slate-400 hover:text-teal-600 dark:text-gray-400 dark:hover:text-white rounded-xl bg-slate-100 dark:bg-gray-800 border border-slate-200/50 dark:border-gray-700/50" />
            </div>
        </div>
    </header>

    <!-- Hero & Search Section -->
    <section class="bg-sasirangan-premium text-white py-12 sm:py-16 relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black bg-teal-500/20 text-teal-300 border border-teal-400/30 mb-4">
                <i class="fas fa-radar text-xs"></i> Pelacakan Mandiri Publik
            </span>
            <h1 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight mb-3 font-display">
                Lacak Status Permohonan Magang
            </h1>
            <p class="text-xs sm:text-sm md:text-base text-teal-100/90 max-w-2xl mx-auto font-medium leading-relaxed mb-8">
                Ketahui status seleksi dan verifikasi berkas magang Anda secara langsung. Masukkan Nomor Registrasi atau Email pendaftaran Anda.
            </p>

            <!-- Search Form -->
            <form id="tracking-search-form" 
                  action="{{ route('tracking.search') }}" 
                  method="GET" 
                  data-turbo="false"
                  @submit.prevent="executeSearch()" 
                  class="max-w-2xl mx-auto">
                <div class="bg-white dark:bg-gray-800 p-2 sm:p-2.5 rounded-2xl sm:rounded-3xl shadow-2xl border border-white/20 flex flex-col sm:flex-row gap-2">
                    <div class="relative flex-1 flex items-center">
                        <i class="fas fa-search absolute left-4 text-slate-400 dark:text-gray-500 text-sm"></i>
                        <input type="text" 
                               name="keyword" 
                               x-model="keyword"
                               required 
                               placeholder="Contoh: REG-202608-XXXXX atau email@kampus.ac.id" 
                               class="w-full pl-11 pr-10 py-3 bg-transparent border-0 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-500 focus:ring-0 text-xs sm:text-sm font-semibold">
                        <button type="button" 
                                x-show="keyword.length > 0" 
                                @click="keyword = ''; results = []; searched = false; errorMsg = '';" 
                                class="absolute right-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1"
                                title="Hapus">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                    <button type="submit" 
                            :disabled="loading"
                            class="px-6 py-3.5 bg-teal-700 hover:bg-teal-800 disabled:opacity-75 disabled:cursor-not-allowed text-white rounded-xl sm:rounded-2xl font-black text-xs uppercase tracking-wider shadow-md transition active:scale-95 flex items-center justify-center gap-2 shrink-0">
                        <template x-if="loading">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-circle-notch fa-spin"></i>
                                <span>Mencari...</span>
                            </span>
                        </template>
                        <template x-if="!loading">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-search"></i>
                                <span>Lacak Sekarang</span>
                            </span>
                        </template>
                    </button>
                </div>
                
                <template x-if="errorMsg">
                    <p class="text-xs text-rose-300 font-bold mt-2 text-left px-2 flex items-center gap-1.5" x-text="errorMsg"></p>
                </template>
                @error('keyword')
                    <p class="text-xs text-rose-300 font-bold mt-2 text-left px-2 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </form>
        </div>
    </section>

    <!-- Result Section -->
    <main class="flex-1 max-w-5xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Loading Skeleton -->
        <div x-show="loading" x-cloak class="space-y-6">
            <div class="animate-pulse bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-gray-700 space-y-6">
                <div class="h-6 bg-slate-200 dark:bg-gray-700 rounded-full w-1/3"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="h-20 bg-slate-100 dark:bg-gray-700/50 rounded-2xl"></div>
                    <div class="h-20 bg-slate-100 dark:bg-gray-700/50 rounded-2xl"></div>
                </div>
                <div class="h-24 bg-slate-100 dark:bg-gray-700/50 rounded-2xl"></div>
            </div>
        </div>

        <!-- Result Container -->
        <div x-show="!loading && searched" x-cloak>
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 dark:border-gray-700 pb-4">
                <div>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-list-check text-teal-700 dark:text-teal-400"></i> Hasil Pelacakan Permohonan
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">
                        Menampilkan hasil pencarian untuk kata kunci: <strong class="text-slate-800 dark:text-gray-200" x-text="'&quot;' + keyword + '&quot;'"></strong>
                    </p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-700 self-start sm:self-auto"
                      x-text="'Ditemukan: ' + results.length + ' Lamaran'"></span>
            </div>

            <!-- List of Results -->
            <template x-for="app in results" :key="app.id">
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80 dark:border-gray-700 mb-8 space-y-6 transition hover:shadow-md">
                    <!-- Top Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-slate-100 dark:border-gray-700 pb-5">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-3 py-1 rounded-xl text-xs font-black bg-teal-50 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60 flex items-center gap-1.5 font-mono">
                                <i class="fas fa-barcode"></i> <span x-text="app.nomor_registrasi"></span>
                            </span>
                            <span :class="app.status_badge_class" class="px-3 py-1 rounded-xl text-xs font-black uppercase border flex items-center gap-1.5">
                                <i :class="'fas ' + app.status_icon"></i> <span x-text="app.status_label"></span>
                            </span>
                            <template x-if="app.is_automatic_placement">
                                <span class="px-2.5 py-1 rounded-xl text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/60">
                                    <i class="fas fa-magic text-[10px]"></i> Penempatan Otomatis
                                </span>
                            </template>
                        </div>
                        <span class="text-xs text-slate-400 dark:text-gray-500 font-semibold">
                            Tgl Pengajuan: <strong class="text-slate-700 dark:text-gray-300" x-text="app.tgl_daftar + ' WITA'"></strong>
                        </span>
                    </div>

                    <!-- Target & Applicant Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 dark:bg-gray-900/60 p-5 rounded-2xl border border-slate-200/70 dark:border-gray-700">
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-gray-500 mb-1">Instansi Tujuan</p>
                            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight" x-text="app.instansi"></h3>
                            <p class="text-xs font-bold text-teal-700 dark:text-teal-400 mt-1" x-text="'Posisi: ' + app.posisi"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-gray-500 mb-1">Data Pelamar (Tersensor)</p>
                            <h4 class="text-sm font-bold text-slate-800 dark:text-gray-200" x-text="app.nama_pemohon"></h4>
                            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5" x-text="app.asal_instansi + ' • ' + app.jurusan"></p>
                        </div>
                    </div>

                    <!-- Timeline Progress Stepper -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-gray-400 mb-4 flex items-center gap-1.5">
                            <i class="fas fa-route text-teal-700 dark:text-teal-400"></i> Tahapan Permohonan
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Stage 1 -->
                            <div class="p-4 rounded-2xl border transition bg-emerald-50/60 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-900/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs bg-emerald-600 text-white">
                                        <i class="fas fa-check text-xs"></i>
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-black text-emerald-900 dark:text-emerald-300">1. Pengajuan Berkas</h5>
                                        <p class="text-[11px] text-slate-500 dark:text-gray-400 font-medium">Terkirim ke sistem</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Stage 2 -->
                            <div class="p-4 rounded-2xl border transition"
                                 :class="app.status === 'ditolak' ? 'bg-rose-50/60 dark:bg-rose-950/20 border-rose-200 dark:border-rose-900/50' : (app.status === 'menunggu' ? 'bg-amber-50/60 dark:bg-amber-950/20 border-amber-200 dark:border-amber-900/50' : (app.status_step >= 2 ? 'bg-emerald-50/60 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-900/50' : 'bg-slate-50 dark:bg-gray-900 border-slate-200 dark:border-gray-800'))">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs"
                                         :class="app.status === 'ditolak' ? 'bg-rose-600 text-white' : (app.status === 'menunggu' ? 'bg-amber-600 text-white' : (app.status_step > 2 ? 'bg-emerald-600 text-white' : 'bg-teal-600 text-white'))">
                                        <template x-if="app.status === 'ditolak'">
                                            <i class="fas fa-times text-xs"></i>
                                        </template>
                                        <template x-if="app.status !== 'ditolak' && app.status_step > 2">
                                            <i class="fas fa-check text-xs"></i>
                                        </template>
                                        <template x-if="app.status !== 'ditolak' && app.status_step <= 2">
                                            <i class="fas fa-spinner fa-spin text-xs"></i>
                                        </template>
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-black text-slate-900 dark:text-gray-100">2. Verifikasi Instansi</h5>
                                        <p class="text-[11px] text-slate-500 dark:text-gray-400 font-medium"
                                           x-text="app.status === 'pending' ? 'Sedang Ditinjau' : (app.status === 'menunggu' ? 'Daftar Tunggu' : 'Selesai Diverifikasi')"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Stage 3 -->
                            <div class="p-4 rounded-2xl border transition"
                                 :class="app.status_step >= 3 ? (app.status === 'ditolak' ? 'bg-rose-50/60 dark:bg-rose-950/20 border-rose-200 dark:border-rose-900/50' : 'bg-emerald-50/60 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-900/50') : 'bg-slate-50 dark:bg-gray-900 border-slate-200 dark:border-gray-800'">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs"
                                         :class="app.status_step >= 3 ? (app.status === 'ditolak' ? 'bg-rose-600 text-white' : 'bg-emerald-600 text-white') : 'bg-slate-200 dark:bg-gray-800 text-slate-500'">
                                        <template x-if="app.status_step >= 3">
                                            <i :class="app.status === 'ditolak' ? 'fas fa-times' : 'fas fa-check'" class="text-xs"></i>
                                        </template>
                                        <template x-if="app.status_step < 3">
                                            <i class="fas fa-flag-checkered text-xs"></i>
                                        </template>
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-black text-slate-900 dark:text-gray-100">3. Keputusan Seleksi</h5>
                                        <p class="text-[11px] text-slate-500 dark:text-gray-400 font-medium" x-text="app.status_label"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Callout Info -->
                    <div :class="app.status_badge_class" class="p-4 sm:p-5 rounded-2xl border">
                        <div class="flex items-start gap-3">
                            <i :class="'fas ' + app.status_icon" class="text-lg mt-0.5 shrink-0"></i>
                            <div>
                                <h4 class="text-xs font-black uppercase tracking-wider mb-1" x-text="'Informasi Status: ' + app.status_label"></h4>
                                <p class="text-xs sm:text-sm font-medium leading-relaxed" x-text="app.status_desc"></p>
                                <template x-if="app.periode_mulai && app.periode_selesai">
                                    <p class="text-xs font-bold mt-2">
                                        <i class="far fa-calendar-alt mr-1"></i> Periode Magang: <span x-text="app.periode_mulai + ' s/d ' + app.periode_selesai"></span>
                                    </p>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap items-center justify-end gap-3 pt-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="min-h-[44px] px-5 py-2.5 bg-teal-700 hover:bg-teal-800 text-white rounded-xl text-xs font-bold shadow-2xs transition flex items-center gap-2" data-turbo="false">
                                <i class="fas fa-columns"></i> Masuk ke Dashboard Saya &rarr;
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="min-h-[44px] px-5 py-2.5 bg-teal-700 hover:bg-teal-800 text-white rounded-xl text-xs font-bold shadow-2xs transition flex items-center gap-2" data-turbo="false">
                                <i class="fas fa-sign-in-alt"></i> Masuk Akun untuk Cetak LoA & ID Card
                            </a>
                        @endauth
                    </div>
                </div>
            </template>

            <!-- Empty Results -->
            <div x-show="results.length === 0" class="bg-white dark:bg-gray-800 rounded-3xl p-8 sm:p-12 text-center border border-slate-200/80 dark:border-gray-700 shadow-2xs">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl mx-auto mb-4 border border-amber-200 dark:border-amber-800">
                    <i class="fas fa-search-minus"></i>
                </div>
                <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">Permohonan Tidak Ditemukan</h3>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-gray-400 max-w-md mx-auto mb-6">
                    Tidak ditemukan permohonan magang dengan kata kunci <strong class="text-slate-800 dark:text-gray-200" x-text="'&quot;' + keyword + '&quot;'"></strong>. Pastikan Nomor Registrasi atau Email pendaftaran Anda sudah benar.
                </p>
                <button type="button" @click="keyword = ''; results = []; searched = false;" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-slate-800 dark:text-white rounded-xl text-xs font-bold transition">
                    <i class="fas fa-arrow-left"></i> Coba Kata Kunci Lain
                </button>
            </div>
        </div>

        <!-- Initial Information State -->
        <div x-show="!loading && !searched" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-slate-200/80 dark:border-gray-700 shadow-2xs text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-400 flex items-center justify-center text-xl mx-auto">
                    <i class="fas fa-barcode"></i>
                </div>
                <h3 class="text-sm font-black text-slate-900 dark:text-white">Nomor Registrasi Unik</h3>
                <p class="text-xs text-slate-500 dark:text-gray-400 leading-relaxed font-medium">
                    Gunakan kode registrasi unik (format: <code>REG-YYYYMM-XXXXX</code>) yang tertera saat pendaftaran permohonan magang.
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-slate-200/80 dark:border-gray-700 shadow-2xs text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 flex items-center justify-center text-xl mx-auto">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="text-sm font-black text-slate-900 dark:text-white">Privasi Terjaga</h3>
                <p class="text-xs text-slate-500 dark:text-gray-400 leading-relaxed font-medium">
                    Data identitas pribadi Anda disamarkan secara otomatis di portal publik untuk menjamin keamanan dan privasi.
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-slate-200/80 dark:border-gray-700 shadow-2xs text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 flex items-center justify-center text-xl mx-auto">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3 class="text-sm font-black text-slate-900 dark:text-white">Informasi Real-Time</h3>
                <p class="text-xs text-slate-500 dark:text-gray-400 leading-relaxed font-medium">
                    Status verifikasi berkas dan penerimaan diperbarui secara langsung saat Admin Instansi memproses lamaran Anda.
                </p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-900 border-t border-slate-200/80 dark:border-gray-800 py-6 text-center text-xs text-slate-400 dark:text-gray-500">
        <p>&copy; {{ date('Y') }} Pemerintah Kota Banjarmasin &bull; Portal Magang & Riset Terpadu</p>
    </footer>

    <script>
    function publicTrackingApp() {
        return {
            keyword: @json($keyword ?? request('keyword', '')),
            loading: false,
            searched: @json($searched ?? false),
            results: @json($formattedApplications ?? []),
            errorMsg: '',

            initApp() {
                const urlParams = new URLSearchParams(window.location.search);
                const queryKeyword = urlParams.get('keyword');
                if (queryKeyword && queryKeyword.trim().length >= 2 && !this.searched) {
                    this.keyword = queryKeyword.trim();
                    this.executeSearch();
                }
            },

            async executeSearch() {
                const cleanKeyword = this.keyword ? this.keyword.trim() : '';
                if (cleanKeyword.length < 2) {
                    this.errorMsg = 'Silakan masukkan minimal 2 karakter Nomor Registrasi atau Email pendaftaran.';
                    return;
                }

                this.errorMsg = '';
                this.loading = true;

                try {
                    const searchUrl = '{{ route('tracking.search') }}?keyword=' + encodeURIComponent(cleanKeyword);
                    const response = await fetch(searchUrl, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        const errJson = await response.json().catch(() => null);
                        this.errorMsg = errJson?.message || 'Terjadi kesalahan saat memproses pelacakan. Silakan coba lagi.';
                        this.loading = false;
                        return;
                    }

                    const json = await response.json();
                    if (json && json.success) {
                        this.results = json.data || [];
                        this.searched = true;
                        
                        // Update URL tanpa reload
                        const newUrl = window.location.pathname + '?keyword=' + encodeURIComponent(cleanKeyword);
                        window.history.pushState({ keyword: cleanKeyword }, '', newUrl);
                    } else {
                        this.errorMsg = json.message || 'Gagal memuat status lamaran.';
                    }
                } catch (err) {
                    // Fallback to standard form submission if fetch/network fails
                    const form = document.getElementById('tracking-search-form');
                    if (form) {
                        form.submit();
                        return;
                    }
                    this.errorMsg = 'Koneksi jaringan terputus. Silakan coba beberapa saat lagi.';
                } finally {
                    this.loading = false;
                }
            }
        };
    }
    </script>
</body>
</html>
