<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and attach robust security headers to the response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(self), microphone=(), geolocation=(self)');

        // HSTS if on HTTPS or in production
        if ($request->isSecure() || config('app.env') === 'production') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        $isProduction = config('app.env') === 'production';

        $scriptSrc = [
            "'self'",
            "'unsafe-inline'",
            "'unsafe-eval'",
            'https://cdn.ckeditor.com',
        ];

        $styleSrc = [
            "'self'",
            "'unsafe-inline'",
            'https://fonts.googleapis.com',
        ];

        $fontSrc = [
            "'self'",
            'https://fonts.gstatic.com',
            'data:',
        ];

        $connectSrc = [
            "'self'",
            'https:',
        ];

        $imgSrc = [
            "'self'",
            'data:',
            'blob:',
            'https:',
        ];

        if (! $isProduction) {
            $devOrigins = 'http://localhost:5173 http://127.0.0.1:5173';
            $devWs = 'ws://localhost:5173 ws://127.0.0.1:5173';
            $scriptSrc[] = $devOrigins;
            $styleSrc[] = $devOrigins;
            $fontSrc[] = $devOrigins;
            $connectSrc[] = $devOrigins.' '.$devWs;
            $imgSrc[] = 'http:';
        }

        // Content Security Policy
        $csp = [
            "default-src 'self'",
            'script-src '.implode(' ', $scriptSrc),
            "worker-src 'self' blob:",
            'style-src '.implode(' ', $styleSrc),
            'font-src '.implode(' ', $fontSrc),
            'img-src '.implode(' ', $imgSrc),
            'connect-src '.implode(' ', $connectSrc),
            "frame-ancestors 'self'",
            "object-src 'none'",
            "base-uri 'self'",
        ];

        $response->headers->set('Content-Security-Policy', implode('; ', $csp));

        return $response;
    }
}
