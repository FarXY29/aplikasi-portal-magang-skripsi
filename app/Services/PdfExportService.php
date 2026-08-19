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
        if ($withSignature) {
            $data = $this->injectSignatureData($data);
        }

        $pdf = Pdf::loadView($view, $data)->setPaper($paper, $orientation);

        return $pdf->stream($filename);
    }

    /**
     * Inject signature data from settings if not already present in data.
     */
    private function injectSignatureData(array $data): array
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');

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
        return Pdf::loadView($view, $data)->setPaper($paper, $orientation);
    }
}
