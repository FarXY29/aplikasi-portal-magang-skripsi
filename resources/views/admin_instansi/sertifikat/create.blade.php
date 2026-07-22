<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-certificate text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Penerbitan Sertifikat Kelulusan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div>
                <a href="{{ route('dinas.peserta.index') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Daftar Peserta
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Profil Peserta --}}
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 p-6 flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                        <div class="flex-shrink-0">
                            @if($app->user->profile_photo_path)
                                <img src="{{ Storage::url($app->user->profile_photo_path) }}" class="w-24 h-24 rounded-full object-cover border-4 border-teal-50 dark:border-teal-950/60 shadow-xs">
                            @else
                                <div class="w-24 h-24 rounded-full bg-teal-50 dark:bg-teal-950/60 flex items-center justify-center text-teal-600 dark:text-teal-300 text-3xl font-black border-4 border-teal-100 dark:border-teal-900/60 shadow-xs">
                                    {{ strtoupper(substr($app->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 truncate">{{ $app->user->name }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 truncate">{{ $app->user->email }}</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-800/60">
                                {{ $app->position->judul_posisi }}
                            </span>
                            <div class="mt-4 text-xs text-gray-600 dark:text-gray-400 font-semibold flex items-center justify-center sm:justify-start gap-2">
                                <i class="far fa-calendar-alt text-teal-500"></i>
                                {{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d M Y') }} — 
                                {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d M Y') }}
                            </div>
                        </div>
                    </div>

                    {{-- Verifikasi Nilai --}}
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-teal-50/50 dark:bg-teal-950/30 flex justify-between items-center">
                            <h4 class="font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2 text-sm">
                                <i class="fas fa-clipboard-check text-teal-600 dark:text-teal-400"></i> Verifikasi Nilai Akhir
                            </h4>
                            <div class="text-2xl font-black text-teal-600 dark:text-teal-400">
                                {{ round($app->nilai_rata_rata ?? $app->avg_nilai ?? 0, 1) }}
                                <span class="text-xs font-medium text-gray-400 dark:text-gray-500">/100</span>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs font-medium">
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-2xl border border-gray-100 dark:border-gray-700/60 flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Kerajinan</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $app->nilai_kerajinan ?? '-' }}</span>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-2xl border border-gray-100 dark:border-gray-700/60 flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Disiplin</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $app->nilai_disiplin ?? '-' }}</span>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-2xl border border-gray-100 dark:border-gray-700/60 flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Adaptasi</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $app->nilai_adaptasi ?? '-' }}</span>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-2xl border border-gray-100 dark:border-gray-700/60 flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Kreatifitas</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $app->nilai_kreatifitas ?? '-' }}</span>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-2xl border border-gray-100 dark:border-gray-700/60 flex justify-between col-span-1 md:col-span-2">
                                    <span class="text-gray-600 dark:text-gray-400">Skill dan Pengetahuan</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $app->nilai_skill_pengetahuan ?? '-' }}</span>
                                </div>
                            </div>
                            
                            <div class="p-4 bg-amber-50 dark:bg-amber-950/40 rounded-2xl border border-amber-200 dark:border-amber-900/60 text-xs text-amber-800 dark:text-amber-300 flex items-start gap-2.5">
                                <i class="fas fa-info-circle text-amber-600 dark:text-amber-400 mt-0.5 text-sm"></i>
                                <p class="leading-relaxed">Pastikan semua nilai di atas sudah benar sebelum menerbitkan sertifikat. Sertifikat yang sudah diterbitkan akan menggunakan nilai ini secara permanen.</p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Form Legalisasi Sertifikat --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-8">
                        <div class="bg-gradient-to-r from-teal-600 to-teal-700 p-5 text-white">
                            <h3 class="font-bold text-base flex items-center gap-2">
                                <i class="fas fa-file-signature"></i> Legalisasi Sertifikat
                            </h3>
                            <p class="text-teal-100 text-xs mt-1">Isi nomor registrasi dan tanggal penerbitan.</p>
                        </div>
                        
                        <div class="p-6">
                            <form action="{{ route('dinas.sertifikat.store', $app->id) }}" method="POST" target="_blank" class="space-y-5">
                                @csrf
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Nomor Sertifikat</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                            <i class="fas fa-barcode text-sm"></i>
                                        </div>
                                        <input type="text" name="nomor_sertifikat" value="{{ old('nomor_sertifikat', $app->nomor_sertifikat ?? $autoNumber) }}" required
                                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-teal-500 focus:border-teal-500 text-xs font-bold"
                                            placeholder="Contoh: 001/MAGANG/DINAS/2026">
                                    </div>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">*Nomor ini akan tercetak secara sah di sertifikat.</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Tanggal Terbit</label>
                                    <input type="date" name="tanggal_sertifikat" value="{{ old('tanggal_sertifikat', date('Y-m-d')) }}" required
                                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-teal-500 focus:border-teal-500 text-xs font-bold [color-scheme:dark]">
                                </div>

                                <hr class="border-gray-100 dark:border-gray-700">

                                <x-primary-button class="w-full justify-center py-3 text-xs">
                                    <i class="fas fa-file-pdf mr-2 text-sm"></i> Simpan & Generate PDF
                                </x-primary-button>
                                
                                <p class="text-center text-[11px] text-gray-400 dark:text-gray-500">
                                    File PDF akan otomatis terbuka di tab baru.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>