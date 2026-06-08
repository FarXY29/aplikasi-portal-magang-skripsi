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

        // Cek apakah role user ada di dalam daftar role yang diperbolehkan
        // Contoh pemakaian: role:admin_kota,admin_instansi (bisa multiple)
        if (in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}