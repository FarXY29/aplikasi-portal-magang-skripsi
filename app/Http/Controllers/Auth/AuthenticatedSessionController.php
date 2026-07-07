<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LoginAttempt;
use App\Models\UserSession;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = $request->user();

        // Bersihkan sesi expired terlebih dahulu
        $user->cleanExpiredSessions();

        // Cek apakah masih ada slot tersedia (maks 3 perangkat)
        if ($user->activeSessions()->count() >= 3) {
            // Slot penuh — blokir login
            Auth::guard('web')->logout();

            // Catat percobaan login yang diblokir
            LoginAttempt::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'reason' => 'max_devices_reached',
                'attempted_at' => now(),
            ]);

            return redirect()->route('login')
                ->with('device_limit_reached', true);
        }

        // Slot tersedia — regenerate session dan buat record sesi baru
        $request->session()->regenerate();

        UserSession::create([
            'user_id' => $user->id,
            'session_id' => $request->session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_name' => UserSession::parseDeviceName($request->userAgent()),
            'last_activity_at' => now(),
        ]);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Hapus record sesi dari tabel user_sessions
        if ($user) {
            $user->activeSessions()
                ->where('session_id', $request->session()->getId())
                ->delete();
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
