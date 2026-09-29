<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-magic text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Pendaftaran Penempatan Otomatis') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @include('peserta._back-link', ['href' => route('home'), 'label' => 'Kembali ke Halaman Utama'])

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- Kolom Kiri: Informasi Penempatan Otomatis --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-br from-teal-600 to-teal-800 dark:from-teal-800 dark:to-teal-950 p-6 text-white">
                            <span class="bg-teal-500/30 border border-teal-400/40 text-teal-100 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider inline-block mb-3">
                                Fitur Cerdas
                            </span>
                            <h2 class="font-black text-xl sm:text-2xl leading-snug">Jalur Distribusi Merata</h2>
                        </div>
                        <div class="p-6 space-y-5">
                            @php
                                // Full literal class strings (no runtime interpolation) so Tailwind JIT detects them.
                                $features = [
                                    [
                                        'icon' => 'fa-university',
                                        'box' => 'bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 border-teal-200 dark:border-teal-800/60',
                                        'title' => 'Sesuai Jurusan',
                                        'text' => 'Sistem hanya akan menempatkan Anda pada posisi yang sesuai dengan jurusan Anda saat ini: <strong class="text-gray-800 dark:text-gray-200">' . e(Auth::user()->major ?? '-') . '</strong>.',
                                    ],
                                    [
                                        'icon' => 'fa-balance-scale',
                                        'box' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-800/60',
                                        'title' => 'Beban Merata',
                                        'text' => 'Algoritma penyeimbangan akan memprioritaskan dinas/instansi yang memiliki jumlah peserta magang paling sedikit saat ini.',
                                    ],
                                    [
                                        'icon' => 'fa-check-double',
                                        'box' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800/60',
                                        'title' => 'Kapasitas Aman',
                                        'text' => 'Sistem akan secara cerdas memvalidasi bahwa instansi target memiliki sisa kuota yang cukup pada tanggal pelaksanaan pilihan Anda.',
                                    ],
                                ];
                            @endphp
                            @foreach($features as $feature)
                                <div class="feature-step-item flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-xl {{ $feature['box'] }} flex items-center justify-center shrink-0 border font-bold text-xs">
                                        <i class="fas {{ $feature['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">{{ $feature['title'] }}</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">{!! $feature['text'] !!}</p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="bg-teal-50/80 dark:bg-teal-950/40 border border-teal-200 dark:border-teal-900/60 rounded-2xl p-4 flex gap-3 items-start mt-4">
                                <i class="fas fa-info-circle text-teal-600 dark:text-teal-400 text-base mt-0.5"></i>
                                <p class="text-xs text-teal-800 dark:text-teal-300 leading-relaxed font-medium">
                                    Jalur ini membantu dinas yang sedang kekurangan tenaga magang dan menjamin peluang diterimanya pendaftaran Anda lebih besar karena didistribusikan ke dinas dengan kuota longgar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Form Pendaftaran --}}
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            <h3 class="font-bold text-gray-800 dark:text-gray-100 text-base flex items-center gap-2">
                                <i class="fas fa-file-signature text-teal-600 dark:text-teal-400"></i> Lengkapi Berkas Penempatan
                            </h3>
                        </div>

                        <div class="p-6 sm:p-8">
                            @if(session('error'))
                                <x-ui.alert type="error" class="mb-6">
                                    <p class="font-bold text-xs uppercase tracking-wider">Gagal Melakukan Pendaftaran</p>
                                    <p class="text-xs mt-1 font-medium">{{ session('error') }}</p>
                                </x-ui.alert>
                            @endif

                            <form action="{{ route('peserta.apply_automatic.store') }}" method="POST" enctype="multipart/form-data" id="applyForm">
                                @csrf

                                @include('peserta.apply._form-fields', ['withWaitingList' => false])

                                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                                    <a href="{{ route('home') }}" class="px-6 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-bold hover:bg-gray-50 dark:hover:bg-gray-900 transition text-xs uppercase tracking-wider">
                                        Batal
                                    </a>
                                    <button type="submit" id="submitBtn" class="px-8 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold shadow-md transition active:scale-95 text-xs uppercase tracking-wider flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="fas fa-paper-plane"></i> Kirim Lamaran
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    @vite('resources/js/peserta/apply-form.js')
    <script type="application/json" id="peserta-apply-config">
        {!! json_encode([
            'formId' => 'applyForm',
            'availabilityUrl' => null,
            'csrfToken' => csrf_token(),
            'enableAvailability' => false,
            'isWaitingListInput' => false,
        ]) !!}
    </script>
    @endpush
</x-app-layout>
