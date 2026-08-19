<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    @push('styles')
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
        <style>
            .action-btn { transition: all 0.2s ease; }
            .action-btn:hover { transform: translateY(-1px); }
            .table-row { transition: background-color 0.15s ease; }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-teal-600 dark:bg-teal-500 text-white flex items-center justify-center shadow-sm">
                    <i class="fas fa-certificate text-sm"></i>
                </div>
                <div>
                    <h2 class="font-black text-xl text-gray-900 dark:text-gray-100 leading-tight">Registri & Tata Kelola Sertifikat Kota</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium hidden sm:block">Direktori seluruh sertifikat magang resmi, verifikasi keabsahan, dan audit pembatalan (*revocation*)</p>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.certificates.export_pdf', request()->query()) }}" target="_blank" class="action-btn inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-gray-700 shadow-xs">
                    <i class="fas fa-print mr-2 text-[10px]"></i> Cetak Buku Register PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 font-[Inter]" x-data="{ revokeModalOpen: false, selectedCert: null, revokeReason: '' }">
        
        <!-- Navigation Back -->
        <div class="flex justify-between items-center print:hidden">
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs text-gray-400 group-hover:text-teal-600"></i>
                </div>
                Kembali ke Dashboard
            </a>
        </div>

        @if(session('success'))
            <x-ui.alert type="success" class="mb-2">
                {{ session('success') }}
            </x-ui.alert>
        @endif

        @if(session('error'))
            <x-ui.alert type="error" class="mb-2">
                {{ session('error') }}
            </x-ui.alert>
        @endif

        <!-- Metric KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Sertifikat Terbit</p>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-gray-100 mt-1">{{ number_format($totalCertificates) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center">
                    <i class="fas fa-file-signature text-xl"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status Sah / Aktif</p>
                    <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($totalActive) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dicabut / Dibatalkan</p>
                    <h3 class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1">{{ number_format($totalRevoked) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                    <i class="fas fa-ban text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <form method="GET" action="{{ route('admin.certificates.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[220px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor SK, token, nama peserta, atau NIK..." class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
            </div>

            <div class="w-44">
                <select name="status" class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                    <option value="">Semua Status Legalitas</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif / Sah</option>
                    <option value="revoked" {{ request('status') == 'revoked' ? 'selected' : '' }}>Dicabut / Dibatalkan</option>
                </select>
            </div>

            <div class="w-52">
                <select name="instansi_id" class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 shadow-xs">
                    <option value="">Semua Instansi / Dinas</option>
                    @foreach($instansis as $instansi)
                        <option value="{{ $instansi->id }}" {{ request('instansi_id') == $instansi->id ? 'selected' : '' }}>{{ $instansi->nama_dinas }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="action-btn px-4 py-2 bg-gray-800 dark:bg-gray-700 text-white rounded-xl font-bold text-xs hover:bg-gray-700 transition">
                <i class="fas fa-filter mr-1.5"></i> Filter
            </button>

            @if(request()->hasAny(['search', 'status', 'instansi_id']))
                <a href="{{ route('admin.certificates.index') }}" class="action-btn px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl font-bold text-xs hover:bg-gray-200 transition">
                    Reset
                </a>
            @endif
        </form>

        <!-- Table Registri Data -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Nomor Sertifikat</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Peserta Magang</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Instansi Penempatan</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">Nilai & Predikat</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Tanggal Terbit</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">Status</th>
                            <th class="py-3.5 px-4 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-xs">
                        @forelse($certificates as $cert)
                            <tr class="table-row hover:bg-gray-50/60 dark:hover:bg-gray-700/30">
                                <td class="py-3.5 px-4">
                                    <div class="font-mono font-bold text-gray-900 dark:text-gray-100 text-sm">{{ $cert->nomor_sertifikat }}</div>
                                    <div class="text-[10px] font-mono text-gray-400 truncate max-w-[140px]">Token: {{ $cert->token_verifikasi }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-gray-900 dark:text-gray-100 text-sm">{{ $cert->application?->user?->name ?? 'Peserta' }}</div>
                                    <div class="text-[11px] text-gray-500 dark:text-gray-400">{{ $cert->application?->user?->asal_instansi ?? '-' }}</div>
                                    <div class="text-[10px] font-mono text-gray-400">NIK: {{ $cert->application?->user?->nik ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-gray-800 dark:text-gray-200">{{ $cert->application?->position?->instansi?->nama_dinas ?? '-' }}</div>
                                    <div class="text-[11px] text-gray-500 dark:text-gray-400">{{ $cert->application?->position?->judul_posisi ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @php
                                        $nilai = (float) ($cert->application?->nilai_angka ?? 0);
                                        $predikat = $nilai >= 85 ? 'Sangat Baik' : ($nilai >= 70 ? 'Baik' : ($nilai > 0 ? 'Cukup' : '-'));
                                    @endphp
                                    <div class="font-black text-sm text-gray-900 dark:text-gray-100">{{ number_format($nilai, 1) }}</div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $nilai >= 85 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-teal-50 text-teal-700 dark:bg-teal-950/40 dark:text-teal-400' }}">
                                        {{ $predikat }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-gray-600 dark:text-gray-400">
                                    {{ $cert->published_at ? $cert->published_at->translatedFormat('d M Y') : ($cert->created_at ? $cert->created_at->translatedFormat('d M Y') : '-') }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($cert->isRevoked())
                                        <div class="inline-flex flex-col items-center">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200 dark:border-rose-800" title="Alasan: {{ $cert->revoked_reason }}">
                                                <i class="fas fa-ban mr-1 text-[9px]"></i> Dicabut
                                            </span>
                                            @if($cert->revoked_at)
                                                <span class="text-[9px] text-gray-400 mt-0.5">{{ $cert->revoked_at->format('d/m/Y') }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                            <i class="fas fa-check-circle mr-1 text-[9px]"></i> Sah / Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.certificates.show', $cert->id) }}" class="action-btn w-7 h-7 rounded-lg bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 flex items-center justify-center hover:bg-teal-100" title="Detail Sertifikat">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>

                                        <a href="{{ route('certificate.verify', $cert->token_verifikasi) }}" target="_blank" class="action-btn w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center hover:bg-blue-100" title="Halaman Verifikasi Publik (QR)">
                                            <i class="fas fa-qrcode text-xs"></i>
                                        </a>

                                        @if(!$cert->isRevoked())
                                            <button type="button" @click="revokeModalOpen = true; selectedCert = { id: {{ $cert->id }}, nomor: '{{ $cert->nomor_sertifikat }}', nama: '{{ addslashes($cert->application?->user?->name ?? 'Peserta') }}' }; revokeReason = ''" class="action-btn w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 flex items-center justify-center hover:bg-rose-100" title="Cabut Status Sertifikat">
                                                <i class="fas fa-ban text-xs"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('admin.certificates.restore', $cert->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memulihkan status sertifikat ini menjadi Aktif/Sah kembali?')" class="inline">
                                                @csrf
                                                <button type="submit" class="action-btn w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center hover:bg-emerald-100" title="Pulihkan Sertifikat">
                                                    <i class="fas fa-undo text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-gray-400 dark:text-gray-500">
                                    <i class="fas fa-certificate text-3xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                    <p class="font-medium">Belum ada sertifikat magang yang sesuai dengan kriteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($certificates->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $certificates->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Pencabutan Sertifikat (Revocation) -->
        <div x-show="revokeModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
            <div @click.away="revokeModalOpen = false" class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-lg w-full p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3 text-rose-600 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/40 flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-lg text-gray-900 dark:text-gray-100 leading-tight">Pencabutan Legalitas Sertifikat</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Batalkan keabsahan sertifikat resmi peserta magang</p>
                    </div>
                </div>

                <div class="p-3 bg-gray-50 dark:bg-gray-900 rounded-xl text-xs space-y-1 mb-4">
                    <p class="text-gray-600 dark:text-gray-400">Nomor Sertifikat: <span class="font-bold font-mono text-gray-900 dark:text-gray-100" x-text="selectedCert ? selectedCert.nomor : '-'"></span></p>
                    <p class="text-gray-600 dark:text-gray-400">Nama Peserta: <span class="font-bold text-gray-900 dark:text-gray-100" x-text="selectedCert ? selectedCert.nama : '-'"></span></p>
                </div>

                <form :action="'{{ url('/admin/certificates') }}/' + (selectedCert ? selectedCert.id : '') + '/revoke'" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="revoked_reason" class="block text-xs font-black uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                            Alasan Resmi Pencabutan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="revoked_reason" name="revoked_reason" x-model="revokeReason" required rows="3" minlength="10" placeholder="Jelaskan alasan resmi pembatalan sertifikat (contoh: Pemalsuan data absensi, pembatalan status kelulusan oleh instansi)..." class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:border-rose-500 focus:ring-rose-500 py-2.5 px-3 shadow-xs"></textarea>
                        <p class="text-[10px] text-gray-400 mt-1">Alasan ini akan ditampilkan pada halaman publik ketika QR Code sertifikat dipindai.</p>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-2.5">
                        <button type="button" @click="revokeModalOpen = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-bold text-xs hover:bg-gray-200 transition">
                            Batal
                        </button>
                        <button type="submit" :disabled="revokeReason.trim().length < 10" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white rounded-xl font-bold text-xs uppercase tracking-wider transition shadow-sm">
                            <i class="fas fa-ban mr-1.5"></i> Konfirmasi Cabut Sertifikat
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
