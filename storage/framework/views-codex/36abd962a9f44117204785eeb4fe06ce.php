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
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased bg-gray-100 dark:bg-gray-800 dark:bg-gray-900 transition-colors duration-300">
        
        <div class="min-h-screen flex flex-col justify-center items-center p-2 sm:p-4">
            
            <div class="w-full">
                <?php echo e($slot); ?>

            </div>

        </div>
        <script src="//instant.page/5.2.0" type="module" integrity="sha384-jnZyxPjiipYXnSU0ygqeac2q7CVYMbh84q0uHVRRxEtvFPiQYbXWUorga2aqZJ0z"></script>
        <?php echo $__env->yieldPushContent('scripts'); ?>
    </body>
</html><?php /**PATH C:\EnvKit\projects\aplikasi-magang\aplikasi-magang\resources\views\layouts\guest.blade.php ENDPATH**/ ?>