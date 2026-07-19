<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Penerbitan Sertifikat Kelulusan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('dinas.peserta.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-teal-600 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Peserta
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-start gap-6">
                        <div class="flex-shrink-0">
                            @if($app->user->profile_photo_path)
                                <img src="{{ Storage::url($app->user->profile_photo_path) }}" class="w-24 h-24 rounded-full object-cover border-4 border-teal-50">
                            @else
                                <div class="w-24 h-24 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 text-3xl font-bold border-4 border-teal-50">
                                    {{ substr($app->user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $app->user->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ $app->user->email }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $app->position->judul_posisi }}
                            </span>
                            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                                <i class="far fa-calendar-alt mr-2 text-teal-500"></i>
                                {{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d M Y') }} - 
                                {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d M Y') }}
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-4 border-b border-gray-50 bg-teal-50/30 flex justify-between items-center">
                            <h4 class="font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <i class="fas fa-clipboard-check text-teal-600"></i> Verifikasi Nilai Akhir
                            </h4>
                            <div class="text-2xl font-black text-teal-600">
                                {{ $app->nilai_rata_rata }}
                                <span class="text-xs font-medium text-gray-400">/100</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-lg flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Kerajinan</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $app->nilai_kerajinan }}</span>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-lg flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Disiplin</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $app->nilai_disiplin }}</span>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-lg flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Adaptasi</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $app->nilai_adaptasi }}</span>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-lg flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Kreatifitas</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $app->nilai_kreatifitas }}</span>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-lg flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Skill dan Pengetahuan</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $app->nilai_skill_pengetahuan }}</span>
                                </div>
                            </div>
                            
                            <div class="mt-4 p-4 bg-yellow-50 rounded-xl border border-yellow-100 text-xs text-yellow-800 flex gap-2">
                                <i class="fas fa-info-circle mt-0.5"></i>
                                <p>Pastikan semua nilai di atas sudah benar sebelum menerbitkan sertifikat. Sertifikat yang sudah diterbitkan tidak dapat diubah nilainya.</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-teal-100 sticky top-8">
                        <div class="bg-teal-600 p-5 rounded-t-2xl text-white">
                            <h3 class="font-bold text-lg">Penerbitan Sertifikat</h3>
                            <p class="text-teal-100 text-xs mt-1">Isi data legalitas sertifikat di bawah ini.</p>
                        </div>
                        
                        <div class="p-6">
                            <form action="{{ route('dinas.sertifikat.store', $app->id) }}" method="POST" target="_blank">
                                @csrf
                                
                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Nomor Sertifikat</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-barcode text-gray-400"></i>
                                        </div>
                                        <input type="text" name="certificate_number" value="{{ old('certificate_number', $app->certificate_number ?? $autoNumber) }}" required
                                            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-teal-500 focus:border-teal-500 text-sm font-medium"
                                            placeholder="Contoh: 001/SRT/2024">
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-1">*Nomor ini akan tercetak di sertifikat.</p>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Tanggal Terbit</label>
                                    <input type="date" name="certificate_date" value="{{ old('certificate_date', date('Y-m-d')) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-teal-500 focus:border-teal-500 text-sm">
                                </div>

                                <hr class="border-gray-100 dark:border-gray-700 mb-6">

                                <button type="submit" class="w-full group bg-teal-600 hover:bg-teal-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-teal-200 transition transform active:scale-95 flex items-center justify-center gap-2">
                                    <i class="fas fa-file-pdf text-lg group-hover:animate-bounce"></i>
                                    <span>Simpan & Generate PDF</span>
                                </button>
                                
                                <p class="text-center text-xs text-gray-400 mt-4">
                                    File PDF akan otomatis terunduh / terbuka di tab baru.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>