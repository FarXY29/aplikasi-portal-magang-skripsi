<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-journal-whills text-teal-600"></i>
                {{ __('Detail Logbook Peserta') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                Total Aktivitas: <span class="font-bold text-teal-600">{{ $logs->count() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center mb-6 print:hidden">
                <a href="{{ route('dinas.peserta.index') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Daftar Peserta
                </a>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-full bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center text-white font-black text-xl shadow-md border-2 border-white">
                        {{ strtoupper(substr($app->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $app->user->name }}</h3>
                        <p class="text-sm text-gray-500 flex items-center gap-1">
                            <i class="fas fa-briefcase text-gray-400 text-xs"></i> {{ $app->position->judul_posisi }}
                        </p>
                    </div>
                </div>
                
                <div class="flex gap-4 text-center">
                    <div class="px-4 py-2 bg-green-50 rounded-xl border border-green-100">
                        <p class="text-xs font-bold text-green-600 uppercase">Disetujui</p>
                        <p class="text-xl font-black text-green-700">{{ $logs->where('status_validasi', 'disetujui')->count() }}</p>
                    </div>
                    <div class="px-4 py-2 bg-yellow-50 rounded-xl border border-yellow-100">
                        <p class="text-xs font-bold text-yellow-600 uppercase">Pending</p>
                        <p class="text-xl font-black text-yellow-700">{{ $logs->where('status_validasi', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>



            <div class="space-y-6">
                @forelse($logs as $log)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300">
                    <div class="flex flex-col lg:flex-row">
                        
                        <div class="lg:w-1/4 bg-gray-50 p-6 flex flex-col items-center justify-center border-b lg:border-b-0 lg:border-r border-gray-100 text-center">
                            <div class="mb-3">
                                <span class="block text-2xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($log->tanggal)->format('d') }}</span>
                                <span class="block text-xs font-bold text-gray-500 uppercase">{{ \Carbon\Carbon::parse($log->tanggal)->format('M Y') }}</span>
                            </div>
                            
                            @if($log->bukti_foto_path)
                                <div class="relative group w-full h-32 rounded-lg overflow-hidden border border-gray-200 cursor-pointer shadow-sm" onclick="openImageModal('{{ Storage::url($log->bukti_foto_path) }}')">
                                    <img src="{{ Storage::url($log->bukti_foto_path) }}" class="w-full h-full object-cover transition transform group-hover:scale-110 duration-500">
                                    <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <i class="fas fa-search-plus text-white text-xl drop-shadow-lg"></i>
                                    </div>
                                </div>
                            @else
                                <div class="w-full h-32 bg-gray-100 rounded-lg flex flex-col items-center justify-center text-gray-400 text-xs border border-dashed border-gray-300">
                                    <i class="fas fa-image text-2xl mb-1"></i>
                                    <span>No Image</span>
                                </div>
                            @endif
                        </div>

                        <div class="lg:w-2/4 p-6 flex flex-col">
                            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-2 border-b border-gray-100 pb-2">Aktivitas Harian</h4>
                            <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-line flex-grow">
                                {{ $log->kegiatan }}
                            </div>

                            @if($log->komentar_pembimbing_lapangan)
                                <div class="mt-4 bg-indigo-50 p-3 rounded-xl border border-indigo-100 flex gap-3 items-start">
                                    <i class="fas fa-comment-dots text-indigo-400 mt-1"></i>
                                    <div>
                                        <span class="block text-xs font-bold text-indigo-700 mb-0.5">Catatan Pembimbing Lapangan</span>
                                        <p class="text-xs text-indigo-600 italic">"{{ $log->komentar_pembimbing_lapangan }}"</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="lg:w-1/4 bg-gray-50 p-6 flex flex-col justify-center border-t lg:border-t-0 lg:border-l border-gray-100">
                            
                            <div class="mb-4 text-center">
                                @php
                                    $statusStyles = [
                                        'disetujui' => 'bg-green-100 text-green-700 border-green-200',
                                        'revisi'    => 'bg-red-100 text-red-700 border-red-200',
                                        'pending'   => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    ];
                                    $style = $statusStyles[$log->status_validasi] ?? 'bg-gray-100';
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase border {{ $style }}">
                                    {{ $log->status_validasi }}
                                </span>
                            </div>

                            @if($log->status_validasi == 'pending')
                                <form action="{{ route('dinas.logbook.validasi', $log->id) }}" method="POST" class="space-y-3">
                                    @csrf
                                    
                                    <input type="text" name="komentar" placeholder="Catatan (Opsional)" 
                                        class="w-full text-xs border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500">
                                    
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="submit" name="status" value="disetujui" class="bg-green-600 text-white py-2 rounded-lg text-xs font-bold hover:bg-green-700 shadow-sm transition flex items-center justify-center gap-1">
                                            <i class="fas fa-check"></i> Terima
                                        </button>
                                        <button type="submit" name="status" value="revisi" class="bg-red-500 text-white py-2 rounded-lg text-xs font-bold hover:bg-red-600 shadow-sm transition flex items-center justify-center gap-1">
                                            <i class="fas fa-times"></i> Revisi
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="text-center">
                                    <p class="text-xs text-gray-400">Divalidasi oleh Pembimbing Lapangan</p>
                                    <div class="w-8 h-1 bg-gray-200 rounded-full mx-auto mt-2"></div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                @empty
                <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-gray-300">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                        <i class="fas fa-book-open text-3xl"></i>
                    </div>
                    <h3 class="text-gray-800 font-bold">Logbook Kosong</h3>
                    <p class="text-gray-500 text-sm mt-1">Peserta belum mengunggah aktivitas apapun.</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>