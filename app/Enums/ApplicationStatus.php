<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Pending = 'pending';
    case Menunggu = 'menunggu';
    case Diterima = 'diterima';
    case Ditolak = 'ditolak';
    case Selesai = 'selesai';
    case Dikeluarkan = 'dikeluarkan';
    case Dibatalkan = 'dibatalkan';

    /**
     * Mendapatkan label ramah pengguna untuk status lamaran magang.
     */
    public function label(): string
    {
        return match($this) {
            self::Pending => 'Menunggu Review',
            self::Menunggu => 'Daftar Tunggu (Antrean)',
            self::Diterima => 'Aktif Magang / Diterima',
            self::Ditolak => 'Ditolak',
            self::Selesai => 'Selesai Magang (Lulus)',
            self::Dikeluarkan => 'Dikeluarkan',
            self::Dibatalkan => 'Dibatalkan Peserta',
        };
    }

    /**
     * Mendapatkan kelas Tailwind CSS untuk badge visual.
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::Pending => 'bg-amber-50 text-amber-700 border-amber-200/80',
            self::Menunggu => 'bg-indigo-50 text-indigo-700 border-indigo-200/80',
            self::Diterima => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
            self::Ditolak => 'bg-rose-50 text-rose-700 border-rose-200/80',
            self::Selesai => 'bg-teal-50 text-teal-700 border-teal-200/80',
            self::Dikeluarkan => 'bg-red-100 text-red-800 border-red-300',
            self::Dibatalkan => 'bg-gray-100 text-gray-700 border-gray-300',
        };
    }

    /**
     * Mendapatkan ikon FontAwesome untuk masing-masing status.
     */
    public function icon(): string
    {
        return match($this) {
            self::Pending => 'fas fa-clock',
            self::Menunggu => 'fas fa-list-ol',
            self::Diterima => 'fas fa-check-circle',
            self::Ditolak => 'fas fa-times-circle',
            self::Selesai => 'fas fa-award',
            self::Dikeluarkan => 'fas fa-ban',
            self::Dibatalkan => 'fas fa-user-slash',
        };
    }
}
