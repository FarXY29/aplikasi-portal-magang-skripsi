<?php

namespace App\Services\Attendance\Rules;

use App\Services\Attendance\AttendanceFraudContext;
use App\Services\Attendance\FraudSignal;

/**
 * Network anomaly (§17) — IP hanya CONTEXTUAL signal (weak).
 *
 * JANGAN: VPN = fraud. JANGAN: IP matches company = proof of presence.
 * Deteksi: perubahan IP mendadak dibanding attempt sebelumnya.
 */
class NetworkAnomalyRule extends AttendanceFraudRule
{
    public function code(): string
    {
        return 'IP_CHANGE';
    }

    public function category(): string
    {
        return 'network';
    }

    public function evaluate(AttendanceFraudContext $context): ?FraudSignal
    {
        $currentIp = $context->ipAddress;
        $previousIp = $context->previousIpAddress;

        if ($currentIp === null || $currentIp === '' || $previousIp === null || $previousIp === '') {
            return null;
        }

        if ($currentIp === $previousIp) {
            return null;
        }

        $s = config('attendance.scores');

        return new FraudSignal(
            'IP_CHANGE',
            $this->category(),
            'low',
            (int) $s['ip_anomaly'],
            [
                'previous_ip' => $this->maskIp($previousIp),
                'current_ip' => $this->maskIp($currentIp),
                'note' => 'Perubahan jaringan — contextual saja, bukan bukti fraud (§17)',
            ],
        );
    }

    /**
     * Masking sebagian IP untuk privasi log (§36).
     */
    private function maskIp(string $ip): string
    {
        if (str_contains($ip, ':')) {
            // IPv6: tampilkan 2 grup pertama.
            $parts = explode(':', $ip);

            return implode(':', array_slice($parts, 0, 2)) . ':…';
        }

        // IPv4: tampilkan 2 oktet pertama.
        $parts = explode('.', $ip);

        return ($parts[0] ?? '*') . '.' . ($parts[1] ?? '*') . '.x.x';
    }
}
