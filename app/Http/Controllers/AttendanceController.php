<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Application;
use App\Models\Instansi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\Attendance\ClockInRequest;
use App\Http\Requests\Attendance\PermissionRequest;
use App\Services\AuditLogService;
use App\Services\AttendanceService;
use App\Services\Attendance\AttendanceChallengeService;
use App\Services\Attendance\AttendanceIdempotencyService;
use App\Services\Attendance\AttendanceLockService;
use App\Services\Attendance\AttendanceFraudContext;
use App\Services\Attendance\AttendanceFraudDetector;
use App\Services\Attendance\AttendanceFraudResult;
use App\Services\Attendance\AttendanceAttemptService;
use App\Services\Attendance\GeoDistanceService;
use Illuminate\Database\QueryException;
use Throwable;

class AttendanceController extends Controller
{
    public function __construct(
        private readonly AttendanceChallengeService $challenge,
        private readonly AttendanceIdempotencyService $idempotency,
        private readonly AttendanceLockService $lock,
        private readonly AttendanceFraudDetector $fraudDetector,
        private readonly AttendanceAttemptService $attempts,
        private readonly GeoDistanceService $geo,
    ) {
    }

    /**
     * Tampilkan riwayat absen (Attendance History) untuk Peserta
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $historyData = app(AttendanceService::class)->historyData($user, $request);
        $application = $historyData['application'];
        $periodMonths = $historyData['periodMonths'];
        $attendanceSummary = $historyData['attendanceSummary'];
        $query = $historyData['query'];

        if (!$application) {
            return redirect()->route('peserta.dashboard')->with('error', 'Anda tidak memiliki status magang aktif untuk melihat absensi.');
        }

        // Urutan
        $sort = $request->get('sort', 'desc');
        $sort = in_array($sort, ['asc', 'desc']) ? $sort : 'desc';
        $query->orderBy('date', $sort);

        $attendances = $query->paginate(15)->withQueryString();

        return view('peserta.absensi.index', compact('attendances', 'application', 'attendanceSummary', 'periodMonths'));
    }

    /**
     * ATTENDANCE CHALLENGE (P0 §5.3)
     *
     * Server menerbitkan nonce cryptographically secure, single-use,
     * short-lived, dan terikat user. Browser mengambilnya SEBELUM
     * mengambil geolocation, lalu mengirimkannya bersama absensi.
     *
     * Nonce BUKAN bukti GPS asli — hanya pengurang replay/old-request abuse.
     */
    public function challenge(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'peserta') {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        $data = $this->challenge->issue($user);

        return response()->json([
            'nonce' => $data['nonce'],
            'expires_at' => $data['expires_at'],
            'ttl' => $data['ttl'],
            'server_time' => now()->getTimestamp(),
        ]);
    }

    /**
     * ABSEN DATANG (Clock In)
     * Mengecek jam mulai masuk dari tabel INSTANSI.
     */
    public function store(ClockInRequest $request)
    {
        return $this->processAttendance($request, 'clock_in');
    }

    /**
     * ABSEN PULANG (Clock Out)
     * Mengecek jam mulai pulang dari tabel INSTANSI.
     */
    public function clockOut(ClockInRequest $request)
    {
        return $this->processAttendance($request, 'clock_out');
    }

    /**
     * ORCHESTRATION P0 + fraud layer (§26).
     *
     * Flow: validate → user → application → instansi → jadwal → nonce →
     * idempotency → lock → geofence existing → fraud detector → attempt →
     * attendance decision → evidence.
     *
     * Pesan sukses/error tetap identik dengan versi existing agar UX
     * user normal tidak berubah.
     */
    private function processAttendance(ClockInRequest $request, string $type)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $today = $now->format('Y-m-d');

        $fraudEnabled = (bool) config('attendance.enabled', true);
        $mode = (string) config('attendance.mode', 'shadow');

        // 1. Resolve Aplikasi Magang Aktif
        $application = Application::where('user_id', $user->id)
                        ->where('status', 'diterima')
                        ->with('position.instansi')
                        ->first();

