<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SingleDeviceLogin
{
    /**
     * Handle an incoming request.
     * Ensure that the user's current session matches the active session ID in the database.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (empty($user->current_session_id)) {
                // Jika user sudah login sebelum fitur ini aktif (atau kolom kosong), jadikan sesi saat ini sebagai sesi aktif
                $user->update(['current_session_id' => $request->session()->getId()]);
            } elseif ($user->current_session_id !== $request->session()->getId()) {
                Auth::guard('web')->logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Akun Anda telah login di perangkat lain. Anda otomatis logout dari perangkat ini demi keamanan.');
            }
        }

        return $next($request);
    }
}
