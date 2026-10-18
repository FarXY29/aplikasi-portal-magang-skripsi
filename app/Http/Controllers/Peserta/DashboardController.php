<?php

namespace App\Http\Controllers\Peserta;

use App\Exceptions\CancellationPolicyException;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Attendance;
use App\Services\ApplicationStateTransitionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Dashboard Peserta
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $statusFilter = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Application::where('user_id', $user->id)
            ->with(['position.instansi', 'certificate'])
            ->latest();

        if ($statusFilter && $statusFilter !== 'semua') {
            $query->where('status', $statusFilter);
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $myApplications = $query->get();

        // Prioritaskan status 'diterima' (aktif), jika tidak ada baru gunakan 'selesai' (riwayat)
        $activeApp = Application::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->with(['position.instansi', 'pembimbing_lapangan'])
            ->first();

        if (! $activeApp) {
            $activeApp = Application::where('user_id', $user->id)
                ->where('status', 'selesai')
                ->with(['position.instansi', 'pembimbing_lapangan', 'certificate'])
                ->latest('updated_at')
                ->first();
        }

        // Cek Absensi Hari Ini
        $attendanceToday = null;
        $jamKerja = null;
        $stats = null;
        $daysRemaining = null;

        if ($activeApp) {
            $attendanceToday = Attendance::where('application_id', $activeApp->id)
                ->where('date', Carbon::now()->format('Y-m-d'))
                ->first();
            $jamKerja = $activeApp->position->instansi;

            $logStats = $activeApp->logs()->toBase()->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status_validasi = 'disetujui' THEN 1 ELSE 0 END) as validated
            ")->first();

            $attStats = Attendance::where('application_id', $activeApp->id)
                ->toBase()
                ->selectRaw("
                    SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as hadir,
                    SUM(CASE WHEN status IN ('izin', 'sakit') THEN 1 ELSE 0 END) as izin,
                    SUM(CASE WHEN status = 'alpa' THEN 1 ELSE 0 END) as alpa
                ")->first();

            $attendanceStats = [
                'hadir' => (int) ($attStats->hadir ?? 0),
                'izin' => (int) ($attStats->izin ?? 0),
                'alpa' => (int) ($attStats->alpa ?? 0),
            ];

            $logsCount = (int) ($logStats->total ?? 0);
            $logsValidated = (int) ($logStats->validated ?? 0);

            $startDate = Carbon::parse($activeApp->tanggal_mulai)->startOfDay();
            $endDate = Carbon::parse($activeApp->tanggal_selesai)->startOfDay();

            $totalDays = (int) $startDate->diffInDays($endDate) + 1;
            $elapsedDays = (int) $startDate->diffInDays(Carbon::now()->startOfDay(), false) + 1;

            $elapsedDays = max(0, min($elapsedDays, $totalDays));

            $progressPercent = $totalDays > 0 ? round(($elapsedDays / $totalDays) * 100) : 0;

            $stats = [
                'logs_count' => $logsCount,
                'logs_validated' => $logsValidated,
                'attendance' => $attendanceStats,
                'total_days' => $totalDays,
                'elapsed_days' => $elapsedDays,
                'progress_percent' => $progressPercent,
            ];

            $daysRemaining = Carbon::now()->startOfDay()->diffInDays($endDate->startOfDay(), false);
        }

        return view('peserta.dashboard', compact('myApplications', 'activeApp', 'attendanceToday', 'jamKerja', 'stats', 'daysRemaining'));
    }

    public function cancelApplication($id, ApplicationStateTransitionService $transitionService)
    {
        $app = Application::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        try {
            $transitionService->cancel(
                $app,
                request('alasan') ?? 'Dibatalkan oleh Peserta',
                Auth::id(),
                true
            );
        } catch (CancellationPolicyException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal membatalkan lamaran: '.$e->getMessage());
        }

        return back()->with('success', 'Lamaran magang berhasil dibatalkan.');
    }

    public function downloadCertificate($id = null)
    {
        $user = Auth::user();

        $finishedAppQuery = Application::where('user_id', $user->id)
            ->where('status', 'selesai')
            ->with(['position.instansi', 'pembimbing_lapangan', 'user.university', 'user.school', 'user.majorDetail', 'certificate']);

        $finishedApp = $id
            ? $finishedAppQuery->whereKey($id)->first()
            : $finishedAppQuery->latest('updated_at')->first();

        if (! $finishedApp) {
            return back()->with('error', 'Anda belum menyelesaikan program magang manapun.');
        }

        if ($finishedApp->certificate?->isRevoked()) {
            return redirect()->route('peserta.dashboard')->with('error', 'Sertifikat ini telah dicabut / dibatalkan oleh Pemerintah Kota. Alasan: '.($finishedApp->certificate->revoked_reason ?? 'Pencabutan administratif.'));
        }

        if (empty($user->nik) || empty($user->asal_instansi)) {
            return redirect()->route('profile.edit')->with('error', 'Mohon lengkapi NIK dan Asal Instansi sebelum download sertifikat.');
        }

        if (empty($finishedApp->nomor_sertifikat) && (float) $finishedApp->avg_nilai <= 0) {
            return redirect()->route('peserta.dashboard')->with('error', 'Peserta belum dinilai oleh pembimbing lapangan. Sertifikat tidak dapat diterbitkan.');
        }

        if (empty($finishedApp->saran_peserta)) {
            return redirect()->route('peserta.dashboard')->with('error', 'Mohon isi saran dan evaluasi terlebih dahulu sebelum mengunduh sertifikat.');
        }

        $pdf = Pdf::loadView('pdf.peserta.sertifikat', ['app' => $finishedApp]);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('Sertifikat-Magang-'.$user->name.'.pdf');
    }

    public function downloadLoA($id)
    {
        $app = Application::with(['user', 'position.instansi', 'pembimbing_lapangan'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($app->status?->value != 'diterima' && $app->status?->value != 'selesai') {
            return back()->with('error', 'Surat hanya tersedia bagi peserta yang sudah DITERIMA.');
        }

        $pdf = Pdf::loadView('pdf.peserta.loa', compact('app'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('LoA_'.$app->user->name.'.pdf');
    }

    public function downloadIdCard($id)
    {
        $app = Application::with(['user', 'position.instansi'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (! in_array($app->status?->value, ['diterima', 'selesai'])) {
            return back()->with('error', 'ID Card hanya tersedia bagi peserta yang sudah DITERIMA.');
        }

        if (empty($app->user->nik) || empty($app->user->asal_instansi)) {
            return redirect()->route('profile.edit')->with('error', 'Mohon lengkapi NIK dan Asal Instansi sebelum mencetak ID Card.');
        }

        if (empty($app->token_verifikasi)) {
            $app->update(['token_verifikasi' => Str::random(32)]);
        }

        $pdf = Pdf::loadView('pdf.peserta.id_card', compact('app'));
        $pdf->setPaper([0, 0, 153.07, 242.64], 'portrait');

        return $pdf->stream('ID_Card_'.$app->user->name.'.pdf');
    }

    public function downloadTranskrip($id)
    {
        $app = Application::with(['user.university', 'user.school', 'user.majorDetail', 'position.instansi', 'pembimbing_lapangan'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($app->status?->value !== 'selesai' || ! $app->nilai_rata_rata) {
            return back()->with('error', 'Transkrip belum tersedia. Tunggu penilaian dari pembimbing_lapangan.');
        }

        if (empty($app->saran_peserta)) {
            return redirect()->route('peserta.dashboard')->with('error', 'Mohon isi saran dan evaluasi terlebih dahulu sebelum mengunduh transkrip nilai.');
        }

        $pdf = Pdf::loadView('pdf.peserta.transkrip_nilai', compact('app'));

        return $pdf->stream('Transkrip-Magang-'.$app->user->name.'.pdf');
    }
}
