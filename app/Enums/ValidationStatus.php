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
            self::Pending => 'bg-amber-50 text-amber-700 border-amber-200/80',
            self::Approved, self::Valid, self::Disetujui, self::DisetujuiPermanen => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
            self::Rejected, self::Revisi => 'bg-rose-50 text-rose-700 border-rose-200/80',
        };
    }
}
