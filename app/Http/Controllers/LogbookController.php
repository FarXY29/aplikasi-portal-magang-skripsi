<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyLog;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; 

class LogbookController extends Controller
{
    public function index()
    {
        // Ambil aplikasi magang yang statusnya 'diterima'
        $activeApp = Application::where('user_id', Auth::id())
                        ->where('status', 'diterima')
                        ->first();

        if (!$activeApp) {
            return redirect()->route('peserta.dashboard')->with('error', 'Anda tidak memiliki status magang aktif.');
        }

        $logs = DailyLog::where('application_id', $activeApp->id)->orderBy('tanggal', 'desc')->get();
        return view('peserta.logbook.index', compact('logs', 'activeApp'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'kegiatan' => 'required',
            'foto' => 'image|max:5048',
            'latitude' => 'required', // Dikirim dari Javascript di Frontend
            'longitude' => 'required',
        ]);

        $user = Auth::user();
        
        // Ambil Data Lamaran Aktif & Lokasi INSTANSI
        $app = Application::with('position.instansi')->where('user_id', $user->id)->where('status', 'diterima')->first();
        
        if (!$app) return back()->with('error', 'Akses ditolak.');

        // 2. LOGIKA GEOTAGGING (Cek Jarak)
        $kantorLat = $app->position->instansi->latitude; // Pastikan tabel INSTANSI punya kolom latitude
        $kantorLng = $app->position->instansi->longitude;
        
        $jarakKm = $this->calculateDistance($request->latitude, $request->longitude, $kantorLat, $kantorLng);
        
        // Batas toleransi 100 meter (0.1 KM)
        if ($jarakKm > 20000) {
            return back()->with('error', 'Gagal Check-in! Posisi Anda terlalu jauh dari kantor dinas (' . number_format($jarakKm*1000) . ' meter).');
        }

        // 3. Upload Foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('logbooks', 'public');
        }

        // 4. Simpan Log
        DailyLog::create([
            'application_id' => $app->id,
            'tanggal' => now(),
            'kegiatan' => $request->kegiatan,
            'bukti_foto_path' => $fotoPath,
            'status_validasi' => 'pending'
        ]);

        return back()->with('success', 'Logbook hari ini berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kegiatan' => 'required',
            'foto' => 'nullable|image|max:5048',
        ]);

        $log = DailyLog::findOrFail($id);
        
        // Pastikan milik user yang sedang login
        if ($log->application->user_id != Auth::id()) {
            return back()->with('error', 'Akses ditolak.');
        }

        if ($log->status_validasi !== 'revisi') {
            return back()->with('error', 'Logbook ini tidak dalam status revisi.');
        }

        $fotoPath = $log->bukti_foto_path;
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($fotoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($fotoPath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('logbooks', 'public');
        }

        $log->update([
            'kegiatan' => $request->kegiatan,
            'bukti_foto_path' => $fotoPath,
            'status_validasi' => 'pending',
            'komentar_pembimbing_lapangan' => null, // Reset komentar pembimbing_lapangan setelah revisi
        ]);

        return back()->with('success', 'Logbook berhasil direvisi dan dikirim ulang untuk divalidasi!');
    }

    // --- FITUR BARU: CETAK REKAP LOGBOOK ---
    public function print()
    {
        $user = Auth::user();
        
        // Ambil data lamaran yang statusnya diterima/selesai
        $app = Application::with(['position.instansi', 'pembimbing_lapangan'])
                ->where('user_id', $user->id)
                ->whereIn('status', ['diterima', 'selesai'])
                ->firstOrFail();

        // Ambil seluruh logbook, urutkan dari tanggal awal
        $logs = DailyLog::where('application_id', $app->id)
                ->orderBy('tanggal', 'asc')
                ->get();

        $pdf = Pdf::loadView('pdf.logbook_rekap', compact('app', 'logs', 'user'));
        
        // Set ukuran kertas F4 atau A4 Landscape agar muat banyak
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Rekap-Kegiatan-'.$user->name.'.pdf');
    }

    // Fungsi Matematika Haversine (Menghitung Jarak 2 Titik Koordinat)
    private function calculateDistance($lat1, $lon1, $lat2, $lon2) 
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