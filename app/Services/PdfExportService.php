<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfExportService
{
    /**
     * Generate and stream a PDF file.
     *
     * @param string $view
     * @param array $data
     * @param string $filename
     * @param string $paper
     * @param string $orientation
     * @param bool $withSignature
     * @return \Illuminate\Http\Response
     */
    public function stream(
        string $view,
        array $data,
        string $filename = 'document.pdf',
        string $paper = 'a4',
        string $orientation = 'portrait',
        bool $withSignature = false
    ): Response {
        $data = $this->injectKopData($data);
        if ($withSignature) {
            $data = $this->injectSignatureData($data);
        }

        $pdf = Pdf::loadView($view, $data)->setPaper($paper, $orientation);

        return $pdf->stream($filename);
    }

    /**
     * Inject Kop data from settings if not already present in data.
     */
    public function injectKopData(array $data, $settings = null): array
    {
        if ($settings === null) {
            $settings = \App\Models\Setting::all()->pluck('value', 'key');
        }

        if (! array_key_exists('kop_line1', $data)) {
            $data['kop_line1'] = ! empty($settings['kop_line1']) ? $settings['kop_line1'] : 'PEMERINTAH KOTA BANJARMASIN';
        }

        if (! array_key_exists('kop_line2', $data)) {
            $data['kop_line2'] = ! empty($settings['kop_line2']) ? $settings['kop_line2'] : 'BADAN KESATUAN BANGSA DAN POLITIK';
        }

        if (! array_key_exists('kop_line3', $data)) {
            $data['kop_line3'] = ! empty($settings['kop_line3']) ? $settings['kop_line3'] : 'Jalan RE Martadinata No. 1, Telp (0511) 3352932, Banjarmasin 70111';
        }

        if (! array_key_exists('kop_logo_path', $data)) {
            $logoPath = null;
            if (! empty($settings['kop_logo']) && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings['kop_logo'])) {
                $rawImg = $settings['kop_logo'];
                if (file_exists(storage_path('app/public/' . $rawImg))) {
                    $logoPath = storage_path('app/public/' . $rawImg);
                } elseif (file_exists(public_path('storage/' . $rawImg))) {
                    $logoPath = public_path('storage/' . $rawImg);
                }
            }

            if (! $logoPath || ! file_exists($logoPath)) {
                $defaultLogo = public_path('images/Banjarmasin_Logo.svg.png');
                $logoPath = file_exists($defaultLogo) ? $defaultLogo : null;
            }

            $data['kop_logo_path'] = $logoPath;
        }

        return $data;
    }

    /**
     * Inject signature data from settings if not already present in data.
     */
    public function injectSignatureData(array $data, $settings = null): array
    {
        if ($settings === null) {
            $settings = \App\Models\Setting::all()->pluck('value', 'key');
        }

        if (! array_key_exists('pejabat_nama', $data) && ! array_key_exists('pejabat_nip', $data) && ! array_key_exists('pejabat_jabatan', $data)) {
            $data['pejabat_nama'] = $settings['pejabat_name'] ?? 'H. Lukman Fadlun, SH';
            $data['pejabat_nip'] = $settings['pejabat_nip'] ?? '-';
            $data['pejabat_jabatan'] = $settings['pejabat_jabatan'] ?? 'Kepala Bakesbangpol Kota Banjarmasin';
        }

        if (! array_key_exists('ttd_image_path', $data)) {
            $ttdPath = null;
            if (! empty($settings['ttd_image']) && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings['ttd_image'])) {
                $rawImg = $settings['ttd_image'];
                if (file_exists(storage_path('app/public/' . $rawImg))) {
                    $ttdPath = storage_path('app/public/' . $rawImg);
                } elseif (file_exists(public_path('storage/' . $rawImg))) {
                    $ttdPath = public_path('storage/' . $rawImg);
                }
            }
            $data['ttd_image_path'] = $ttdPath;
        }

        return $data;
    }

    /**
     * Generate and force download a PDF file.
     *
     * @param string $view
     * @param array $data
     * @param string $filename
     * @param string $paper
     * @param string $orientation
     * @return \Illuminate\Http\Response
     */
    public function download(
        string $view,
        array $data,
        string $filename = 'document.pdf',
        string $paper = 'a4',
        string $orientation = 'portrait'
    ): Response {
        $data = $this->injectKopData($data);
        $pdf = Pdf::loadView($view, $data)->setPaper($paper, $orientation);

        return $pdf->download($filename);
    }

    /**
     * Get the raw PDF instance if custom manipulation is required.
     *
     * @param string $view
     * @param array $data
     * @param string $paper
     * @param string $orientation
     * @return \Barryvdh\DomPDF\PDF
     */
    public function make(
        string $view,
        array $data,
        string $paper = 'a4',
        string $orientation = 'portrait'
    ) {
        $data = $this->injectKopData($data);
        return Pdf::loadView($view, $data)->setPaper($paper, $orientation);
    }
}
