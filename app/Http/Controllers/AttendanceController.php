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
    public function clockOut()
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
}