<?php

namespace Tests\Feature\Security;

use Tests\TestCase;

class SecurityHeadersCspTest extends TestCase
{
    public function test_security_headers_are_attached_to_responses(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(self), microphone=(), geolocation=(self)');
        $this->assertTrue($response->headers->has('Content-Security-Policy'));
    }

    public function test_csp_header_enforces_strict_sources_and_removes_unnecessary_cdns(): void
    {
        $response = $this->get('/');
        $csp = (string) $response->headers->get('Content-Security-Policy');

        // Allowed sources
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("worker-src 'self' blob:", $csp);
        $this->assertStringContainsString('https://cdn.ckeditor.com', $csp);
        $this->assertStringContainsString('https://fonts.googleapis.com', $csp);
        $this->assertStringContainsString('https://fonts.gstatic.com', $csp);

        // Disallowed / de-CDN removed sources
        $this->assertStringNotContainsString('cdnjs.cloudflare.com', $csp);
        $this->assertStringNotContainsString('cdn.jsdelivr.net', $csp);
        $this->assertStringNotContainsString('unpkg.com', $csp);
        $this->assertStringNotContainsString('cdn.tailwindcss.com', $csp);
    }

    public function test_csp_in_production_mode_excludes_vite_dev_origins(): void
    {
        config(['app.env' => 'production']);

        $response = $this->get('/');
        $csp = (string) $response->headers->get('Content-Security-Policy');

        $this->assertStringNotContainsString('5173', $csp);
        $this->assertStringNotContainsString('ws://', $csp);
        $response->assertHeader('Strict-Transport-Security');
    }
}
