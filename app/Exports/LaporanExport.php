<?php

namespace App\Exports;

use App\Models\Instansi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
    * Mengambil data dari database
    */
    public function collection()
    {
        // Kita ambil semua INSTANSI beserta relasinya
        return Instansi::with(['positions.applications'])->get();
    }

    /**
    * Mengatur data apa saja yang masuk ke kolom Excel
    */
    public function map($dinas): array
    {
        // Hitung manual seperti di controller
        $totalPelamar = $dinas->positions->flatMap->applications->count();
        $totalDiterima = $dinas->positions->flatMap->applications
                        ->whereIn('status', ['diterima', 'selesai'])->count();
        $lowonganAktif = $dinas->positions->where('status', 'buka')->count();

        return [
            $dinas->nama_dinas,
            $lowonganAktif . ' Posisi',
            $totalPelamar . ' Orang',
            $totalDiterima . ' Orang'
        ];
    }

    /**
    * Judul Header Kolom (Baris 1)
    */
    public function headings(): array
    {
        return [
            'Nama Dinas (INSTANSI)',
            'Lowongan Buka',
            'Jumlah Pelamar',
            'Peserta Diterima/Lulus',
        ];
    }

    /**
    * Styling sederhana (Bold Header)
    */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}