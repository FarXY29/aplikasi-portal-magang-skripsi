<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Tampilkan riwayat absen (Attendance History) untuk Peserta
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $application = Application::where('user_id', $user->id)
                        ->whereIn('status', ['diterima', 'selesai'])
                        ->first();

        if (!$application) {
            return redirect()->route('peserta.dashboard')->with('error', 'Anda tidak memiliki status magang aktif untuk melihat absensi.');
        }

        $query = Attendance::where('application_id', $application->id)
                           ->orderBy('date', 'desc');

        // Filter berdasarkan bulan jika ada
        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('date', Carbon::parse($request->month)->month)
                  ->whereYear('date', Carbon::parse($request->month)->year);
        }

        $attendances = $query->paginate(15);

        return view('peserta.absensi.index', compact('attendances', 'application'));
    }

    /**
     * ABSEN DATANG (Clock In)
     * Mengecek jam mulai masuk dari tabel INSTANSI.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $today = $now->format('Y-m-d');

        // 1. Cari Aplikasi Magang yang Aktif
        $application = Application::where('user_id', $user->id)
                        ->where('status', 'diterima')
                        ->with('position.instansi') // Load Relasi INSTANSI untuk ambil jam kerja
                        ->first();

        if (!$application) {
            return back()->with('error', 'Anda tidak memiliki status magang aktif untuk melakukan absensi.');
        }

        if ($application->display_status === 'belum mulai') {
            return back()->with('error', 'Masa magang Anda belum dimulai. Silakan kembali pada ' . \Carbon\Carbon::parse($application->tanggal_mulai)->translatedFormat('d F Y') . '.');
        }

        // 2. CEK JADWAL MASUK (DINAMIS DARI DB)
        // Ambil jam masuk dari INSTANSI, misal "08:00:00"
        $jamMasukINSTANSI = $application->position->instansi->jam_mulai_masuk ?? '07:30:00'; // Default jika null
        
        // Buat objek Carbon untuk jam masuk hari ini
        $waktuBukaAbsen = Carbon::createFromFormat('H:i:s', $jamMasukINSTANSI);

        // Validasi: Jika sekarang lebih awal dari jam buka absen
        if ($now->lessThan($waktuBukaAbsen)) {
            return back()->with('error', 'Absen datang belum dibuka. Jadwal absen masuk dimulai pukul ' . $waktuBukaAbsen->format('H:i'));
        }

        // 2.5 CEK LOKASI GPS & RADIUS ABSEN
        $kantorLat = $application->position->instansi->latitude ?? null;
        $kantorLng = $application->position->instansi->longitude ?? null;
        $radiusAbsen = $application->position->instansi->radius_absen ?? 100;

        if ($kantorLat && $kantorLng) {
            if (!$request->has('latitude') || !$request->has('longitude') || $request->latitude == null || $request->longitude == null) {
                return back()->with('error', 'Gagal Absen Datang! Lokasi GPS Anda tidak ditemukan. Pastikan izin lokasi (Location/GPS) diaktifkan di browser/HP Anda.');
            }

            $jarakKm = $this->calculateDistance($request->latitude, $request->longitude, $kantorLat, $kantorLng);
            $jarakMeter = $jarakKm * 1000;

            if ($jarakMeter > $radiusAbsen) {
                return back()->with('error', 'Gagal Absen Datang! Posisi Anda berada di luar radius kantor (' . number_format($jarakMeter, 0) . ' meter, batas maksimal ' . $radiusAbsen . ' meter).');
            }
        }

        // 3. Cek Duplikasi (Sudah absen hari ini?)
        $existing = Attendance::where('application_id', $application->id)
                        ->where('date', $today)
                        ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mengisi data absensi hari ini.');
        }

        // 4. Simpan Data
        Attendance::create([
            'application_id' => $application->id,
            'date' => $today,
            'status' => 'hadir',
            'clock_in' => $now->format('H:i:s'),
            'validation_status' => 'pending',
        ]);

        return back()->with('success', 'Berhasil Absen Datang! Selamat beraktivitas.');
    }

    /**
     * ABSEN PULANG (Clock Out)
     * Mengecek jam mulai pulang dari tabel INSTANSI.
     */
    public function clockOut(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $today = $now->format('Y-m-d');

        // 1. Cari Aplikasi
        $application = Application::where('user_id', $user->id)
                        ->where('status', 'diterima')
                        ->with('position.instansi')
                        ->first();

        if (!$application) {
            return back()->with('error', 'Status magang tidak aktif.');
        }

        if ($application->display_status === 'belum mulai') {
            return back()->with('error', 'Masa magang Anda belum dimulai.');
        }

        // 2. CEK JADWAL PULANG (DINAMIS DARI DB)
        $jamPulangINSTANSI = $application->position->instansi->jam_mulai_pulang ?? '16:00:00';
        $waktuBolehPulang = Carbon::createFromFormat('H:i:s', $jamPulangINSTANSI);

        // Validasi: Jika sekarang belum waktunya pulang
        if ($now->lessThan($waktuBolehPulang)) {
            return back()->with('error', 'Belum waktunya pulang! Absen pulang baru dibuka pukul ' . $waktuBolehPulang->format('H:i'));
        }

        // 2.5 CEK LOKASI GPS & RADIUS ABSEN
        $kantorLat = $application->position->instansi->latitude ?? null;
        $kantorLng = $application->position->instansi->longitude ?? null;
        $radiusAbsen = $application->position->instansi->radius_absen ?? 100;

        if ($kantorLat && $kantorLng) {
            if (!$request->has('latitude') || !$request->has('longitude') || $request->latitude == null || $request->longitude == null) {
                return back()->with('error', 'Gagal Absen Pulang! Lokasi GPS Anda tidak ditemukan. Pastikan izin lokasi (Location/GPS) diaktifkan di browser/HP Anda.');
            }

            $jarakKm = $this->calculateDistance($request->latitude, $request->longitude, $kantorLat, $kantorLng);
            $jarakMeter = $jarakKm * 1000;

            if ($jarakMeter > $radiusAbsen) {
                return back()->with('error', 'Gagal Absen Pulang! Posisi Anda berada di luar radius kantor (' . number_format($jarakMeter, 0) . ' meter, batas maksimal ' . $radiusAbsen . ' meter).');
            }
        }

        // 3. Cari Data Absen Pagi Tadi
        $attendance = Attendance::where('application_id', $application->id)
                        ->where('date', $today)
                        ->where('status', 'hadir')
                        ->first();

        if (!$attendance) {
            return back()->with('error', 'Anda belum melakukan absen datang hari ini.');
        }

        if ($attendance->clock_out != null) {
            return back()->with('error', 'Anda sudah melakukan absen pulang sebelumnya.');
        }

        // 4. Update Jam Pulang
        $attendance->update([
            'clock_out' => $now->format('H:i:s'),
        ]);

        return back()->with('success', 'Berhasil Absen Pulang! Hati-hati di jalan.');
    }

    /**
     * PENGAJUAN IZIN / SAKIT
     * Bisa dilakukan kapan saja tanpa batasan jam.
     */
    public function permission(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'status' => 'required|in:izin,sakit',
            'description' => 'required|string|max:255',
            'proof_file' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Wajib bukti
        ]);

        $user = Auth::user();
        $today = Carbon::now()->format('Y-m-d');

        $application = Application::where('user_id', $user->id)
                        ->where('status', 'diterima')
                        ->first();

        if (!$application) {
            return back()->with('error', 'Status magang tidak aktif.');
        }

        if ($application->display_status === 'belum mulai') {
            return back()->with('error', 'Masa magang Anda belum dimulai.');
        }

        // 2. Cek Duplikasi
        $existing = Attendance::where('application_id', $application->id)
                        ->where('date', $today)
                        ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mengisi data absensi/izin hari ini.');
        }

        // 3. Upload File Bukti
        $path = $request->file('proof_file')->store('documents/izin', 'public');

        // 4. Simpan Data
        Attendance::create([
            'application_id' => $application->id,
            'date' => $today,
            'status' => $request->status,
            'description' => $request->description,
            'proof_file' => $path,
            'clock_in' => null, // Tidak ada jam masuk
            'clock_out' => null, // Tidak ada jam pulang
            'validation_status' => 'pending'
        ]);

        return back()->with('success', 'Pengajuan Izin/Sakit berhasil dikirim.');
    }

    /**
     * Fungsi Matematika Haversine (Menghitung Jarak 2 Titik Koordinat dalam KM)
     */
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

        return $distance;
    }
}