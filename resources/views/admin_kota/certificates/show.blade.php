<x-app-layout>
    @push('styles')
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    @endpush

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl {{ $certificate->isRevoked() ? 'bg-rose-600' : 'bg-teal-600' }} text-white flex items-center justify-center shadow-sm">
                    <i class="fas fa-certificate text-sm"></i>
                </div>
                <div>
                    <h2 class="font-black text-xl text-gray-900 dark:text-gray-100 leading-tight">Detail Sertifikat & Audit Keabsahan</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium font-mono">{{ $certificate->nomor_sertifikat }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($certificate->isRevoked())
                    <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-black bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200 dark:border-rose-800">
                        <i class="fas fa-ban mr-1.5 text-xs"></i> STATUS: DICABUT / DIBATALKAN
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-black bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                        <i class="fas fa-check-circle mr-1.5 text-xs"></i> STATUS: SAH / AKTIF
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6 font-[Inter]">
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.certificates.index') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs text-gray-400 group-hover:text-teal-600"></i>
                </div>
                Kembali ke Registri Sertifikat
            </a>

            <div class="flex items-center gap-2">
                <a href="{{ route('certificate.verify', $certificate->token_verifikasi) }}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-xs transition">
                    <i class="fas fa-external-link-alt mr-1.5"></i> Buka Halaman Scan QR Publik
                </a>
            </div>
        </div>

        @if($certificate->isRevoked())
            <div class="p-5 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-2xl">
                <div class="flex items-start gap-3.5">
                    <div class="w-9 h-9 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0">
                        <i class="fas fa-exclamation-circle text-base"></i>
                    </div>
                    <div class="space-y-1">
                        <h4 class="font-black text-rose-900 dark:text-rose-200 text-sm">Sertifikat Telah Dicabut / Dibatalkan</h4>
                        <p class="text-xs text-rose-700 dark:text-rose-300"><span class="font-bold">Alasan:</span> {{ $certificate->revoked_reason }}</p>
                        <div class="text-[11px] text-rose-600 dark:text-rose-400 pt-1 font-mono">
                            Dicabut pada: {{ $certificate->revoked_at?->translatedFormat('d F Y - H:i') }} WITA | Oleh: {{ $certificate->revokedBy?->name ?? 'Super Admin' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Informasi Utama -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-5">
                    <h3 class="text-sm font-black uppercase tracking-wider text-gray-900 dark:text-gray-100 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <i class="fas fa-user-graduate text-teal-600 mr-2"></i> Data Peserta & Magang
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <p class="text-gray-400 font-medium">Nama Lengkap</p>
                            <p class="font-bold text-gray-900 dark:text-gray-100 text-sm mt-0.5">{{ $certificate->application?->user?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 font-medium">NIK / NIM</p>
                            <p class="font-mono font-bold text-gray-900 dark:text-gray-100 mt-0.5">{{ $certificate->application?->user?->nik ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 font-medium">Asal Institusi / Kampus</p>
                            <p class="font-bold text-gray-800 dark:text-gray-200 mt-0.5">{{ $certificate->application?->user?->asal_instansi ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 font-medium">Program Studi / Jurusan</p>
                            <p class="font-bold text-gray-800 dark:text-gray-200 mt-0.5">{{ $certificate->application?->user?->majorDetail?->name ?? ($certificate->application?->user?->major ?? '-') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 font-medium">Instansi Penempatan</p>
                            <p class="font-bold text-teal-700 dark:text-teal-400 mt-0.5">{{ $certificate->application?->position?->instansi?->nama_dinas ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 font-medium">Posisi Magang</p>
                            <p class="font-bold text-gray-800 dark:text-gray-200 mt-0.5">{{ $certificate->application?->position?->judul_posisi ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Penilaian Akhir -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-4">
                    <h3 class="text-sm font-black uppercase tracking-wider text-gray-900 dark:text-gray-100 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <i class="fas fa-star text-amber-500 mr-2"></i> Evaluasi & Penilaian Kinerja
                    </h3>

                    @php
                        $app = $certificate->application;
                        $nilai = (float) ($app?->nilai_angka ?? 0);
                    @endphp

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                        <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700">
                            <p class="text-[10px] text-gray-400 uppercase font-bold">Nilai Teknis</p>
                            <p class="text-base font-black text-gray-900 dark:text-gray-100 mt-0.5">{{ $app?->nilai_teknis ?? '-' }}</p>
                        </div>
                        <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700">
                            <p class="text-[10px] text-gray-400 uppercase font-bold">Kedisiplinan</p>
                            <p class="text-base font-black text-gray-900 dark:text-gray-100 mt-0.5">{{ $app?->nilai_disiplin ?? '-' }}</p>
                        </div>
                        <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700">
                            <p class="text-[10px] text-gray-400 uppercase font-bold">Perilaku</p>
                            <p class="text-base font-black text-gray-900 dark:text-gray-100 mt-0.5">{{ $app?->nilai_perilaku ?? '-' }}</p>
                        </div>
                        <div class="p-3 rounded-xl bg-teal-50 dark:bg-teal-950/40 border border-teal-200 dark:border-teal-800">
                            <p class="text-[10px] text-teal-700 dark:text-teal-400 uppercase font-bold">Rata-rata Akhir</p>
                            <p class="text-base font-black text-teal-700 dark:text-teal-300 mt-0.5">{{ number_format($nilai, 1) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Samping: QR & Legalitas Dokumen -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-4 text-center">
                    <h3 class="text-xs font-black uppercase tracking-wider text-gray-700 dark:text-gray-300">QR Code Verifikasi</h3>

                    @if($certificate->qr_code_path && Storage::disk('public')->exists($certificate->qr_code_path))
                        <div class="w-36 h-36 mx-auto p-2 bg-white rounded-xl border border-gray-200 shadow-sm flex items-center justify-center">
                            <img src="{{ Storage::url($certificate->qr_code_path) }}" alt="QR Code" class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="w-36 h-36 mx-auto p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400">
                            <i class="fas fa-qrcode text-3xl mb-1"></i>
                            <span class="text-[10px]">QR Code Dinamis</span>
                        </div>
                    @endif

                    <div class="text-left text-xs space-y-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <div>
                            <p class="text-gray-400 text-[10px] uppercase font-bold">Penandatangan Resmi</p>
                            <p class="font-bold text-gray-800 dark:text-gray-200">{{ $certificate->signer_name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-[10px] uppercase font-bold">Token Keamanan</p>
                            <p class="font-mono text-[10px] text-gray-600 dark:text-gray-400 break-all bg-gray-50 dark:bg-gray-900 p-1.5 rounded">{{ $certificate->token_verifikasi }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-[10px] uppercase font-bold">Digital Signature Hash</p>
                            <p class="font-mono text-[10px] text-teal-700 dark:text-teal-400 break-all bg-teal-50 dark:bg-teal-950/40 p-1.5 rounded">{{ $certificate->signature_mock }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
