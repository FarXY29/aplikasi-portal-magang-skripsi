<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#0d9488">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Portal Magang">
    <link rel="apple-touch-icon" href="{{ asset('images/Banjarmasin_Logo.svg.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <title>Verifikasi Sertifikat - Portal Magang Banjarmasin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full overflow-hidden border border-gray-100 dark:border-gray-700">
        @if(!$isValid)
        <div class="bg-rose-600 p-6 text-center">
            <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3 shadow">
                <i class="fas fa-times-circle text-3xl text-rose-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-white">Data Tidak Ditemukan</h2>
            <p class="text-rose-100 text-sm mt-1">Token atau nomor sertifikat tidak terdaftar pada sistem.</p>
        </div>
        <div class="p-6 text-center space-y-4">
            <p class="text-sm text-gray-600 dark:text-gray-400">Pastikan Anda memindai QR Code resmi yang diterbitkan oleh Pemerintah Kota Banjarmasin.</p>
            <a href="{{ route('home') }}" class="inline-block px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-xl transition">Kembali ke Beranda</a>
        </div>
        @elseif($app->certificate && $app->certificate->isRevoked())
        <div class="bg-red-600 p-6 text-center">
            <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3 shadow">
                <i class="fas fa-ban text-3xl text-red-600"></i>
            </div>
            <h2 class="text-2xl font-black text-white uppercase tracking-wide">Sertifikat Dibatalkan</h2>
            <p class="text-red-100 text-xs mt-1">Sertifikat ini dinyatakan TIDAK BERLAKU / DICABUT oleh Pemkot Banjarmasin</p>
        </div>
        <div class="p-6 space-y-4">
            <div class="p-4 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-left">
                <p class="text-xs font-bold text-red-800 dark:text-red-300 uppercase tracking-wider mb-1">Alasan Pembatalan Resmi:</p>
                <p class="text-sm text-red-700 dark:text-red-300">{{ $app->certificate->revoked_reason ?? 'Pencabutan status legalitas oleh administrator.' }}</p>
                @if($app->certificate->revoked_at)
                <p class="text-[11px] text-red-500 mt-2 font-mono">Tanggal Dicabut: {{ $app->certificate->revoked_at->translatedFormat('d F Y - H:i') }} WITA</p>
                @endif
            </div>

            <div class="text-center pb-4 border-b border-gray-100 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Identitas Pemilik</p>
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ $app->user->name }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $app->user->asal_instansi ?? 'Universitas/Sekolah' }}</p>
                <p class="text-xs font-mono bg-gray-100 dark:bg-gray-700 inline-block px-2 py-1 mt-1 rounded text-gray-600 dark:text-gray-300">Nomor: {{ $app->nomor_sertifikat ?? '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">Lokasi Penempatan</p>
                <p class="font-bold text-gray-800 dark:text-gray-200">{{ $app->position->instansi->nama_dinas }}</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-lg text-xs text-gray-500 dark:text-gray-400 text-center mt-4">
                Peringatan: Dokumen ini tidak dapat digunakan sebagai bukti kelulusan magang yang sah.
            </div>
        </div>
        @elseif($app->status?->value === 'selesai')
        <div class="bg-emerald-600 p-6 text-center">
            <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3 shadow">
                <i class="fas fa-certificate text-3xl text-emerald-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-white">Sertifikat Sah & Valid</h2>
            <p class="text-emerald-100 text-sm mt-1">Lulus Program Magang Resmi Pemkot Banjarmasin</p>
        </div>

        <div class="p-6 space-y-4">
            <div class="text-center pb-4 border-b border-gray-100 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Identitas Peserta</p>
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ $app->user->name }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $app->user->asal_instansi ?? 'Universitas/Sekolah' }}</p>
                <p class="text-xs font-mono bg-gray-100 dark:bg-gray-700 inline-block px-2 py-1 mt-1 rounded text-gray-600 dark:text-gray-300">NIK/NIM: {{ $app->user->nik ?? '-' }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs">Nomor Sertifikat</p>
                    <p class="font-mono font-bold text-gray-800 dark:text-gray-200">{{ $app->nomor_sertifikat ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs">Predikat Nilai</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">
                        {{ $app->nilai_angka >= 85 ? 'Sangat Baik' : ($app->nilai_angka >= 70 ? 'Baik' : 'Cukup') }} 
                        ({{ number_format((float) ($app->nilai_angka ?? 0), 1) }})
                    </p>
                </div>
            </div>
            
            @if($app->certificate)
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-start gap-4">
                @if($app->certificate->qr_code_path)
                <div class="w-20 h-20 shrink-0 bg-white dark:bg-gray-800 p-1 border rounded shadow-sm">
                    <img src="{{ Storage::url($app->certificate->qr_code_path) }}" alt="QR Code" class="w-full h-full object-contain">
                </div>
                @endif
                <div class="flex-1">
                    <p class="text-gray-500 dark:text-gray-400 text-[10px] uppercase">Penandatangan Resmi</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200 text-sm">{{ $app->certificate->signer_name ?? '-' }}</p>
                    
                    <p class="text-gray-500 dark:text-gray-400 text-[10px] uppercase mt-2">Digital Signature Hash</p>
                    <p class="font-mono text-[11px] text-teal-700 dark:text-teal-400 break-all bg-teal-50 dark:bg-teal-950/40 px-2 py-1 rounded">{{ $app->certificate->signature_mock }}</p>
                </div>
            </div>
            @endif

            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">Instansi Penempatan</p>
                <p class="font-bold text-gray-800 dark:text-gray-200">{{ $app->position->instansi->nama_dinas }}</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-lg text-xs text-gray-500 dark:text-gray-400 text-center mt-4">
                Verifikasi ini dihasilkan secara resmi oleh sistem Portal Magang Pemerintah Kota Banjarmasin.
            </div>
        </div>
        @else
        <div class="bg-teal-600 p-6 text-center">
            <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3 shadow">
                <i class="fas fa-user-check text-3xl text-teal-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-white">Peserta Aktif</h2>
            <p class="text-teal-100 text-sm mt-1">Identitas Resmi Peserta Magang</p>
        </div>

        <div class="p-6 space-y-4">
            <div class="text-center pb-4 border-b border-gray-100 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Identitas Peserta</p>
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ $app->user->name }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $app->user->asal_instansi ?? 'Universitas/Sekolah' }}</p>
                <p class="text-xs font-mono bg-gray-100 dark:bg-gray-700 inline-block px-2 py-1 mt-1 rounded text-gray-600 dark:text-gray-300">NIK/NIM: {{ $app->user->nik ?? '-' }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs">Periode Magang</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">
                        {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} - 
                        {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs">Status Saat Ini</p>
                    <p class="font-bold text-teal-600 uppercase"><i class="fas fa-circle text-[8px] mr-1"></i> Sedang Magang</p>
                </div>
            </div>

            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">Lokasi Penempatan</p>
                <p class="font-bold text-gray-800 dark:text-gray-200">{{ $app->position->instansi->nama_dinas }}</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-lg text-xs text-gray-500 dark:text-gray-400 text-center mt-4">
                Verifikasi ini dihasilkan secara otomatis oleh sistem Portal Magang Pemerintah Kota Banjarmasin.
            </div>
        </div>
        @endif
    </div>
</body>
</html>