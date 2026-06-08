<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-tasks text-teal-600"></i>
                {{ __('Validasi Logbook Peserta') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                Menunggu Validasi: <span class="font-bold text-yellow-600">{{ $logs->where('status_validasi', 'pending')->count() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center mb-6 print:hidden">
                <a href="{{ route('pembimbing_lapangan.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="h-16 w-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-2xl shadow-md border-2 border-white">
                    {{ strtoupper(substr($app->user->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $app->user->name }}</h1>
                    <p class="text-sm text-gray-500 flex items-center gap-2">
                        <span><i class="far fa-envelope text-gray-400"></i> {{ $app->user->email }}</span>
                        <span class="text-gray-300">|</span>
                        <span><i class="fas fa-briefcase text-gray-400"></i> {{ $app->position->judul_posisi }}</span>
                    </p>
                </div>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center p-4 text-green-800 rounded-xl bg-green-50 border border-green-100 shadow-sm relative">
                    <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 mr-3 text-green-600"></i>
                    <div class="text-sm font-bold">{{ session('success') }}</div>
                    <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <div class="space-y-6">
                @forelse($logs as $log)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300">
                    <div class="flex flex-col lg:flex-row">
                        
                        <div class="lg:w-1/4 bg-gray-50 p-6 flex flex-col items-center justify-center border-b lg:border-b-0 lg:border-r border-gray-100 text-center">
                            <div class="mb-4">
                                <span class="block text-3xl font-black text-gray-700">{{ \Carbon\Carbon::parse($log->tanggal)->format('d') }}</span>
                                <span class="block text-xs font-bold text-gray-500 uppercase tracking-widest">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('F Y') }}</span>
                                <span class="block text-[10px] text-gray-400 mt-1">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('l') }}</span>
                            </div>
                            
                            @if($log->bukti_foto_path)
                                <div class="relative group w-full h-32 rounded-xl overflow-hidden border border-gray-200 cursor-pointer shadow-sm" 
                                     onclick="openImageModal('{{ Storage::url($log->bukti_foto_path) }}')">
                                    <img src="{{ Storage::url($log->bukti_foto_path) }}" class="w-full h-full object-cover transition transform group-hover:scale-110 duration-500">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <span class="text-white text-xs font-bold flex items-center gap-1"><i class="fas fa-search-plus"></i> Zoom</span>
                                    </div>
                                </div>
                            @else
                                <div class="w-full h-32 bg-gray-200/50 rounded-xl flex flex-col items-center justify-center text-gray-400 text-xs border-2 border-dashed border-gray-300">
                                    <i class="far fa-image text-2xl mb-1"></i>
                                    <span>Tanpa Foto</span>
                                </div>
                            @endif
                        </div>

                        <div class="lg:w-2/4 p-6 flex flex-col">
                            <div class="flex justify-between items-start mb-3 border-b border-gray-50 pb-2">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wide">Uraian Kegiatan</h4>
                            </div>
                            
                            <div class="text-gray-800 text-sm leading-relaxed whitespace-pre-line flex-grow font-medium">
                                {{ $log->kegiatan }}
                            </div>

                            @if($log->komentar_pembimbing_lapangan)
                                <div class="mt-4 bg-yellow-50 p-3 rounded-xl border border-yellow-100 flex gap-3 items-start relative">
                                    <div class="absolute -top-1.5 left-4 w-3 h-3 bg-yellow-50 border-t border-l border-yellow-100 transform rotate-45"></div>
                                    <i class="fas fa-comment-dots text-yellow-500 mt-1"></i>
                                    <div>
                                        <span class="block text-[10px] font-bold text-yellow-700 uppercase mb-0.5">Catatan Anda Sebelumnya</span>
                                        <p class="text-xs text-yellow-800 italic">"{{ $log->komentar_pembimbing_lapangan }}"</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="lg:w-1/4 bg-white p-6 border-t lg:border-t-0 lg:border-l border-gray-100 flex flex-col justify-center">
                            
                            <div class="mb-5 text-center">
                                @php
                                    $statusConfig = [
                                        'disetujui' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200', 'icon' => 'fa-check-circle'],
                                        'revisi'    => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => 'fa-exclamation-circle'],
                                        'pending'   => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-200', 'icon' => 'fa-clock'],
                                    ];
                                    $s = $statusConfig[$log->status_validasi] ?? $statusConfig['pending'];
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase border {{ $s['bg'] }} {{ $s['text'] }} {{ $s['border'] }} inline-flex items-center gap-1.5">
                                    <i class="fas {{ $s['icon'] }}"></i> {{ $log->status_validasi }}
                                </span>
                            </div>

                            <form action="{{ route('pembimbing_lapangan.logbook.validasi', $log->id) }}" method="POST" class="space-y-3">
                                @csrf
                                
                                <div>
                                    <label class="sr-only">Komentar</label>
                                    <textarea name="komentar" rows="2" 
                                        class="w-full text-xs border-gray-200 rounded-xl focus:border-indigo-500 focus:ring focus:ring-indigo-100 bg-gray-50 placeholder-gray-400 resize-none"
                                        placeholder="Tulis catatan untuk peserta..."></textarea>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="submit" name="status" value="disetujui" 
                                        class="flex items-center justify-center w-full py-2 bg-green-600 text-white rounded-lg text-xs font-bold hover:bg-green-700 transition shadow-sm hover:shadow-md transform active:scale-95">
                                        <i class="fas fa-check mr-1.5"></i> Setuju
                                    </button>
                                    
                                    <button type="submit" name="status" value="revisi" 
                                        class="flex items-center justify-center w-full py-2 bg-white text-red-600 border border-red-200 rounded-lg text-xs font-bold hover:bg-red-50 transition shadow-sm hover:shadow-md transform active:scale-95">
                                        <i class="fas fa-undo mr-1.5"></i> Revisi
                                    </button>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-16 bg-white rounded-2xl border border-dashed border-gray-300 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300">
                        <i class="fas fa-book-open text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Logbook Kosong</h3>
                    <p class="text-sm text-gray-500">Mahasiswa ini belum mengunggah aktivitas apapun.</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>