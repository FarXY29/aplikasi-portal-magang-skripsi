<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionLimitGuard
{
    /**
     * Handle an incoming request.
     * Memastikan sesi user saat ini terdaftar di tabel user_sessions.
     * Jika tidak terdaftar (sudah di-kick atau expired), user akan di-logout.
     * Juga mengupdate last_activity_at (throttled: max 1x per menit).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $currentSessionId = $request->session()->getId();

            $activeSession = $user->activeSessions()
                ->where('session_id', $currentSessionId)
                ->first();

            if ($activeSession) {
                // Sesi valid — update last_activity_at (throttled: max 1x per menit)
                if (!$activeSession->last_activity_at || $activeSession->last_activity_at->lt(now()->subMinute())) {
                    $activeSession->update(['last_activity_at' => now()]);
                }
            } else {
                // Sesi tidak terdaftar — kemungkinan sudah di-kick atau expired
                Auth::guard('web')->logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Sesi Anda telah berakhir atau dikeluarkan dari perangkat ini demi keamanan.');
            }
        }

        return $next($request);
    }
}
