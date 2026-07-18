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
            self::Hadir => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
            self::Sakit => 'bg-amber-50 text-amber-700 border-amber-200/80',
            self::Izin => 'bg-blue-50 text-blue-700 border-blue-200/80',
            self::Alpa => 'bg-rose-50 text-rose-700 border-rose-200/80',
        };
    }
}