        if (!$application) {
            return back()->with('error', $type === 'clock_in'
                ? 'Anda tidak memiliki status magang aktif untuk melakukan absensi.'
                : 'Status magang tidak aktif.');
        }

        $instansi = $application->position?->instansi;

        $todayCarbon = Carbon::today();
        $startDate = Carbon::parse($application->tanggal_mulai)->startOfDay();
        $endDate = Carbon::parse($application->tanggal_selesai)->endOfDay();

        if ($todayCarbon->lt($startDate)) {
            return back()->with('error', $type === 'clock_in'
                ? 'Masa magang Anda belum dimulai. Silakan kembali pada ' . $startDate->translatedFormat('d F Y') . '.'
                : 'Masa magang Anda belum dimulai.');
        }

        if ($todayCarbon->gt($endDate)) {
            return back()->with('error', $type === 'clock_in'
                ? 'Masa magang Anda telah berakhir pada ' . $endDate->translatedFormat('d F Y') . '.'
                : 'Masa magang Anda telah berakhir.');
        }

        // 2. Jadwal (dinamis dari DB) — behavior existing dipertahankan.
        if ($type === 'clock_in') {
            $jamMulai = $instansi->jam_mulai_masuk ?? '07:30:00';
            $waktuBuka = Carbon::parse($jamMulai);

            if ($now->lessThan($waktuBuka)) {
                return back()->with('error', 'Absen datang belum dibuka. Jadwal absen masuk dimulai pukul ' . $waktuBuka->format('H:i'));
            }
        } else {
            $jamPulang = $instansi->jam_mulai_pulang ?? '16:00:00';
            $waktuBolehPulang = Carbon::parse($jamPulang);

            if ($now->lessThan($waktuBolehPulang)) {
                return back()->with('error', 'Belum waktunya pulang! Absen pulang baru dibuka pukul ' . $waktuBolehPulang->format('H:i'));
            }
        }

        // 3. Geofence koordinat instansi (existing — TIDAK diubah).
        $kantorLat = $instansi->latitude ?? null;
        $kantorLng = $instansi->longitude ?? null;
        $radiusAbsen = $instansi->radius_absen ?? 100;
        $hasGeofence = ($kantorLat !== null && $kantorLng !== null);

        $latitude = $request->filled('latitude') ? (float) $request->latitude : null;
        $longitude = $request->filled('longitude') ? (float) $request->longitude : null;

        $distanceMeters = null;

        if ($hasGeofence) {
            // User TIDAK boleh menghindari geofence dengan menghilangkan
            // latitude/longitude (§6).
            if ($latitude === null || $longitude === null) {
                return back()->with('error', $type === 'clock_in'
                    ? 'Gagal Absen Datang! Lokasi GPS Anda tidak ditemukan. Pastikan izin lokasi (Location/GPS) diaktifkan di browser/HP Anda.'
                    : 'Gagal Absen Pulang! Lokasi GPS Anda tidak ditemukan. Pastikan izin lokasi (Location/GPS) diaktifkan di browser/HP Anda.');
            }

            $distanceMeters = $this->geo->distanceMeters($latitude, $longitude, (float) $kantorLat, (float) $kantorLng);

            if ($distanceMeters > $radiusAbsen) {
                return back()->with('error', ($type === 'clock_in' ? 'Gagal Absen Datang!' : 'Gagal Absen Pulang!')
                    . ' Posisi Anda berada di luar radius kantor (' . number_format($distanceMeters, 0) . ' meter, batas maksimal ' . $radiusAbsen . ' meter).');
            }
        }

        // 4. NONCE / replay protection (P0 §5.3).
        $nonce = $request->input('nonce');
        $requireNonce = $fraudEnabled && (bool) config('attendance.require_nonce', true);

