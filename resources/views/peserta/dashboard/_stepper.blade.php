@php
    $profileComplete = !empty(Auth::user()->nik) && !empty(Auth::user()->asal_instansi);
    $hasApplications = ($myApplications ?? collect())->count() > 0;
    
    // Determine current active stage
    $currentStage = 1;
    if (!$profileComplete) {
        $currentStage = 1; // Lengkapi Profil
    } elseif ($profileComplete && !$hasApplications) {
        $currentStage = 2; // Cari & Lamar Lowongan
    } elseif ($hasApplications && !($activeApp ?? null)) {
        $currentStage = 3; // Menunggu Seleksi
    } elseif (($activeApp ?? null) && $activeApp->display_status === 'belum mulai') {
        $currentStage = 3; // Menunggu Mulai
    } elseif (($activeApp ?? null) && $activeApp->status?->value === 'diterima') {
        $currentStage = 4; // Aktif Magang
    } elseif (($activeApp ?? null) && $activeApp->status?->value === 'selesai' && empty($activeApp->saran_peserta)) {
        $currentStage = 5; // Beri Evaluasi / Saran
    } else {
        $currentStage = 6; // Selesai / Cetak Sertifikat
    }
    
    $stages = [
        1 => ['name' => 'Profil', 'desc' => 'Lengkapi NIK & Instansi', 'icon' => 'fa-user-edit'],
        2 => ['name' => 'Lamar', 'desc' => 'Pilih & Lamar Lowongan', 'icon' => 'fa-search'],
        3 => ['name' => 'Seleksi', 'desc' => 'Proses Peninjauan', 'icon' => 'fa-file-signature'],
        4 => ['name' => 'Magang', 'desc' => 'Absen & Logbook', 'icon' => 'fa-briefcase'],
        5 => ['name' => 'Evaluasi', 'desc' => 'Saran & Kuesioner', 'icon' => 'fa-comment-alt'],
        6 => ['name' => 'Sertifikat', 'desc' => 'Unduh Sertifikat', 'icon' => 'fa-award'],
    ];
@endphp

{{-- Horizontal Visual Progress Stepper --}}
<div class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl p-4 sm:p-5 md:p-6 shadow-2xs border border-slate-200/80 dark:border-gray-700 overflow-hidden mb-6">
    <h3 class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-4 sm:mb-6 flex items-center gap-2">
        <i class="fas fa-route text-teal-700 dark:text-teal-400"></i> Alur Perjalanan Magang Anda
    </h3>
    <div class="relative flex md:flex-row justify-start md:justify-between items-start md:items-center gap-y-3 sm:gap-y-6 gap-x-2 overflow-x-auto md:overflow-visible no-scrollbar pb-2 md:pb-0 -mx-1 px-1">
        @foreach($stages as $index => $stage)
            <div class="flex items-center gap-3 md:flex-col md:text-center md:flex-1 relative shrink-0 md:shrink">
                {{-- Connector Line Horizontal (md+) --}}
                @if($index < 6)
                    <div class="hidden md:block absolute top-5 left-1/2 right-[-50%] h-[3px] {{ $currentStage > $index ? 'bg-teal-600 dark:bg-teal-400' : 'bg-slate-200 dark:bg-gray-700' }} -z-0"></div>
                @endif

                {{-- Circle Badge --}}
                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 border-2 font-bold transition duration-300 relative z-10
                    {{ $currentStage > $index 
                        ? 'bg-teal-700 dark:bg-teal-500 border-teal-700 dark:border-teal-500 text-white shadow-2xs' 
                        : ($currentStage == $index 
                            ? 'bg-white dark:bg-gray-800 border-teal-700 text-teal-800 dark:text-teal-400 shadow-2xs ring-4 ring-teal-50 dark:ring-teal-950/40' 
                            : 'bg-white dark:bg-gray-800 border-slate-200 dark:border-gray-700 text-slate-400 dark:text-gray-500') }}">
                    @if($currentStage > $index)
                        <i class="fas fa-check text-xs"></i>
                    @else
                        <i class="fas {{ $stage['icon'] }} text-xs"></i>
                    @endif
                </div>

                {{-- Text --}}
                <div class="md:mt-2 max-w-[140px] sm:max-w-none md:max-w-[120px] md:mx-auto">
                    <h4 class="text-xs font-black {{ $currentStage == $index ? 'text-teal-800 dark:text-teal-300' : ($currentStage > $index ? 'text-slate-800 dark:text-gray-200' : 'text-slate-400 dark:text-gray-500') }}">{{ $stage['name'] }}</h4>
                    <p class="text-[11px] sm:text-[10px] text-slate-400 dark:text-gray-500 font-semibold mt-0.5 leading-tight">{{ $stage['desc'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
