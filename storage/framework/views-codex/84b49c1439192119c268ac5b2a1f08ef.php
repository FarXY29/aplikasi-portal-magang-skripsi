<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#0d9488">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Portal Magang">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/Banjarmasin_Logo.svg.png')); ?>">
    <link rel="manifest" href="<?php echo e(asset('manifest.json')); ?>">

    <title><?php echo e(config('app.name', 'Portal Magang')); ?></title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <style>
        [x-cloak] { display: none !important; }
        /* Sembunyikan scrollbar default tapi tetap bisa scroll */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
    </style>
    <?php echo $__env->yieldPushContent('head'); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Force HTTPS on public domains (e.g. Cloudflare Tunnels) for camera/location APIs
        const isLocal = ['localhost', '127.0.0.1', '::1'].includes(location.hostname) || 
                        !location.hostname.includes('.') ||
                        location.hostname.endsWith('.test') ||
                        location.hostname.endsWith('.dev') ||
                        location.hostname.endsWith('.local') ||
                        location.hostname.startsWith('192.168.') || 
                        location.hostname.startsWith('10.') || 
                        location.hostname.startsWith('172.');
        if (location.protocol !== 'https:' && !isLocal) {
            location.replace('https:' + location.href.substring(location.protocol.length));
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 dark:text-gray-100 transition-colors duration-300" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
    
    <div class="flex h-screen overflow-hidden">

        <!-- Backdrop untuk Mode Drawer (Tablet/Mobile < lg) -->
        <div x-show="sidebarOpen" x-cloak 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-gray-900/60 lg:hidden backdrop-blur-xs">
        </div>

        <!-- MAIN SIDEBAR (Desktop & Drawer Slide-Over) -->
        <aside x-cloak 
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-64 md:w-72 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700/80 shadow-2xl lg:shadow-none lg:static lg:inset-auto lg:translate-x-0 transition-all duration-300 transform h-full flex flex-col flex-shrink-0">
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </aside>

        <!-- MAIN CONTENT WRAPPER -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-gray-50 dark:bg-gray-900">
            
            <!-- DESKTOP & TABLET HEADER (md dan ke atas) -->
            <header class="hidden md:flex glass-panel border-b border-gray-200/50 dark:border-gray-700/50 min-h-[4rem] py-3 items-center justify-between px-6 lg:px-8 z-30 shadow-sm sticky top-0">
                
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <!-- Tombol Hamburger (Muncul pada tablet md ke lg untuk membuka drawer sidebar) -->
                    <button @click="sidebarOpen = true" class="p-2.5 -ml-2 text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-teal-50/80 dark:hover:bg-teal-900/30 rounded-xl focus:outline-none lg:hidden transition active:scale-95 flex-shrink-0" title="Buka Sidebar">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <div class="flex-1 min-w-0">
                        <?php if(isset($header)): ?>
                            <?php echo e($header); ?>

                        <?php else: ?>
                            <h2 class="font-black text-xl text-gray-800 dark:text-gray-200 dark:text-gray-100 leading-tight truncate">
                                Dashboard
                            </h2>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex items-center gap-3 print:hidden">
                    <div class="flex items-center gap-2 text-xs font-bold text-gray-600 dark:text-gray-400 dark:text-gray-300 bg-gray-100 dark:bg-gray-800/80 px-3.5 py-2 rounded-xl border border-gray-200 dark:border-gray-700/60 shadow-2xs">
                        <i class="far fa-calendar-alt text-teal-600 dark:text-teal-400"></i>
                        <span><?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?></span>
                    </div>
                    
                    <div id="realtime-clock" class="flex items-center gap-2 text-xs font-mono font-black text-teal-700 dark:text-teal-400 bg-teal-50 dark:bg-teal-900/30 px-3.5 py-2 rounded-xl border border-teal-200/80 dark:border-teal-800/80 shadow-2xs">
                        <i class="far fa-clock"></i>
                        <span id="clock-span">00:00:00</span>
                    </div>
                </div>
            </header>

            <!-- MOBILE NATIVE TOP BAR (Android & iOS < md) -->
            <header class="md:hidden sticky top-0 z-30 glass-panel border-b border-gray-200/50 dark:border-gray-700/50 px-4 py-3 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Tombol Hamburger di pojok kiri atas mobile -->
                    <button @click="sidebarOpen = true" class="p-2 -ml-1 text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 focus:outline-none active:scale-95 transition" title="Buka Sidebar">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-teal-600 text-white flex items-center justify-center p-1 shadow-xs flex-shrink-0">
                            <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'w-full h-full fill-current text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-full h-full fill-current text-white']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $attributes = $__attributesOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $component = $__componentOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__componentOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
                        </div>
                        <div class="min-w-0">
                            <h1 class="text-sm font-black text-gray-900 dark:text-gray-100 leading-none truncate">Portal<span class="text-teal-600 dark:text-teal-400">Magang</span></h1>
                            <span class="inline-block mt-0.5 px-1.5 py-0.5 text-[8px] font-black uppercase bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400 rounded border border-teal-200/60 dark:border-teal-800 tracking-wider">
                                <?php echo e(str_replace('_', ' ', Auth::user()->role)); ?>

                            </span>
                        </div>
                    </a>
                </div>

                <div class="flex items-center gap-2.5">
                    <div id="mobile-clock" class="text-[11px] font-mono font-black text-teal-700 dark:text-teal-400 bg-teal-50 dark:bg-teal-900/30 px-2.5 py-1.5 rounded-lg border border-teal-200/60 dark:border-teal-800/80 shadow-2xs">
                        <i class="far fa-clock mr-1 text-teal-600 dark:text-teal-400"></i><span id="mobile-clock-span">00:00:00</span>
                    </div>

                    <!-- Tombol Menu Cepat / Profil -->
                    <button @click="$dispatch('open-mobile-menu')" class="w-9 h-9 rounded-xl bg-gradient-to-tr from-teal-600 to-teal-500 text-white flex items-center justify-center font-black text-xs shadow-sm ring-2 ring-teal-100 active:scale-95 transition flex-shrink-0" title="Menu Navigasi Mobile">
                        <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                    </button>
                </div>
            </header>

            <!-- MAIN BODY SLOT -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900 custom-scrollbar p-4 pb-24 md:p-6 lg:p-8 md:pb-8">
                <?php echo e($slot); ?>

            </main>

        </div>
    </div>

    <?php echo $__env->make('layouts.partials._mobile-bottom-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->make('layouts.partials._mobile-sheet', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script src="//instant.page/5.2.0" type="module" integrity="sha384-jnZyxPjiipYXnSU0ygqeac2q7CVYMbh84q0uHVRRxEtvFPiQYbXWUorga2aqZJ0z"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const updateClock = () => {
                const clockSpan = document.getElementById('clock-span');
                const mobileClockSpan = document.getElementById('mobile-clock-span');
                if (clockSpan || mobileClockSpan) {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    const timeStr = `${hours}:${minutes}:${seconds}`;
                    if (clockSpan) clockSpan.textContent = timeStr;
                    if (mobileClockSpan) mobileClockSpan.textContent = timeStr;
                }
            };
            setInterval(updateClock, 1000);
            updateClock();
        });
        
        // Support Turbo page transitions
        document.addEventListener('turbo:load', () => {
            if (window.Alpine) {
                const bodyEl = document.querySelector('body');
                if (bodyEl && bodyEl.__x) {
                    bodyEl.__x.$data.sidebarOpen = false;
                }
            }
            const updateClock = () => {
                const clockSpan = document.getElementById('clock-span');
                const mobileClockSpan = document.getElementById('mobile-clock-span');
                if (clockSpan || mobileClockSpan) {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    const timeStr = `${hours}:${minutes}:${seconds}`;
                    if (clockSpan) clockSpan.textContent = timeStr;
                    if (mobileClockSpan) mobileClockSpan.textContent = timeStr;
                }
            };
            updateClock();
        });
    </script>
    <div id="global-image-modal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 p-4 opacity-0 pointer-events-none transition-opacity duration-300 backdrop-blur-sm" onclick="closeImageModal()">
        <div class="relative max-w-4xl max-h-[90vh] w-full flex justify-center items-center" onclick="event.stopPropagation()">
            <button onclick="closeImageModal()" class="absolute -top-4 -right-4 md:top-4 md:right-4 bg-white dark:bg-gray-800/10 hover:bg-white dark:hover:bg-gray-800/20 text-white rounded-full w-10 h-10 flex items-center justify-center focus:outline-none transition backdrop-blur-md border border-white/20 z-10">
                <i class="fas fa-times text-xl"></i>
            </button>
            <img id="global-image-modal-img" src="" class="max-w-full max-h-[85vh] object-contain rounded-xl shadow-2xl" alt="Preview Image">
        </div>
    </div>

    <script>
        function openImageModal(src) {
            const modal = document.getElementById('global-image-modal');
            const img = document.getElementById('global-image-modal-img');
            img.src = src;
            modal.classList.remove('opacity-0', 'pointer-events-none');
        }
        
        function closeImageModal() {
            const modal = document.getElementById('global-image-modal');
            modal.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => {
                document.getElementById('global-image-modal-img').src = '';
            }, 300);
        }
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
    <?php if (isset($component)) { $__componentOriginal88666171f4405d3a16fed3b8192db59d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal88666171f4405d3a16fed3b8192db59d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.confirm-dialog','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.confirm-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal88666171f4405d3a16fed3b8192db59d)): ?>
<?php $attributes = $__attributesOriginal88666171f4405d3a16fed3b8192db59d; ?>
<?php unset($__attributesOriginal88666171f4405d3a16fed3b8192db59d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal88666171f4405d3a16fed3b8192db59d)): ?>
<?php $component = $__componentOriginal88666171f4405d3a16fed3b8192db59d; ?>
<?php unset($__componentOriginal88666171f4405d3a16fed3b8192db59d); ?>
<?php endif; ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views/layouts/app.blade.php ENDPATH**/ ?>