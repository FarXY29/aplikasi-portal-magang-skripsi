<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-journal-whills text-teal-600 dark:text-teal-400"></i>
                {{ __('Detail Logbook Peserta') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400 font-medium bg-white dark:bg-gray-800 px-4 py-1.5 rounded-full shadow-sm border border-gray-100 dark:border-gray-700">
                Total Aktivitas: <span class="font-bold text-teal-600 dark:text-teal-400">{{ $logs->count() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center mb-6 print:hidden">
                <a href="{{ route('dinas.peserta.index') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-sm">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Daftar Peserta
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-full bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center text-white font-black text-xl shadow-md border-2 border-white dark:border-gray-700">
                        {{ strtoupper(substr($app->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $app->user->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1.5 mt-0.5">
                            <i class="fas fa-briefcase text-gray-400 dark:text-gray-500 text-xs"></i> {{ $app->position->judul_posisi }}
                        </p>
                    </div>
                </div>
                
                <div class="flex gap-4 text-center">
                    <div class="px-4 py-2 bg-green-50 dark:bg-green-950/60 rounded-xl border border-green-100 dark:border-green-800/60">
                        <p class="text-xs font-bold text-green-700 dark:text-green-300 uppercase">Disetujui</p>
                        <p class="text-xl font-black text-green-700 dark:text-green-400">{{ $logs->where('status_validasi', 'disetujui')->count() }}</p>
                    </div>
                    <div class="px-4 py-2 bg-amber-50 dark:bg-amber-950/60 rounded-xl border border-amber-100 dark:border-amber-800/60">
                        <p class="text-xs font-bold text-amber-700 dark:text-amber-300 uppercase">Pending</p>
                        <p class="text-xl font-black text-amber-700 dark:text-amber-400">{{ $logs->where('status_validasi', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                @forelse($logs as $log)
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition duration-300">
                    <div class="flex flex-col lg:flex-row">
                        
                        <div class="lg:w-1/4 bg-gray-50 dark:bg-gray-900 p-6 flex flex-col items-center justify-center border-b lg:border-b-0 lg:border-r border-gray-100 dark:border-gray-700 text-center">
                            <div class="mb-3">
                                <span class="block text-2xl font-black text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($log->tanggal)->format('d') }}</span>
                                <span class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">{{ \Carbon\Carbon::parse($log->tanggal)->format('M Y') }}</span>
                            </div>
                            
                            @if($log->bukti_foto_path)
                                <div class="relative group w-full h-32 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 cursor-pointer shadow-sm" onclick="window.open('{{ route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]) }}', '_blank')">
                                    <img src="{{ route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]) }}" class="w-full h-full object-cover transition transform group-hover:scale-110 duration-500">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <i class="fas fa-search-plus text-white text-xl drop-shadow-lg"></i>
                                    </div>
                                </div>
                            @else
                                <div class="w-full h-32 bg-gray-100 dark:bg-gray-800 rounded-xl flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 text-xs border border-dashed border-gray-300 dark:border-gray-700">
                                    <i class="fas fa-image text-2xl mb-1"></i>
                                    <span>No Image</span>
                                </div>
                            @endif
                        </div>

                        <div class="lg:w-2/4 p-6 flex flex-col">
                            <h4 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 border-b border-gray-100 dark:border-gray-700 pb-2">Aktivitas Harian</h4>
                            <div class="text-gray-800 dark:text-gray-200 text-sm leading-relaxed whitespace-pre-line flex-grow">
                                {{ $log->kegiatan }}
                            </div>

                            @if($log->komentar_pembimbing_lapangan)
                                <div class="mt-4 bg-indigo-50/70 dark:bg-indigo-950/40 p-3.5 rounded-2xl border border-indigo-100 dark:border-indigo-900/50 flex gap-3 items-start">
                                    <i class="fas fa-comment-dots text-indigo-500 dark:text-indigo-400 mt-1"></i>
                                    <div>
                                        <span class="block text-xs font-bold text-indigo-900 dark:text-indigo-300 mb-0.5">Catatan Pembimbing Lapangan</span>
                                        <p class="text-xs text-indigo-700 dark:text-indigo-300 italic">"{{ $log->komentar_pembimbing_lapangan }}"</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="lg:w-1/4 bg-gray-50 dark:bg-gray-900 p-6 flex flex-col justify-center border-t lg:border-t-0 lg:border-l border-gray-100 dark:border-gray-700">
                            
                            <div class="mb-4 text-center">
                                @php
                                    $statusStyles = [
                                        'disetujui' => 'bg-green-100 dark:bg-green-950/60 text-green-700 dark:text-green-300 border-green-200 dark:border-green-800',
                                        'revisi'    => 'bg-red-100 dark:bg-red-950/60 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
                                        'pending'   => 'bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                    ];
                                    $style = $statusStyles[$log->status_validasi] ?? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300';
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase border {{ $style }}">
                                    {{ $log->status_validasi }}
                                </span>
                            </div>

                            @if($log->status_validasi == 'pending')
                                <form action="{{ route('dinas.logbook.validasi', $log->id) }}" method="POST" class="space-y-3">
                                    @csrf
                                    
                                    <input type="text" name="komentar" placeholder="Catatan (Opsional)" 
                                        class="w-full text-xs border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-xl focus:ring-teal-500 focus:border-teal-500 p-2.5">
                                    
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="submit" name="status" value="disetujui" class="bg-teal-600 dark:bg-teal-500 text-white py-2 rounded-xl text-xs font-bold hover:bg-teal-700 dark:hover:bg-teal-600 shadow-xs transition flex items-center justify-center gap-1">
                                            <i class="fas fa-check"></i> Terima
                                        </button>
                                        <button type="submit" name="status" value="revisi" class="bg-red-600 text-white py-2 rounded-xl text-xs font-bold hover:bg-red-700 shadow-xs transition flex items-center justify-center gap-1">
                                            <i class="fas fa-times"></i> Revisi
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="text-center">
                                    <p class="text-xs text-gray-400 dark:text-gray-500 font-medium">Divalidasi oleh Pembimbing Lapangan</p>
                                    <div class="w-8 h-1 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mt-2"></div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                @empty
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-12 text-center border border-dashed border-gray-300 dark:border-gray-700">
                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 dark:text-gray-600 border border-gray-200 dark:border-gray-700">
                        <i class="fas fa-book-open text-3xl"></i>
                    </div>
                    <h3 class="text-gray-800 dark:text-gray-200 font-bold">Logbook Kosong</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Peserta belum mengunggah aktivitas apapun.</p>
                </div>
                @endforelse
            </div>
            <div class="mt-6">
                {{ $logs->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
