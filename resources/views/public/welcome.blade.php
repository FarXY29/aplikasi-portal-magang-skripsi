<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#0f766e">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Portal Magang">
    <link rel="apple-touch-icon" href="{{ asset('images/Banjarmasin_Logo.svg.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <title>SiMagang - Pemerintah Kota Banjarmasin</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome Icons & Google Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; 
            -webkit-tap-highlight-color: transparent;
        }
        h1, h2, h3, h4, .font-display {
            font-family: 'Outfit', sans-serif;
        }
        
        /* Premium Sasirangan Modern Background */
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
<body class="bg-slate-50 dark:bg-gray-900 text-slate-600 dark:text-slate-400 flex flex-col min-h-screen overflow-x-hidden antialiased transition-colors duration-300">

    @include('public.welcome._navbar')
    @include('public.welcome._hero')
    @include('public.welcome._stats')
    @include('public.welcome._lowongan-grid')
    @include('public.welcome._alur-magang')
    @include('public.welcome._faq')
    @include('public.welcome._footer')

</body>
</html>