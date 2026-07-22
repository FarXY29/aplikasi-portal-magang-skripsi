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
     * Mendapatkan kelas Tailwind CSS untuk badge visual (Light & Dark Mode).
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::Pending => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200/80 dark:border-amber-800/60',
            self::Menunggu => 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border-indigo-200/80 dark:border-indigo-800/60',
            self::Diterima => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200/80 dark:border-emerald-800/60',
            self::Ditolak => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200/80 dark:border-rose-800/60',
            self::Selesai => 'bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border-teal-200/80 dark:border-teal-800/60',
            self::Dikeluarkan => 'bg-red-100 dark:bg-red-950/60 text-red-800 dark:text-red-300 border-red-300 dark:border-red-800/60',
            self::Dibatalkan => 'bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-700',
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
