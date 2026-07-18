<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if ($request->user() && ! $request->user()->hasVerifiedEmail()) {
            $user = $request->user();
            if (in_array($user->role, ['peserta', 'pembimbing'])) {
                $cacheKey = 'resend_verification_' . $user->id;
                if (! Cache::has($cacheKey)) {
                    $user->sendEmailVerificationNotification();
                    Cache::put($cacheKey, true, now()->addMinutes(2));
                    session()->flash('status', 'verification-link-sent');
                }
            }
            return view('auth.verify-email');
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
