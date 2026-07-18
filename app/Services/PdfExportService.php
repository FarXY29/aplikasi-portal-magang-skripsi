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
     * @return \Illuminate\Http\Response
     */
    public function stream(
        string $view,
        array $data,
        string $filename = 'document.pdf',
        string $paper = 'a4',
        string $orientation = 'portrait'
    ): Response {
        $pdf = Pdf::loadView($view, $data)->setPaper($paper, $orientation);

        return $pdf->stream($filename);
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
