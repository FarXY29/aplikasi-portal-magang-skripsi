@props(['logs' => [], 'app' => null])

{{-- Detail Pane (Log Detail Tabs) --}}
<div class="md:col-span-8 col-span-1">
    @foreach($logs as $log)
    <div x-show="activeTab === {{ $log->id }}" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         role="tabpanel"
         style="display: none;">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
            
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl font-black text-gray-800 dark:text-gray-100">Detail Kegiatan Jurnal</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center font-bold">
                        <i class="far fa-calendar-alt mr-1.5 text-teal-600 dark:text-teal-400"></i> 
                        {{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                
                @php
                    $statusClass = match($log->status_validasi) {
                        'disetujui' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                        'revisi', 'ditolak' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
                        default => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60'
                    };
                    $statusIcon = match($log->status_validasi) {
                        'disetujui' => 'fa-check-circle',
                        'revisi' => 'fa-undo',
                        'ditolak' => 'fa-ban',
                        default => 'fa-clock'
                    };
                @endphp
                <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase border {{ $statusClass }} flex items-center gap-1.5">
                    <i class="fas {{ $statusIcon }}"></i> {{ ucfirst($log->status_validasi) }}
                </span>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
                <div class="flex flex-col lg:flex-row gap-6">
                    <div class="w-full lg:w-1/3 flex-shrink-0">
                        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Dokumentasi</h4>
                        @if($log->bukti_foto_path)
                            @php
                                $fotoUrl = route('storage.access', ['type' => 'logbook', 'filename' => basename($log->bukti_foto_path)]);
                                $fotoTitle = 'Dokumentasi Logbook - ' . \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d F Y') . ' (' . $app->user->name . ')';
                            @endphp
                            <div class="relative group rounded-2xl overflow-hidden shadow-xs border border-gray-200 dark:border-gray-700 cursor-pointer"
                                 data-image-modal
                                 data-modal-src="{{ $fotoUrl }}"
                                 data-modal-title="{{ $fotoTitle }}"
                                 role="button" tabindex="0"
                                 aria-label="Perbesar dokumentasi logbook">
                                <img src="{{ $fotoUrl }}" class="w-full h-48 object-cover transition transform group-hover:scale-105 duration-500" alt="Dokumentasi">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/25 transition flex items-center justify-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-black/60 text-white text-xs font-bold backdrop-blur-sm opacity-0 group-hover:opacity-100 transition duration-200 drop-shadow">
                                        <i class="fas fa-search-plus text-xs"></i> Perbesar Foto
                                    </span>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-2 text-center">*Klik gambar untuk melihat ukuran penuh</p>
                        @else
                            <div class="w-full h-44 bg-gray-50 dark:bg-gray-900 rounded-2xl flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 text-xs border-2 border-dashed border-gray-200 dark:border-gray-700">
                                <i class="far fa-image text-3xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                <span class="font-bold">Tidak ada foto bukti</span>
                            </div>
                        @endif
                    </div>

                    <div class="w-full lg:w-2/3">
                        <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Deskripsi Pekerjaan</h4>
                        <div class="p-5 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 text-xs sm:text-sm leading-relaxed whitespace-pre-line min-h-[11rem]">
                            {{ $log->kegiatan }}
                        </div>
                    </div>
                </div>

                @if($log->komentar_pembimbing_lapangan)
                    <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                        <div class="p-4 bg-blue-50/60 dark:bg-blue-950/40 rounded-2xl border border-blue-200 dark:border-blue-800/60 flex gap-3 items-start">
                            <i class="fas fa-comment-dots text-blue-600 dark:text-blue-400 mt-0.5 text-base flex-shrink-0"></i>
                            <div>
                                <span class="block text-xs font-bold text-blue-800 dark:text-blue-300 uppercase mb-1">Catatan Pembimbing Lapangan:</span>
                                <p class="text-xs sm:text-sm text-blue-900 dark:text-blue-200 italic">"{{ $log->komentar_pembimbing_lapangan }}"</p>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
    @endforeach
</div>
