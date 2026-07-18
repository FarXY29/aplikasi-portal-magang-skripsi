<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified (compatible for both guest and authenticated user).
     */
    public function __invoke(Request $request, $id, $hash): RedirectResponse
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Tautan verifikasi tidak valid atau telah kadaluwarsa.');
        }

        if ($user->hasVerifiedEmail()) {
            if (Auth::check() && Auth::id() == $user->id) {
                return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
            }
            return redirect()->route('login')->with('status', 'Email Anda sudah diverifikasi sebelumnya. Silakan login.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        if (Auth::check() && Auth::id() == $user->id) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        return redirect()->route('login')->with('status', 'Email Anda berhasil diverifikasi! Silakan login ke akun Anda.');
    }
}