        $nonceResult = null;
        if ($fraudEnabled) {
            $consumed = $this->challenge->consume($user, $nonce);

            // Hanya bila nonce GAGAL dikonsumsi → signal hard INVALID_NONCE.
            if (!$consumed) {
                $nonceResult = $this->fraudDetector->scoreNonceInvalid([
                    'nonce_present' => is_string($nonce) && $nonce !== '',
                    'consumed' => false,
                ]);
            }

            if ($requireNonce && !$consumed) {
                // Attempt replay/nonce invalid tetap tercatat sebagai bukti.
                $context = $this->buildContext($request, $user, $application, $instansi, $type, $now, $latitude, $longitude, $distanceMeters);
                $this->attempts->record($context, $nonceResult, null, 'rejected');
                $this->auditReplay($application, $nonceResult, $request);

                return back()->with('error', 'Sesi keamanan absensi tidak valid atau sudah kedaluwarsa. Silakan muat ulang halaman lalu coba lagi.');
            }
        }

        // 5. IDEMPOTENCY (P0 §5.2) — duplicate request → hasil sebelumnya.
        $idemKey = $this->idempotency->resolveKey($request);
        if ($idemKey !== null && $this->idempotency->isProcessed($idemKey)) {
            $previous = $this->idempotency->getResult($idemKey);

            return back()->with($previous['type'] ?? 'error', $previous['message'] ?? 'Permintaan absensi sudah diproses sebelumnya.');
        }

        // 6. ATOMIC LOCK (P0 §5.5) — double-click / multi-tab / race.
        $lockAcquired = $this->lock->acquire($user);
        if (!$lockAcquired) {
            return back()->with('error', 'Permintaan absensi Anda sedang diproses. Mohon tunggu beberapa saat.');
        }

