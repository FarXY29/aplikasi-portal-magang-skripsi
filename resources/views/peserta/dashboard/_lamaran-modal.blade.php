{{--
    Detail modal for a single "lamaran" (application).
    Included by resources/views/peserta/dashboard/_lamaran-card.blade.php.

    Expects:
      - $app     : application model
      - $badges  : array map of display_status => badge classes
--}}
<x-ui.modal name="modal-lamaran-{{ $app->id }}" focusable>
    <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 text-slate-900 dark:text-gray-100">
        <div class="flex justify-between items-start mb-4 border-b border-slate-100 dark:border-gray-700 pb-3 gap-3">
            <h2 class="text-base font-bold text-slate-900 dark:text-gray-100 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-teal-700 dark:text-teal-400"></i> Detail Lamaran Magang
            </h2>
            <button x-on:click="$dispatch('close')" class="min-w-[44px] min-h-[44px] flex items-center justify-center text-slate-400 dark:text-gray-500 hover:text-slate-600 dark:hover:text-gray-300 bg-slate-100 dark:bg-gray-900 p-2 rounded-full transition shrink-0"><i class="fas fa-times"></i></button>
        </div>

        <div class="mb-6 bg-slate-50 dark:bg-gray-900 p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-gray-700">
            <h4 class="font-bold text-slate-900 dark:text-gray-100 text-base mb-1">{{ $app->position->instansi->nama_dinas }}</h4>
            <p class="text-xs text-teal-700 dark:text-teal-400 font-bold mb-4">{{ $app->position->judul_posisi }}</p>

            <div class="grid grid-cols-2 gap-3 sm:gap-4 text-xs">
                <div>
                    <p class="text-slate-500 dark:text-gray-400 mb-1 text-[10px] font-bold uppercase">No. Registrasi</p>
                    <p class="font-mono font-black text-teal-700 dark:text-teal-400">{{ $app->nomor_registrasi ?? ('REG-' . $app->id) }}</p>
                </div>
                <div>
                    <p class="text-slate-500 dark:text-gray-400 mb-1 text-[10px] font-bold uppercase">Status Akhir</p>
                    <p><span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $badges[$app->display_status] ?? 'bg-slate-200' }}">{{ $app->display_status }}</span></p>
                </div>
                <div>
                    <p class="text-slate-500 dark:text-gray-400 mb-1 text-[10px] font-bold uppercase">Tanggal Daftar</p>
                    <p class="font-bold text-slate-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($app->created_at)->translatedFormat('d M Y') }}</p>
                </div>
                @if(in_array($app->status?->value, ['diterima', 'selesai']) && $app->tanggal_mulai)
                    <div class="col-span-2">
                        <p class="text-slate-500 dark:text-gray-400 mb-1 text-[10px] font-bold uppercase">Periode Pelaksanaan</p>
                        <p class="font-bold text-slate-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d M Y') }} <span class="text-slate-400 mx-2">&rarr;</span> {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d M Y') }}</p>
                    </div>
                @endif
            </div>
        </div>

        @if(in_array($app->display_status, ['diterima', 'belum mulai', 'selesai']))
        @php
            // Bangun daftar dokumen sekali (menghindari 3 cabang duplikat).
            // locked => tampil sebagai tombol non-aktif dengan ikon gembok.
            $docBtn = 'min-h-[44px] justify-center px-3 py-2.5 rounded-xl text-xs font-bold shadow-2xs flex items-center justify-center gap-2';
            $docs = [];

            $docs[] = ['type' => 'link', 'route' => 'peserta.id_card.download', 'icon' => 'fa-id-card', 'label' => 'ID Card', 'class' => 'bg-emerald-700 text-white hover:bg-emerald-800 transition'];
            $docs[] = ['type' => 'link', 'route' => 'peserta.loa.download', 'icon' => 'fa-file-contract', 'label' => 'Surat Balasan', 'class' => 'bg-indigo-700 text-white hover:bg-indigo-800 transition'];

            if (in_array($app->display_status, ['diterima', 'selesai'])) {
                $docs[] = ['type' => 'link', 'route' => 'peserta.logbook.print', 'icon' => 'fa-file-pdf', 'label' => 'Rekap Logbook', 'class' => 'bg-slate-800 dark:bg-gray-700 text-white hover:bg-slate-900 dark:hover:bg-gray-600 transition'];
            }

            if ($app->display_status === 'selesai') {
                if (empty($app->saran_peserta)) {
                    $lockClass = 'bg-slate-200 dark:bg-gray-700 text-slate-500 dark:text-gray-400 cursor-not-allowed';
                    $lockTitle = 'Mohon isi saran dan evaluasi terlebih dahulu';
                    $docs[] = ['type' => 'locked', 'icon' => 'fa-lock', 'label' => 'Sertifikat (Kunci)', 'class' => $lockClass, 'title' => $lockTitle];
                    $docs[] = ['type' => 'locked', 'icon' => 'fa-lock', 'label' => 'Transkrip (Kunci)', 'class' => $lockClass, 'title' => $lockTitle];
                } else {
                    $docs[] = ['type' => 'link', 'route' => 'peserta.sertifikat', 'icon' => 'fa-certificate', 'label' => 'Sertifikat', 'class' => 'bg-teal-700 text-white hover:bg-teal-800 transition'];
                    $docs[] = ['type' => 'link', 'route' => 'peserta.download.nilai', 'icon' => 'fa-file-alt', 'label' => 'Transkrip', 'class' => 'bg-teal-700 text-white hover:bg-teal-800 transition'];
                }
            }
        @endphp
        <div class="mb-6 bg-white dark:bg-gray-800 border border-slate-200/80 dark:border-gray-700 rounded-2xl p-4 sm:p-5 shadow-2xs">
            <h3 class="font-bold text-slate-800 dark:text-gray-200 text-xs flex items-center gap-2 mb-3 pb-2 border-b border-slate-100 dark:border-gray-700">
                <i class="fas fa-download text-teal-700 dark:text-teal-400"></i> Unduh Dokumen
            </h3>
            <div class="grid grid-cols-2 gap-2 sm:gap-3">
                @foreach($docs as $doc)
                    @if($doc['type'] === 'link')
                        <a href="{{ route($doc['route'], $app->id) }}" target="_blank" class="{{ $docBtn }} {{ $doc['class'] }}">
                            <i class="fas {{ $doc['icon'] }}"></i> {{ $doc['label'] }}
                        </a>
                    @else
                        <button type="button" class="{{ $docBtn }} {{ $doc['class'] }}" title="{{ $doc['title'] }}" disabled aria-disabled="true">
                            <i class="fas {{ $doc['icon'] }}"></i> {{ $doc['label'] }}
                        </button>
                    @endif
                @endforeach
            </div>
            <p class="text-[11px] text-slate-400 dark:text-gray-500 font-medium mt-3 flex items-center gap-1.5">
                <i class="fas fa-info-circle"></i> Dokumen dapat diunduh kembali kapan saja dari menu ini.
            </p>
        </div>
        @endif

        @if($app->catatan_pembimbing_lapangan)
            <div class="bg-amber-50/60 dark:bg-amber-950/40 border border-amber-200/80 dark:border-amber-800/60 p-4 rounded-2xl shadow-2xs mb-6">
                <h3 class="font-bold text-amber-800 dark:text-amber-300 text-xs flex items-center gap-2 mb-2">
                    <i class="fas fa-lightbulb text-amber-600"></i> Pesan & Saran dari Pembimbing Lapangan
                </h3>
                <p class="text-xs text-amber-900 dark:text-amber-200 italic font-medium leading-relaxed">"{{ $app->catatan_pembimbing_lapangan }}"</p>
            </div>
        @endif

        @if($app->status?->value == 'selesai')
            <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-2xl shadow-2xs border border-slate-200/80 dark:border-gray-700">
                <h3 class="font-bold text-slate-800 dark:text-gray-200 text-xs flex items-center gap-2 mb-3 pb-2 border-b border-slate-100 dark:border-gray-700">
                    <i class="fas fa-star text-teal-600"></i> Evaluasi & Saran untuk Instansi
                </h3>
                @if($app->saran_peserta)
                    <div class="bg-slate-50 dark:bg-gray-900 p-4 rounded-xl border border-slate-200/80 dark:border-gray-700">
                        <p class="text-xs text-slate-700 dark:text-gray-300 italic font-medium">"{{ $app->saran_peserta }}"</p>
                        <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-200/80 dark:border-gray-700">
                            <i class="fas fa-check-circle text-emerald-600"></i>
                            <p class="text-xs text-slate-600 dark:text-gray-400 font-bold">Evaluasi telah dikirimkan secara anonim.</p>
                        </div>
                    </div>
                @else
                    {{-- Satu-satunya formulir editable ada di banner dashboard (#saran-evaluasi).
                         Di sini hanya menampilkan arahan, agar tidak ada dua form untuk aksi yang sama. --}}
                    <div class="bg-indigo-50/60 dark:bg-indigo-950/40 p-4 rounded-xl border border-indigo-200/80 dark:border-indigo-800/60">
                        <p class="text-xs text-indigo-800 dark:text-indigo-300 font-medium leading-relaxed">
                            <i class="fas fa-info-circle mr-1"></i>
                            Evaluasi &amp; saran belum diisi. Silakan isi melalui kartu <strong>Isi Evaluasi &amp; Saran Magang</strong> di halaman dashboard untuk membuka Sertifikat &amp; Transkrip.
                        </p>
                        <div class="flex justify-end mt-3">
                            <button type="button"
                                x-on:click.prevent="$dispatch('close'); document.getElementById('saran-evaluasi')?.scrollIntoView({ behavior: 'smooth', block: 'center' })"
                                class="min-h-[44px] px-5 py-2.5 bg-indigo-700 text-white rounded-xl font-bold hover:bg-indigo-800 shadow-2xs transition active:scale-95 text-xs uppercase tracking-wider flex items-center gap-2">
                                <i class="fas fa-arrow-up"></i> Isi Evaluasi di Dashboard
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="mt-6 flex justify-end">
            <button type="button" x-on:click.prevent="$dispatch('close')" class="min-h-[44px] px-5 py-2.5 bg-white dark:bg-gray-800 border border-slate-300 dark:border-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-gray-900 rounded-xl font-bold text-xs transition uppercase tracking-wider">Tutup</button>
        </div>
    </div>
</x-ui.modal>
