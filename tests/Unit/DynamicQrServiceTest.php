<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Instansi;
use App\Services\Attendance\DynamicQrService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DynamicQrServiceTest extends TestCase
{
    use RefreshDatabase;

    private DynamicQrService $service;
    private Instansi $instansi;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DynamicQrService::class);
        $this->instansi = Instansi::create([
            'nama_dinas' => 'Dinas Kominfo',
            'kode_unit_kerja' => 'DISKOMINFO-01',
            'alamat' => 'Jl. Pemuda No. 1',
            'qr_absensi_enabled' => true,
        ]);
    }

    public function test_generate_token_produces_valid_payload(): void
    {
        $fixedTime = 1725178815; // 15 detik dalam 30s block
        $tokenData = $this->service->generateTokenData($this->instansi, $fixedTime);

        $this->assertNotEmpty($tokenData['token']);
        $this->assertEquals(30, $tokenData['interval']);
        $this->assertGreaterThan(0, $tokenData['remaining_seconds']);
        $this->assertLessThanOrEqual(30, $tokenData['remaining_seconds']);

        // Verifikasi token pada detik yang sama
        $verification = $this->service->verifyToken($this->instansi, $tokenData['token'], $fixedTime);
        $this->assertTrue($verification['valid']);
        $this->assertNull($verification['reason']);
    }

    public function test_token_is_valid_within_grace_window_one_step_back(): void
    {
        $step0Time = 1725178810; // step = floor(1725178810 / 30) = 57505960
        $tokenData = $this->service->generateTokenData($this->instansi, $step0Time);

        // Uji verifikasi 35 detik kemudian (step berikutnya: 1725178845 -> step 57505961)
        $step1Time = $step0Time + 35;
        $verification = $this->service->verifyToken($this->instansi, $tokenData['token'], $step1Time);

        $this->assertTrue($verification['valid'], 'Token harus valid pada interval T-1 (toleransi delay 30s)');
    }

    public function test_token_fails_after_two_steps_expired(): void
    {
        $step0Time = 1725178810;
        $tokenData = $this->service->generateTokenData($this->instansi, $step0Time);

        // Uji verifikasi 70 detik kemudian (step T+2)
        $step2Time = $step0Time + 70;
        $verification = $this->service->verifyToken($this->instansi, $tokenData['token'], $step2Time);

        $this->assertFalse($verification['valid']);
        $this->assertEquals('expired', $verification['reason']);
    }

    public function test_token_fails_with_tampered_signature(): void
    {
        $fixedTime = 1725178815;
        $tokenData = $this->service->generateTokenData($this->instansi, $fixedTime);

        // Rusak token (modifikasi base64)
        $decoded = json_decode(base64_decode($tokenData['token']), true);
        $decoded['sig'] = 'fake_tampered_signature_123';
        $tamperedToken = base64_encode(json_encode($decoded));

        $verification = $this->service->verifyToken($this->instansi, $tamperedToken, $fixedTime);
        $this->assertFalse($verification['valid']);
        $this->assertEquals('invalid_signature', $verification['reason']);
    }

    public function test_token_fails_when_presented_to_different_instansi(): void
    {
        $otherInstansi = Instansi::create([
            'nama_dinas' => 'Dinas Pendidikan',
            'kode_unit_kerja' => 'DISDIK-02',
            'alamat' => 'Jl. Veteran No. 2',
        ]);

        $fixedTime = 1725178815;
        $tokenData = $this->service->generateTokenData($this->instansi, $fixedTime);

        $verification = $this->service->verifyToken($otherInstansi, $tokenData['token'], $fixedTime);
        $this->assertFalse($verification['valid']);
        $this->assertEquals('invalid_instansi', $verification['reason']);
    }

    public function test_token_fails_with_malformed_string(): void
    {
        $verification = $this->service->verifyToken($this->instansi, 'not-a-valid-token-string');
        $this->assertFalse($verification['valid']);
        $this->assertEquals('malformed_token', $verification['reason']);
    }

    public function test_generate_qr_svg_produces_svg_markup(): void
    {
        $tokenData = $this->service->generateTokenData($this->instansi);
        $svg = $this->service->generateQrSvg($tokenData['token'], 200);

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('</svg>', $svg);
    }
}
