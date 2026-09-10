<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-slate-800 dark:text-slate-100 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60 shadow-2xs">
                    <i class="fas fa-clipboard-check text-teal-700 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Laporan Pendaftaran & Pelacakan Permohonan') }}
            </h2>
            <div class="flex items-center gap-2">
                @if(request()->anyFilled(['instansi_id', 'status', 'posisi_id', 'start_date', 'end_date', 'search']))
                    <a href="{{ route('admin.laporan.pendaftaran') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30 hover:bg-rose-100 dark:hover:bg-rose-500/20 rounded-xl font-bold text-xs transition shadow-2xs">
                        <i class="fas fa-redo-alt text-[10px]"></i> Reset Filter
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-[#0f172a] min-h-screen font-sans text-slate-900 dark:text-slate-100">
        <div class="flex justify-between items-center mb-6 print:hidden max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

        {{-- Top Navigation & PDF Export --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 print:hidden max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('admin.laporan.hub') }}" class="group flex items-center text-sm font-bold text-slate-500 dark:text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                <div class="w-8 h-8 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-2xs">
                    <i class="fas fa-arrow-left text-xs text-slate-400 dark:text-slate-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                </div>
                Kembali ke Pusat Laporan
            </a>
            @if($applications->count() > 0)
                <a href="{{ route('admin.laporan.pendaftaran.print', request()->query()) }}" target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 active:bg-teal-800 text-white rounded-xl text-xs font-bold shadow-md shadow-teal-600/20 transition gap-2">
                    <i class="fas fa-file-pdf text-sm"></i> Cetak PDF Resmi
                </a>
            @endif
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- 6 Stats Cards Grid --}}
            {{-- 6 Stats Cards Grid (Clean & Eye-Comfort) --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                {{-- Total Pendaftar --}}
                <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-4 shadow-2xs border border-slate-200 dark:border-slate-700/80 text-center cursor-help transition hover:shadow-md" title="Total permohonan pendaftaran magang yang terdata di seluruh Pemerintah Kota Banjarmasin.">
                    <div class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center mx-auto mb-2 border border-teal-100 dark:border-teal-900/50">
                        <i class="fas fa-file-signature text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-slate-800 dark:text-slate-100">{{ number_format($stats['total']) }}</p>
                    <p class="text-[9px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-wider mt-1">Total Pendaftar</p>
                </div>

                {{-- Status Pending --}}
                <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-4 shadow-2xs border border-slate-200 dark:border-slate-700/80 text-center cursor-help transition hover:shadow-md" title="Permohonan baru yang masih menantikan proses peninjauan / verifikasi awal dinas.">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto mb-2 border border-amber-100 dark:border-amber-900/50">
                        <i class="fas fa-hourglass-start text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-amber-600 dark:text-amber-400">{{ number_format($stats['pending']) }}</p>
                    <p class="text-[9px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-wider mt-1">Pending (Baru)</p>
                </div>

                {{-- Status Menunggu / Waiting List --}}
                <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-4 shadow-2xs border border-slate-200 dark:border-slate-700/80 text-center cursor-help transition hover:shadow-md" title="Permohonan yang dimasukkan ke dalam antrian daftar tunggu kuota magang.">
                    <div class="w-8 h-8 rounded-xl bg-yellow-50 dark:bg-yellow-950/60 text-yellow-600 dark:text-yellow-400 flex items-center justify-center mx-auto mb-2 border border-yellow-100 dark:border-yellow-900/50">
                        <i class="fas fa-clock text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-yellow-600 dark:text-yellow-400">{{ number_format($stats['menunggu']) }}</p>
                    <p class="text-[9px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-wider mt-1">Daftar Tunggu</p>
                </div>

                {{-- Status Diterima --}}
                <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-4 shadow-2xs border border-slate-200 dark:border-slate-700/80 text-center cursor-help transition hover:shadow-md" title="Permohonan yang telah disetujui / diterima dan sedang aktif menjalani kegiatan magang.">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-2 border border-emerald-100 dark:border-emerald-900/50">
                        <i class="fas fa-check-circle text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($stats['diterima']) }}</p>
                    <p class="text-[9px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-wider mt-1">Diterima / Aktif</p>
                </div>

                {{-- Status Ditolak --}}
                <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-4 shadow-2xs border border-slate-200 dark:border-slate-700/80 text-center cursor-help transition hover:shadow-md" title="Permohonan yang tidak lolos seleksi berkas atau kuota penuh.">
                    <div class="w-8 h-8 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-2 border border-rose-100 dark:border-rose-900/50">
                        <i class="fas fa-times-circle text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-rose-600 dark:text-rose-400">{{ number_format($stats['ditolak']) }}</p>
                    <p class="text-[9px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-wider mt-1">Ditolak</p>
                </div>

                {{-- Status Selesai --}}
                <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-4 shadow-2xs border border-slate-200 dark:border-slate-700/80 text-center cursor-help transition hover:shadow-md" title="Peserta yang telah menuntaskan seluruh periode magang secara tuntas.">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-2 border border-blue-100 dark:border-blue-900/50">
                        <i class="fas fa-graduation-cap text-xs"></i>
                    </div>
                    <p class="text-xl font-black text-blue-600 dark:text-blue-400">{{ number_format($stats['selesai']) }}</p>
                    <p class="text-[9px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-wider mt-1">Selesai Magang</p>
                </div>
            </div>

            {{-- Highlight Action Banner --}}
            <div class="bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-700 rounded-3xl p-5 sm:p-6 text-white shadow-lg shadow-teal-700/20 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-white/20 dark:bg-slate-900/30 backdrop-blur-sm flex items-center justify-center text-xl sm:text-2xl flex-shrink-0 border border-white/20">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="text-center sm:text-left flex-grow">
                    <p class="text-xs font-bold uppercase tracking-wider text-teal-100">Pelacakan Status Real-Time Se-Kota Banjarmasin</p>
                    <p class="text-lg sm:text-xl font-black mt-0.5">Total {{ number_format($stats['total']) }} Berkas Permohonan Terdata</p>
                    <p class="text-xs sm:text-sm text-teal-100 font-medium">Monitoring pergerakan status pendaftaran mahasiswa dan siswa magang di seluruh SKPD Pemerintah Kota Banjarmasin.</p>
                </div>
                @if($applications->count() > 0)
                <div class="sm:ml-auto flex-shrink-0">
                    <a href="{{ route('admin.laporan.pendaftaran.print', request()->query()) }}" target="_blank"
                       class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-slate-800 text-teal-700 dark:text-teal-300 hover:bg-teal-50 dark:hover:bg-slate-700 rounded-xl transition text-xs font-bold shadow-md border border-white/20 dark:border-slate-700" title="Cetak Berkas Laporan PDF">
                        <i class="fas fa-file-pdf mr-1.5 text-rose-500 text-sm"></i> Cetak PDF
                    </a>
                </div>
                @endif
            </div>

            {{-- Filter Box --}}
            {{-- Filter Box (Server-Side) --}}
            <div class="bg-white dark:bg-slate-800/90 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-2xs border border-slate-200 dark:border-slate-700/80">
                <form method="GET" action="{{ route('admin.laporan.pendaftaran') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        {{-- Filter Dinas / OPD --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1.5">
                                <i class="fas fa-building text-teal-500 mr-1"></i> Dinas / OPD Penempatan
                            </label>
                            <select name="instansi_id" class="w-full border border-slate-300 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/80 text-slate-800 dark:text-slate-100 rounded-xl text-xs font-semibold focus:ring-teal-500 focus:border-teal-500 shadow-2xs py-2.5">
                                <option value="">Semua Dinas / SKPD</option>
                                @foreach($listDinas as $dinas)
                                    <option value="{{ $dinas->id }}" {{ request('instansi_id') == $dinas->id ? 'selected' : '' }}>
                                        {{ $dinas->nama_dinas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Status Permohonan --}}
                        {{-- Filter Status Permohonan (Server Query) --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1.5">
                                <i class="fas fa-filter text-teal-500 mr-1"></i> Status Permohonan
                                <i class="fas fa-filter text-teal-500 mr-1"></i> Filter Status Database
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
                                        {{ $pos->judul_posisi }} {{ $pos->instansi ? '('.($pos->instansi->singkatan ?: $pos->instansi->nama_dinas).')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Baris 2: Tanggal Pengajuan & Pencarian Cepat --}}
                    {{-- Baris 2: Tanggal Pengajuan & Pencarian Server --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end pt-1">
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

                        {{-- Kolom Pencarian Cepat --}}
                        {{-- Kolom Pencarian Kata Kunci --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1.5">
                                <i class="fas fa-search text-teal-500 mr-1"></i> Pencarian Cepat
                                <i class="fas fa-search text-teal-500 mr-1"></i> Cari di Server
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="fas fa-search text-xs"></i>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Nama, NIM, Kampus, No. Reg..."
                                       placeholder="Nama, NIM, No. Reg..."
                                       class="w-full pl-9 border border-slate-300 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/80 text-slate-800 dark:text-slate-100 placeholder-slate-400 rounded-xl text-xs font-semibold focus:ring-teal-500 focus:border-teal-500 shadow-2xs py-2.5">
                            </div>
                        </div>

                        {{-- Tombol Terapkan --}}
                        {{-- Tombol Terapkan & Reset --}}
                        <div class="flex items-center gap-2">
                            @if(request()->anyFilled(['instansi_id', 'status', 'posisi_id', 'start_date', 'end_date', 'search']))
                                <a href="{{ route('admin.laporan.pendaftaran') }}" title="Bersihkan Filter"
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

            {{-- Card Tabel Utama --}}
            {{-- Interactive Toolbar: Status Pills Tab & Fast Client-Side Search --}}
            <div class="bg-white dark:bg-slate-800/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-2xs border border-slate-200 dark:border-slate-700/80 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                {{-- 1-Click Status Filter Pills --}}
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 custom-scrollbar text-xs font-bold">
                    <button type="button" 
                            @click="statusFilter = 'semua'"
                            :class="statusFilter === 'semua' ? 'bg-teal-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                            class="px-3.5 py-2 rounded-xl transition whitespace-nowrap flex items-center gap-1.5">
                        <span>Semua</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="statusFilter === 'semua' ? 'bg-teal-700/80 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300'">{{ $stats['total'] }}</span>
                    </button>
                    <button type="button" 
                            @click="statusFilter = 'pending'"
                            :class="statusFilter === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                            class="px-3.5 py-2 rounded-xl transition whitespace-nowrap flex items-center gap-1.5">
                        <span>Pending</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="statusFilter === 'pending' ? 'bg-amber-600 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300'">{{ $stats['pending'] }}</span>
                    </button>
                    <button type="button" 
                            @click="statusFilter = 'menunggu'"
                            :class="statusFilter === 'menunggu' ? 'bg-yellow-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                            class="px-3.5 py-2 rounded-xl transition whitespace-nowrap flex items-center gap-1.5">
                        <span>Daftar Tunggu</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="statusFilter === 'menunggu' ? 'bg-yellow-600 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300'">{{ $stats['menunggu'] }}</span>
                    </button>
                    <button type="button" 
                            @click="statusFilter = 'diterima'"
                            :class="statusFilter === 'diterima' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                            class="px-3.5 py-2 rounded-xl transition whitespace-nowrap flex items-center gap-1.5">
                        <span>Diterima / Aktif</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="statusFilter === 'diterima' ? 'bg-emerald-700 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300'">{{ $stats['diterima'] }}</span>
                    </button>
                    <button type="button" 
                            @click="statusFilter = 'ditolak'"
                            :class="statusFilter === 'ditolak' ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                            class="px-3.5 py-2 rounded-xl transition whitespace-nowrap flex items-center gap-1.5">
                        <span>Ditolak</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="statusFilter === 'ditolak' ? 'bg-rose-700 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300'">{{ $stats['ditolak'] }}</span>
                    </button>
                    <button type="button" 
                            @click="statusFilter = 'selesai'"
                            :class="statusFilter === 'selesai' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                            class="px-3.5 py-2 rounded-xl transition whitespace-nowrap flex items-center gap-1.5">
                        <span>Selesai</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="statusFilter === 'selesai' ? 'bg-blue-700 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300'">{{ $stats['selesai'] }}</span>
                    </button>
                </div>

                {{-- Live Client-Side Table Filter --}}
                <div class="relative w-full md:w-72 flex-shrink-0">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="fas fa-filter text-xs"></i>
                    </span>
                    <input type="text" 
                           x-model="clientSearch"
                           placeholder="Filter cepat tabel di layar..." 
                           class="w-full pl-9 pr-8 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-teal-500 focus:border-teal-500 shadow-2xs font-medium">
                    <button x-show="clientSearch.length > 0" 
                            @click="clientSearch = ''" 
                            type="button" 
                            class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>

            {{-- Card Tabel Utama & Card View Mobile --}}
            <div class="bg-white dark:bg-slate-800/90 rounded-2xl sm:rounded-3xl shadow-2xs border border-slate-200 dark:border-slate-700/80 overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-700/60 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-slate-50/60 dark:bg-slate-900/40">
                    <div>
                        <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-slate-100">Daftar Pelacakan Permohonan Magang</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Rekapitulasi riwayat pengajuan, nomor registrasi, status verifikasi SKPD, dan masa magang.</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Rekapitulasi riwayat pengajuan, nomor registrasi, status verifikasi SKPD, dan riwayat mutasi.</p>
                    </div>
                    <span class="text-xs font-bold text-teal-700 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/60 px-3 py-1 rounded-full border border-teal-200 dark:border-teal-800/60">
                        {{ number_format($applications->total()) }} Data Ditemukan
                    </span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-teal-700 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/60 px-3 py-1 rounded-full border border-teal-200 dark:border-teal-800/60">
                            {{ number_format($applications->total()) }} Data Terdaftar
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                {{-- DESKTOP TABULAR VIEW (Hidden on Mobile) --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full divide-y divide-slate-100 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-900/80">
                            <tr>
                                <th class="px-4 py-3.5 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-12 whitespace-nowrap">No</th>
                                <th class="px-4 py-3.5 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap min-w-[170px]">No. Registrasi & Tgl</th>
                                <th class="px-4 py-3.5 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap min-w-[220px]">Pemohon & Asal Institusi</th>
                                <th class="px-4 py-3.5 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap min-w-[220px]">Dinas & Posisi Magang</th>
                                <th class="px-4 py-3.5 text-left text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap min-w-[170px]">Periode Magang</th>
                                <th class="px-4 py-3.5 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-32 whitespace-nowrap">Status Terkini</th>
                                <th class="px-4 py-3.5 text-center text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider w-24 whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800/90 divide-y divide-slate-100 dark:divide-slate-700/60 text-sm">
                            @forelse($applications as $app)
                            <tr class="hover:bg-teal-50/20 dark:hover:bg-slate-900/60 transition duration-150">
                            @php
                                $statusValue = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->value : (string)$app->status;
                                $appItemJson = [
                                    'id' => $app->id,
                                    'nomor_reg' => $app->nomor_registrasi ?? ('REG-' . $app->id),
                                    'nama' => $app->user->name ?? '-',
                                    'email' => $app->user->email ?? '-',
                                    'nim' => $app->user->nim ?? '-',
                                    'kampus' => $app->user->asal_instansi ?? ($app->user->university->name ?? ($app->user->school->name ?? '-')),
                                    'jurusan' => $app->user->major ?? ($app->user->jurusan ?? '-'),
                                    'phone' => $app->user->phone_number ?? ($app->user->nomor_telepon ?? '-'),
                                    'dinas' => $app->position->instansi->nama_dinas ?? '-',
                                    'posisi' => $app->position->judul_posisi ?? '-',
                                    'status' => $statusValue,
                                    'status_label' => $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->label() : ucfirst($statusValue),
                                    'tgl_lamar' => \Carbon\Carbon::parse($app->created_at)->translatedFormat('d F Y, H:i'),
                                    'tgl_mulai' => $app->tanggal_mulai ? \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d F Y') : null,
                                    'tgl_selesai' => $app->tanggal_selesai ? \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d F Y') : null,
                                    'durasi' => ($app->tanggal_mulai && $app->tanggal_selesai) ? \Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai)) . ' Hari' : null,
                                    'pembimbing' => $app->pembimbing_lapangan->name ?? null,
                                    'is_auto' => (bool)$app->is_automatic_placement,
                                    'surat_url' => $app->surat_pengantar_path ? route('storage.access', ['type' => 'surat', 'filename' => basename($app->surat_pengantar_path)]) : null,
                                    'cv_url' => $app->cv_path ? route('storage.access', ['type' => 'cv', 'filename' => basename($app->cv_path)]) : null,
                                    'rejected_reason' => $app->rejected_reason ?? null,
                                    'timelines' => $app->timelines->map(function($tl) {
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
                            @endphp
                            <tr x-show="matchesSearch({{ json_encode($appItemJson) }})" 
                                class="hover:bg-teal-50/20 dark:hover:bg-slate-900/60 transition duration-150">
                                <td class="px-4 py-3.5 text-xs text-slate-400 dark:text-slate-500 text-center font-bold">
                                    {{ $applications->firstItem() + $loop->index }}
                                </td>
                                
                                {{-- No. Registrasi & Tanggal Lamar --}}
                                <td class="px-4 py-3.5">
                                    <div class="flex flex-col gap-1">
                                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-black bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-600 font-mono w-fit">
                                            {{ $app->nomor_registrasi ?? ('REG-' . $app->id) }}
                                        </span>
                                        <span class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold flex items-center gap-1">
                                            <i class="far fa-calendar-alt text-slate-400"></i>
                                            {{ \Carbon\Carbon::parse($app->created_at)->translatedFormat('d M Y, H:i') }}
                                        </span>
                                        @if($app->is_automatic_placement)
                                            <span class="text-[9px] font-bold text-teal-700 dark:text-teal-400 flex items-center gap-1 mt-0.5">
                                                <i class="fas fa-magic text-[8px]"></i> Penempatan Otomatis
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Pemohon & Asal Institusi --}}
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-full bg-gradient-to-br from-teal-100 to-emerald-200 dark:from-teal-950/60 dark:to-emerald-900/60 flex items-center justify-center text-teal-700 dark:text-teal-300 font-bold text-xs border border-teal-300 dark:border-teal-800/60 flex-shrink-0 shadow-2xs">
                                            {{ strtoupper(substr($app->user->name ?? 'P', 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-bold text-slate-900 dark:text-slate-100 truncate">{{ $app->user->name ?? '-' }}</div>
                                            <p class="text-[11px] text-teal-700 dark:text-teal-400 font-bold truncate">
                                                {{ $app->user->asal_instansi ?? ($app->user->university->name ?? ($app->user->school->name ?? '-')) }}
                                            </p>
                                            <div class="flex items-center gap-2 text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 flex-wrap font-medium">
                                                @if($app->user->nim)
                                                    <span class="font-mono">NIM/NISN: {{ $app->user->nim }}</span>
                                                    <span class="font-mono">NIM: {{ $app->user->nim }}</span>
                                                    <span>•</span>
                                                @endif
                                                <span>{{ $app->user->major ?? ($app->user->jurusan ?? '-') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Dinas & Posisi yang Dilamar --}}
                                <td class="px-4 py-3.5">
                                    <div class="text-xs font-bold text-slate-900 dark:text-slate-100">
                                        {{ $app->position->instansi->nama_dinas ?? '-' }}
                                    </div>
                                    <p class="text-[11px] text-teal-700 dark:text-teal-400 font-semibold mt-0.5">
                                        {{ $app->position->judul_posisi ?? '-' }}
                                    </p>
                                    @if($app->pembimbing_lapangan)
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1">
                                            <i class="fas fa-user-tie text-[9px] text-teal-600"></i>
                                            PL: <span class="font-bold text-slate-700 dark:text-slate-300">{{ $app->pembimbing_lapangan->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-[9px] text-slate-400 dark:text-slate-500 italic mt-0.5 block">PL: Belum ditugaskan</span>
                                    @endif
                                </td>

                                {{-- Periode Magang --}}
                                <td class="px-4 py-3.5">
                                    @if($app->tanggal_mulai && $app->tanggal_selesai)
                                        <div class="flex flex-col gap-1">
                                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300 flex items-center gap-1">
                                                <i class="far fa-calendar-check text-slate-400 dark:text-slate-500"></i>
                                                {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} &rarr; {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}
                                            </span>
                                            <span class="text-[9px] text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 border border-teal-200 dark:border-teal-900/40 px-2 py-0.5 rounded-md w-fit font-bold font-mono">
                                                {{ \Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai)) }} Hari
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 dark:text-slate-500 italic">-</span>
                                    @endif
                                </td>

                                {{-- Status Terkini --}}
                                <td class="px-4 py-3.5 text-center">
                                    <x-ui.badge :status="$app->status" />
                                </td>

                                {{-- Aksi / Detail Pelacakan --}}
                                <td class="px-4 py-3.5 text-center">
                                    <button type="button"
                                            @click="openDetail({{ json_encode($appItemJson) }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 dark:bg-slate-700/80 hover:bg-teal-50 dark:hover:bg-teal-950/60 text-slate-700 dark:text-slate-300 hover:text-teal-700 dark:hover:text-teal-300 border border-slate-200 dark:border-slate-600 rounded-xl text-xs font-bold transition shadow-2xs">
                                        <i class="fas fa-search-location text-teal-600 dark:text-teal-400 text-xs"></i>
                                        <span>Detail</span>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400 dark:text-slate-500">
                                        <div class="w-16 h-16 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mb-3 border border-slate-200 dark:border-slate-700">
                                            <i class="fas fa-inbox text-2xl text-slate-400 dark:text-slate-500"></i>
                                        </div>
                                        <p class="text-slate-900 dark:text-slate-100 font-bold">Tidak ada permohonan pendaftaran yang ditemukan</p>
                                        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Coba ubah kriteria filter dinas, status, atau kata kunci pencarian Anda.</p>
                                        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Coba sesuaikan kriteria filter atau kata kunci pencarian Anda.</p>
                                        <a href="{{ route('admin.laporan.pendaftaran') }}" class="mt-4 text-teal-600 dark:text-teal-400 hover:underline text-sm font-bold">
                                            Reset Semua Filter
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                {{-- MOBILE CARD VIEW (Visible on Small Screens) --}}
                <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-700/60">
                    @forelse($applications as $app)
                    @php
                        $statusValue = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->value : (string)$app->status;
                        $appItemJson = [
                            'id' => $app->id,
                            'nomor_reg' => $app->nomor_registrasi ?? ('REG-' . $app->id),
                            'nama' => $app->user->name ?? '-',
                            'email' => $app->user->email ?? '-',
                            'nim' => $app->user->nim ?? '-',
                            'kampus' => $app->user->asal_instansi ?? ($app->user->university->name ?? ($app->user->school->name ?? '-')),
                            'jurusan' => $app->user->major ?? ($app->user->jurusan ?? '-'),
                            'phone' => $app->user->phone_number ?? ($app->user->nomor_telepon ?? '-'),
                            'dinas' => $app->position->instansi->nama_dinas ?? '-',
                            'posisi' => $app->position->judul_posisi ?? '-',
                            'status' => $statusValue,
                            'status_label' => $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->label() : ucfirst($statusValue),
                            'tgl_lamar' => \Carbon\Carbon::parse($app->created_at)->translatedFormat('d F Y, H:i'),
                            'tgl_mulai' => $app->tanggal_mulai ? \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d F Y') : null,
                            'tgl_selesai' => $app->tanggal_selesai ? \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d F Y') : null,
                            'durasi' => ($app->tanggal_mulai && $app->tanggal_selesai) ? \Carbon\Carbon::parse($app->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($app->tanggal_selesai)) . ' Hari' : null,
                            'pembimbing' => $app->pembimbing_lapangan->name ?? null,
                            'is_auto' => (bool)$app->is_automatic_placement,
                            'surat_url' => $app->surat_pengantar_path ? route('storage.access', ['type' => 'surat', 'filename' => basename($app->surat_pengantar_path)]) : null,
                            'cv_url' => $app->cv_path ? route('storage.access', ['type' => 'cv', 'filename' => basename($app->cv_path)]) : null,
                            'rejected_reason' => $app->rejected_reason ?? null,
                            'timelines' => $app->timelines->map(function($tl) {
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
                    @endphp
                    <div x-show="matchesSearch({{ json_encode($appItemJson) }})" 
                         class="p-4 bg-white dark:bg-slate-800/90 space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="px-2 py-0.5 rounded text-[11px] font-black bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-600 font-mono">
                                    {{ $app->nomor_registrasi ?? ('REG-' . $app->id) }}
                                </span>
                                <p class="text-[10px] text-slate-400 mt-1">
                                    <i class="far fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::parse($app->created_at)->translatedFormat('d M Y, H:i') }}
                                </p>
                            </div>
                            <div>
                                <x-ui.badge :status="$app->status" />
                            </div>
                        </div>

                        <div>
                            <p class="text-sm font-black text-slate-900 dark:text-slate-100">{{ $app->user->name ?? '-' }}</p>
                            <p class="text-xs text-teal-700 dark:text-teal-400 font-bold">{{ $app->user->asal_instansi ?? ($app->user->university->name ?? ($app->user->school->name ?? '-')) }}</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">NIM: {{ $app->user->nim ?? '-' }} • {{ $app->user->major ?? ($app->user->jurusan ?? '-') }}</p>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-900/60 p-2.5 rounded-xl border border-slate-100 dark:border-slate-700/60 text-xs space-y-1">
                            <p class="text-slate-800 dark:text-slate-200 font-bold flex items-center gap-1.5">
                                <i class="fas fa-building text-teal-600 text-[10px]"></i>
                                {{ $app->position->instansi->nama_dinas ?? '-' }}
                            </p>
                            <p class="text-teal-700 dark:text-teal-400 font-semibold flex items-center gap-1.5">
                                <i class="fas fa-briefcase text-[10px]"></i>
                                {{ $app->position->judul_posisi ?? '-' }}
                            </p>
                            @if($app->tanggal_mulai && $app->tanggal_selesai)
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1 pt-1 border-t border-slate-200 dark:border-slate-700">
                                <i class="far fa-calendar-check text-[10px]"></i>
                                {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}
                            </p>
                            @endif
                        </div>

                        <div class="flex items-center justify-end pt-1">
                            <button type="button" 
                                    @click="openDetail({{ json_encode($appItemJson) }})"
                                    class="w-full py-2 bg-teal-50 dark:bg-teal-950/60 hover:bg-teal-100 dark:hover:bg-teal-900/60 text-teal-700 dark:text-teal-300 font-bold text-xs rounded-xl border border-teal-200 dark:border-teal-800/60 flex items-center justify-center gap-1.5 transition">
                                <i class="fas fa-search-location text-xs"></i>
                                <span>Lihat Detail & Pelacakan Status</span>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-400 dark:text-slate-500 text-sm">
                        Tidak ada permohonan pendaftaran yang ditemukan.
                    </div>
                    @endforelse
                </div>

                {{-- Pagination Links --}}
                @if($applications->hasPages())
                <div class="p-5 sm:p-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60">
                    {{ $applications->links() }}
                </div>
                @endif
            </div>

        </div>

        {{-- MODAL DETAIL PELACAKAN PERMOHONAN MAGANG --}}
        <div x-cloak 
             x-show="activeApp !== null" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                {{-- Backdrop --}}
                <div x-show="activeApp !== null" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="closeDetail()"
                     class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" 
                     aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal Panel --}}
                <div x-show="activeApp !== null" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl sm:rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full border border-slate-200 dark:border-slate-700">
                    
                    {{-- Modal Header --}}
                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/80 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                                <i class="fas fa-clipboard-list text-base"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900 dark:text-slate-100" id="modal-title">
                                    Detail & Pelacakan Permohonan
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-mono" x-text="activeApp ? activeApp.nomor_reg : ''"></p>
                            </div>
                        </div>
                        <button type="button" 
                                @click="closeDetail()" 
                                class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700/60 hover:bg-rose-50 dark:hover:bg-rose-500/10 text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 transition flex items-center justify-center">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>

                    {{-- Modal Content Body --}}
                    <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto custom-scrollbar" x-if="activeApp">
                        
                        {{-- Status Summary Card --}}
                        <div class="p-4 rounded-2xl bg-teal-50/50 dark:bg-teal-950/20 border border-teal-200/70 dark:border-teal-900/40 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                            <div>
                                <p class="text-[11px] font-black text-teal-700 dark:text-teal-400 uppercase tracking-wider">Status Permohonan Terkini</p>
                                <p class="text-lg font-black text-slate-900 dark:text-slate-100 mt-0.5" x-text="activeApp.status_label"></p>
                                <p class="text-xs text-slate-500 dark:text-slate-400" x-text="'Diajukan pada: ' + activeApp.tgl_lamar"></p>
                            </div>
                            <div x-show="activeApp.is_auto" class="px-3 py-1 rounded-full text-xs font-bold bg-teal-100 dark:bg-teal-900/60 text-teal-800 dark:text-teal-200 border border-teal-300 dark:border-teal-700">
                                <i class="fas fa-magic mr-1"></i> Penempatan Otomatis
                            </div>
                        </div>

                        {{-- Catatan Penolakan (jika ditolak) --}}
                        <div x-show="activeApp.rejected_reason" class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 text-xs">
                            <p class="font-bold text-rose-800 dark:text-rose-300 flex items-center gap-1.5 mb-1">
                                <i class="fas fa-exclamation-circle"></i> Alasan Penolakan:
                            </p>
                            <p class="text-rose-700 dark:text-rose-200" x-text="activeApp.rejected_reason"></p>
                        </div>

                        {{-- Profil Pemohon --}}
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-3 flex items-center gap-1.5">
                                <i class="fas fa-user-graduate text-teal-500"></i> Identitas Pemohon
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-50 dark:bg-slate-900/60 p-4 rounded-2xl border border-slate-200 dark:border-slate-700/60 text-xs">
                                <div>
                                    <span class="text-slate-400 block font-medium">Nama Lengkap:</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-100" x-text="activeApp.nama"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block font-medium">NIM / NISN:</span>
                                    <span class="font-mono font-bold text-slate-800 dark:text-slate-100" x-text="activeApp.nim"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block font-medium">Asal Instansi Pendidikan:</span>
                                    <span class="font-bold text-teal-700 dark:text-teal-400" x-text="activeApp.kampus"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block font-medium">Program Studi / Jurusan:</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-100" x-text="activeApp.jurusan"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block font-medium">Email:</span>
                                    <span class="text-slate-800 dark:text-slate-100 font-medium" x-text="activeApp.email"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block font-medium">No. Telepon / WhatsApp:</span>
                                    <span class="text-slate-800 dark:text-slate-100 font-medium" x-text="activeApp.phone"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Info Penempatan & Formasi --}}
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-3 flex items-center gap-1.5">
                                <i class="fas fa-building text-teal-500"></i> SKPD & Formasi Magang
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-50 dark:bg-slate-900/60 p-4 rounded-2xl border border-slate-200 dark:border-slate-700/60 text-xs">
                                <div>
                                    <span class="text-slate-400 block font-medium">Dinas / Instansi:</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-100" x-text="activeApp.dinas"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block font-medium">Posisi / Jabatan:</span>
                                    <span class="font-bold text-teal-700 dark:text-teal-400" x-text="activeApp.posisi"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block font-medium">Periode Kegiatan:</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-100" x-text="(activeApp.tgl_mulai && activeApp.tgl_selesai) ? (activeApp.tgl_mulai + ' s.d ' + activeApp.tgl_selesai) : 'Belum ditetapkan'"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block font-medium">Pembimbing Lapangan:</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-100" x-text="activeApp.pembimbing ? activeApp.pembimbing : 'Belum ditugaskan'"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Berkas & Dokumen Terlampir --}}
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-3 flex items-center gap-1.5">
                                <i class="fas fa-file-alt text-teal-500"></i> Berkas Persyaratan
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                {{-- Surat Pengantar --}}
                                <div class="p-3 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2.5 truncate">
                                        <i class="fas fa-file-pdf text-rose-500 text-base"></i>
                                        <div class="truncate">
                                            <p class="font-bold text-slate-800 dark:text-slate-200 truncate">Surat Pengantar</p>
                                            <p class="text-[10px] text-slate-400">Dari Institusi Pendidikan</p>
                                        </div>
                                    </div>
                                    <template x-if="activeApp.surat_url">
                                        <a :href="activeApp.surat_url" target="_blank" 
                                           class="px-2.5 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1">
                                            <i class="fas fa-external-link-alt text-[10px]"></i> Buka
                                        </a>
                                    </template>
                                    <template x-if="!activeApp.surat_url">
                                        <span class="text-slate-400 italic text-[11px]">Tidak Ada</span>
                                    </template>
                                </div>

                                {{-- Dokumen CV --}}
                                <div class="p-3 bg-slate-50 dark:bg-slate-900/60 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2.5 truncate">
                                        <i class="fas fa-id-card text-blue-500 text-base"></i>
                                        <div class="truncate">
                                            <p class="font-bold text-slate-800 dark:text-slate-200 truncate">Curriculum Vitae (CV)</p>
                                            <p class="text-[10px] text-slate-400">Biodata Peserta</p>
                                        </div>
                                    </div>
                                    <template x-if="activeApp.cv_url">
                                        <a :href="activeApp.cv_url" target="_blank" 
                                           class="px-2.5 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1">
                                            <i class="fas fa-external-link-alt text-[10px]"></i> Buka
                                        </a>
                                    </template>
                                    <template x-if="!activeApp.cv_url">
                                        <span class="text-slate-400 italic text-[11px]">Tidak Ada</span>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Visual Timeline Riwayat Pelacakan Status --}}
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-3 flex items-center gap-1.5">
                                <i class="fas fa-history text-teal-500"></i> Riwayat Mutasi Status & Pelacakan
                            </h4>
                            <div class="bg-slate-50 dark:bg-slate-900/60 p-4 rounded-2xl border border-slate-200 dark:border-slate-700/60">
                                <template x-if="activeApp.timelines && activeApp.timelines.length > 0">
                                    <div class="relative border-l-2 border-teal-500/40 ml-3 pl-5 space-y-4">
                                        <template x-for="(item, idx) in activeApp.timelines" :key="idx">
                                            <div class="relative">
                                                <div class="absolute -left-[27px] top-0.5 w-3.5 h-3.5 rounded-full bg-teal-600 border-2 border-white dark:border-slate-800 shadow-2xs"></div>
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs font-bold text-slate-900 dark:text-slate-100" x-text="item.event"></span>
                                                        <span class="text-[10px] text-slate-400 font-mono" x-text="item.date"></span>
                                                    </div>
                                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5" x-text="'Oleh: ' + item.actor"></p>
                                                    <p x-show="item.notes" class="text-xs text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 p-2 rounded-lg border border-slate-200 dark:border-slate-700 mt-1 font-medium" x-text="item.notes"></p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="!activeApp.timelines || activeApp.timelines.length === 0">
                                    <div class="text-center py-4 text-slate-400 text-xs italic">
                                        Belum ada riwayat mutasi status yang tercatat secara spesifik untuk berkas ini.
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-3.5 bg-slate-50 dark:bg-slate-900/80 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                        <button type="button" 
                                @click="closeDetail()"
                                class="px-5 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-200 text-xs font-bold rounded-xl transition">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</x-app-layout>
