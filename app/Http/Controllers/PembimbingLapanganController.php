<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\DailyLog;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

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
        if($app->pembimbing_lapangan_id != Auth::id()) abort(403);

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

    public function validateLogbook(Request $request, $id)
    {
        $log = DailyLog::findOrFail($id);
        if($log->application->pembimbing_lapangan_id != Auth::id()) abort(403);

        $log->update([
            'status_validasi' => $request->status,
            'komentar_pembimbing_lapangan' => $request->komentar
        ]);

        return back()->with('success', 'Logbook divalidasi.');
    }

    // --- FITUR BARU: PENILAIAN AKHIR ---

    public function gradingForm($id)
    {
        $app = Application::findOrFail($id);
        if($app->pembimbing_lapangan_id != Auth::id()) abort(403);

        return view('pembimbing_lapangan.grading', compact('app'));
    }

    public function storeGrade(Request $request, $id)
    {
        $app = Application::findOrFail($id);
        if($app->pembimbing_lapangan_id != Auth::id()) abort(403);

        $request->validate([
            'nilai_angka' => 'required|numeric|min:0|max:100',
            'catatan' => 'required|string'
        ]);

        // Hitung Predikat Otomatis
        $nilai = $request->nilai_angka;
        $predikat = 'E';
        if ($nilai >= 85) $predikat = 'A (Sangat Baik)';
        elseif ($nilai >= 75) $predikat = 'B (Baik)';
        elseif ($nilai >= 60) $predikat = 'C (Cukup)';
        elseif ($nilai >= 50) $predikat = 'D (Kurang)';

        $app->update([
            'nilai_angka' => $nilai,
            'predikat' => $predikat,
            'catatan_pembimbing_lapangan' => $request->catatan
        ]);

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

        // 3. Buat List 7 Hari Terakhir untuk Sidebar
        $dateList = collect([]);
        for ($i = 0; $i < 7; $i++) {
            $dateList->push(\Carbon\Carbon::now()->subDays($i));
        }

        // 4. Ambil ID Mahasiswa Bimbingan
        $applicationIds = Application::where('pembimbing_lapangan_id', $pembimbing_lapanganId)
                            ->whereIn('status', ['diterima', 'selesai'])
                            ->pluck('id');

        // 5. Query Absensi Berdasarkan Filter
        $query = Attendance::whereIn('application_id', $applicationIds)
                    ->with(['application.user', 'application.position']);

        if ($filterType === 'mingguan') {
            $startOfWeek = $carbonDate->copy()->startOfWeek()->format('Y-m-d');
            $endOfWeek = $carbonDate->copy()->endOfWeek()->format('Y-m-d');
            $query->whereBetween('date', [$startOfWeek, $endOfWeek]);
        } elseif ($filterType === 'bulanan') {
            $query->whereMonth('date', $carbonDate->month)
                  ->whereYear('date', $carbonDate->year);
        } else {
            $query->where('date', $selectedDate);
        }

        $attendances = $query->orderBy('date', 'desc')
                    ->latest()
                    ->get();

        return view('pembimbing_lapangan.attendance', compact('attendances', 'dateList', 'selectedDate', 'filterType'));
    }

    /**
     * PROSES VALIDASI IZIN/SAKIT
     */
    public function validateAttendance(Request $request, $id)
    {
        $request->validate([
            'status_validasi' => 'required|in:approved,rejected',
            'pembimbing_lapangan_note' => 'nullable|string'
        ]);

        $attendance = Attendance::findOrFail($id);
        
        // Pastikan yang memvalidasi adalah pembimbing_lapangan yang berhak
        if ($attendance->application->pembimbing_lapangan_id != Auth::id()) {
            abort(403, 'Akses Ditolak');
        }

        $attendance->update([
            'validation_status' => $request->status_validasi,
            'pembimbing_lapangan_note' => $request->pembimbing_lapangan_note
        ]);

        return back()->with('success', 'Status izin/sakit berhasil diperbarui.');
    }

    public function simpanNilai(Request $request, $id)
    {
        // 1. Validasi 10 Input
        $validated = $request->validate([
            'nilai_kerajinan' => 'required|numeric|min:0|max:100',
            'nilai_disiplin' => 'required|numeric|min:0|max:100',
            'nilai_adaptasi' => 'required|numeric|min:0|max:100',
            'nilai_kreatifitas' => 'required|numeric|min:0|max:100',
            'nilai_skill_pengetahuan' => 'required|numeric|min:0|max:100',
            'catatan_pembimbing_lapangan' => 'nullable|string',
            'saran_pembimbing' => 'nullable|string',
        ]);

        $app = Application::findOrFail($id);

        // 2. Hitung Rata-rata
        $total = $request->nilai_kerajinan + $request->nilai_disiplin + $request->nilai_adaptasi + 
                $request->nilai_kreatifitas + $request->nilai_skill_pengetahuan;
        
        $rataRata = $total / 5;

        // 3. Simpan ke Database
        $app->update(array_merge($validated, [
            'nilai_rata_rata' => $rataRata,
            'status' => 'selesai' // Status berubah jadi selesai agar peserta bisa download sertifikat
        ]));

        return redirect()->route('pembimbing_lapangan.dashboard')->with('success', 'Penilaian berhasil disimpan!');
    }

    public function formPenilaian($id)
    {
        // Ambil data aplikasi berdasarkan ID
        // Pastikan aplikasi ini memang dibimbing oleh pembimbing_lapangan yang sedang login (Opsional tapi disarankan untuk keamanan)
        $application = Application::findOrFail($id);

        // Cek apakah pembimbing_lapangan berhak menilai (misal cek pembimbing_lapangan_id)
        if ($application->pembimbing_lapangan_id != Auth::id()) {
            abort(403, 'Anda bukan pembimbing peserta ini.');
        }

        return view('pembimbing_lapangan.penilaian', compact('application'));
    }
}