<?php

namespace App\Enums;

enum AttendanceFraudStatus: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case VeryHigh = 'very_high';
    case Critical = 'critical';

    public static function fromScore(int $score): self
    {
        return match (true) {
            $score >= 100 => self::Critical,
            $score >= 75 => self::VeryHigh,
            $score >= 50 => self::High,
            $score >= 25 => self::Medium,
            default => self::Low,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Low => 'Normal',
            self::Medium => 'Mencurigakan (Ringan)',
            self::High => 'Mencurigakan (Tinggi)',
            self::VeryHigh => 'Sangat Mencurigakan',
            self::Critical => 'Kritis',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Low => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
            self::Medium => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
            self::High => 'bg-orange-50 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300 border-orange-200 dark:border-orange-800/60',
            self::VeryHigh => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
            self::Critical => 'bg-red-100 dark:bg-red-950/60 text-red-800 dark:text-red-300 border-red-300 dark:border-red-800/60',
        };
    }
}
