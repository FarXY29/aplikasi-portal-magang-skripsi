<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class AttendanceService
{
    /**
     * Memeriksa apakah waktu saat ini berada dalam rentang jadwal buka absensi instansi.
     */
    public function verifyTimeWindow(Carbon $now, ?string $targetTimeStr, string $type = 'masuk'): array
    {
        $defaultTime = $type === 'masuk' ? '07:30:00' : '16:00:00';
        $timeStr = $targetTimeStr ?? $defaultTime;

        $targetTime = Carbon::createFromFormat('H:i:s', $timeStr);

        if ($now->lessThan($targetTime)) {
            $label = $type === 'masuk' ? 'datang' : 'pulang';
            return [
                'is_valid' => false,
                'message' => "Absen {$label} belum dibuka. Jadwal absen {$label} dimulai pukul " . $targetTime->format('H:i'),
            ];
        }

        return ['is_valid' => true, 'message' => 'Waktu valid.'];
    }

    /**
     * Memeriksa dan memvalidasi koordinat GPS peserta apakah berada dalam radius absensi kantor instansi.
     */
    public function verifyGpsLocation($requestLat, $requestLng, ?float $officeLat, ?float $officeLng, int $maxRadiusMeters = 100, string $type = 'Datang'): array
    {
        if ($officeLat && $officeLng) {
            if ($requestLat === null || $requestLng === null) {
                return [
                    'is_valid' => false,
                    'message' => "Gagal Absen {$type}! Lokasi GPS Anda tidak ditemukan. Pastikan izin lokasi (Location/GPS) diaktifkan di browser/HP Anda.",
                ];
            }

            $jarakKm = $this->calculateDistance($requestLat, $requestLng, $officeLat, $officeLng);
            $jarakMeter = $jarakKm * 1000;

            if ($jarakMeter > $maxRadiusMeters) {
                return [
                    'is_valid' => false,
                    'distance_meters' => $jarakMeter,
                    'message' => "Gagal Absen {$type}! Posisi Anda berada di luar radius kantor (" . number_format($jarakMeter, 0) . " meter, batas maksimal {$maxRadiusMeters} meter).",
                ];
            }

            return ['is_valid' => true, 'distance_meters' => $jarakMeter];
        }

        // Jika kantor tidak menentukan koordinat GPS, izinkan absen dari mana saja
        return ['is_valid' => true, 'distance_meters' => 0];
    }

    /**
     * Fungsi Matematika Haversine (Menghitung Jarak 2 Titik Koordinat dalam KM).
     *
     * Delegasi ke GeoDistanceService terpusat — behavior identik,
     * hanya deduplikasi implementasi (§27).
     */
    public function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        return app(\App\Services\Attendance\GeoDistanceService::class)
            ->distanceKm((float) $lat1, (float) $lon1, (float) $lat2, (float) $lon2);
    }

    /**
     * Siapkan data riwayat absensi: periodMonths, query, summary.
     *
     * @return array{
     *     application: \App\Models\Application,
     *     periodMonths: array<string, string>,
     *     attendanceSummary: array{total: int, hadir: int, izin: int, alpa: int},
     *     query: Builder
     * }
     */
    public function historyData($user, $request)
    {
        // Prioritaskan status 'diterima' (aktif), jika tidak ada baru gunakan 'selesai' (riwayat)
        $application = Application::where('user_id', $user->id)
                        ->where('status', 'diterima')
                        ->first();

        if (!$application) {
            $application = Application::where('user_id', $user->id)
                            ->where('status', 'selesai')
                            ->latest('updated_at')
                            ->first();
        }

        // Generate daftar pilihan bulan berdasarkan rentang tanggal magang peserta (terbaru -> terlama)
        $periodMonths = [];
        if ($application->tanggal_mulai && $application->tanggal_selesai) {
            $start = Carbon::parse($application->tanggal_mulai)->startOfMonth();
            $end = Carbon::parse($application->tanggal_selesai)->endOfMonth();
            $today = Carbon::today()->endOfMonth();

            if ($end->gt($today)) {
                $end = $today;
            }

            if ($start->lte($end)) {
                $curr = $end->copy();
                while ($curr->gte($start)) {
                    $periodMonths[$curr->format('Y-m')] = $curr->translatedFormat('F Y');
                    $curr->subMonth();
                }
            }
        }

        $query = Attendance::where('application_id', $application->id);

        // Filter berdasarkan bulan jika ada
        if ($request->filled('month')) {
            $monthVal = trim($request->month);
            if (preg_match('/^\d{4}-\d{1,2}$/', $monthVal)) {
                [$yr, $mo] = explode('-', $monthVal);
                $query->whereYear('date', (int)$yr)
                      ->whereMonth('date', (int)$mo);
            } elseif (is_numeric($monthVal) && (int)$monthVal >= 1 && (int)$monthVal <= 12) {
                $query->whereMonth('date', (int)$monthVal);
                if ($request->filled('year')) {
                    $query->whereYear('date', (int)$request->year);
                }
            } else {
                try {
                    $monthDate = Carbon::parse($monthVal);
                    $query->whereMonth('date', $monthDate->month)
                          ->whereYear('date', $monthDate->year);
                } catch (\Exception $e) {
                    // Abaikan jika format bulan tidak valid
                }
            }
        }

        // Summary counts (berdasarkan query bulan jika ada, sebelum filter status & search agar statistik tetap komprehensif)
        $attendanceSummary = [
            'total' => (clone $query)->count(),
            'hadir' => (clone $query)->where('status', 'hadir')->count(),
            'izin'  => (clone $query)->whereIn('status', ['izin', 'sakit'])->count(),
            'alpa'  => (clone $query)->where('status', 'alpa')->count(),
        ];

        // Filter berdasarkan status
        if ($request->filled('status') && in_array($request->status, ['hadir', 'izin', 'sakit', 'alpa'])) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan pencarian deskripsi/catatan
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('description', 'like', "%{$search}%");
        }

        return compact('application', 'periodMonths', 'attendanceSummary', 'query');
    }
}
