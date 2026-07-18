<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Attendance;
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
                            ->with(['position.instansi'])
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

        $activeApp = Application::where('user_id', $user->id)
                        ->whereIn('status', ['diterima', 'selesai'])
                        ->with(['position.instansi', 'pembimbing_lapangan'])
                        ->latest('updated_at')
                        ->first();

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

            $logsCount = $activeApp->logs()->count();
            $logsValidated = $activeApp->logs()->where('status_validasi', 'valid')->count();

            $attendances = Attendance::where('application_id', $activeApp->id)->get();
            $attendanceStats = [
                'hadir' => $attendances->where('status', 'hadir')->count(),
                'izin' => $attendances->whereIn('status', ['izin', 'sakit'])->count(),
                'alpa' => $attendances->where('status', 'alpa')->count(),
            ];

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

    public function cancelApplication($id, \App\Services\InternshipApplicationService $applicationService)
    {
        $app = Application::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $canCancel = in_array($app->status, ['pending', 'menunggu']) || ($app->status === 'diterima' && $app->display_status === 'belum mulai');

        if (!$canCancel) {
            return back()->with('error', 'Lamaran ini tidak dapat dibatalkan karena magang sudah dimulai atau status sudah tidak relevan.');
        }

        $applicationService->cancelApplicant($app, request('alasan') ?? 'Dibatalkan oleh Peserta', 'dibatalkan');
        
        return back()->with('success', 'Lamaran magang berhasil dibatalkan.');
    }

    public function downloadCertificate()
    {
        $user = Auth::user();
        
        $finishedApp = Application::where('user_id', $user->id)
                        ->where('status', 'selesai')
                        ->with(['position.instansi', 'user'])
                        ->latest('updated_at')
                        ->first();

        if (!$finishedApp) {
            return back()->with('error', 'Anda belum menyelesaikan program magang manapun.');
        }

        if (empty($user->nik) || empty($user->asal_instansi)) {
            return redirect()->route('profile.edit')->with('error', 'Mohon lengkapi NIK dan Asal Instansi sebelum download sertifikat.');
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

        if ($app->status != 'diterima' && $app->status != 'selesai') {
            return back()->with('error', 'Surat hanya tersedia bagi peserta yang sudah DITERIMA.');
        }

        $pdf = Pdf::loadView('pdf.peserta.loa', compact('app'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('LoA_' . $app->user->name . '.pdf');
    }

    public function downloadIdCard($id)
    {
        $app = Application::with(['user', 'position.instansi'])
                    ->where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        if (!in_array($app->status, ['diterima', 'selesai'])) {
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
        
        return $pdf->stream('ID_Card_' . $app->user->name . '.pdf');
    }

    public function downloadTranskrip($id)
    {
        $app = Application::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        if ($app->status !== 'selesai' || !$app->nilai_rata_rata) {
            return back()->with('error', 'Transkrip belum tersedia. Tunggu penilaian dari pembimbing_lapangan.');
        }

        $pdf = Pdf::loadView('pdf.peserta.transkrip_nilai', compact('app'));
        return $pdf->stream('Transkrip-Magang-'.$app->user->name.'.pdf');
    }
}
