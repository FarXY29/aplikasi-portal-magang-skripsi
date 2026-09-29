<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-file-signature text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Formulir Lamaran Magang') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @include('peserta._back-link', ['href' => route('home'), 'label' => 'Kembali ke Daftar Lowongan'])

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- Left Sidebar Detail Lowongan --}}
                <div class="lg:col-span-1 space-y-6 form-sticky-sidebar">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-br from-teal-600 to-teal-800 dark:from-teal-800 dark:to-teal-950 p-6 text-white">
                            <h3 class="font-bold text-sm leading-tight text-teal-100 uppercase tracking-wider">{{ $position->instansi->nama_dinas }}</h3>
                            <h2 class="font-black text-xl sm:text-2xl mt-1 leading-snug">{{ $position->judul_posisi }}</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 rounded-xl text-xs font-bold border border-teal-200 dark:border-teal-800/60 flex items-center">
                                    <i class="fas fa-graduation-cap mr-1.5"></i> {{ $position->required_major }}
                                </span>
                                <span class="px-3 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 rounded-xl text-xs font-bold border border-blue-200 dark:border-blue-800/60 flex items-center">
                                    <i class="fas fa-users mr-1.5"></i> Kuota: {{ $position->kuota }}
                                </span>
                            </div>

                            <div>
                                <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 pb-2 border-b border-gray-100 dark:border-gray-700">Detail Pekerjaan</h4>
                                <div class="prose prose-sm text-gray-600 dark:text-gray-300 text-xs sm:text-sm leading-relaxed whitespace-pre-line">
                                    {!! \App\Services\HtmlSanitizer::clean($position->deskripsi) !!}
                                </div>
                            </div>

                            <div class="bg-amber-50/80 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/60 rounded-2xl p-4 flex gap-3 items-start">
                                <i class="fas fa-lightbulb text-amber-500 text-lg mt-0.5"></i>
                                <p class="text-xs text-amber-800 dark:text-amber-300 font-medium leading-relaxed">
                                    Pastikan tanggal magang yang Anda ajukan sesuai dengan ketentuan kampus dan ketersediaan kuota instansi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Form Container --}}
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            <h3 class="font-bold text-gray-800 dark:text-gray-100 text-base flex items-center gap-2">
                                <i class="fas fa-pen-alt text-teal-600 dark:text-teal-400"></i> Lengkapi Data Lamaran
                            </h3>
                        </div>

                        <div class="p-6 sm:p-8">
                            @if(session('error'))
                                <x-ui.alert type="error" class="mb-6">
                                    <p class="font-bold text-xs uppercase tracking-wider">Gagal Mengirim Lamaran</p>
                                    <p class="text-xs mt-1 font-medium">{{ session('error') }}</p>
                                </x-ui.alert>
                            @endif

                            <form action="{{ route('peserta.daftar', $position->id) }}" method="POST" enctype="multipart/form-data" id="applyForm">
                                @csrf

                                @include('peserta.apply._form-fields', ['withWaitingList' => true])

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
            'availabilityUrl' => route('magang.check.availability', $position->id),
            'csrfToken' => csrf_token(),
            'enableAvailability' => true,
            'isWaitingListInput' => true,
        ]) !!}
    </script>
    @endpush
</x-app-layout>
