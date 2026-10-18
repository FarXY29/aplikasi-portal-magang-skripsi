<?php

namespace App\Actions;

use App\Models\Application;
use App\Services\CertificateService;

class GenerateCertificateNumberAction
{
    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Menghasilkan nomor sertifikat magang unik dan token verifikasi untuk peserta yang selesai.
     */
    public function execute(Application $application): Application
    {
        if (empty($application->nomor_sertifikat)) {
            $this->certificateService->generateCertificate($application);
        }

        return $application;
    }
}
