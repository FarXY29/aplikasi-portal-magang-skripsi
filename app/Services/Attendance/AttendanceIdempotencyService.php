<?php

namespace App\Services\Attendance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Idempotency key (P0 §5.2).
 *
 * Request yang sama (key sama) tidak boleh menghasilkan attendance kedua;
 * duplicate request mengembalikan hasil request sebelumnya.
 *
 * Key dibaca dari header `Idempotency-Key` ATAU form field `idempotency_key`
 * (blade <form> tidak dapat mengirim custom header tanpa JS).
 *
 * Database uniqueness (application_id + date) tetap menjadi protection kedua.
 */
class AttendanceIdempotencyService
{
    /**
     * Ambil idempotency key dari request (header atau field).
     */
    public function resolveKey(Request $request): ?string
    {
        $key = $request->header('Idempotency-Key') ?: $request->input('idempotency_key');

        if (!is_string($key) || $key === '') {
            return null;
        }

        return substr(trim($key), 0, 64);
    }

    /**
     * True bila key ini sudah selesai diproses sebelumnya.
     */
    public function isProcessed(string $key): bool
    {
        return Cache::has($this->cacheKey($key));
    }

    /**
     * Ambil hasil tersimpan dari request pertama.
     *
     * @return array|null {type: 'success'|'error', message: string}
     */
    public function getResult(string $key): ?array
    {
        return Cache::get($this->cacheKey($key));
    }

    /**
     * Simpan hasil request agar duplicate mengembalikan hasil sama.
     */
    public function storeResult(string $key, string $type, string $message): void
    {
        Cache::put(
            $this->cacheKey($key),
            ['type' => $type, 'message' => $message, 'at' => now()->getTimestamp()],
            (int) config('attendance.idempotency_ttl', 300)
        );
    }

    /**
     * Tandai key sedang diproses (mengurangi window race antar request).
     */
    public function beginProcessing(string $key): void
    {
        Cache::put(
            $this->inflightKey($key),
            true,
            (int) config('attendance.lock_ttl', 10)
        );
    }

    public function endProcessing(string $key): void
    {
        Cache::forget($this->inflightKey($key));
    }

    private function cacheKey(string $key): string
    {
        return 'attendance:idem:' . hash('sha256', $key);
    }

    private function inflightKey(string $key): string
    {
        return 'attendance:idem-inflight:' . hash('sha256', $key);
    }
}
