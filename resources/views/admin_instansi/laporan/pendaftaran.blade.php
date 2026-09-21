<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header
            title="Laporan Pendaftaran & Pelacakan Permohonan"
            icon="fas fa-clipboard-check"
            :breadcrumbs="[['label' => 'Pusat Laporan', 'url' => route('dinas.laporan.hub')], ['label' => 'Pendaftaran']]">
            @if(request()->anyFilled(['status', 'posisi_id', 'start_date', 'end_date', 'search']))
                <x-slot name="actions">
                    <a href="{{ route('dinas.laporan.pendaftaran') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30 hover:bg-rose-100 dark:hover:bg-rose-500/20 rounded-xl font-bold text-xs transition shadow-2xs">
                        <i class="fas fa-redo-alt text-[10px]"></i> Reset Filter
                    </a>
                </x-slot>
            @endif
        </x-ui.page-header>
    </x-slot>

    <div x-data="{
        clientSearch: '',
        statusFilter: '{{ request('status', 'semua') }}',
        activeApp: null,
        openDetail(data) {
            this.activeApp = data;
            document.body.classList.add('overflow-hidden');
        },
        closeDetail() {
            this.activeApp = null;
            document.body.classList.remove('overflow-hidden');
        },
        matchesSearch(item) {
            if (this.statusFilter !== 'semua' && item.status !== this.statusFilter) {
                return false;
            }
            if (!this.clientSearch.trim()) return true;
            const q = this.clientSearch.toLowerCase();
            return (item.nomor_reg && item.nomor_reg.toLowerCase().includes(q)) ||
                   (item.nama && item.nama.toLowerCase().includes(q)) ||
                   (item.nim && item.nim.toLowerCase().includes(q)) ||
                   (item.kampus && item.kampus.toLowerCase().includes(q)) ||
                   (item.jurusan && item.jurusan.toLowerCase().includes(q)) ||
                   (item.dinas && item.dinas.toLowerCase().includes(q)) ||
                   (item.posisi && item.posisi.toLowerCase().includes(q));
        }
    }" 
    @keydown.escape.window="closeDetail()"
    class="py-8 bg-slate-50 dark:bg-[#0f172a] min-h-screen font-sans text-slate-900 dark:text-slate-100">

        @php
            // Dibangun sekali, dipakai bersama oleh tabel desktop & kartu mobile.
            $buildAppItem = function ($app) {
                $statusValue = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->value : (string) $app->status;

                return [
                    'id' => $app->id,
                    'nomor_reg' => $app->nomor_registrasi ?? ('REG-' . $app->id),
                    'nama' => $app->user->name ?? '-',
                    'email' => $app->user->email ?? '-',
                    'nim' => $app->user->nim ?? '-',
                    'kampus' => $app->user->asal_instansi ?? ($app->user->university->name ?? ($app->user->school->name ?? '-')),
                    'jurusan' => $app->user->major ?? ($app->user->jurusan ?? '-'),
                    'phone' => $app->user->phone ?? ($app->user->phone_number ?? ($app->user->nomor_telepon ?? '-')),
                    'dinas' => $app->position->instansi->nama_dinas ?? '-',
                    'posisi' => $app->position->judul_posisi ?? '-',
                    'status' => $statusValue,
                    'status_label' => $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->label() : ucfirst($statusValue),
                    'tgl_lamar' => \Carbon\Carbon::parse($app->created_at)->translatedFormat('d F Y, H:i'),
                    'tgl_mulai' => $app->tanggal_mulai ? \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d F Y') : null,
                    'tgl_selesai' => $app->tanggal_selesai ? \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d F Y') : null,
                    'durasi' => ($app->tanggal_mulai && $app->tanggal_selesai) ? \Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai)) . ' Hari' : null,
                    'pembimbing' => $app->pembimbing_lapangan->name ?? null,
                    'is_auto' => (bool) $app->is_automatic_placement,
                    'surat_url' => $app->surat_pengantar_path ? route('storage.access', ['type' => 'surat', 'filename' => basename($app->surat_pengantar_path)]) : null,
                    'cv_url' => $app->cv_path ? route('storage.access', ['type' => 'cv', 'filename' => basename($app->cv_path)]) : null,
                    'rejected_reason' => $app->rejected_reason ?? null,
                    'timelines' => $app->timelines->map(function ($tl) {
                        return [
                            'event' => $tl->getEventLabel(),
                            'old_status' => $tl->old_status,
                            'new_status' => $tl->new_status,
                            'actor' => $tl->actor->name ?? 'Sistem',
                            'date' => \Carbon\Carbon::parse($tl->created_at)->translatedFormat('d M Y, H:i'),
                            'notes' => $tl->metadata['notes'] ?? ($tl->metadata['reason'] ?? null),
                        ];
                    })->toArray(),
                ];
            };
        @endphp

        {{-- Top Navigation & PDF Export --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 print:hidden max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('dinas.laporan.hub') }}" class="group flex items-center text-sm font-bold text-slate-500 dark:text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                <div class="w-8 h-8 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-2xs">
                    <i class="fas fa-arrow-left text-xs text-slate-400 dark:text-slate-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
            @if($applications->count() > 0)
                <a href="{{ route('dinas.laporan.pendaftaran.print', request()->query()) }}" target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 active:bg-teal-800 text-white rounded-xl text-xs font-bold shadow-md shadow-teal-600/20 transition gap-2">
                    <i class="fas fa-file-pdf text-sm"></i> Cetak PDF Resmi
                </a>
            @endif
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- 6 Stats Cards Grid --}}
            @include('shared.laporan._pendaftaran-stats', ['stats' => $stats])

            {{-- Highlight Action Banner --}}
            <div class="bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-700 rounded-3xl p-5 sm:p-6 text-white shadow-lg shadow-teal-700/20 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-white/20 dark:bg-slate-900/30 backdrop-blur-sm flex items-center justify-center text-xl sm:text-2xl flex-shrink-0 border border-white/20">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="text-center sm:text-left flex-grow">
                    <p class="text-xs font-bold uppercase tracking-wider text-teal-100">Pelacakan Status Real-Time di Instansi</p>
                    <p class="text-lg sm:text-xl font-black mt-0.5">Total {{ number_format($stats['total']) }} Berkas Permohonan Terdata</p>
                    <p class="text-xs sm:text-sm text-teal-100 font-medium">Monitoring pergerakan status pendaftaran mahasiswa dan siswa magang di lingkungan dinas/instansi terkait.</p>
                </div>
                @if($applications->count() > 0)
                <div class="sm:ml-auto flex-shrink-0">
                    <a href="{{ route('dinas.laporan.pendaftaran.print', request()->query()) }}" target="_blank"
                       class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-slate-800 text-teal-700 dark:text-teal-300 hover:bg-teal-50 dark:hover:bg-slate-700 rounded-xl transition text-xs font-bold shadow-md border border-white/20 dark:border-slate-700" title="Cetak Berkas Laporan PDF">
                        <i class="fas fa-file-pdf mr-1.5 text-rose-500 text-sm"></i> Cetak PDF
                    </a>
                </div>
                @endif
            </div>

            {{-- Filter Box (Server-Side) --}}
            <div class="bg-white dark:bg-slate-800/90 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-2xs border border-slate-200 dark:border-slate-700/80">
                <form method="GET" action="{{ route('dinas.laporan.pendaftaran') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Filter Status Permohonan (Server Query) --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1.5">
                                <i class="fas fa-filter text-teal-500 mr-1"></i> Status Permohonan
                            </label>
                            <select name="status" class="w-full border border-slate-300 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/80 text-slate-800 dark:text-slate-100 rounded-xl text-xs font-semibold focus:ring-teal-500 focus:border-teal-500 shadow-2xs py-2.5">
                                <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending / Menunggu Peninjauan</option>
                                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Daftar Tunggu (Waiting List)</option>
                                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima / Aktif</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai Magang</option>
                            </select>
                        </div>

                        {{-- Filter Posisi Magang --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1.5">
                                <i class="fas fa-briefcase text-teal-500 mr-1"></i> Posisi / Formasi
                            </label>
                            <select name="posisi_id" class="w-full border border-slate-300 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/80 text-slate-800 dark:text-slate-100 rounded-xl text-xs font-semibold focus:ring-teal-500 focus:border-teal-500 shadow-2xs py-2.5">
                                <option value="">Semua Formasi Magang</option>
                                @foreach($positions as $pos)
                                    <option value="{{ $pos->id }}" {{ request('posisi_id') == $pos->id ? 'selected' : '' }}>
                                        {{ $pos->judul_posisi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal Lamar Dari --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1.5">
                                <i class="far fa-calendar-alt text-teal-500 mr-1"></i> Tanggal Lamar Dari
                            </label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                   class="w-full border border-slate-300 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/80 text-slate-800 dark:text-slate-100 rounded-xl text-xs font-semibold focus:ring-teal-500 focus:border-teal-500 shadow-2xs py-2.5">
                        </div>

                        {{-- Tanggal Lamar Sampai --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1.5">
                                <i class="far fa-calendar-check text-teal-500 mr-1"></i> Tanggal Lamar Sampai
                            </label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                   class="w-full border border-slate-300 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/80 text-slate-800 dark:text-slate-100 rounded-xl text-xs font-semibold focus:ring-teal-500 focus:border-teal-500 shadow-2xs py-2.5">
                        </div>
                    </div>

                    {{-- Baris 2: Pencarian Cepat & Tombol Aksi --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end pt-1">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1.5">
                                <i class="fas fa-search text-teal-500 mr-1"></i> Pencarian Cepat Server
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="fas fa-search text-xs"></i>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Nama, NIM, Kampus, No. Reg..."
                                       class="w-full pl-9 border border-slate-300 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/80 text-slate-800 dark:text-slate-100 placeholder-slate-400 rounded-xl text-xs font-semibold focus:ring-teal-500 focus:border-teal-500 shadow-2xs py-2.5">
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if(request()->anyFilled(['status', 'posisi_id', 'start_date', 'end_date', 'search']))
                                <a href="{{ route('dinas.laporan.pendaftaran') }}" title="Bersihkan Filter"
                                   class="px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-rose-50 dark:hover:bg-rose-500/10 text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold transition flex items-center justify-center shadow-2xs">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                            <button type="submit" class="w-full py-2.5 px-4 bg-teal-600 hover:bg-teal-700 active:bg-teal-800 text-white rounded-xl text-xs font-black shadow-md shadow-teal-600/20 transition uppercase tracking-wider flex items-center justify-center gap-2">
                                <i class="fas fa-filter text-xs"></i> Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tabel & Kartu Data --}}
            @include('shared.laporan._pendaftaran-table', [
                'applications' => $applications,
                'stats' => $stats,
                'scope' => 'instansi',
                'buildAppItem' => $buildAppItem
            ])

        </div>

        {{-- Detail Modal --}}
        @include('shared.laporan._pendaftaran-modal')

    </div>
</x-app-layout>
