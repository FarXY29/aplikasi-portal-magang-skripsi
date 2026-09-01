<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dynamic QR Presensi Kantor - {{ $instansi->nama_dinas }}</title>

    <!-- Tailwind CSS & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                            950: '#042f2e',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700;800&display=swap');
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        .qr-card-glow {
            box-shadow: 0 0 50px -10px rgba(20, 184, 166, 0.25);
        }
        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(1000%); }
        }
        .scan-line {
            animation: scanline 3s linear infinite;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between selection:bg-teal-500 selection:text-white antialiased overflow-x-hidden">

    <!-- Top Header Bar -->
    <header class="w-full bg-slate-900/80 backdrop-blur-md border-b border-slate-800 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-teal-500/10 border border-teal-500/30 flex items-center justify-center text-teal-400 text-2xl shadow-inner">
                    <i class="fas fa-building-columns"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-full bg-teal-500/20 text-teal-300 border border-teal-500/30">
                            Layar Presensi Kantor
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-emerald-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            Live Active
                        </span>
                    </div>
                    <h1 class="text-lg md:text-xl font-extrabold text-white tracking-tight mt-0.5">
                        {{ $instansi->nama_dinas }}
                    </h1>
                </div>
            </div>

            <!-- Controls (Fullscreen & Back) -->
            <div class="flex items-center gap-3">
                @auth
                    @if(auth()->user()->role === 'admin_instansi' || auth()->user()->role === 'pembimbing_lapangan')
                        <a href="{{ route('dinas.dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-bold transition flex items-center gap-2 border border-slate-700">
                            <i class="fas fa-arrow-left"></i> Dashboard
                        </a>
                    @endif
                @endauth
                <button onclick="toggleFullScreen()" id="btn-fullscreen" class="px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-teal-900/30">
                    <i class="fas fa-expand" id="icon-fullscreen"></i> <span id="text-fullscreen">Layar Penuh</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-6 py-8 flex flex-col lg:flex-row items-center justify-center gap-8 lg:gap-16">
        
        <!-- Left Side: Digital Clock, Date & Office Schedule -->
        <div class="flex-1 w-full max-w-lg text-center lg:text-left space-y-6">
            
            <!-- Live Clock -->
            <div class="bg-slate-900/60 rounded-3xl p-6 border border-slate-800/80 shadow-2xl relative overflow-hidden backdrop-blur-sm">
                <div class="absolute top-0 right-0 w-32 h-32 bg-teal-500/5 rounded-full blur-3xl -z-0"></div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1" id="live-date">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </p>
                <div class="text-5xl sm:text-6xl md:text-7xl font-black font-mono tracking-tight text-white flex items-center justify-center lg:justify-start gap-1">
                    <span id="live-hours">00</span>
                    <span class="text-teal-400 animate-pulse">:</span>
                    <span id="live-minutes">00</span>
                    <span class="text-teal-400 animate-pulse">:</span>
                    <span id="live-seconds" class="text-teal-400 text-4xl sm:text-5xl">00</span>
                    <span class="text-xs font-bold text-slate-400 ml-2 self-end mb-2">WIB</span>
                </div>
            </div>

            <!-- Instructions Card -->
            <div class="space-y-4">
                <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-900/40 border border-slate-800/60">
                    <div class="w-10 h-10 rounded-xl bg-teal-500/10 text-teal-400 flex items-center justify-center text-lg shrink-0 border border-teal-500/20">
                        <i class="fas fa-mobile-screen-button"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-200">1. Buka Portal Peserta</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Login ke akun magang Anda melalui browser HP dan buka menu <strong>Dashboard Peserta</strong>.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-900/40 border border-slate-800/60">
                    <div class="w-10 h-10 rounded-xl bg-teal-500/10 text-teal-400 flex items-center justify-center text-lg shrink-0 border border-teal-500/20">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-200">2. Pindai Kode QR</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Klik tombol <strong>Absen Datang / Pulang</strong> untuk membuka kamera dan scan kode dinamis di layar ini.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-900/40 border border-slate-800/60">
                    <div class="w-10 h-10 rounded-xl bg-teal-500/10 text-teal-400 flex items-center justify-center text-lg shrink-0 border border-teal-500/20">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-200">3. Kehadiran Tervalidasi</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Sistem memvalidasi koordinat GPS kantor & token keamanan secara instan dalam 1 klik.</p>
                    </div>
                </div>
            </div>

            <!-- Schedule Badges -->
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3.5 rounded-2xl bg-slate-900/60 border border-slate-800 text-center">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Absen Masuk Buka</span>
                    <span class="text-sm font-extrabold text-teal-400 font-mono mt-0.5 block">
                        {{ \Carbon\Carbon::parse($instansi->jam_mulai_masuk ?? '07:30')->format('H:i') }} WIB
                    </span>
                </div>
                <div class="p-3.5 rounded-2xl bg-slate-900/60 border border-slate-800 text-center">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Absen Pulang Buka</span>
                    <span class="text-sm font-extrabold text-amber-400 font-mono mt-0.5 block">
                        {{ \Carbon\Carbon::parse($instansi->jam_mulai_pulang ?? '16:00')->format('H:i') }} WIB
                    </span>
                </div>
            </div>

        </div>

        <!-- Right Side: Dynamic Rolling QR Card -->
        <div class="flex-1 w-full max-w-md flex flex-col items-center">
            <div class="w-full bg-slate-900 rounded-3xl p-6 sm:p-8 border border-teal-500/30 qr-card-glow text-center relative overflow-hidden">
                
                <!-- Anti-Screenshot Dynamic Rotating Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-950/80 border border-teal-500/40 text-teal-300 text-xs font-extrabold tracking-wide mb-5">
                    <i class="fas fa-arrows-rotate animate-spin text-[10px]"></i>
                    <span>Dynamic QR Presensi Kantor</span>
                </div>

                <!-- QR Container Box -->
                <div class="relative bg-white p-4 sm:p-5 rounded-2xl shadow-inner mx-auto w-fit max-w-full flex items-center justify-center min-h-[300px] sm:min-h-[340px] overflow-hidden">
                    <div id="qr-svg-container" class="transition-opacity duration-300 flex items-center justify-center">
                        {!! $qrSvg !!}
                    </div>
                </div>

                <!-- 30-Second Countdown Progress Bar -->
                <div class="mt-6 space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-300 px-1">
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-stopwatch text-teal-400"></i> Rotasi Kode Baru
                        </span>
                        <span class="font-mono text-teal-300 text-sm">
                            <span id="countdown-sec">{{ $tokenData['remaining_seconds'] }}</span>s
                        </span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-slate-800 rounded-full h-2.5 overflow-hidden p-0.5 border border-slate-700">
                        <div id="progress-bar" class="bg-gradient-to-r from-teal-500 to-emerald-400 h-full rounded-full transition-all duration-1000 ease-linear" style="width: {{ ($tokenData['remaining_seconds'] / 30) * 100 }}%;"></div>
                    </div>
                </div>

                <p class="text-[11px] text-slate-400 font-medium mt-4">
                    Kode berputar otomatis setiap 30 detik untuk menjamin validitas kehadiran fisik.
                </p>

            </div>
        </div>

    </main>

    <!-- Bottom Footer Status -->
    <footer class="w-full bg-slate-900/60 border-t border-slate-800/80 px-6 py-3 text-center">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-400 font-medium">
            <span>&copy; {{ date('Y') }} Portal Magang & Praktik Kerja — Validasi Token Kehadiran Fisik</span>
            <span class="flex items-center gap-2">
                <i class="fas fa-location-dot text-rose-400"></i> Radius Geofence: <strong>{{ $instansi->radius_absen ?? 100 }} meter</strong>
            </span>
        </div>
    </footer>

    <!-- Interactive Client Scripts -->
    <script>
        const KIOSK_TOKEN = "{{ $instansi->kiosk_token ?? '' }}";
        const FETCH_URL = KIOSK_TOKEN 
            ? "{{ route('kiosk.live_qr', ':token') }}".replace(':token', KIOSK_TOKEN)
            : "{{ route('dinas.kiosk.live_qr.auth', [], false) }}";

        let remainingSeconds = {{ $tokenData['remaining_seconds'] }};
        const TOTAL_INTERVAL = 30;
        let isFetching = false;

        // 1. Live Digital Clock
        function updateLiveClock() {
            const now = new Date();
            const pad = (n) => String(n).padStart(2, '0');
            
            const hoursEl = document.getElementById('live-hours');
            const minutesEl = document.getElementById('live-minutes');
            const secondsEl = document.getElementById('live-seconds');

            if (hoursEl) hoursEl.innerText = pad(now.getHours());
            if (minutesEl) minutesEl.innerText = pad(now.getMinutes());
            if (secondsEl) secondsEl.innerText = pad(now.getSeconds());
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        // 2. Countdown and Auto-Refresh
        function updateCountdownUI() {
            const countEl = document.getElementById('countdown-sec');
            const progressEl = document.getElementById('progress-bar');
            
            if (countEl) countEl.innerText = remainingSeconds;
            if (progressEl) {
                const percentage = Math.max(0, Math.min(100, (remainingSeconds / TOTAL_INTERVAL) * 100));
                progressEl.style.width = percentage + '%';
            }
        }

        async function fetchNewQr() {
            if (isFetching) return;
            isFetching = true;

            try {
                const res = await fetch(FETCH_URL, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (!res.ok) throw new Error('Fetch failed ' + res.status);
                const data = await res.json();

                if (data.success && data.svg) {
                    const container = document.getElementById('qr-svg-container');
                    if (container) {
                        container.style.opacity = '0';
                        setTimeout(() => {
                            container.innerHTML = data.svg;
                            container.style.opacity = '1';
                        }, 150);
                    }
                    remainingSeconds = data.remaining_seconds || TOTAL_INTERVAL;
                } else {
                    remainingSeconds = 5; // retry cepat bila gagal
                }
            } catch (err) {
                console.warn('Gagal memuat QR baru:', err);
                remainingSeconds = 5;
            } finally {
                isFetching = false;
                updateCountdownUI();
            }
        }

        setInterval(() => {
            remainingSeconds--;
            if (remainingSeconds <= 0) {
                fetchNewQr();
            } else {
                updateCountdownUI();
            }
        }, 1000);

        // 3. Fullscreen Controller
        function toggleFullScreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().then(() => {
                    document.getElementById('text-fullscreen').innerText = 'Keluar Layar Penuh';
                    document.getElementById('icon-fullscreen').className = 'fas fa-compress';
                }).catch(err => {
                    console.log('Fullscreen error:', err);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen().then(() => {
                        document.getElementById('text-fullscreen').innerText = 'Layar Penuh';
                        document.getElementById('icon-fullscreen').className = 'fas fa-expand';
                    });
                }
            }
        }

        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement) {
                document.getElementById('text-fullscreen').innerText = 'Layar Penuh';
                document.getElementById('icon-fullscreen').className = 'fas fa-expand';
            }
        });
    </script>
</body>
</html>

