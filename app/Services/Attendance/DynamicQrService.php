<?php

namespace App\Services\Attendance;

use App\Models\Instansi;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DynamicQrService
{
    /**
     * Interval rotasi Dynamic QR dalam detik (default 30 detik).
     */
    public const ROTATION_INTERVAL = 30;

    /**
     * Menghasilkan data token dinamis untuk instansi pada waktu tertentu.
     *
     * @param Instansi $instansi
     * @param int|null $timestamp
     * @return array{token: string, interval: int, step: int, remaining_seconds: int, expires_at: int, server_time: int}
     */
    public function generateTokenData(Instansi $instansi, ?int $timestamp = null): array
    {
        $time = $timestamp ?? time();
        $step = $this->getTimeStep($time);
        $secret = $this->getInstansiSecret($instansi);
        $signature = $this->computeSignature((int) $instansi->id, $step, $secret);

        $payload = [
            'i'   => (int) $instansi->id,
            's'   => $step,
            'sig' => $signature,
        ];

        $token = base64_encode(json_encode($payload));
        $remaining = $this->getRemainingSeconds($time);
        $expiresAt = ($step + 1) * self::ROTATION_INTERVAL;

        return [
            'token'             => $token,
            'interval'          => self::ROTATION_INTERVAL,
            'step'              => $step,
            'remaining_seconds' => $remaining,
            'expires_at'        => $expiresAt,
            'server_time'       => $time,
        ];
    }

    /**
     * Memverifikasi keabsahan token presensi dinamis untuk instansi terkait.
     * Mendukung toleransi 1 interval mundur (step T dan T-1, total max 60 detik)
     * untuk mengantisipasi latensi transmisi jaringan seluler peserta.
     *
     * @param Instansi $instansi
     * @param string|null $token
     * @param int|null $timestamp
     * @return array{valid: bool, reason: string|null, step?: int}
     */
    public function verifyToken(Instansi $instansi, ?string $token, ?int $timestamp = null): array
    {
        if (empty($token) || !is_string($token)) {
            return ['valid' => false, 'reason' => 'missing_token'];
        }

        $decodedRaw = base64_decode($token, true);
        if ($decodedRaw === false) {
            return ['valid' => false, 'reason' => 'malformed_token'];
        }

        $payload = json_decode($decodedRaw, true);
        if (!is_array($payload) || !isset($payload['i'], $payload['s'], $payload['sig'])) {
            return ['valid' => false, 'reason' => 'malformed_token'];
        }

        $tokenInstansiId = (int) $payload['i'];
        $tokenStep = (int) $payload['s'];
        $tokenSig = (string) $payload['sig'];

        // 1. Verifikasi kecocokan instansi
        if ($tokenInstansiId !== (int) $instansi->id) {
            return ['valid' => false, 'reason' => 'invalid_instansi'];
        }

        // 2. Verifikasi window waktu (T, T-1)
        $time = $timestamp ?? time();
        $currentStep = $this->getTimeStep($time);

        if ($tokenStep < ($currentStep - 1)) {
            return ['valid' => false, 'reason' => 'expired'];
        }

        if ($tokenStep > ($currentStep + 1)) {
            return ['valid' => false, 'reason' => 'future_token'];
        }

        // 3. Verifikasi signature kriptografis
        $secret = $this->getInstansiSecret($instansi);
        $expectedSig = $this->computeSignature((int) $instansi->id, $tokenStep, $secret);

        if (!hash_equals($expectedSig, $tokenSig)) {
            return ['valid' => false, 'reason' => 'invalid_signature'];
        }

        return [
            'valid' => true,
            'reason' => null,
            'step' => $tokenStep,
        ];
    }

    /**
     * Render SVG QR code secara langsung.
     */
    public function generateQrSvg(string $content, int $size = 280): string
    {
        return (string) QrCode::size($size)->margin(1)->generate($content);
    }

    /**
     * Hitung time-step 30 detik dari timestamp epoch.
     */
    public function getTimeStep(int $timestamp): int
    {
        return (int) floor($timestamp / self::ROTATION_INTERVAL);
    }

    /**
     * Hitung sisa detik dalam window interval 30s saat ini.
     */
    public function getRemainingSeconds(int $timestamp): int
    {
        $rem = self::ROTATION_INTERVAL - ($timestamp % self::ROTATION_INTERVAL);

        return $rem > 0 ? $rem : self::ROTATION_INTERVAL;
    }

    /**
     * Secret HMAC khusus per-instansi yang stabil.
     */
    private function getInstansiSecret(Instansi $instansi): string
    {
        $appKey = (string) config('app.key', 'default-magang-secret-key-2026');

        return hash('sha256', $appKey . '|instansi|' . $instansi->id . '|' . ($instansi->created_at?->timestamp ?? 'static'));
    }

    /**
     * Hitung HMAC-SHA256 signature.
     */
    private function computeSignature(int $instansiId, int $step, string $secret): string
    {
        return hash_hmac('sha256', "{$instansiId}|{$step}", $secret);
    }
}

