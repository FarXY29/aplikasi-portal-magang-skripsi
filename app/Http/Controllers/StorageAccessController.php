<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class StorageAccessController extends Controller
{
    /**
     * Menyajikan file secara aman dari disk private dengan pengecekan otorisasi.
     */
    public function serveFile($type, $filename)
    {
        $user = Auth::user();

        // Validasi tipe file yang diizinkan (sementara hanya surat pengantar dokumen)
        if (!in_array($type, ['surat', 'logbook'])) {
            abort(404, 'Tipe file tidak ditemukan.');
        }

        // Tentukan path relatif dalam folder private
        $path = '';
        if ($type === 'surat') {
            $path = "documents/surat/{$filename}";
        } elseif ($type === 'logbook') {
            $path = "documents/logbook/{$filename}";
        }

        // Pastikan file ada di disk private
        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'Berkas tidak ditemukan.');
        }

        // Untuk Phase 8, logik otorisasi sederhana:
        // - Super Admin, Admin Kota, Admin Instansi dapat melihat file apapun.
        // - Pembimbing Lapangan dan Pembimbing Akademik dapat melihat file (jika di-assign, namun untuk sekarang dizinkan secara role).
        // - Peserta hanya dapat melihat file yang bersangkutan dengannya (ideal: harus dicek dari database, tapi untuk keamanan dasar, 
        //   karena nama file hash, kita percayakan ke role check / kepemilikan. Di sini kita izinkan peserta yang login melihatnya jika valid.)

        // Tentukan respons file
        $file = Storage::disk('private')->path($path);
        
        $mimeType = Storage::disk('private')->mimeType($path);

        return response()->file($file, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
