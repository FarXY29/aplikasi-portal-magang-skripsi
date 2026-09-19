<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-slate-900 dark:text-gray-100 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200/80 dark:border-teal-800/60 shadow-2xs">
                    <i class="fas fa-columns text-teal-700 dark:text-teal-400 text-base"></i>
                </div>
                {{ __('Dashboard Peserta') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <x-ui.alert type="success" class="mb-4">
                    {{ session('success') }}
                </x-ui.alert>
            @endif

            @if(session('error'))
                <x-ui.alert type="error" class="mb-4">
                    {{ session('error') }}
                </x-ui.alert>
            @endif

            {{-- Horizontal Visual Progress Stepper --}}
            @include('peserta.dashboard._stepper')

            {{-- Notifikasi H-7 Magang Berakhir --}}
            @if(isset($daysRemaining) && $daysRemaining >= 0 && $daysRemaining <= 7)
                <div class="notification-banner bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/40 dark:to-indigo-950/40 border-l-4 border-blue-600 p-4 sm:p-5 md:p-6 rounded-r-2xl sm:rounded-r-3xl shadow-2xs border border-blue-200/80 dark:border-blue-900/60 flex gap-3 sm:gap-4 items-start relative overflow-hidden">
                    <div class="absolute right-0 top-0 translate-x-4 -translate-y-4 opacity-5 text-blue-600 pointer-events-none">
                        <i class="fas fa-stopwatch text-7xl sm:text-9xl"></i>
                    </div>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 flex items-center justify-center flex-shrink-0 shadow-inner border border-blue-200 dark:border-blue-800/60">
                        <i class="fas fa-exclamation-triangle text-base sm:text-lg"></i>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-xs font-extrabold text-blue-900 dark:text-blue-300 uppercase tracking-wider mb-1 flex items-center gap-2">
                            Peringatan Berakhirnya Magang
                            <span class="countdown-badge-pulse inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-700 text-white shadow-2xs">
                                <i class="fas fa-clock"></i>
                                {{ $daysRemaining == 0 ? 'HARI INI' : $daysRemaining . ' HARI LAGI' }}
                            </span>
                        </h4>
                        <p class="text-xs sm:text-sm text-blue-950 dark:text-blue-200 font-medium leading-relaxed">
                            Harap segera melengkapi semua logbook, absensi harian, dan memastikan penilaian dari pembimbing lapangan telah diselesaikan sebelum tanggal berakhir.
                        </p>
                    </div>
                </div>
            @endif

            @if(empty(Auth::user()->nik) || empty(Auth::user()->asal_instansi))
                <div class="bg-amber-50/80 dark:bg-amber-950/40 border-l-4 border-amber-500 p-4 sm:p-5 rounded-r-2xl shadow-2xs border border-amber-200/80 dark:border-amber-900/60">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-amber-600 dark:text-amber-400 text-lg mt-0.5"></i>
                        <div>
                            <h3 class="text-xs font-bold text-amber-800 dark:text-amber-300 uppercase tracking-wider">Profil Belum Lengkap</h3>
                            <div class="mt-1 sm:mt-0.5 text-xs text-amber-900 dark:text-amber-200 font-medium">
                                <span>Silakan lengkapi NIK dan Asal Instansi agar sertifikat dapat dicetak.</span> 
                                <a href="{{ route('profile.edit') }}" class="font-bold underline hover:text-amber-950 dark:hover:text-amber-100 mt-2 sm:mt-0 sm:ml-1 inline-block">Lengkapi Sekarang &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($activeApp && in_array($activeApp->status?->value, ['diterima', 'selesai']))
                @include('peserta.dashboard._welcome-banner')

                @include('peserta.dashboard._saran-card')

                {{-- Grid Dashboard Stats & Detail Magang --}}
                @if($stats)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
                        @include('peserta.dashboard._stats')
                        @include('peserta.dashboard._absensi-card')
                        @include('peserta.dashboard._logbook-card')
                    </div>
                @endif

                @include('peserta.dashboard._modal-izin')
            @else
                @include('peserta.dashboard._banner-penempatan-otomatis')
            @endif

            @include('peserta.dashboard._banner-unduh-dokumen')

            @include('peserta.dashboard._lamaran-list')

        </div>
    </div>

    @push('scripts')
    @vite('resources/js/peserta/dashboard.js')
    <script type="application/json" id="peserta-dashboard-config">
        {!! json_encode([
            'challengeUrl' => route('peserta.absensi.challenge'),
        ]) !!}
    </script>
    @endpush
</x-app-layout>
