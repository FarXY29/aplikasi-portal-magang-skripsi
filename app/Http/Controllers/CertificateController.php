<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Certificate;
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
     * Halaman Publik Hasil Scan QR Code / Verifikasi Token
     */
    public function verify($token)
    {
        $token = trim($token);

        // Cari berdasarkan token_verifikasi di Applications atau di Certificates
        $app = Application::with(['user', 'position.instansi', 'pembimbing_lapangan', 'certificate'])
            ->where(function ($query) use ($token) {
                $query->where('token_verifikasi', $token)
                      ->orWhereHas('certificate', function ($q) use ($token) {
                          $q->where('token_verifikasi', $token);
                      });
            })
            ->whereIn('status', ['diterima', 'selesai'])
            ->first();

        return view('public.verifikasi.verify', [
            'app' => $app,
            'searchedToken' => $token,
            'isValid' => !is_null($app),
        ]);
    }

    /**
     * Halaman Publik Hasil Scan QR Code ID Card Peserta Magang
     */
    public function verifyIdCard($token)
    {
        $token = trim($token);

        $app = Application::with([
            'user.majorDetail.category',
            'position.instansi',
            'pembimbing_lapangan',
            'certificate',
        ])
        ->where('token_verifikasi', $token)
        ->first();

        $idCardStatus = 'invalid';
        if ($app) {
            $statusValue = $app->status_value;
            $today = Carbon::today();
            $endDate = $app->tanggal_selesai ? Carbon::parse($app->tanggal_selesai)->endOfDay() : null;

            if (in_array($statusValue, ['dibatalkan', 'dikeluarkan'])) {
                $idCardStatus = 'revoked';
            } elseif ($statusValue === 'selesai' || ($endDate && $today->gt($endDate))) {
                $idCardStatus = 'finished';
            } elseif ($statusValue === 'diterima') {
                $idCardStatus = 'active';
            } else {
                $idCardStatus = 'pending';
            }
        }

        return view('public.verifikasi.id_card', [
            'app' => $app,
            'searchedToken' => $token,
            'isValid' => !is_null($app),
            'idCardStatus' => $idCardStatus,
        ]);
    }

    public function showScanner()
    {
        return view('public.verifikasi.scanner');
    }

    /**
     * Logic Pencarian Manual (Untuk Admin/Publik)
     */
    public function search(Request $request)
    {
        $request->validate([
            'nomor_sertifikat' => 'required|string',
        ]);

        $keyword = trim($request->input('nomor_sertifikat'));

        // 1. Cek tabel Certificate terlebih dahulu (Nomor Sertifikat atau Token Sertifikat)
        $certificate = Certificate::where('nomor_sertifikat', $keyword)
            ->orWhere('token_verifikasi', $keyword)
            ->first();

        if ($certificate) {
            return redirect()->route('certificate.verify', $certificate->token_verifikasi);
        }

        // 2. Cek tabel Application
        $app = Application::where('nomor_sertifikat', $keyword)
            ->orWhere('token_verifikasi', $keyword)
            ->first();

        if ($app) {
            // Jika aplikasi sudah memiliki nomor sertifikat atau berstatus selesai, arahkan ke sertifikat
            if (!empty($app->nomor_sertifikat) || $app->status_value === 'selesai') {
                return redirect()->route('certificate.verify', $app->token_verifikasi ?? $keyword);
            }

            // Jika peserta aktif atau ID card token
            return redirect()->route('id_card.verify', $app->token_verifikasi ?? $keyword);
        }

        return back()->with('error', 'Data sertifikat atau ID Card tidak ditemukan. Pastikan Nomor Sertifikat atau Token yang dimasukkan sudah benar.')->withInput();
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

        $this->authorize('manageActiveIntern', $app);

        if ($app->status_value !== 'selesai') {
            return redirect()->back()->with('error', 'Peserta belum dinyatakan selesai. Sertifikat tidak dapat diterbitkan.');
        }

        // Validasi ini wajib diulang pada endpoint POST, bukan hanya halaman form.
        if (is_null($app->nilai_rata_rata) || (float) $app->nilai_rata_rata <= 0) {
            return redirect()->back()->with('error', 'Peserta belum dinilai oleh pembimbing_lapangan. Sertifikat tidak dapat diterbitkan.');
        }

        // Generate Nomor Sertifikat Otomatis (Suggestion)
        // Format: 001/MAGANG/NAMA-DINAS/TAHUN
        $count = Application::whereNotNull('nomor_sertifikat')->count() + 1;
        $kodeDinas = $app->position->instansi->kode_instansi ?? strtoupper(Str::slug($app->position->instansi->nama_dinas));
        $autoNumber = sprintf("%03d/MAGANG/%s/%s", $count, $kodeDinas, date('Y'));

        return view('admin_instansi.sertifikat.create', compact('app', 'autoNumber'));
    }

    /**
     * Simpan Data & Generate PDF
     */
    public function store(Request $request, $applicationId)
    {
        $nomorSertifikat = $request->input('nomor_sertifikat') ?? $request->input('certificate_number');
        $tanggalSertifikat = $request->input('tanggal_sertifikat') ?? $request->input('certificate_date');

        $request->merge([
            'nomor_sertifikat' => $nomorSertifikat,
            'tanggal_sertifikat' => $tanggalSertifikat,
        ]);

        $request->validate([
            'nomor_sertifikat' => 'required|string|max:100|unique:applications,nomor_sertifikat,' . $applicationId,
            'tanggal_sertifikat' => 'required|date',
        ]);

        $app = Application::findOrFail($applicationId);

        $this->authorize('manageActiveIntern', $app);

        if ($app->status_value !== 'selesai') {
            return redirect()->back()->with('error', 'Peserta belum dinyatakan selesai. Sertifikat tidak dapat diterbitkan.');
        }

        // Jangan percayakan guard pada halaman GET; request POST dapat dibuat langsung.
        if (is_null($app->nilai_rata_rata) || (float) $app->nilai_rata_rata <= 0) {
            return redirect()->back()->with('error', 'Peserta belum dinilai oleh pembimbing_lapangan. Sertifikat tidak dapat diterbitkan.');
        }

        $tokenVerifikasi = $app->token_verifikasi ?? Str::random(32);

        // 1. Simpan Data Legalitas Sertifikat di Application
        $app->update([
            'nomor_sertifikat' => $request->nomor_sertifikat,
            'updated_at' => $request->tanggal_sertifikat . ' ' . now()->format('H:i:s'), 
            'token_verifikasi' => $tokenVerifikasi,
            'status' => 'selesai'
        ]);

        // 2. Simpan atau sinkronkan ke Master Certificate
        Certificate::updateOrCreate(
            ['application_id' => $app->id],
            [
                'nomor_sertifikat' => $request->nomor_sertifikat,
                'token_verifikasi' => $tokenVerifikasi,
                'signer_name' => $app->position?->instansi?->nama_kepala ?? 'Kepala Instansi',
                'status' => 'active',
                'published_at' => Carbon::parse($request->tanggal_sertifikat),
            ]
        );

        // 3. Siapkan Data untuk View PDF
        $data = [
            'app' => $app,
            'user' => $app->user,
            'instansi' => $app->position->instansi,
            'position' => $app->position,
            'tanggal' => Carbon::parse($request->tanggal_sertifikat)->translatedFormat('d F Y'),
            'qr_code' => route('certificate.verify', $tokenVerifikasi)
        ];

        // 4. Generate PDF
        $pdf = Pdf::loadView('pdf.peserta.sertifikat', $data);
        $pdf->setPaper('a4', 'landscape');

        // Stream PDF ke browser (Preview)
        return $pdf->stream('Sertifikat-' . Str::slug($app->user->name) . '.pdf');
    }
}
