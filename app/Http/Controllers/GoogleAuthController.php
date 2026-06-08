<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // 1. Redirect ke halaman Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Handle callback dari Google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cek apakah user dengan email ini sudah ada
            $existingUser = User::where('email', $googleUser->getEmail())->first();

            if ($existingUser) {
                // Jika sudah ada, update google_id-nya (jika belum ada) dan login
                if (empty($existingUser->google_id)) {
                    $existingUser->update(['google_id' => $googleUser->getId()]);
                }
                
                Auth::login($existingUser);
                
                // Redirect sesuai role (Logic redirect dashboard Anda)
                return $this->redirectBasedOnRole($existingUser->role);
            } else {
                // Jika belum ada, buat user baru (Default Role: Peserta)
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => null, // Password kosong
                    'role' => 'peserta', // Default role
                    'email_verified_at' => now(), // Otomatis verified karena dari Google
                ]);

                Auth::login($newUser);

                return redirect()->route('peserta.dashboard');
            }

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }
    }

    // Helper untuk redirect (Sesuaikan dengan logic route dashboard Anda)
    protected function redirectBasedOnRole($role)
    {
        if ($role == 'admin_kota') return redirect()->route('admin.dashboard');
        if ($role == 'admin_instansi') return redirect()->route('dinas.dashboard');
        if ($role == 'pembimbing_lapangan') return redirect()->route('pembimbing_lapangan.dashboard');
        if ($role == 'peserta') return redirect()->route('peserta.dashboard');
        if ($role == 'pembimbing') return redirect()->route('pembimbing.dashboard');
        if ($role == 'kepala_dinas') return redirect()->route('kepala_dinas.dashboard');
        
        return redirect('/');
    }
}