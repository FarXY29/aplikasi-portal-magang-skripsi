<?php

namespace App\Enums;

enum ValidationStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Valid = 'valid';
    case Disetujui = 'disetujui';
    case Revisi = 'revisi';
    case DisetujuiPermanen = 'disetujui_permanen';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Menunggu Validasi',
            self::Approved, self::Valid, self::Disetujui, self::DisetujuiPermanen => 'Disetujui / Valid',
            self::Rejected, self::Revisi => 'Ditolak / Perlu Perbaikan',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Pending => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200/80 dark:border-amber-800/60',
            self::Approved, self::Valid, self::Disetujui, self::DisetujuiPermanen => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200/80 dark:border-emerald-800/60',
            self::Rejected, self::Revisi => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200/80 dark:border-rose-800/60',
        };
    }
}
