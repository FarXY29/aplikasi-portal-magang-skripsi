<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\DailyLog;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Internship\ValidateDailyLogRequest;
use App\Http\Requests\Internship\ValidateAttendanceRequest;
use App\Services\AuditLogService;

class PembimbingLapanganController extends Controller
{
    public function index()
    {
        $pembimbing_lapanganId = Auth::id();

        // 1. Ambil Data Mahasiswa Bimbingan
        $interns = Application::where('pembimbing_lapangan_id', $pembimbing_lapanganId)
                    ->whereIn('status', ['diterima', 'selesai'])
                    ->with(['user', 'position.instansi'])
                    ->get();

        // 2. HITUNG LOGBOOK PENDING (Untuk Badge Logbook - Opsional jika sudah ada)
        $pendingLogbooks = DailyLog::whereHas('application', function($q) use ($pembimbing_lapanganId) {
            $q->where('pembimbing_lapangan_id', $pembimbing_lapanganId);
        })->where('status_validasi', 'pending')->count();

        // 3. HITUNG ABSENSI PENDING (Untuk Badge Absensi - BARU)
        // Menghitung berapa izin/sakit yang belum disetujui
        $pendingAttendance = Attendance::whereHas('application', function($q) use ($pembimbing_lapanganId) {
            $q->where('pembimbing_lapangan_id', $pembimbing_lapanganId);
        })->where('validation_status', 'pending')->count();

        return view('pembimbing_lapangan.dashboard', compact('interns', 'pendingLogbooks', 'pendingAttendance'));
    }

    public function showLogbook(Request $request, $applicationId)
    {
        $app = Application::findOrFail($applicationId);
        $this->authorize('view', $app);

        $filterType = $request->input('filter_type', 'semua');
        $selectedDate = $request->input('date', date('Y-m-d'));
        $carbonDate = \Carbon\Carbon::parse($selectedDate);

        $query = DailyLog::where('application_id', $applicationId);

        if ($filterType === 'mingguan') {
            $startOfWeek = $carbonDate->copy()->startOfWeek()->format('Y-m-d');
            $endOfWeek = $carbonDate->copy()->endOfWeek()->format('Y-m-d');
            $query->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);
        } elseif ($filterType === 'bulanan') {
            $query->whereMonth('tanggal', $carbonDate->month)
                  ->whereYear('tanggal', $carbonDate->year);
        }

        $logs = $query->orderBy('tanggal', 'desc')->get();

