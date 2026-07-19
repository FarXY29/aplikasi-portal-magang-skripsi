<?php

namespace App\Http\Controllers\AdminInstansi;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Attendance;
use App\Models\DailyLog;
use App\Models\User;
use App\Enums\ApplicationStatus;
use App\Services\AuditLogService;
use App\Services\PdfExportService;
use App\Http\Requests\Internship\AssignMentorRequest;
use App\Http\Requests\Internship\ValidateDailyLogRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ActiveInternController extends Controller
{
    protected $pdfService;
    protected $auditLogService;

    public function __construct(PdfExportService $pdfService, AuditLogService $auditLogService)
    {
        $this->pdfService = $pdfService;
        $this->auditLogService = $auditLogService;
    }

    public function activeInterns(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;
        $pembimbing_lapangan = User::where('instansi_id', $instansiId)->where('role', 'pembimbing_lapangan')->get();

        $status = $request->input('status', 'semua');

        $query = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })
        ->with(['user', 'position', 'pembimbing_lapangan'])
        ->orderBy('status', 'asc');

        if ($status === 'aktif') {
            $query->where('status', 'diterima');
        } elseif ($status === 'selesai') {
            $query->where('status', 'selesai');
        } else {
            $query->whereIn('status', ['diterima', 'selesai']);
        }

        $interns = $query->paginate(15)->withQueryString();

        $activeCount = Application::whereHas('position', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })
        ->where('status', 'diterima')
        ->count();

        return view('admin_instansi.peserta.index', compact('interns', 'pembimbing_lapangan', 'activeCount'));
    }

    public function assignPembimbingLapangan(AssignMentorRequest $request, $id)
    {
        $app = Application::findOrFail($id);
        $this->authorize('manageActiveIntern', $app);

        $mentor = User::query()
            ->whereKey($request->validated('pembimbing_lapangan_id'))
            ->where('instansi_id', auth()->user()->instansi_id)
            ->where('role', 'pembimbing_lapangan')
            ->firstOrFail();

        $app->update(['pembimbing_lapangan_id' => $mentor->id]);
        $this->auditLogService->record('application.mentor_assigned', $app, [
            'pembimbing_lapangan_id' => $mentor->id,
        ]);
        return back()->with('success', 'Pembimbing lapangan berhasil ditetapkan.');
    }

    public function finishIntern($id, \App\Services\ApplicationLifecycleService $lifecycleService)
    {
        $app = Application::with(['user', 'position.instansi'])->findOrFail($id);
        $this->authorize('manageActiveIntern', $app);
        
        $lifecycleService->markAsFinished($app);
        try {
            if ($app->user && $app->user->email) {
                Mail::to($app->user->email)->send(new \App\Mail\InternshipCompleted($app));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send internship completed email: ' . $e->getMessage());
        }
        
        return back()->with('success', 'Peserta berhasil diluluskan! Sertifikat kini tersedia.');
    }

    public function expelIntern($id)
    {
        $app = Application::findOrFail($id);
        $this->authorize('manageActiveIntern', $app);

        $statusValue = $app->status instanceof ApplicationStatus ? $app->status->value : $app->status;
        if ($statusValue !== ApplicationStatus::Diterima->value) {
            return back()->with('error', 'Hanya peserta dengan status aktif yang dapat dikeluarkan.');
        }

        $app->update(['status' => ApplicationStatus::Dikeluarkan->value]);
        $this->auditLogService->record('application.expelled', $app, [
            'applicant_user_id' => $app->user_id,
        ]);

        return back()->with('success', 'Peserta berhasil dikeluarkan dari magang.');
    }

    public function showLogbooks($applicationId)
    {
        $app = Application::with(['user', 'position'])->findOrFail($applicationId);
        $this->authorize('view', $app);

        $logs = DailyLog::where('application_id', $applicationId)
            ->orderBy('tanggal', 'desc')
            ->paginate(15)
            ->withQueryString();
        return view('admin_instansi.peserta.detail', compact('app', 'logs'));
    }

    public function validateLogbook(ValidateDailyLogRequest $request, $id)
    {
        $log = DailyLog::with('application')->findOrFail($id);
        $this->authorize('validateRecords', $log->application);

        $log->update([
            'status_validasi' => $request->validated('status'),
            'komentar_pembimbing_lapangan' => $request->validated('komentar')
        ]);
        $this->auditLogService->record('daily_log.validated', $log, [
            'status_validasi' => $request->validated('status'),
            'application_id' => $log->application_id,
        ]);
        return back()->with('success', 'Status logbook diperbarui.');
    }

    public function showAbsensi(Request $request, $id)
    {
        $app = Application::with(['user', 'position'])->findOrFail($id);
        $this->authorize('view', $app);

        $query = Attendance::where('application_id', $id)->orderBy('date', 'desc');

        if ($request->has('bulan') && $request->bulan != '') {
            $query->whereMonth('date', $request->bulan);
        }

        $absensi = $query->paginate(15)->withQueryString();

        $allData = Attendance::where('application_id', $id)->get();

        $stats = [
            'tepat_waktu' => $allData->filter(function ($item) {
                return $item->status == 'hadir' && $item->clock_in && $item->clock_in <= '08:00:00';
            })->count(),

            'terlambat' => $allData->filter(function ($item) {
                return $item->status == 'hadir' && $item->clock_in && $item->clock_in > '08:00:00';
            })->count(),

            'izin' => $allData->whereIn('status', ['izin', 'sakit'])->count(),

            'alpha' => $allData->where('status', 'alpa')->count(),
        ];

        return view('admin_instansi.peserta.absensi', compact('app', 'absensi', 'stats'));
    }

    public function printAbsensi(Request $request, $id)
    {
        $app = Application::with(['user', 'position.instansi', 'pembimbing_lapangan'])->findOrFail($id);
        $this->authorize('view', $app);

        $query = Attendance::where('application_id', $id)->orderBy('date', 'asc');

        if ($request->has('bulan') && $request->bulan != '') {
            $query->whereMonth('date', $request->bulan);
        }

        $data = $query->get();

        $payload = [
            'data'   => $data,
            'app'    => $app,
            'bulan'  => $request->bulan ? Carbon::create()->month($request->bulan)->translatedFormat('F') : 'Semua Periode'
        ];

        return $this->pdfService->stream('pdf.admin_instansi.rekap_absensi', $payload, 'Laporan-Absensi-' . $app->user->name . '.pdf', 'a4', 'portrait');
    }
}
