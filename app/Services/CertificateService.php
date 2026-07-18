<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Certificate;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class CertificateService
{
    /**
     * Generate Dummy TTE, QR Code, dan masukkan ke tabel certificates.
     */
    public function generateCertificate(Application $application): Certificate
    {
        // 1. Buat Token Verifikasi & Nomor Sertifikat unik
        $token = Str::random(32);
        $tahun = date('Y');
        
        $num = 1;
        do {
            $nomor_sertifikat = 'MG-' . $tahun . '-' . str_pad($num, 5, '0', STR_PAD_LEFT);
            $num++;
        } while (Application::where('nomor_sertifikat', $nomor_sertifikat)->exists() || Certificate::where('nomor_sertifikat', $nomor_sertifikat)->exists());
        
        // 2. Dummy Signature Hash (seolah-olah hash dokumen PDF)
        $signature_mock = hash('sha256', $application->id . $token . time());
        
        // 3. Generate QR Code
        $url_verifikasi = route('verify.certificate', ['token' => $token]);
        
        // Pastikan folder public/qrcodes ada
        if (!Storage::disk('public')->exists('qrcodes')) {
            Storage::disk('public')->makeDirectory('qrcodes');
        }

        $qrCodeFilename = 'qrcodes/' . $token . '.svg';
        
        // Generate and save QR code as SVG
        QrCode::size(200)->generate($url_verifikasi, storage_path('app/public/' . $qrCodeFilename));

        // 4. Dapatkan nama Pejabat Penandatangan dari Instansi
        $signer_name = $application->position->instansi->nama_pejabat ?? 'Admin Portal Magang';

        // 5. Simpan ke Database
        $certificate = Certificate::create([
            'application_id' => $application->id,
            'nomor_sertifikat' => $nomor_sertifikat,
            'token_verifikasi' => $token,
            'qr_code_path' => $qrCodeFilename,
            'signer_name' => $signer_name,
            'signature_mock' => $signature_mock,
            'published_at' => now(),
        ]);

        // Opsional: Update tabel applications juga untuk backward compatibility jika diperlukan
        $application->update([
            'nomor_sertifikat' => $nomor_sertifikat,
            'token_verifikasi' => $token,
        ]);

        return $certificate;
    }
}
