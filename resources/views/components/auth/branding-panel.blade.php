@props([
    'title' => 'SiMagang',
    'subtitle' => null,
    'backLabel' => 'Kembali ke Beranda',
    'backRoute' => 'home',
    'icon' => 'fa-solid fa-hands-helping',
    'copyright' => 'Diskominfotik Kota Banjarmasin',
])

<div {{ $attributes->merge(['class' => 'auth-branding relative flex w-full flex-col justify-between overflow-hidden rounded-2xl border border-teal-500/30 p-6 sm:p-8 md:p-6 lg:p-8 shadow-2xl min-h-[260px] md:min-h-[520px] sm:rounded-3xl']) }}>
    {{-- Decorative Background Light Accents --}}
    <div class="pointer-events-none absolute -top-16 -right-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-20 -left-20 h-72 w-72 rounded-full bg-teal-950/40 blur-3xl"></div>
    <div class="pointer-events-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-80 w-80 rounded-full bg-emerald-400/10 blur-2xl"></div>

    {{-- Back Link Button --}}
    <div class="relative z-10">
        <a href="{{ route($backRoute) }}" class="group inline-flex items-center text-xs sm:text-sm font-bold text-teal-100/90 transition hover:text-white">
            <span class="mr-2.5 flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full border border-white/20 bg-white/10 backdrop-blur-md shadow-sm transition-all duration-300 group-hover:scale-110 group-hover:bg-white/20 group-hover:border-white/40">
                <i class="fa-solid fa-arrow-left text-xs text-white"></i>
            </span>
            <span>{{ $backLabel }}</span>
        </a>
    </div>

    {{-- Hero Main Branding Content --}}
    <div class="relative z-10 my-6 md:my-0 text-center md:text-left">
        <div class="mx-auto mb-4 flex h-16 w-16 sm:h-20 sm:w-20 items-center justify-center rounded-2xl border border-white/25 bg-white/15 p-3 shadow-2xl backdrop-blur-md transition-transform duration-300 hover:rotate-3 md:mx-0">
            <x-application-logo class="h-10 w-10 sm:h-12 sm:w-12 fill-current text-white drop-shadow-md" />
        </div>
        
        <div class="inline-flex items-center gap-1.5 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[11px] font-extrabold uppercase tracking-widest text-teal-100 backdrop-blur-md mb-2">
            <span class="h-2 w-2 rounded-full bg-emerald-300 animate-pulse"></span>
            PEMKO BANJARMASIN
        </div>

        <h1 class="font-display text-2xl font-black tracking-tight text-white drop-shadow-sm sm:text-3xl lg:text-4xl">
            {{ $title }}
        </h1>
        
        @if ($subtitle)
            <p class="mt-3 text-xs sm:text-sm font-medium leading-relaxed text-teal-100/90 max-w-md">
                {{ $subtitle }}
            </p>
        @endif

        {{-- Feature Highlights Badges (Desktop View) --}}
        <div class="mt-6 hidden md:flex flex-wrap gap-2 text-xs font-semibold text-teal-50">
            <span class="inline-flex items-center gap-1.5 rounded-xl border border-white/15 bg-white/10 px-3 py-1.5 backdrop-blur-sm">
                <i class="fas fa-location-dot text-emerald-300"></i> Presensi GPS Radius
            </span>
            <span class="inline-flex items-center gap-1.5 rounded-xl border border-white/15 bg-white/10 px-3 py-1.5 backdrop-blur-sm">
                <i class="fas fa-book-open text-emerald-300"></i> Logbook Digital
            </span>
            <span class="inline-flex items-center gap-1.5 rounded-xl border border-white/15 bg-white/10 px-3 py-1.5 backdrop-blur-sm">
                <i class="fas fa-certificate text-emerald-300"></i> Sertifikat QR Resmi
            </span>
        </div>
    </div>

    {{-- Footer Copyright --}}
    <div class="relative z-10 hidden md:block border-t border-white/10 pt-4">
        <p class="text-[11px] font-medium text-teal-200/70">
            &copy; {{ date('Y') }} {{ $copyright }}. Hak Cipta Dilindungi.
        </p>
    </div>

    {{ $slot ?? '' }}
</div>
