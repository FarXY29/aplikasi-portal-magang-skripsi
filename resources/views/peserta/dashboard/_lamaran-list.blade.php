            <div class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-2xs border border-slate-200/80 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-slate-200/80 dark:border-gray-700 bg-slate-50/80 dark:bg-gray-900 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h3 class="text-base font-bold text-slate-900 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-briefcase text-teal-700 dark:text-teal-400"></i> Riwayat Lamaran
                    </h3>
                    
                    {{-- Form Filter Riwayat --}}
                    <form action="{{ route('peserta.dashboard') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2 sm:gap-3">
                        {{-- Filter Status --}}
                        <div class="w-full sm:w-auto">
                            <select name="status" onchange="this.form.submit()" class="w-full sm:w-auto min-h-[44px] text-xs rounded-xl border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100 focus:border-teal-600 focus:ring-teal-600 py-2 pl-3 pr-8 cursor-pointer shadow-2xs font-bold [color-scheme:dark]">
                                <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Daftar Tunggu</option>
                                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        
                        <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-start">
                            {{-- Filter Tanggal Awal --}}
                            <div class="flex items-center gap-1.5 flex-1 sm:flex-initial">
                                <span class="text-[11px] sm:text-[10px] font-bold text-slate-500 dark:text-gray-400 uppercase">Dari:</span>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()" 
                                       class="w-full sm:w-auto min-h-[44px] text-xs rounded-xl border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100 focus:border-teal-600 focus:ring-teal-600 py-2 sm:py-1.5 px-2.5 cursor-pointer shadow-2xs font-bold [color-scheme:dark]">
                            </div>

                            {{-- Filter Tanggal Akhir --}}
                            <div class="flex items-center gap-1.5 flex-1 sm:flex-initial">
                                <span class="text-[11px] sm:text-[10px] font-bold text-slate-500 dark:text-gray-400 uppercase">S/D:</span>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()" 
                                       class="w-full sm:w-auto min-h-[44px] text-xs rounded-xl border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100 focus:border-teal-600 focus:ring-teal-600 py-2 sm:py-1.5 px-2.5 cursor-pointer shadow-2xs font-bold [color-scheme:dark]">
                            </div>

                            @if(request('status') || request('start_date') || request('end_date'))
                                <a href="{{ route('peserta.dashboard') }}" class="min-w-[44px] min-h-[44px] flex items-center justify-center p-2 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 hover:bg-rose-100 border border-rose-200/80 dark:border-rose-800/60 rounded-xl transition shrink-0" title="Reset Filter">
                                    <i class="fas fa-times text-xs"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
                
                <div class="p-4 sm:p-6 space-y-4">
                    @forelse($myApplications as $app)
                        @php
                            $appStatus = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->value : $app->status;
                        @endphp
                        <div x-data class="flex flex-col lg:flex-row justify-between items-start lg:items-center p-4 sm:p-5 rounded-2xl border transition hover:shadow-2xs cursor-pointer gap-4 {{ $appStatus == 'diterima' ? 'bg-teal-50/40 dark:bg-teal-950/20 border-teal-200/80 dark:border-teal-900/60 hover:border-teal-400' : ($appStatus == 'selesai' ? 'bg-blue-50/40 dark:bg-blue-950/20 border-blue-200/80 dark:border-blue-900/60 hover:border-blue-400' : ($appStatus == 'menunggu' ? 'bg-amber-50/40 dark:bg-amber-950/20 border-amber-200/80 dark:border-amber-900/60' : 'bg-white dark:bg-gray-800 border-slate-200/90 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-700')) }}"
                            x-on:click="$dispatch('open-modal', 'modal-lamaran-{{ $app->id }}')">
                            
                            <div class="w-full lg:flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                    <h4 class="font-black text-slate-900 dark:text-gray-100 text-sm sm:text-base md:text-lg leading-tight">{{ $app->position->instansi->nama_dinas }}</h4>
                                    @php
                                        $badges = [
                                            'pending' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200/70 dark:border-amber-800/60',
                                            'menunggu' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200/70 dark:border-amber-800/60',
                                            'diterima' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200/70 dark:border-emerald-800/60',
                                            'belum mulai' => 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-800 dark:text-indigo-300 border-indigo-200/70 dark:border-indigo-800/60',
                                            'selesai' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border-blue-200/70 dark:border-blue-800/60',
                                            'ditolak' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border-rose-200/70 dark:border-rose-800/60'
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase whitespace-nowrap border {{ $badges[$app->display_status] ?? 'bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-gray-300 border-slate-200 dark:border-gray-700' }}">
                                        {{ $app->display_status }}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-600 flex items-center gap-1 whitespace-nowrap font-mono">
                                        <i class="fas fa-barcode text-[10px]"></i> {{ $app->nomor_registrasi ?? ('REG-' . $app->id) }}
                                    </span>
                                    @if($app->is_automatic_placement)
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/70 dark:border-teal-800/60 flex items-center gap-1 whitespace-nowrap">
                                            <i class="fas fa-magic text-[10px]"></i> Penempatan Otomatis
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-gray-400 font-medium">{{ $app->position->judul_posisi }}</p>
                                
                                <div class="flex flex-col sm:flex-row sm:flex-wrap items-start sm:items-center gap-x-4 gap-y-1 mt-2 text-xs text-slate-500 dark:text-gray-400 font-medium">
                                    <span class="flex items-center gap-1.5">
                                        <i class="far fa-paper-plane text-slate-400 dark:text-gray-500"></i>
                                        Tgl Lamar: <span class="text-slate-800 dark:text-gray-200 font-bold">{{ \Carbon\Carbon::parse($app->created_at)->translatedFormat('d M Y') }}</span>
                                    </span>
                                    @if(in_array($appStatus, ['diterima', 'selesai']) && $app->tanggal_mulai)
                                    <span class="flex items-center gap-1.5">
                                        <i class="far fa-calendar-alt text-slate-400 dark:text-gray-500"></i>
                                        Periode: <span class="text-slate-800 dark:text-gray-200 font-bold">{{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d M Y') }} &mdash; {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d M Y') }}</span>
                                    </span>
                                    @endif
                                </div>
                                
                                @if($app->nilai_angka)
                                    <div class="mt-2 inline-flex items-center px-3 py-1 bg-white dark:bg-gray-800 rounded-xl border border-blue-200 dark:border-blue-800/60 text-xs font-bold text-blue-800 dark:text-blue-300 shadow-2xs">
                                        <i class="fas fa-star mr-1 text-amber-400"></i> Nilai Akhir: {{ $app->nilai_angka }} ({{ $app->predikat }})
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-2 justify-start lg:justify-end w-full lg:w-auto shrink-0 mt-3 lg:mt-0" x-on:click.stop>
                                @if(in_array($app->status?->value, ['pending', 'menunggu']) || ($app->status?->value === 'diterima' && $app->display_status === 'belum mulai'))
                                    <form action="{{ route('peserta.lamaran.batal', $app->id) }}" method="POST" @submit.prevent="$dispatch('open-confirm', { message: 'Apakah Anda yakin ingin membatalkan lamaran magang ini? Tindakan ini tidak dapat dikembalikan.', onConfirm: () => $el.submit() })">
                                        @csrf
                                        <button type="submit" class="min-h-[44px] w-full sm:w-auto justify-center px-3 sm:px-4 py-2.5 bg-rose-700 text-white rounded-xl text-xs font-bold hover:bg-rose-800 transition shadow-2xs flex items-center justify-center gap-2">
                                            <i class="fas fa-times-circle"></i> Batalkan
                                        </button>
                                    </form>
                                @endif

                                <button type="button" class="min-h-[44px] justify-center px-3 sm:px-4 py-2.5 bg-teal-700 text-white rounded-xl text-xs font-bold hover:bg-teal-800 transition shadow-2xs flex items-center justify-center gap-2" x-on:click.prevent="$dispatch('open-modal', 'modal-lamaran-{{ $app->id }}')">
                                    <span><i class="fas fa-clipboard-list"></i> Detail</span>
                                </button>
                            </div>
                        </div>

                        {{-- Modal Detail Lamaran --}}
                        <x-modal name="modal-lamaran-{{ $app->id }}" focusable>
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
                                <div class="mb-6 bg-white dark:bg-gray-800 border border-slate-200/80 dark:border-gray-700 rounded-2xl p-4 sm:p-5 shadow-2xs">
                                    <h3 class="font-bold text-slate-800 dark:text-gray-200 text-xs flex items-center gap-2 mb-3 pb-2 border-b border-slate-100 dark:border-gray-700">
                                        <i class="fas fa-download text-teal-700 dark:text-teal-400"></i> Unduh Dokumen
                                    </h3>
                                    <div class="grid grid-cols-2 gap-2 sm:gap-3">
                                        @if($app->display_status == 'diterima')
                                            <a href="{{ route('peserta.id_card.download', $app->id) }}" target="_blank" class="min-h-[44px] justify-center px-3 py-2.5 bg-emerald-700 text-white rounded-xl text-xs font-bold hover:bg-emerald-800 transition shadow-2xs flex items-center justify-center gap-2">
                                                <i class="fas fa-id-card"></i> ID Card
                                            </a>
                                            <a href="{{ route('peserta.loa.download', $app->id) }}" target="_blank" class="min-h-[44px] justify-center px-3 py-2.5 bg-indigo-700 text-white rounded-xl text-xs font-bold hover:bg-indigo-800 transition shadow-2xs flex items-center justify-center gap-2">
                                                <i class="fas fa-file-contract"></i> Surat Balasan
                                            </a>
                                            <a href="{{ route('peserta.logbook.print', $app->id) }}" target="_blank" class="min-h-[44px] justify-center px-3 py-2.5 bg-slate-800 dark:bg-gray-700 text-white rounded-xl text-xs font-bold hover:bg-slate-900 dark:hover:bg-gray-600 transition shadow-2xs flex items-center justify-center gap-2">
                                                <i class="fas fa-file-pdf"></i> Rekap Logbook
                                            </a>
                                        @elseif($app->display_status == 'belum mulai')
                                            <a href="{{ route('peserta.id_card.download', $app->id) }}" target="_blank" class="min-h-[44px] justify-center px-3 py-2.5 bg-emerald-700 text-white rounded-xl text-xs font-bold hover:bg-emerald-800 transition shadow-2xs flex items-center justify-center gap-2">
                                                <i class="fas fa-id-card"></i> ID Card
                                            </a>
                                            <a href="{{ route('peserta.loa.download', $app->id) }}" target="_blank" class="min-h-[44px] justify-center px-3 py-2.5 bg-indigo-700 text-white rounded-xl text-xs font-bold hover:bg-indigo-800 transition shadow-2xs flex items-center justify-center gap-2">
                                                <i class="fas fa-file-contract"></i> Surat Balasan
                                            </a>
                                        @elseif($app->display_status == 'selesai')
                                            <a href="{{ route('peserta.id_card.download', $app->id) }}" target="_blank" class="min-h-[44px] justify-center px-3 py-2.5 bg-emerald-700 text-white rounded-xl text-xs font-bold hover:bg-emerald-800 transition shadow-2xs flex items-center justify-center gap-2">
                                                <i class="fas fa-id-card"></i> ID Card
                                            </a>
                                            <a href="{{ route('peserta.loa.download', $app->id) }}" target="_blank" class="min-h-[44px] justify-center px-3 py-2.5 bg-indigo-700 text-white rounded-xl text-xs font-bold hover:bg-indigo-800 transition shadow-2xs flex items-center justify-center gap-2">
                                                <i class="fas fa-file-contract"></i> Surat Balasan
                                            </a>
                                            <a href="{{ route('peserta.logbook.print', $app->id) }}" target="_blank" class="min-h-[44px] justify-center px-3 py-2.5 bg-slate-800 dark:bg-gray-700 text-white rounded-xl text-xs font-bold hover:bg-slate-900 dark:hover:bg-gray-600 transition shadow-2xs flex items-center justify-center gap-2">
                                                <i class="fas fa-file-pdf"></i> Rekap Logbook
                                            </a>
                                            @if(empty($app->saran_peserta))
                                                <button type="button" class="min-h-[44px] justify-center px-3 py-2.5 bg-slate-200 dark:bg-gray-700 text-slate-500 dark:text-gray-400 rounded-xl text-xs font-bold cursor-not-allowed flex items-center justify-center gap-2" title="Mohon isi saran dan evaluasi terlebih dahulu">
                                                    <i class="fas fa-lock"></i> Sertifikat (Kunci)
                                                </button>
                                                <button type="button" class="min-h-[44px] justify-center px-3 py-2.5 bg-slate-200 dark:bg-gray-700 text-slate-500 dark:text-gray-400 rounded-xl text-xs font-bold cursor-not-allowed flex items-center justify-center gap-2" title="Mohon isi saran dan evaluasi terlebih dahulu">
                                                    <i class="fas fa-lock"></i> Transkrip (Kunci)
                                                </button>
                                            @else
                                                <a href="{{ route('peserta.sertifikat', $app->id) }}" target="_blank" class="min-h-[44px] justify-center px-3 py-2.5 bg-teal-700 text-white rounded-xl text-xs font-bold hover:bg-teal-800 transition shadow-2xs flex items-center justify-center gap-2">
                                                    <i class="fas fa-certificate"></i> Sertifikat
                                                </a>
                                                <a href="{{ route('peserta.download.nilai', $app->id) }}" target="_blank" class="min-h-[44px] justify-center px-3 py-2.5 bg-teal-700 text-white rounded-xl text-xs font-bold hover:bg-teal-800 transition shadow-2xs flex items-center justify-center gap-2">
                                                    <i class="fas fa-file-alt"></i> Transkrip
                                                </a>
                                            @endif
                                        @endif
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
                                            <form action="{{ route('peserta.saran.store', $app->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-4">
                                                    <label class="block text-xs font-bold text-slate-700 dark:text-gray-300 uppercase mb-2">Beri Masukan Pembangun (Kerahasiaan Terjamin)</label>
                                                    <textarea name="saran_peserta" rows="3" class="w-full min-h-[80px] rounded-xl border-slate-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100 focus:border-teal-600 focus:ring focus:ring-teal-600/20 text-xs font-medium" placeholder="Tuliskan saran atau kritik membangun Anda mengenai instansi maupun pembimbing..." required></textarea>
                                                </div>
                                                <div class="flex justify-end">
                                                    <button type="submit" class="min-h-[44px] px-5 py-2.5 bg-teal-700 text-white rounded-xl font-bold hover:bg-teal-800 shadow-2xs transition active:scale-95 text-xs uppercase tracking-wider flex items-center gap-2">
                                                        <i class="fas fa-paper-plane"></i> Kirim Evaluasi
                                                    </button>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="mt-6 flex justify-end">
                                    <button type="button" x-on:click.prevent="$dispatch('close')" class="min-h-[44px] px-5 py-2.5 bg-white dark:bg-gray-800 border border-slate-300 dark:border-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-gray-900 rounded-xl font-bold text-xs transition uppercase tracking-wider">Tutup</button>
                                </div>
                            </div>
                        </x-modal>
                    @empty
                        <x-ui.empty-state 
                            title="Tidak Ada Lamaran" 
                            description="Tidak ada lamaran magang yang ditemukan atau sesuai dengan filter pencarian Anda."
                            icon="fa-inbox">
                            @if(request('status') || request('start_date') || request('end_date'))
                                <a href="{{ route('peserta.dashboard') }}" class="min-h-[44px] inline-flex items-center px-4 py-2 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 hover:bg-rose-100 border border-rose-200/80 dark:border-rose-800/60 rounded-xl text-xs font-bold transition">Reset Filter</a>
                            @else
                                <a href="{{ route('home') }}" class="min-h-[44px] inline-flex items-center px-4 py-2.5 bg-teal-700 text-white hover:bg-teal-800 rounded-xl text-xs font-bold transition shadow-2xs">Cari Lowongan Magang &rarr;</a>
                            @endif
                        </x-ui.empty-state>
                    @endforelse
                </div>
            </div>
