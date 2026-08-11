<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class QrScannerTest extends TestCase
{
    public function test_public_scanner_exposes_mobile_camera_flow(): void
    {
        $response = $this->get(route('qr.scanner'));

        $response->assertOk()
            ->assertSee('id="btn-start-scan"', false)
            ->assertSee('facingMode: "environment"', false)
            ->assertSee('window.Html5Qrcode', false)
            ->assertSee('HTTPS', false);
    }

    public function test_https_forwarded_host_is_used_for_public_links(): void
    {
        $this->withServerVariables([
            'HTTP_X_FORWARDED_HOST' => 'portal-ujicoba.ngrok-free.app',
            'HTTP_X_FORWARDED_PROTO' => 'https',
        ])->get(route('qr.scanner'));

        $this->assertSame(
            'https://portal-ujicoba.ngrok-free.app/verify-certificate/demo-token',
            URL::route('certificate.verify', 'demo-token')
        );
    }
}
