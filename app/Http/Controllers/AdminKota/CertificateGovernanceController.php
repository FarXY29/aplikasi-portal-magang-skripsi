<?php

namespace App\Http\Controllers\AdminKota;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Instansi;
use App\Models\Setting;
use App\Services\AuditLogService;
use App\Services\PdfExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateGovernanceController extends Controller
{
    protected AuditLogService $auditLogService;
    protected PdfExportService $pdfService;

    public function __construct(AuditLogService $auditLogService, PdfExportService $pdfService)
    {
        $this->auditLogService = $auditLogService;
        $this->pdfService = $pdfService;
    }

    /**
     * Direktori & Registri Seluruh Sertifikat Magang Kota Banjarmasin.
     */
    public function index(Request $request)
    {
        $query = Certificate::with([
            'application.user',
            'application.position.instansi',
            'revokedBy',
        ]);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nomor_sertifikat', 'like', "%{$search}%")
                  ->orWhere('token_verifikasi', 'like', "%{$search}%")
                  ->orWhereHas('application.user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('application.position.instansi', function ($i) use ($search) {
                      $i->where('nama_dinas', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('instansi_id')) {
            $query->whereHas('application.position', function ($p) use ($request) {
                $p->where('instansi_id', $request->instansi_id);
            });
        }

        // Metrics
        $totalCertificates = Certificate::count();
        $totalActive = Certificate::where('status', 'active')->count();
        $totalRevoked = Certificate::where('status', 'revoked')->count();

        $certificates = $query->latest('published_at')->paginate(12)->withQueryString();
        $instansis = Instansi::orderBy('nama_dinas', 'asc')->get();

        return view('admin_kota.certificates.index', compact(
            'certificates',
            'instansis',
            'totalCertificates',
            'totalActive',
            'totalRevoked'
        ));
    }

    /**
     * Detail Sertifikat & Audit Keabsahan.
     */
    public function show($id)
    {
        $certificate = Certificate::with([
            'application.user',
            'application.position.instansi',
            'application.pembimbing_lapangan',
            'revokedBy',
        ])->findOrFail($id);

        return view('admin_kota.certificates.show', compact('certificate'));
    }

    /**
     * Cabut / Batalkan Sertifikat Resmi (Revocation).
     */
    public function revoke(Request $request, $id)
    {
        $certificate = Certificate::with('application.user')->findOrFail($id);

        if ($certificate->isRevoked()) {
            return back()->with('error', 'Sertifikat ini sudah dalam status dicabut sebelumnya.');
        }

        $request->validate([
            'revoked_reason' => 'required|string|min:10|max:1000',
        ], [
            'revoked_reason.required' => 'Alasan pencabutan sertifikat wajib diisi.',
            'revoked_reason.min' => 'Alasan pencabutan sertifikat minimal 10 karakter.',
        ]);

        $certificate->update([
            'status' => 'revoked',
            'revoked_at' => now(),
            'revoked_reason' => $request->revoked_reason,
            'revoked_by' => auth()->id(),
        ]);

        $this->auditLogService->record('certificate.revoked', $certificate, [
            'nomor_sertifikat' => $certificate->nomor_sertifikat,
            'user_name' => $certificate->application?->user?->name,
            'reason' => $request->revoked_reason,
        ]);

        return back()->with('success', 'Sertifikat ' . $certificate->nomor_sertifikat . ' berhasil dicabut status keabsahannya.');
    }

    /**
     * Pulihkan Status Sertifikat yang Dicabut.
     */
    public function restore($id)
    {
        $certificate = Certificate::with('application.user')->findOrFail($id);

        if ($certificate->isActive()) {
            return back()->with('error', 'Sertifikat ini sudah aktif.');
        }

        $certificate->update([
            'status' => 'active',
            'revoked_at' => null,
            'revoked_reason' => null,
            'revoked_by' => null,
        ]);

        $this->auditLogService->record('certificate.restored', $certificate, [
            'nomor_sertifikat' => $certificate->nomor_sertifikat,
            'user_name' => $certificate->application?->user?->name,
        ]);

        return back()->with('success', 'Status sertifikat ' . $certificate->nomor_sertifikat . ' berhasil dipulihkan menjadi Aktif / Sah.');
    }

    /**
     * Cetak Buku Register Sertifikat Magang Kota (PDF).
     */
    public function exportPdf(Request $request)
    {
        $query = Certificate::with([
            'application.user',
            'application.position.instansi',
            'revokedBy',
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('instansi_id')) {
            $query->whereHas('application.position', function ($p) use ($request) {
                $p->where('instansi_id', $request->instansi_id);
            });
        }

        $certificates = $query->orderBy('nomor_sertifikat', 'asc')->get();

        $settings = Setting::all()->pluck('value', 'key');
        $pejabat_nama = $settings['pejabat_name'] ?? 'H. Lukman Fadlun, SH';
        $pejabat_nip = $settings['pejabat_nip'] ?? '-';
        $pejabat_jabatan = $settings['pejabat_jabatan'] ?? 'Kepala Bakesbangpol Kota Banjarmasin';

        $ttd_image_path = null;
        if (! empty($settings['ttd_image']) && Storage::disk('public')->exists($settings['ttd_image'])) {
            $ttd_image_path = public_path('storage/' . $settings['ttd_image']);
        }

        $data = [
            'certificates' => $certificates,
            'pejabat_nama' => $pejabat_nama,
            'pejabat_nip' => $pejabat_nip,
            'pejabat_jabatan' => $pejabat_jabatan,
            'ttd_image_path' => $ttd_image_path,
            'filter_status' => $request->status,
        ];

        return $this->pdfService->stream('pdf.admin_kota.certificates_register', $data, 'Buku-Register-Sertifikat-Magang.pdf', 'a4', 'landscape');
    }
}