        try {
            return $this->finalizeAttendance(
                $request, $user, $application, $instansi, $type, $now, $today,
                $latitude, $longitude, $distanceMeters, $radiusAbsen,
                $fraudEnabled, $mode, $nonceResult, $idemKey,
            );
        } finally {
            $this->lock->release($user);
        }
    }

    private function finalizeAttendance(
        ClockInRequest $request,
        $user,
        $application,
        ?Instansi $instansi,
        string $type,
        Carbon $now,
        string $today,
        ?float $latitude,
        ?float $longitude,
        ?float $distanceMeters,
        int $radiusAbsen,
        bool $fraudEnabled,
        string $mode,
        ?AttendanceFraudResult $nonceResult,
        ?string $idemKey,
    ) {
        // 7. Pre-check duplicate & record existing (behavior existing).
        if ($type === 'clock_in') {
            $existing = Attendance::where('application_id', $application->id)
                            ->where('date', $today)
                            ->first();

            if ($existing) {
                return back()->with('error', 'Anda sudah mengisi data absensi hari ini.');
            }
        } else {
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
        }

        // 8. FRAUD DETECTOR (server-side, §9-§22).
        $fraudResult = AttendanceFraudResult::clean();

        if ($fraudEnabled) {
            $context = $this->buildContext($request, $user, $application, $instansi, $type, $now, $latitude, $longitude, $distanceMeters);

            // Bila nonce valid namun ada signal lain → jalankan full detector.
            $fraudResult = $this->fraudDetector->detect($context);

            // Nonce divalidasi di langkah 4 — bila invalid tapi non-required,
            // signal CRITICAL tetap digabung (shadow/soft mencatat).
            if ($nonceResult !== null && $nonceResult->isCritical()) {
                $fraudResult = $nonceResult;
            }
        }

        // 9. Keputusan berdasarkan mode (§23, §24).
        $blocked = $fraudEnabled && $fraudResult->shouldBlock($mode);

        // 10. Persist attendance + attempt evidence.
        try {
            if ($type === 'clock_in') {
                $attendance = Attendance::create([
                    'application_id' => $application->id,
                    'date' => $today,
                    'status' => 'hadir',
                    'clock_in' => $now->format('H:i:s'), // SERVER time authoritative
                    'latitude_in' => $latitude,
                    'longitude_in' => $longitude,
                    'ip_address' => $request->ip(),
                    'device_info' => $request->userAgent(),
                    'validation_status' => 'approved',
                    'risk_score' => $fraudEnabled ? $fraudResult->score : null,
                    'fraud_status' => $fraudEnabled ? $fraudResult->status->value : null,
                ]);

                app(AuditLogService::class)->record('attendance.clocked_in', $attendance, [
                    'application_id' => $application->id,
                    'date' => $today,
                ]);

                $successMessage = 'Berhasil Absen Datang! Selamat beraktivitas.';
            } else {
                // Guard race: hanya update bila clock_out masih null.
                $updated = Attendance::where('application_id', $application->id)
                            ->where('date', $today)
                            ->where('status', 'hadir')
                            ->whereNull('clock_out')
                            ->update([
                                'clock_out' => $now->format('H:i:s'), // SERVER time authoritative
                                'latitude_out' => $latitude,
                                'longitude_out' => $longitude,
                                'risk_score' => $fraudEnabled ? $fraudResult->score : null,
                                'fraud_status' => $fraudEnabled ? $fraudResult->status->value : null,
                            ]);

                if ($updated === 0) {
                    return back()->with('error', 'Anda sudah melakukan absen pulang sebelumnya.');
                }

                $attendance = $attendance->refresh();

                app(AuditLogService::class)->record('attendance.clocked_out', $attendance, [
                    'application_id' => $application->id,
                    'date' => $today,
                ]);

                $successMessage = 'Berhasil Absen Pulang! Hati-hati di jalan.';
            }

            // Bila fraud layer aktif: simpan attempt sukses + events.
            if ($fraudEnabled) {
                $context = $this->buildContext($request, $user, $application, $instansi, $type, $now, $latitude, $longitude, $distanceMeters);
                $attempt = $this->attempts->record($context, $fraudResult, $attendance, $blocked ? 'blocked' : 'accepted');

                if ($attempt && $idemKey) {
                    $this->attempts->attachIdempotencyKey($attempt, $idemKey);
                }
            }

            // Idempotency: simpan hasil agar duplicate mengembalikan hasil sama.
            if ($idemKey !== null) {
                $this->idempotency->storeResult($idemKey, 'success', $successMessage);
            }

            // Blocked (enforce) TIDAK menghapus record yang sudah dibuat di atas;
            // kebijakan enforce-pasca-insert: attendance tetap valid, ditandai
            // critical untuk review admin. (Block-before-insert terjadi di
            // langkah nonce di atas untuk hard rule replay.)
            return back()->with('success', $successMessage);
        } catch (QueryException $e) {
            // 11. Database uniqueness = protection terakhir race condition
            // (unique application_id+date) → pesan ramah, bukan HTTP 500.
            if ($this->isDuplicateEntry($e)) {
                $message = $type === 'clock_in'
                    ? 'Anda sudah mengisi data absensi hari ini.'
                    : 'Anda sudah melakukan absen pulang sebelumnya.';

                if ($idemKey !== null) {
                    $this->idempotency->storeResult($idemKey, 'error', $message);
                }

                return back()->with('error', $message);
            }

            throw $e;
        }
    }

    /**
     * PENGAJUAN IZIN / SAKIT
     * Bisa dilakukan kapan saja tanpa batasan jam.
     */
    public function permission(PermissionRequest $request)
    {
        $user = Auth::user();
        $today = Carbon::now()->format('Y-m-d');

        $application = Application::where('user_id', $user->id)
                        ->where('status', 'diterima')
                        ->first();

        if (!$application) {
            return back()->with('error', 'Status magang tidak aktif.');
        }

        $today = Carbon::today();
        $startDate = Carbon::parse($application->tanggal_mulai)->startOfDay();
        $endDate = Carbon::parse($application->tanggal_selesai)->endOfDay();

        if ($today->lt($startDate)) {
            return back()->with('error', 'Masa magang Anda belum dimulai.');
        }

        if ($today->gt($endDate)) {
            return back()->with('error', 'Masa magang Anda telah berakhir.');
        }

        // 2. Cek Duplikasi
        $existing = Attendance::where('application_id', $application->id)
                        ->where('date', $today)
                        ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mengisi data absensi/izin hari ini.');
        }

        // 3. Upload File Bukti
        $path = $request->file('proof_file')->store('documents/attendance', 'private');

        // 4. Simpan Data
        $attendance = Attendance::create([
            'application_id' => $application->id,
            'date' => $today,
            'status' => $request->status,
            'description' => $request->description,
            'proof_file' => $path,
            'clock_in' => null, // Tidak ada jam masuk
            'clock_out' => null, // Tidak ada jam pulang
            'validation_status' => 'pending'
        ]);

        app(AuditLogService::class)->record('attendance.permission_requested', $attendance, [
            'application_id' => $application->id,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Pengajuan Izin/Sakit berhasil dikirim.');
    }

    // -----------------------------------------------------------------
    // Helper anti-fraud
    // -----------------------------------------------------------------

    private function buildContext(
        ClockInRequest $request,
        $user,
        $application,
        ?Instansi $instansi,
        string $type,
        Carbon $now,
        ?float $latitude,
        ?float $longitude,
        ?float $distanceMeters,
    ): AttendanceFraudContext {
        $historyCount = (int) config('attendance.location_history_count', 10);

        // Histori terbaru milik user (lintas application untuk deteksi pola).
        $history = Attendance::whereHas('application', fn ($q) => $q->where('user_id', $user->id))
            ->whereDate('date', '<=', $now->toDateString())
            ->latest('date')
            ->limit($historyCount)
            ->get();

        // Attendance terakhir SEBELUM request ini untuk impossible travel.
        $previous = Attendance::whereHas('application', fn ($q) => $q->where('user_id', $user->id))
            ->whereDate('date', '<=', $now->toDateString())
            ->orderByDesc('date')
            ->first();

        $lastNetwork = $this->attempts->lastAttemptNetwork($user->id);

        $sessionHash = null;
        try {
            $sessionId = $request->session()->getId();
            $sessionHash = $sessionId ? hash('sha256', $sessionId) : null;
        } catch (Throwable) {
            // session tidak tersedia (stateless) — abaikan.
        }

        return new AttendanceFraudContext(
            user: $user,
            application: $application,
            instansi: $instansi,
            attendanceType: $type,
            latitude: $latitude,
            longitude: $longitude,
            accuracy: $request->filled('accuracy') ? (float) $request->accuracy : null,
            altitude: $request->filled('altitude') ? (float) $request->altitude : null,
            speed: $request->filled('speed') ? (float) $request->speed : null,
            heading: $request->filled('heading') ? (float) $request->heading : null,
            clientTimestampMs: $request->filled('client_timestamp') ? (int) $request->client_timestamp : null,
            serverReceivedAt: $now->copy(),
            ipAddress: $request->ip(),
            userAgent: $request->userAgent(),
            sessionHash: $sessionHash,
            previousIpAddress: $lastNetwork['ip'],
            previousUserAgent: $lastNetwork['user_agent'],
            previousAttendance: $previous,
            attendanceHistory: $history,
            recentAttemptCount: $this->attempts->recentAttemptCount($user->id),
            distanceToInstance: $distanceMeters,
        );
    }

    private function auditReplay($application, AttendanceFraudResult $result, Request $request): void
    {
        try {
            app(AuditLogService::class)->record('attendance.request.replayed', null, [
                'application_id' => $application->id,
                'risk_score' => $result->score,
                'indicators' => $result->indicatorCodes(),
                'ip' => $request->ip(),
            ]);
        } catch (Throwable) {
            // audit tidak boleh merusak flow
        }
    }

    private function isDuplicateEntry(QueryException $e): bool
    {
        // MySQL duplicate entry (1062) / PostgreSQL unique violation (23505).
        return in_array($e->getCode(), [23000, 23505], true)
            && str_contains($e->getMessage(), 'Duplicate entry');
    }
}
