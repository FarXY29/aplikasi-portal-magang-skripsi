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
                    ->withCount(['logs as approved_logs_count' => function ($q) {
                        $q->where('status_validasi', 'disetujui');
                    }])
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

        // 4. Pengumuman global (dipindahkan dari view agar template tidak menjalankan query)
        $globalAnnouncement = \App\Models\Setting::where('key', 'announcement')->value('value');

        return view('pembimbing_lapangan.dashboard', compact('interns', 'pendingLogbooks', 'pendingAttendance', 'globalAnnouncement'));
    }

    public function logbookHub()
    {
        $pembimbing_lapanganId = Auth::id();
        $firstApp = Application::where('pembimbing_lapangan_id', $pembimbing_lapanganId)
                    ->whereIn('status', ['diterima', 'selesai'])
                    ->first();

        if ($firstApp) {
            return redirect()->route('pembimbing_lapangan.logbook', $firstApp->id);
        }

        return redirect()->route('pembimbing_lapangan.dashboard')->with('info', 'Belum ada mahasiswa bimbingan aktif.');
    }

    public function showLogbook(Request $request, $applicationId)
    {
        $app = Application::findOrFail($applicationId);
        $this->authorize('view', $app);

        $logbookData = app(\App\Services\PembimbingLogbookService::class)->logbookData($applicationId, $request);

        return view('pembimbing_lapangan.logbook', array_merge(compact('app'), $logbookData));
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

        session(['last_id' => $log->id]);

        return back()->with('success', 'Logbook divalidasi.');
    }

    public function batchValidateLogbook(Request $request)
    {
        $validated = $request->validate([
            'log_ids' => 'required|array',
            'status' => 'required|in:disetujui,revisi',
            'komentar' => 'nullable|string|max:2000'
        ]);

        // Revisi massal wajib menyertakan catatan perbaikan agar peserta tahu apa yang harus diperbaiki.
        if ($validated['status'] === 'revisi' && empty(trim($validated['komentar'] ?? ''))) {
            return back()->withErrors(['komentar' => 'Catatan revisi wajib diisi saat melakukan validasi revisi massal.']);
        }

        $logs = DailyLog::whereIn('id', $request->log_ids)->with('application')->get();

        $validatedCount = 0;
        foreach ($logs as $log) {
            $this->authorize('validateRecords', $log->application);
            $log->update([
                'status_validasi' => $validated['status'],
                'komentar_pembimbing_lapangan' => $validated['komentar']
            ]);
            $validatedCount++;
        }

        // Pulihkan tab aktif ke logbook terakhir yang divalidasi
        if (! empty($logs)) {
            session(['last_id' => $logs->last()->id]);
        }

        return back()->with('success', $validatedCount . ' Logbook berhasil divalidasi massal.');
    }

    // --- FITUR BARU: PENILAIAN AKHIR ---

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
        ]);

        // 2. Hitung Rata-rata
        $total = $request->nilai_kerajinan + $request->nilai_disiplin + $request->nilai_adaptasi +
                $request->nilai_kreatifitas + $request->nilai_skill_pengetahuan;

        $rataRata = round($total / 5, 2);
        $predikat = Application::predikatFor($rataRata);

        // 3. Simpan ke Database
        $app->update(array_merge($validated, [
            'nilai_rata_rata' => $rataRata,
            'nilai_angka' => $rataRata,
            'predikat' => $predikat,
        ]));

        if ($app->status_value !== 'selesai') {
            $lifecycleService->markAsFinished($app);
        }

        return redirect()->route('pembimbing_lapangan.dashboard')->with('success', 'Penilaian Berhasil Diperbarui');
    }

    public function formPenilaian($id)
    {
        $application = Application::findOrFail($id);
        $this->authorize('grade', $application);

        return view('pembimbing_lapangan.penilaian', compact('application'));
    }

    public function attendance(Request $request)
    {
        $pembimbing_lapanganId = Auth::user()->id;

        // 1. Data Mahasiswa Bimbingan untuk Dropdown Filter
        $interns = Application::where('pembimbing_lapangan_id', $pembimbing_lapanganId)
                    ->whereIn('status', ['diterima', 'selesai'])
                    ->with('user')
                    ->get();

        // 2. Tipe Filter Rentang Waktu: harian, mingguan, bulanan, semua
        $filterType = $request->input('filter_type', 'harian');

        // 3. Tentukan Tanggal yang Dipilih (Default Hari Ini)
        $selectedDate = $request->input('date', date('Y-m-d'));
        try {
            $carbonDate = \Carbon\Carbon::parse($selectedDate);
        } catch (\Exception $e) {
            $carbonDate = \Carbon\Carbon::today();
            $selectedDate = $carbonDate->format('Y-m-d');
        }

        // Query Utama: Absensi dari mahasiswa yang dibimbing
        $query = Attendance::whereHas('application', function($q) use ($pembimbing_lapanganId) {
            $q->where('pembimbing_lapangan_id', $pembimbing_lapanganId);
        })->with(['application.user', 'application.position']);

        // Filter berdasarkan Peserta tertentu
        if ($request->filled('application_id')) {
            $query->where('application_id', $request->application_id);
        }

        // Filter berdasarkan Rentang Waktu
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

        // Filter berdasarkan Status Kehadiran
        if ($request->filled('status') && in_array($request->status, ['hadir', 'izin', 'sakit', 'alpa'])) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan Status Validasi (misal: pending untuk izin/sakit yang butuh persetujuan)
        if ($request->filled('validation_status') && in_array($request->validation_status, ['pending', 'approved', 'rejected'])) {
            $query->where('validation_status', $request->validation_status);
        }

        // Filter pencarian nama peserta atau deskripsi/catatan
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('application.user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // List 7 Hari Terakhir untuk Pilihan Cepat
        $dateList = collect([]);
        for ($i = 0; $i < 7; $i++) {
            $dateList->push(\Carbon\Carbon::now()->subDays($i));
        }

        $attendances = $query->orderBy('date', 'desc')->orderBy('clock_in', 'asc')->get();

        return view('pembimbing_lapangan.attendance', compact(
            'attendances',
            'interns',
            'dateList',
            'filterType',
            'selectedDate'
        ));
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
}
