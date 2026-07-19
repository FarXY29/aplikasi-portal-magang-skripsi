<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-inbox text-teal-600"></i>
                {{ __('Daftar Pelamar Magang') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400 font-medium bg-white dark:bg-gray-800 px-4 py-1.5 rounded-full shadow-sm border border-gray-100 dark:border-gray-700">
                Total Pelamar: <span class="font-bold text-teal-600">{{ $applicants->total() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900/50 min-h-screen font-sans" x-data="{
        showPdfModal: false,
        pdfUrl: '',
        pdfTitle: '',
        openPdf(url, title) {
            this.pdfUrl = url;
            this.pdfTitle = title;
            this.showPdfModal = true;
        },
        showRejectModal: false,
        rejectActionUrl: '',
        rejectApplicantName: '',
        openReject(url, name) {
            this.rejectActionUrl = url;
            this.rejectApplicantName = name;
            this.showRejectModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Tombol Kembali & Multi-Criteria Filter Bar -->
            <div class="flex flex-col gap-4 mb-6 print:hidden">
                <div class="flex justify-between items-center">
                    <a href="{{ route('dinas.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 transition">
                        <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                            <i class="fas fa-arrow-left text-xs"></i>
                        </div>
                        Kembali ke Dashboard
                    </a>
                </div>

                <!-- Form Filter Multi-Kriteria -->
                <x-ui.filter-bar :action="route('dinas.pelamar')" :resetUrl="request()->hasAny(['search', 'posisi_id', 'status']) ? route('dinas.pelamar') : null">
                    <div class="flex-grow min-w-[200px]">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, no. surat..." class="w-full text-xs rounded-xl border-gray-200 dark:border-gray-700 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-sm font-medium">
                    </div>

                    <div class="min-w-[150px]">
                        <select name="posisi_id" class="w-full text-xs rounded-xl border-gray-200 dark:border-gray-700 focus:border-teal-500 focus:ring-teal-500 py-2 pl-3 pr-8 cursor-pointer shadow-sm font-bold text-gray-600 dark:text-gray-400">
                            <option value="">-- Semua Posisi --</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id }}" {{ request('posisi_id') == $pos->id ? 'selected' : '' }}>
                                    {{ $pos->judul_posisi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="min-w-[150px]">
                        <select name="status" class="w-full text-xs rounded-xl border-gray-200 dark:border-gray-700 focus:border-teal-500 focus:ring-teal-500 py-2 pl-3 pr-8 cursor-pointer shadow-sm font-bold text-gray-600 dark:text-gray-400">
                            <option value="semua" {{ request('status') == 'semua' || !request()->has('status') ? 'selected' : '' }}>Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu (Waiting List)</option>
                            <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                </x-ui.filter-bar>
            </div>

            @if(session('success'))
    <x-ui.alert type="success" class="mb-4">
        {{ session('success') }}
    </x-ui.alert>
@endif
            
            @if(session('error'))
    <x-ui.alert type="error" class="mb-4">
        {{ session('error') }}
    </x-ui.alert>
@endif

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Profil Peserta</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Periode & Posisi</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-5 py-3.5 text-right text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi / Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-50">
                            @forelse($applicants as $app)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition duration-150 ease-in-out group">
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-teal-100 to-teal-200 flex items-center justify-center text-teal-700 font-bold border border-teal-300 shadow-sm">
                                                {{ strtoupper(substr($app->user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">{{ $app->user->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 flex flex-col gap-0.5">
                                                <span class="flex items-center"><i class="far fa-envelope mr-1.5 w-3 text-gray-400"></i> {{ $app->user->email }}</span>
                                                <span class="flex items-center"><i class="fas fa-phone-alt mr-1.5 w-3 text-gray-400"></i> {{ $app->user->phone ?? '-' }}</span>
                                                <span class="flex items-center"><i class="fas fa-university mr-1.5 w-3 text-gray-400"></i> {{ $app->user->university?->name ?? $app->user->school?->name ?? $app->user->asal_instansi ?? '-' }}</span>
                                                @if($app->letter_number)
                                                    <span class="flex items-center font-medium text-teal-700"><i class="fas fa-file-signature mr-1.5 w-3"></i> No. Surat: {{ $app->letter_number }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <div class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-1">
                                            {{ $app->position->judul_posisi ?? 'Posisi Umum' }}
                                        </div>
                                        
                                        @if($app->is_automatic_placement)
                                            <div class="mb-1.5">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200 gap-1">
                                                    <i class="fas fa-magic text-[8px]"></i> Penempatan Otomatis
                                                </span>
                                            </div>
                                        @endif

                                        @if($app->tanggal_mulai)
                                            <div class="flex items-center text-xs text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded w-fit">
                                                <i class="far fa-calendar-alt mr-1.5 text-gray-400"></i>
                                                <span>{{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }}</span>
                                                <i class="fas fa-arrow-right mx-1.5 text-gray-300 text-[10px]"></i>
                                                <span>{{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}</span>
                                            </div>
                                            <div class="text-[10px] text-teal-600 font-semibold mt-0.5 ml-1">
                                                Durasi: {{ \Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai)) }} Hari
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Tanggal belum ditentukan</span>
                                        @endif

                                        <div class="mt-1">
                                            @if($app->position->kuota > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                                    Sisa Kuota: {{ $app->position->kuota }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-700 border border-red-100">
                                                    Kuota Penuh (0)
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusLabel = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->label() : ucfirst($app->status);
                                        $statusClass = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->badgeClass() : 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700';
                                        $statusIcon = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->icon() : 'fas fa-question-circle';
                                    @endphp
                                     <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $statusClass }} items-center gap-1.5">
                                         <i class="{{ $statusIcon }}"></i> {{ $statusLabel }}
                                     </span>

                                    @if($app->status == 'diterima')
                                        <div class="mt-2 text-[10px] text-gray-500 dark:text-gray-400 font-medium">
                                            Pembimbing Lapangan: <span class="text-gray-700 dark:text-gray-300">{{ $app->pembimbing_lapangan->name ?? 'Belum Ada' }}</span>
                                        </div>
                                    @endif

                                    @if($app->rejected_reason && in_array($app->status, ['ditolak', 'dibatalkan']))
                                        <div class="mt-1 text-[10px] text-red-600 bg-red-50 p-1.5 rounded max-w-[180px] mx-auto truncate" title="{{ $app->rejected_reason }}">
                                            Catatan: {{ $app->rejected_reason }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex flex-col items-end gap-2">

                                        @php
                                            $canAct = in_array(
                                                $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->value : $app->status,
                                                ['pending', 'menunggu']
                                            );
                                        @endphp

                                        @if($canAct)
                                            {{-- Label konteks jika bukan pending --}}
                                            @if(($app->status instanceof \App\Enums\ApplicationStatus ? $app->status->value : $app->status) === 'menunggu')
                                                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full mb-0.5">
                                                    <i class="fas fa-clock mr-1"></i>Dalam Waiting List
                                                </span>
                                            @endif

                                            <div class="flex items-center gap-2">
                                                @if($app->position->kuota > 0)
                                                    <form action="{{ route('dinas.pelamar.terima', $app->id) }}" method="POST" @submit.prevent="$dispatch('open-confirm', { message: 'Apakah Anda yakin ingin menerima peserta ini? Kuota akan dikunci dan notifikasi dikirim.', onConfirm: () => $el.submit() })">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-teal-600 text-white text-xs font-bold rounded-lg hover:bg-teal-700 active:scale-95 transition shadow-sm" title="Terima Peserta">
                                                            <i class="fas fa-check mr-1.5"></i> Terima
                                                        </button>
                                                    </form>
                                                @else
                                                    <button disabled class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-400 text-xs font-bold rounded-lg cursor-not-allowed border border-gray-200 dark:border-gray-700" title="Kuota Penuh">
                                                        <i class="fas fa-ban mr-1.5"></i> Kuota Penuh
                                                    </button>
                                                @endif

                                                <button type="button" @click="openReject('{{ route('dinas.pelamar.tolak', $app->id) }}', '{{ $app->user->name }}')" class="inline-flex items-center px-3 py-1.5 bg-white dark:bg-gray-800 text-red-600 border border-red-200 text-xs font-bold rounded-lg hover:bg-red-50 active:scale-95 transition shadow-sm" title="Tolak Peserta">
                                                    <i class="fas fa-times mr-1"></i> Tolak
                                                </button>
                                            </div>
                                        @endif

                                        <!-- Tombol In-Browser PDF Viewer -->
                                        @if($app->surat_pengantar_path)
                                            <button type="button" @click="openPdf('{{ route('storage.access', ['type' => 'surat', 'filename' => basename($app->surat_pengantar_path)]) }}', 'Surat Pengantar - {{ $app->user->name }}')" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:underline flex items-center cursor-pointer bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100 transition">
                                                <i class="fas fa-file-pdf mr-1.5 text-red-500"></i> Lihat Surat
                                            </button>
                                        @else
                                            <span class="text-[10px] text-gray-400 italic">Tidak ada surat</span>
                                        @endif

                                        @if(!$canAct && !$app->surat_pengantar_path)
                                            <span class="text-[10px] text-gray-400 italic">—</span>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12">
                                    <x-ui.empty-state 
                                        title="Tidak Ada Pelamar" 
                                        description="Belum ada pelamar magang yang sesuai dengan kriteria filter pencarian Anda."
                                        icon="fa-inbox" 
                                        class="shadow-none border-none py-6 bg-transparent" />
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    {{ $applicants->links() }}
                </div>
            </div>

        </div>

        <!-- Modal In-Browser PDF Viewer -->
        <div x-show="showPdfModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showPdfModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" @click="showPdfModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showPdfModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100 dark:border-gray-700">
                    <div class="bg-gray-800 px-6 py-4 flex items-center justify-between text-white">
                        <h3 class="text-sm font-bold flex items-center gap-2" x-text="pdfTitle">Surat Pengantar</h3>
                        <div class="flex items-center gap-3">
                            <a :href="pdfUrl" target="_blank" class="text-xs bg-gray-700 hover:bg-gray-600 px-3 py-1.5 rounded-lg font-bold flex items-center gap-1.5 text-gray-200 transition">
                                <i class="fas fa-external-link-alt"></i> Buka Tab Baru / Download
                            </a>
                            <button type="button" @click="showPdfModal = false" class="text-gray-400 hover:text-white transition">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-2 bg-gray-100 dark:bg-gray-800">
                        <iframe :src="pdfUrl" class="w-full h-[75vh] rounded-xl border border-gray-300 dark:border-gray-600 shadow-inner bg-white dark:bg-gray-800"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Alasan Penolakan -->
        <div x-show="showRejectModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showRejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" @click="showRejectModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showRejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100 dark:border-gray-700">
                    <form :action="rejectActionUrl" method="POST">
                        @csrf
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-4 text-red-600">
                                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center font-bold text-lg">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Tolak Lamaran Magang</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="'Peserta: ' + rejectApplicantName"></p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2">Alasan Penolakan / Catatan untuk Peserta <span class="text-red-500">*</span></label>
                                <textarea name="alasan" rows="3" required placeholder="Misal: Kuota periode tersebut sudah penuh, atau berkas surat pengantar tidak sesuai..." class="w-full text-xs rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-red-500 focus:border-red-500 p-3"></textarea>
                                <p class="text-[10px] text-gray-400 mt-1">Alasan ini akan dikirimkan ke email peserta dan tercatat di sistem.</p>
                            </div>

                            <div class="flex justify-end gap-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                <button type="button" @click="showRejectModal = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 text-gray-700 dark:text-gray-300 text-xs font-bold rounded-xl transition">
                                    Batal
                                </button>
                                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center gap-1.5">
                                    <i class="fas fa-times"></i> Konfirmasi Penolakan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
