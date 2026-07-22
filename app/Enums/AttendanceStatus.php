<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case Hadir = 'hadir';
    case Sakit = 'sakit';
    case Izin = 'izin';
    case Alpa = 'alpa';

    public function label(): string
    {
        return match($this) {
            self::Hadir => 'Hadir Tepat Waktu / Kerja',
            self::Sakit => 'Sakit dengan Surat Keterangan',
            self::Izin => 'Izin Resmi',
            self::Alpa => 'Alpa / Tanpa Keterangan',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Hadir => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200/80 dark:border-emerald-800/60',
            self::Sakit => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200/80 dark:border-amber-800/60',
            self::Izin => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200/80 dark:border-blue-800/60',
            self::Alpa => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200/80 dark:border-rose-800/60',
        };
    }
}
