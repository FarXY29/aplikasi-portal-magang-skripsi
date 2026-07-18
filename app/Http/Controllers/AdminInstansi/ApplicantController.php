<?php

namespace App\Http\Controllers\AdminInstansi;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Services\AuditLogService;
use App\Services\InternshipApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicantController extends Controller
{
    protected $applicationService;
    protected $auditLogService;

    public function __construct(InternshipApplicationService $applicationService, AuditLogService $auditLogService)
    {
        $this->applicationService = $applicationService;
        $this->auditLogService = $auditLogService;
    }

    public function applicants(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;
        $query = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->with(['user', 'position'])->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status != 'semua' && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('posisi_id') && $request->posisi_id != '') {
            $query->where('internship_position_id', $request->posisi_id);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                })->orWhere('letter_number', 'like', "%{$search}%");
            });
        }

        $applicants = $query->paginate(15)->withQueryString();
        $positions = \App\Models\InternshipPosition::where('instansi_id', $instansiId)->get();

        return view('admin_instansi.pelamar', compact('applicants', 'positions'));
    }

    public function downloadSurat($id)
    {
        $app = Application::with('position')->findOrFail($id);
        $this->authorize('view', $app);

        if (!$app->surat_pengantar_path || !Storage::disk('public')->exists($app->surat_pengantar_path)) {
            return back()->with('error', 'Berkas surat pengantar tidak ditemukan.');
        }

        return Storage::disk('public')->response($app->surat_pengantar_path);
    }

    /**
     * TERIMA PELAMAR
     */
    public function acceptApplicant($id)
    {
        $app = Application::with('position', 'user')->findOrFail($id);
        
        if ($app->position->kuota <= 0) {
            return back()->with('error', 'Peringatan: Posisi ini memiliki kapasitas 0 (Ditutup).');
        }

        try {
            $this->applicationService->acceptApplicant($app);
            $this->auditLogService->record('application.accepted', $app, [
                'applicant_user_id' => $app->user_id,
                'position_id' => $app->internship_position_id,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Peserta diterima! Jadwal telah dikunci sesuai pengajuan, dan email pemberitahuan telah dikirim.');
    }

    public function rejectApplicant($id)
    {
        $app = Application::with('position.instansi', 'user')->findOrFail($id);
        
        $this->applicationService->rejectApplicant($app, request('alasan'));
        $this->auditLogService->record('application.rejected', $app, [
            'applicant_user_id' => $app->user_id,
            'position_id' => $app->internship_position_id,
            'rejected_reason' => request('alasan'),
        ]);

        return back()->with('success', 'Peserta ditolak dan catatan telah disimpan.');
    }
}
