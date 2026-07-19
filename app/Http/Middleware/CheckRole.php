<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika user belum login, lempar
        if (! $request->user()) {
            return redirect('login');
        }

        // Role Spatie menjadi acuan utama; kolom role lama hanya fallback selama
        // proses backfill data pengguna masih berlangsung.
        if ($request->user()->hasPortalRole($roles)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
