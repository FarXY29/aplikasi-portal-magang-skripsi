<?php

namespace App\Enums;

/**
 * Operational decisions for attendance attempts (§P0-1103).
 *
 * Distinct from risk levels (LOW, MEDIUM, HIGH, CRITICAL):
 * - Risk level evaluates anomalous telemetry heuristics (untrusted client inputs).
 * - Operational decision specifies the administrative/system workflow action taken.
 */
enum AttendanceOperationalDecision: string
{
    case Accepted = 'ACCEPTED';
    case AcceptedForReview = 'ACCEPTED_FOR_REVIEW';
    case Rejected = 'REJECTED';

    public function label(): string
    {
        return match ($this) {
            self::Accepted => 'Diterima Langsung',
            self::AcceptedForReview => 'Diterima untuk Ditinjau',
            self::Rejected => 'Ditolak',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Accepted => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
            self::AcceptedForReview => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
            self::Rejected => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
        };
    }
}
