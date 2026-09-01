<?php

namespace App\Services\Attendance;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Cache\Lock;
use Illuminate\Support\Facades\Cache;

/**
 * Atomic lock operasi attendance (P0 §5.5).
 *
 * Melindungi terhadap double-click, multiple browser tabs, parallel
 * requests, repeated requests, dan race conditions.
 *
 * Lock key: attendance:user:{user_id} — durasi pendek dan terkontrol
 * (default 10 detik).
 *
 * Catatan implementasi: object Lock disimpan agar release() memakai
 * owner token yang benar (Lock Laravel punya owner random per acquire).
 */
class AttendanceLockService
{
    /** @var array<int, Lock> */
    private array $heldLocks = [];

    /**
     * Coba akuisisi lock user (non-blocking).
     */
    public function acquire(Authenticatable|int $user): bool
    {
        $userId = $user instanceof Authenticatable ? $user->getAuthIdentifier() : (int) $user;

        $lock = Cache::lock(
            $this->lockKey($userId),
            (int) config('attendance.lock_ttl', 10)
        );

        if (!$lock->get()) {
            return false;
        }

        $this->heldLocks[$userId] = $lock;

        return true;
    }

    /**
     * Lepaskan lock user bila ada (memakai owner token yang sama).
     */
    public function release(Authenticatable|int $user): void
    {
        $userId = $user instanceof Authenticatable ? $user->getAuthIdentifier() : (int) $user;

        $lock = $this->heldLocks[$userId] ?? null;

        if ($lock instanceof Lock) {
            try {
                $lock->release();
            } catch (\Throwable) {
                // lock sudah expired/dilepas — aman diabaikan.
            }
            unset($this->heldLocks[$userId]);
        }
    }

    private function lockKey(int|string $userId): string
    {
        return "attendance:user:{$userId}";
    }
}
