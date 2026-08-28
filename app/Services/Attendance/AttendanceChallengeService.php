<?php

namespace App\Services\Attendance;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Cache;

/**
 * One-time nonce / attendance challenge (P0 §5.3).
 *
 * Flow: browser minta challenge → server generate nonce cryptographically
 * secure → browser ambil geolocation → POST attendance + nonce → server
 * validasi & konsumsi nonce (single-use, short-lived, user-bound).
 *
 * CATATAN PENTING: nonce BUKAN bukti GPS asli. Fungsinya hanya mengurangi
 * replay dan old-request abuse.
 */
class AttendanceChallengeService
{
    /**
     * Terbitkan nonce baru untuk user terautentikasi.
     *
     * @return array{nonce: string, expires_at: int, ttl: int}
     */
    public function issue(Authenticatable $user): array
    {
        // Cryptographically secure random (CSPRNG) — 32 byte hex.
        $nonce = bin2hex(random_bytes(32));

        $ttl = (int) config('attendance.nonce_ttl', 60);

        // Nonce terikat pada user — nonce user lain tidak dapat dipakai.
        Cache::put($this->cacheKey($user->getAuthIdentifier(), $nonce), true, $ttl);

        return [
            'nonce' => $nonce,
            'expires_at' => now()->addSeconds($ttl)->getTimestamp(),
            'ttl' => $ttl,
        ];
    }

    /**
     * Validasi DAN konsumsi nonce (atomic single-use).
     *
     * Cache::pull = hapus atomik: dua request paralel memakai nonce sama,
     * hanya satu yang berhasil.
     */
    public function consume(Authenticatable|int $user, ?string $nonce): bool
    {
        if (!is_string($nonce) || $nonce === '' || strlen($nonce) > 64) {
            return false;
        }

        $userId = $user instanceof Authenticatable ? $user->getAuthIdentifier() : (int) $user;

        return (bool) Cache::pull($this->cacheKey($userId, $nonce), false);
    }

    private function cacheKey(int|string $userId, string $nonce): string
    {
        return "attendance:nonce:{$userId}:" . hash('sha256', $nonce);
    }
}
