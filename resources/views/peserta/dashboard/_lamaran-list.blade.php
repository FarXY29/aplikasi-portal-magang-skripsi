            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-briefcase text-teal-600 dark:text-teal-400"></i> Riwayat Lamaran
                    </h3>
                    
                    {{-- Form Filter Riwayat --}}
                    <form action="{{ route('peserta.dashboard') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2 sm:gap-3">
                        {{-- Filter Status --}}
                        <div class="w-full sm:w-auto">
                            <select name="status" onchange="this.form.submit()" class="w-full sm:w-auto text-xs rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 pl-3 pr-8 cursor-pointer shadow-xs font-bold [color-scheme:dark]">
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
                                <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Dari:</span>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()" 
                                       class="w-full sm:w-auto text-xs rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-1.5 px-2.5 cursor-pointer shadow-xs font-bold [color-scheme:dark]">
                            </div>

                            {{-- Filter Tanggal Akhir --}}
                            <div class="flex items-center gap-1.5 flex-1 sm:flex-initial">
                                <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">S/D:</span>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()" 
                                       class="w-full sm:w-auto text-xs rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-1.5 px-2.5 cursor-pointer shadow-xs font-bold [color-scheme:dark]">
                            </div>

                            @if(request('status') || request('start_date') || request('end_date'))
                                <a href="{{ route('peserta.dashboard') }}" class="p-2 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 border border-rose-200 dark:border-rose-800/60 rounded-xl transition shrink-0" title="Reset Filter">
                                    <i class="fas fa-times text-xs"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
                
                <div class="p-6 space-y-4">
                    @forelse($myApplications as $app)
                        @php
                            $appStatus = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->value : $app->status;
                        @endphp
                        <div x-data class="flex flex-col lg:flex-row justify-between items-start lg:items-center p-5 rounded-2xl border transition hover:shadow-xs cursor-pointer gap-4 {{ $appStatus == 'diterima' ? 'bg-teal-50/40 dark:bg-teal-950/20 border-teal-200 dark:border-teal-900/60 hover:border-teal-400' : ($appStatus == 'selesai' ? 'bg-blue-50/40 dark:bg-blue-950/20 border-blue-200 dark:border-blue-900/60 hover:border-blue-400' : ($appStatus == 'menunggu' ? 'bg-amber-50/40 dark:bg-amber-950/20 border-amber-200 dark:border-amber-900/60' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-700')) }}"
                            x-on:click="$dispatch('open-modal', 'modal-lamaran-{{ $app->id }}')">
                            
                            <div class="w-full lg:flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                    <h4 class="font-black text-gray-900 dark:text-gray-100 text-base sm:text-lg leading-tight">{{ $app->position->instansi->nama_dinas }}</h4>
                                    @php
                                        $badges = [
                                            'pending' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
                                            'menunggu' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
                                            'diterima' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                                            'belum mulai' => 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800/60',
                                            'selesai' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800/60',
                                            'ditolak' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60'
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase whitespace-nowrap border {{ $badges[$app->display_status] ?? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700' }}">
                                        {{ $app->display_status }}
                                    </span>
                                    @if($app->is_automatic_placement)
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60 flex items-center gap-1 whitespace-nowrap">
                                            <i class="fas fa-magic text-[10px]"></i> Penempatan Otomatis
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">{{ $app->position->judul_posisi }}</p>
                                
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-xs text-gray-500 dark:text-gray-400 font-medium">
                                    <span class="flex items-center gap-1.5">
                                        <i class="far fa-paper-plane text-gray-400 dark:text-gray-500"></i>
                                        Tgl Lamar: <span class="text-gray-800 dark:text-gray-200 font-bold">{{ \Carbon\Carbon::parse($app->created_at)->translatedFormat('d M Y') }}</span>
                                    </span>
                                    @if(in_array($appStatus, ['diterima', 'selesai']) && $app->tanggal_mulai)
                                    <span class="flex items-center gap-1.5">
                                        <i class="far fa-calendar-alt text-gray-400 dark:text-gray-500"></i>
                                        Periode: <span class="text-gray-800 dark:text-gray-200 font-bold">{{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d M Y') }}</span>
                                    </span>
                                    @endif
                                </div>
                                
                                @if($app->nilai_angka)
                                    <div class="mt-2 inline-flex items-center px-3 py-1 bg-white dark:bg-gray-800 rounded-xl border border-blue-200 dark:border-blue-800/60 text-xs font-bold text-blue-700 dark:text-blue-300 shadow-xs">
                                        <i class="fas fa-star mr-1 text-amber-400"></i> Nilai Akhir: {{ $app->nilai_angka }} ({{ $app->predikat }})
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col sm:flex-row flex-wrap gap-2 justify-start lg:justify-end w-full lg:w-auto shrink-0 mt-3 lg:mt-0" x-on:click.stop>
                                @if($app->display_status == 'diterima')
                                    <a href="{{ route('peserta.id_card.download', $app->id) }}" target="_blank" class="w-full sm:w-auto justify-center px-4 py-2.5 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition shadow-xs flex items-center gap-2">
                                        <i class="fas fa-id-card"></i> ID Card
                                    </a>
                                    <a href="{{ route('peserta.loa.download', $app->id) }}" target="_blank" class="w-full sm:w-auto justify-center px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition shadow-xs flex items-center gap-2">
                                        <i class="fas fa-file-contract"></i> Surat Balasan
                                    </a>

                                    <a href="{{ route('peserta.logbook.index') }}" class="w-full sm:w-auto justify-center px-4 py-2.5 bg-teal-600 text-white rounded-xl text-xs font-bold hover:bg-teal-700 transition shadow-xs flex items-center gap-2">
                                        <i class="fas fa-book-open"></i> Logbook
                                    </a>

                                @elseif($app->display_status == 'belum mulai')
                                    <a href="{{ route('peserta.id_card.download', $app->id) }}" target="_blank" class="w-full sm:w-auto justify-center px-4 py-2.5 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition shadow-xs flex items-center gap-2">
                                        <i class="fas fa-id-card"></i> ID Card
                                    </a>
                                    <a href="{{ route('peserta.loa.download', $app->id) }}" target="_blank" class="w-full sm:w-auto justify-center px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition shadow-xs flex items-center gap-2">
                                        <i class="fas fa-file-contract"></i> Surat Balasan
                                    </a>
                                @elseif($app->display_status == 'selesai')
                                    <a href="{{ route('peserta.id_card.download', $app->id) }}" target="_blank" class="w-full sm:w-auto justify-center px-4 py-2.5 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition shadow-xs flex items-center gap-2">
                                        <i class="fas fa-id-card"></i> ID Card
                                    </a>
                                    <a href="{{ route('peserta.loa.download', $app->id) }}" target="_blank" class="w-full sm:w-auto justify-center px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition shadow-xs flex items-center gap-2">
                                        <i class="fas fa-file-contract"></i> Surat Balasan
                                    </a>
                                    @if(empty($app->saran_peserta))
                                        <button type="button" class="w-full sm:w-auto justify-center px-4 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-xl text-xs font-bold cursor-not-allowed flex items-center gap-2" title="Mohon isi saran dan evaluasi terlebih dahulu">
                                            <i class="fas fa-lock"></i> Sertifikat (Kunci)
                                        </button>
                                        <button type="button" class="w-full sm:w-auto justify-center px-4 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-xl text-xs font-bold cursor-not-allowed flex items-center gap-2" title="Mohon isi saran dan evaluasi terlebih dahulu">
                                            <i class="fas fa-lock"></i> Transkrip (Kunci)
                                        </button>
                                    @else
                                        <a href="{{ route('peserta.sertifikat') }}" target="_blank" class="w-full sm:w-auto justify-center px-4 py-2.5 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition shadow-xs flex items-center gap-2">
                                            <i class="fas fa-certificate"></i> Sertifikat
                                        </a>
                                        <a href="{{ route('peserta.download.nilai', $app->id) }}" target="_blank" class="w-full sm:w-auto justify-center px-4 py-2.5 bg-teal-600 text-white rounded-xl text-xs font-bold hover:bg-teal-700 transition shadow-xs flex items-center gap-2">
                                            <i class="fas fa-file-alt"></i> Transkrip
                                        </a>
                                    @endif
                                @endif

                                @if(in_array($app->status?->value, ['pending', 'menunggu']) || ($app->status?->value === 'diterima' && $app->display_status === 'belum mulai'))
                                    <form action="{{ route('peserta.lamaran.batal', $app->id) }}" method="POST" class="w-full sm:w-auto inline" @submit.prevent="$dispatch('open-confirm', { message: 'Apakah Anda yakin ingin membatalkan lamaran magang ini? Tindakan ini tidak dapat dikembalikan.', onConfirm: () => $el.submit() })">
                                        @csrf
                                        <button type="submit" class="w-full sm:w-auto justify-center px-4 py-2.5 bg-rose-600 text-white rounded-xl text-xs font-bold hover:bg-rose-700 transition shadow-xs flex items-center gap-2">
                                            <i class="fas fa-times-circle"></i> Batalkan
                                        </button>
                                    </form>
                                @endif

                                <button type="button" class="w-full sm:w-auto justify-center px-4 py-2.5 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold hover:bg-gray-200 dark:hover:bg-gray-700 transition shadow-xs flex items-center gap-2" x-on:click.prevent="$dispatch('open-modal', 'modal-lamaran-{{ $app->id }}')">
                                    <i class="fas fa-info-circle"></i> Detail
                                </button>
                            </div>
                        </div>

                        {{-- Modal Detail Lamaran --}}
                        <x-modal name="modal-lamaran-{{ $app->id }}" focusable>
                            <div class="p-6 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                <div class="flex justify-between items-start mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
                                    <h2 class="text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                        <i class="fas fa-clipboard-list text-teal-600 dark:text-teal-400"></i> Detail Lamaran Magang
                                    </h2>
                                    <button x-on:click="$dispatch('close')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 bg-gray-100 dark:bg-gray-900 p-2 rounded-full transition"><i class="fas fa-times"></i></button>
                                </div>
                                
                                <div class="mb-6 bg-gray-50 dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-700">
                                    <h4 class="font-bold text-gray-900 dark:text-gray-100 text-base mb-1">{{ $app->position->instansi->nama_dinas }}</h4>
                                    <p class="text-xs text-teal-600 dark:text-teal-400 font-bold mb-4">{{ $app->position->judul_posisi }}</p>
                                    
                                    <div class="grid grid-cols-2 gap-4 text-xs">
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400 mb-1 text-[10px] font-bold uppercase">Status Akhir</p>
                                            <p><span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $badges[$app->display_status] ?? 'bg-gray-200' }}">{{ $app->display_status }}</span></p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400 mb-1 text-[10px] font-bold uppercase">Tanggal Daftar</p>
                                            <p class="font-bold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($app->created_at)->translatedFormat('d M Y') }}</p>
                                        </div>
                                        @if(in_array($app->status?->value, ['diterima', 'selesai']) && $app->tanggal_mulai)
                                            <div class="col-span-2">
                                                <p class="text-gray-500 dark:text-gray-400 mb-1 text-[10px] font-bold uppercase">Periode Pelaksanaan</p>
                                                <p class="font-bold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d M Y') }} <span class="text-gray-400 mx-2">&rarr;</span> {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d M Y') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if($app->catatan_pembimbing_lapangan)
                                    <div class="bg-amber-50/60 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 p-4 rounded-2xl shadow-xs mb-6">
                                        <h3 class="font-bold text-amber-800 dark:text-amber-300 text-xs flex items-center gap-2 mb-2">
                                            <i class="fas fa-lightbulb text-amber-500"></i> Pesan & Saran dari Pembimbing Lapangan
                                        </h3>
                                        <p class="text-xs text-amber-900 dark:text-amber-200 italic font-medium leading-relaxed">"{{ $app->catatan_pembimbing_lapangan }}"</p>
                                    </div>
                                @endif

                                @if($app->status?->value == 'selesai')
                                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-xs border border-gray-200 dark:border-gray-700">
                                        <h3 class="font-bold text-gray-800 dark:text-gray-200 text-xs flex items-center gap-2 mb-3 pb-2 border-b border-gray-100 dark:border-gray-700">
                                            <i class="fas fa-star text-teal-500"></i> Evaluasi & Saran untuk Instansi
                                        </h3>
                                        @if($app->saran_peserta)
                                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                                                <p class="text-xs text-gray-700 dark:text-gray-300 italic font-medium">"{{ $app->saran_peserta }}"</p>
                                                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                                    <i class="fas fa-check-circle text-emerald-500"></i>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400 font-bold">Evaluasi telah dikirimkan secara anonim.</p>
                                                </div>
                                            </div>
                                        @else
                                            <form action="{{ route('peserta.saran.store', $app->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-4">
                                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Beri Masukan Pembangun (Kerahasiaan Terjamin)</label>
                                                    <textarea name="saran_peserta" rows="3" class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring focus:ring-teal-200 text-xs font-medium" placeholder="Tuliskan saran atau kritik membangun Anda mengenai instansi maupun pembimbing..." required></textarea>
                                                </div>
                                                <div class="flex justify-end">
                                                    <button type="submit" class="px-5 py-2.5 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 shadow-md transition active:scale-95 text-xs uppercase tracking-wider flex items-center gap-2">
                                                        <i class="fas fa-paper-plane"></i> Kirim Evaluasi
                                                    </button>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="mt-6 flex justify-end">
                                    <button type="button" x-on:click.prevent="$dispatch('close')" class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 rounded-xl font-bold text-xs transition uppercase tracking-wider">Tutup</button>
                                </div>
                            </div>
                        </x-modal>
                    @empty
                        <x-ui.empty-state 
                            title="Tidak Ada Lamaran" 
                            description="Tidak ada lamaran magang yang ditemukan atau sesuai dengan filter pencarian Anda."
                            icon="fa-inbox">
                            @if(request('status') || request('start_date') || request('end_date'))
                                <a href="{{ route('peserta.dashboard') }}" class="px-4 py-2 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 hover:bg-rose-100 border border-rose-200 dark:border-rose-800/60 rounded-xl text-xs font-bold transition">Reset Filter</a>
                            @else
                                <a href="{{ route('home') }}" class="px-4 py-2.5 bg-teal-600 text-white hover:bg-teal-700 rounded-xl text-xs font-bold transition shadow-xs">Cari Lowongan Magang &rarr;</a>
                            @endif
                        </x-ui.empty-state>
                    @endforelse
                </div>
            </div>
