<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyLog;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf; 
use Carbon\Carbon;
use App\Http\Requests\Logbook\StoreDailyLogRequest;
use App\Http\Requests\Logbook\UpdateDailyLogRequest;
use App\Services\AuditLogService;

class LogbookController extends Controller
{
    public function index()
    {
        // Prioritaskan status 'diterima' (aktif), jika tidak ada baru gunakan 'selesai' (riwayat)
        $activeApp = Application::with('position.instansi')
            ->where('user_id', Auth::id())
            ->where('status', 'diterima')
            ->first();

        if (!$activeApp) {
            $activeApp = Application::with('position.instansi')
                ->where('user_id', Auth::id())
                ->where('status', 'selesai')
                ->latest('updated_at')
                ->first();
        }

        if (!$activeApp) {
            return redirect()->route('peserta.dashboard')->with('error', 'Anda tidak memiliki status magang aktif.');
        }

        $logs = DailyLog::where('application_id', $activeApp->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(15)
            ->withQueryString();
        return view('peserta.logbook.index', compact('logs', 'activeApp'));
    }

    public function store(StoreDailyLogRequest $request, AuditLogService $auditLogService)
    {
        $user = $request->user();
        
        // Ambil Data Lamaran Aktif & Lokasi INSTANSI
        $app = Application::with('position.instansi')
            ->where('user_id', $user->id)
            ->where('status', 'diterima')
            ->latest('updated_at')
            ->first();
        
        if (!$app) return back()->with('error', 'Status magang Anda telah selesai atau tidak aktif. Anda tidak dapat membuat atau menyimpan jurnal baru.');

        $today = Carbon::today();
        $startDate = Carbon::parse($app->tanggal_mulai)->startOfDay();
        $endDate = Carbon::parse($app->tanggal_selesai)->endOfDay();

        if ($today->lt($startDate)) {
            return back()->with('error', 'Masa magang Anda belum dimulai. Silakan kembali pada ' . $startDate->translatedFormat('d F Y') . '.');
        }

        if ($today->gt($endDate)) {
            return back()->with('error', 'Masa magang Anda telah berakhir pada ' . $endDate->translatedFormat('d F Y') . '.');
        }

        // 2. LOGIKA GEOTAGGING (Cek Jarak & Radius)
        $instansi = $app->position?->instansi;
        $kantorLat = $instansi?->latitude;
        $kantorLng = $instansi?->longitude;
        $radiusAbsen = $instansi?->radius_absen ?? 100;
        
        if ($kantorLat !== null && $kantorLng !== null) {
            $jarakKm = $this->calculateDistance(
                $request->validated('latitude'),
                $request->validated('longitude'),
                (float) $kantorLat,
                (float) $kantorLng,
            );
            $jarakMeter = $jarakKm * 1000;
            
            if ($jarakMeter > $radiusAbsen) {
                return back()->with('error', 'Gagal Check-in! Posisi Anda terlalu jauh dari kantor dinas (' . number_format($jarakMeter, 0) . ' meter, batas maksimal ' . $radiusAbsen . ' meter).');
            }
        }

        // 3. Upload Foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('documents/logbook', 'private');
        }

        // 4. Simpan Log
        $log = DailyLog::create([
            'application_id' => $app->id,
            'tanggal' => now(),
            'kegiatan' => $request->validated('kegiatan'),
            'bukti_foto_path' => $fotoPath,
            'status_validasi' => 'pending'
        ]);

        $auditLogService->record('daily_log.created', $log, [
            'application_id' => $app->id,
            'has_proof' => (bool) $fotoPath,
        ]);

        return back()->with('success', 'Logbook hari ini berhasil disimpan!');
    }

    public function update(UpdateDailyLogRequest $request, $id, AuditLogService $auditLogService)
    {
        $log = DailyLog::with('application')->findOrFail($id);
        if ($log->application?->status_value === 'selesai' || $log->application?->status === 'selesai') {
            return back()->with('error', 'Status magang Anda telah selesai. Logbook tidak dapat diubah lagi.');
        }

        $this->authorize('update', $log);

        if (!in_array($log->status_validasi, ['pending', 'revisi'], true)) {
            return back()->with('error', 'Logbook ini tidak dalam status pending atau revisi.');
        }

        $isRevision = $log->status_validasi === 'revisi';

        $fotoPath = $log->bukti_foto_path;
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            $oldFotoPath = $fotoPath;
            $fotoPath = $request->file('foto')->store('documents/logbook', 'private');
        }

        $log->update([
            'kegiatan' => $request->validated('kegiatan'),
            'bukti_foto_path' => $fotoPath,
            'status_validasi' => 'pending',
            'komentar_pembimbing_lapangan' => null, // Reset komentar pembimbing_lapangan setelah revisi
        ]);

        if (isset($oldFotoPath) && $oldFotoPath) {
            $disk = Storage::disk('private')->exists($oldFotoPath) ? 'private' : 'public';
            Storage::disk($disk)->delete($oldFotoPath);
        }

        $auditLogService->record($isRevision ? 'daily_log.revised' : 'daily_log.updated', $log, [
            'application_id' => $log->application_id,
            'has_proof' => (bool) $fotoPath,
        ]);

        $message = $isRevision
            ? 'Logbook berhasil direvisi dan dikirim ulang untuk divalidasi!'
            : 'Logbook harian berhasil diperbarui!';

        return back()->with('success', $message);
    }

    public function destroy($id, AuditLogService $auditLogService)
    {
        $log = DailyLog::with('application')->findOrFail($id);

        if ($log->application?->status_value === 'selesai' || $log->application?->status === 'selesai') {
            return back()->with('error', 'Status magang Anda telah selesai. Logbook tidak dapat dihapus lagi.');
        }

        $this->authorize('delete', $log);

        $fotoPath = $log->bukti_foto_path;

        $log->delete();

        if ($fotoPath) {
            $disk = Storage::disk('private')->exists($fotoPath) ? 'private' : 'public';
            Storage::disk($disk)->delete($fotoPath);
        }

        $auditLogService->record('daily_log.deleted', $log, [
            'application_id' => $log->application_id,
        ]);

        return back()->with('success', 'Logbook harian berhasil dihapus!');
    }

    // --- CETAK REKAP LOGBOOK ---
    public function print($id = null)
    {
        $user = Auth::user();
        
        $query = Application::with(['position.instansi', 'pembimbing_lapangan'])
                ->where('user_id', $user->id)
                ->whereIn('status', ['diterima', 'selesai']);

        if ($id) {
            $app = $query->where('id', $id)->firstOrFail();
        } else {
            $app = $query->latest('updated_at')->firstOrFail();
        }

        // Ambil seluruh logbook, urutkan dari tanggal awal
        $logs = DailyLog::where('application_id', $app->id)
                ->orderBy('tanggal', 'asc')
                ->get();

        $pdf = Pdf::loadView('pdf.peserta.logbook_rekap', compact('app', 'logs', 'user'));
        
        // Set ukuran kertas A4 Portrait
        $pdf->setPaper('a4', 'portrait');

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Rekap-Kegiatan-' . (Str::slug($user->name) ?: 'peserta') . '.pdf"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    // Fungsi Matematika Haversine (Menghitung Jarak 2 Titik Koordinat)
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Radius bumi dalam KM

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        return $distance; // Hasil dalam Kilometer
    }
}
