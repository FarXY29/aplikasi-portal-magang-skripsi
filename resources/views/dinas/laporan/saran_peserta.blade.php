<x-app-layout>
    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-6 print:hidden">
            <a href="{{ route('dinas.laporan.hub') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                </div>
                Kembali ke Pusat Laporan
                </a>
                <a href="{{ route('dinas.laporan.saran_peserta.print') }}" target="_blank" class="bg-white border border-gray-200 hover:border-teal-500 text-gray-700 hover:text-teal-600 font-bold py-2 px-4 rounded-full shadow-sm flex items-center gap-2 transition text-sm">
                    <i class="fas fa-print text-teal-500"></i> Cetak Laporan PDF
                </a>
            </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">Kotak Saran Peserta (Anonim)</h2>
                    <p class="text-sm text-gray-500 mt-1">Ulasan, kritik, dan saran yang diberikan oleh peserta setelah lulus magang.</p>
                </div>
                <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                    Total Masukan: <span class="font-bold text-teal-600">{{ $sarans->count() }}</span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($sarans as $saran)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col relative overflow-hidden group hover:shadow-md transition">
                    <!-- Decor -->
                    <div class="absolute top-0 right-0 w-20 h-20 bg-teal-50 rounded-bl-full -mr-4 -mt-4 opacity-50 group-hover:scale-110 transition-transform"></div>
                    
                    <div class="flex items-start gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                            <i class="fas fa-user-secret"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 text-sm">Peserta Anonim</h3>
                            <p class="text-xs text-gray-500 font-medium flex items-center gap-1 mt-0.5">
                                <i class="fas fa-briefcase text-[10px] text-teal-500"></i> {{ Str::limit($saran->position->judul_posisi, 25) }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex-1 relative">
                        <i class="fas fa-quote-left absolute text-gray-100 text-3xl -top-2 -left-2 z-0"></i>
                        <p class="text-sm text-gray-600 italic relative z-10 pl-4 leading-relaxed">
                            "{{ $saran->saran_peserta }}"
                        </p>
                    </div>
                    
                    <div class="mt-5 pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-400">
                        <span><i class="far fa-clock mr-1"></i> {{ $saran->updated_at->diffForHumans() }}</span>
                        <span>Diterima pada: {{ $saran->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
                @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-white rounded-2xl p-12 text-center border border-dashed border-gray-300">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                        <i class="fas fa-comment-slash text-3xl"></i>
                    </div>
                    <h3 class="text-gray-800 font-bold">Belum Ada Masukan</h3>
                    <p class="text-gray-500 text-sm mt-1">Belum ada peserta yang lulus dan memberikan saran/evaluasi.</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
