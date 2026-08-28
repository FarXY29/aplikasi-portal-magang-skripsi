<?php

namespace App\Services\Attendance;

use App\Enums\AttendanceFraudStatus;
use Illuminate\Support\Collection;

/**
 * Hasil analisis fraud satu attempt absensi.
 */
class AttendanceFraudResult
{
    /**
     * @param Collection<int, FraudSignal> $signals
     */
    public function __construct(
        public readonly int $score,
        public readonly AttendanceFraudStatus $status,
        public readonly Collection $signals,
    ) {
    }

    public static function clean(): self
    {
        return new self(0, AttendanceFraudStatus::Low, collect());
    }

    /**
     * Kode indikator untuk disimpan/ditampilkan (contoh admin §32).
     *
     * @return array<string>
     */
    public function indicatorCodes(): array
    {
        return $this->signals->map(fn (FraudSignal $s) => $s->code)->values()->all();
    }

    /**
     * Label penyebab ringkas dalam bahasa Indonesia.
     *
     * @return array<string>
     */
    public function indicatorLabels(): array
    {
        return $this->signals
            ->map(fn (FraudSignal $s) => $s->code . ': ' . $s->describe())
            ->values()->all();
    }

    public function isCritical(): bool
    {
        return $this->status === AttendanceFraudStatus::Critical;
    }

    /**
     * Apakah attempt boleh ditolak keras (hanya relevan di mode enforce).
     */
    public function shouldBlock(string $mode): bool
    {
        // Hard rule: nonce invalid/replayed → CRITICAL → block di enforce.
        return $mode === 'enforce'
            && $this->isCritical()
            && $this->signals->contains(fn (FraudSignal $s) => $s->code === 'INVALID_NONCE');
    }
}
