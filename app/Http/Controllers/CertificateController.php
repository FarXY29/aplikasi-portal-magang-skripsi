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
        $app = Application::with(['user', 'position.instansi', 'pembimbing_lapangan', 'certificate']) 
                ->where('token_verifikasi', $token)
                ->whereIn('status', ['diterima', 'selesai'])
                ->firstOrFail();

        return view('public.certificate.verify', compact('app'));
    }

    public function showScanner()
    {
        return view('public.certificate.scanner');
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
    public function store(Request $request, $applicationId, \App\Services\CertificateService $certificateService)
    {
        $app = Application::with('position.instansi')->findOrFail($applicationId);

        // Proteksi Lintas Instansi
        if (auth()->user()->role === 'admin_instansi' && auth()->user()->instansi_id !== $app->position->instansi_id) {
            abort(403, 'Akses ditolak. Anda tidak berwenang mengelola data instansi lain.');
        }

        $request->validate([
            'nomor_sertifikat' => 'required|string|max:100|unique:certificates,nomor_sertifikat',
            'tanggal_sertifikat' => 'required|date',
        ]);

        // Gunakan CertificateService sebagai satu-satunya jalur resmi
        $certificate = $certificateService->generateCertificate($app);

        // Update nomor dan tanggal terbit custom
        $certificate->update([
            'nomor_sertifikat' => $request->nomor_sertifikat,
            'published_at' => $request->tanggal_sertifikat
        ]);

        $app->update([
            'nomor_sertifikat' => $request->nomor_sertifikat,
            'token_verifikasi' => $certificate->token_verifikasi,
            'status' => 'selesai'
        ]);

        // Siapkan Data untuk View PDF
        $data = [
            'app' => $app,
            'user' => $app->user,
            'instansi' => $app->position->instansi,
            'position' => $app->position,
            'tanggal' => Carbon::parse($request->tanggal_sertifikat)->translatedFormat('d F Y'),
            'qr_code' => route('certificate.verify', $certificate->token_verifikasi)
        ];

        // Generate PDF menggunakan view yang benar
        $pdf = Pdf::loadView('pdf.peserta.sertifikat', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('Sertifikat-' . Str::slug($app->user->name) . '.pdf');
    }
}