        return view('pembimbing_lapangan.logbook', compact('app', 'logs', 'filterType', 'selectedDate'));
    }

    public function validateLogbook(ValidateDailyLogRequest $request, $id, AuditLogService $auditLogService)
    {
        $log = DailyLog::with('application')->findOrFail($id);
        $this->authorize('validateRecords', $log->application);

        $log->update([
            'status_validasi' => $request->validated('status'),
            'komentar_pembimbing_lapangan' => $request->validated('komentar')
        ]);

        $auditLogService->record('daily_log.validated', $log, ['status_validasi' => $request->validated('status')]);

        return back()->with('success', 'Logbook divalidasi.');
    }

    public function batchValidateLogbook(Request $request)
    {
        $request->validate([
            'log_ids' => 'required|array',
            'status' => 'required|in:disetujui,ditolak',
            'komentar' => 'nullable|string'
        ]);

        $logs = DailyLog::whereIn('id', $request->log_ids)->with('application')->get();

        $validatedCount = 0;
        foreach ($logs as $log) {
            $this->authorize('validateRecords', $log->application);
            $log->update([
                'status_validasi' => $request->status,
                'komentar_pembimbing_lapangan' => $request->komentar
            ]);
            $validatedCount++;
        }

        return back()->with('success', $validatedCount . ' Logbook berhasil divalidasi massal.');
    }

    // --- FITUR BARU: PENILAIAN AKHIR ---

    public function gradingForm($id)
    {
        $app = Application::findOrFail($id);
        $this->authorize('grade', $app);

        return view('pembimbing_lapangan.grading', compact('app'));
    }

    public function storeGrade(Request $request, $id, \App\Services\ApplicationLifecycleService $lifecycleService)
    {
        $app = Application::findOrFail($id);
        $this->authorize('grade', $app);

        $request->validate([
            'nilai_disiplin' => 'required|numeric|min:0|max:100',
            'nilai_kinerja' => 'required|numeric|min:0|max:100',
            'catatan_pembimbing_lapangan' => 'nullable|string'
        ]);

        // Hitung Predikat & Nilai Akhir Otomatis
        $disiplin = $request->nilai_disiplin;
        $kinerja = $request->nilai_kinerja;
        $nilai_akhir = round(($disiplin * 0.40) + ($kinerja * 0.60), 2);
        
        $predikat = 'D (Kurang)';
        if ($nilai_akhir >= 85) $predikat = 'A (Sangat Baik)';
        elseif ($nilai_akhir >= 75) $predikat = 'B (Baik)';
        elseif ($nilai_akhir >= 60) $predikat = 'C (Cukup)';

        $app->update([
            'nilai_disiplin' => $disiplin,
            'nilai_kinerja' => $kinerja,
            'nilai_angka' => $nilai_akhir,
            'nilai_rata_rata' => $nilai_akhir,
            'predikat' => $predikat,
            'catatan_pembimbing_lapangan' => $request->catatan_pembimbing_lapangan
        ]);

        if ($app->status_value !== 'selesai') {
            $lifecycleService->markAsFinished($app);
        }

        return redirect()->route('pembimbing_lapangan.dashboard')->with('success', 'Nilai berhasil disimpan.');
    }

    public function attendance(Request $request)
    {
        $pembimbing_lapanganId = Auth::user()->id;
        
        // 1. Tipe Filter: harian, mingguan, bulanan
        $filterType = $request->input('filter_type', 'harian');

        // 2. Tentukan Tanggal yang Dipilih (Default Hari Ini)
        $selectedDate = $request->input('date', date('Y-m-d'));
        $carbonDate = \Carbon\Carbon::parse($selectedDate);

        // Query Utama: Absensi dari mahasiswa yang dibimbing
        $query = Attendance::whereHas('application', function($q) use ($pembimbing_lapanganId) {
            $q->where('pembimbing_lapangan_id', $pembimbing_lapanganId);
        })->with(['application.user']);

        if ($filterType === 'harian') {
            $query->where('date', $selectedDate);
        } elseif ($filterType === 'mingguan') {
            $startOfWeek = $carbonDate->copy()->startOfWeek()->format('Y-m-d');
            $endOfWeek = $carbonDate->copy()->endOfWeek()->format('Y-m-d');
            $query->whereBetween('date', [$startOfWeek, $endOfWeek]);
        } elseif ($filterType === 'bulanan') {
            $query->whereMonth('date', $carbonDate->month)
                  ->whereYear('date', $carbonDate->year);
        }

        // 3. Buat List 7 Hari Terakhir untuk Sidebar
        $dateList = collect([]);
        for ($i = 0; $i < 7; $i++) {
            $dateList->push(\Carbon\Carbon::now()->subDays($i));
        }

        $attendances = $query->orderBy('date', 'desc')->orderBy('clock_in', 'asc')->get();

        return view('pembimbing_lapangan.attendance', compact('attendances', 'dateList', 'filterType', 'selectedDate'));
    }

    /**
     * PROSES VALIDASI IZIN/SAKIT
     */
    public function validateAttendance(ValidateAttendanceRequest $request, $id, AuditLogService $auditLogService)
    {
        $attendance = Attendance::with('application')->findOrFail($id);
        $this->authorize('validateRecords', $attendance->application);

        $attendance->update([
            'validation_status' => $request->validated('status_validasi'),
            'pembimbing_lapangan_note' => $request->validated('pembimbing_lapangan_note')
        ]);

        $auditLogService->record('attendance.validated', $attendance, ['validation_status' => $request->validated('status_validasi')]);

        return back()->with('success', 'Status izin/sakit berhasil diperbarui.');
    }

    public function simpanNilai(Request $request, $id, \App\Services\ApplicationLifecycleService $lifecycleService)
    {
        $app = Application::findOrFail($id);
        $this->authorize('grade', $app);

        // 1. Validasi Input
        $validated = $request->validate([
            'nilai_kerajinan' => 'required|numeric|min:0|max:100',
            'nilai_disiplin' => 'required|numeric|min:0|max:100',
            'nilai_adaptasi' => 'required|numeric|min:0|max:100',
            'nilai_kreatifitas' => 'required|numeric|min:0|max:100',
            'nilai_skill_pengetahuan' => 'required|numeric|min:0|max:100',
            'catatan_pembimbing_lapangan' => 'nullable|string',
            'saran_pembimbing' => 'nullable|string',
        ]);

        // 2. Hitung Rata-rata
        $total = $request->nilai_kerajinan + $request->nilai_disiplin + $request->nilai_adaptasi + 
                $request->nilai_kreatifitas + $request->nilai_skill_pengetahuan;
        
        $rataRata = round($total / 5, 2);

        $predikat = 'D (Kurang)';
        if ($rataRata >= 90) $predikat = 'A (Sangat Baik)';
        elseif ($rataRata >= 80) $predikat = 'B (Baik)';
        elseif ($rataRata >= 70) $predikat = 'C (Cukup)';

        // 3. Simpan ke Database
        $app->update(array_merge($validated, [
            'nilai_rata_rata' => $rataRata,
            'nilai_angka' => $rataRata,
            'predikat' => $predikat,
        ]));

        if ($app->status_value !== 'selesai') {
            $lifecycleService->markAsFinished($app);
        }

        return redirect()->route('pembimbing_lapangan.dashboard')->with('success', 'Penilaian berhasil disimpan!');
    }

    public function formPenilaian($id)
    {
        $application = Application::findOrFail($id);
        $this->authorize('grade', $application);

        return view('pembimbing_lapangan.penilaian', compact('application'));
    }
}
