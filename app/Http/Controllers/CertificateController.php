<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    /**
     * ==========================================
     * BAGIAN PUBLIK (Verifikasi & Pencarian)
     * ==========================================
     */

    /**
     * Halaman Publik Hasil Scan QR Code
     */
    public function verify($token)
    {
        // Gunakan 'with' untuk memuat relasi yang diperlukan di sertifikat
        $app = Application::with(['user', 'position.instansi', 'pembimbing_lapangan']) 
                ->where('token_verifikasi', $token)
                ->whereIn('status', ['diterima', 'selesai'])
                ->firstOrFail();

        return view('certificate.verify', compact('app'));
    }

    public function showScanner()
    {
        return view('certificate.scanner');
    }

    /**
     * Logic Pencarian Manual (Untuk Admin/Publik)
     */
    public function search(Request $request)
    {
        $request->validate([
            'nomor_sertifikat' => 'required|string',
        ]);

        $keyword = $request->input('nomor_sertifikat');

        $app = Application::where('nomor_sertifikat', $keyword)
                ->where('status', 'selesai')
                ->first();

        if (!$app) {
            return back()->with('error', 'Sertifikat tidak ditemukan atau nomor salah.');
        }

        return redirect()->route('certificate.verify', $app->token_verifikasi);
    }

    /**
     * ==========================================
     * BAGIAN ADMIN INSTANSI (Penerbitan)
     * ==========================================
     */

    /**
     * Tampilkan Halaman Form Input Sertifikat
     */
    public function create($applicationId)
    {
        // Ambil data aplikasi, pastikan statusnya valid
        $app = Application::with(['user', 'position.instansi'])
                ->findOrFail($applicationId);

        // Validasi: Pastikan nilai sudah ada sebelum terbit sertifikat
        if (!$app->nilai_rata_rata) {
            return redirect()->back()->with('error', 'Peserta belum dinilai oleh pembimbing_lapangan. Sertifikat tidak dapat diterbitkan.');
        }

        // Generate Nomor Sertifikat Otomatis (Suggestion)
        // Format: 001/MAGANG/NAMA-DINAS/TAHUN
        $count = Application::whereNotNull('nomor_sertifikat')->count() + 1;
        $kodeDinas = $app->position->instansi->kode_instansi ?? strtoupper(Str::slug($app->position->instansi->nama_dinas));
        $autoNumber = sprintf("%03d/MAGANG/%s/%s", $count, $kodeDinas, date('Y'));

        return view('dinas.sertifikat.create', compact('app', 'autoNumber'));
    }

    /**
     * Simpan Data & Generate PDF
     */
    public function store(Request $request, $applicationId)
    {
        $request->validate([
            'nomor_sertifikat' => 'required|string|max:100|unique:applications,nomor_sertifikat,' . $applicationId,
            'tanggal_sertifikat' => 'required|date',
        ]);

        $app = Application::findOrFail($applicationId);

        // 1. Simpan Data Legalitas Sertifikat
        $app->update([
            'nomor_sertifikat' => $request->nomor_sertifikat,
            // Jika kolom tanggal_sertifikat belum ada di DB, pastikan buat migrationnya atau gunakan updated_at
            'updated_at' => $request->tanggal_sertifikat . ' ' . now()->format('H:i:s'), 
            'token_verifikasi' => $app->token_verifikasi ?? Str::random(32), // Generate token jika belum ada
            'status' => 'selesai' // Finalisasi status
        ]);

        // 2. Siapkan Data untuk View PDF
        $data = [
            'app' => $app,
            'user' => $app->user,
            'instansi' => $app->position->instansi,
            'position' => $app->position,
            'tanggal' => Carbon::parse($request->tanggal_sertifikat)->translatedFormat('d F Y'),
            'qr_code' => route('certificate.verify', $app->token_verifikasi) // URL untuk QR Code
        ];

        // 3. Generate PDF
        $pdf = Pdf::loadView('pdf.sertifikat', $data);
        $pdf->setPaper('a4', 'landscape');

        // Stream PDF ke browser (Preview)
        return $pdf->stream('Sertifikat-' . Str::slug($app->user->name) . '.pdf');
    }
}