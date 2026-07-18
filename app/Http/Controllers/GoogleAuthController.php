<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    // 1. Redirect ke halaman Google
    public function redirectToGoogle(Request $request)
    {
        if ($request->has('role') && in_array($request->role, ['peserta', 'pembimbing'])) {
            session(['google_register_role' => $request->role]);
        } else {
            if (!session()->has('google_register_role')) {
                session(['google_register_role' => 'peserta']);
            }
        }
        session()->save();

        return Socialite::driver('google')->redirect();
    }

    // 2. Handle callback dari Google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Ambil role pilihan dari session saat mendaftar, default 'peserta'
            $targetRole = session('google_register_role', 'peserta');
            if (!in_array($targetRole, ['peserta', 'pembimbing'])) {
                $targetRole = 'peserta';
            }

            // Cek apakah user dengan email ini sudah ada
            $existingUser = User::where('email', $googleUser->getEmail())->first();

            if ($existingUser) {
                // Jika sudah ada, update google_id-nya (jika belum ada) dan otomatis verifikasi email jika belum
                $updates = [];
                if (empty($existingUser->google_id)) {
                    $updates['google_id'] = $googleUser->getId();
                }
                if (empty($existingUser->email_verified_at)) {
                    $updates['email_verified_at'] = now();
                }
                if (!empty($updates)) {
                    $existingUser->update($updates);
                }
                
                Auth::login($existingUser);
                
                session()->forget('google_register_role');
                
                // Redirect sesuai role
                return $this->redirectBasedOnRole($existingUser->role);
            } else {
                // Generate username unik dari email/nama
                $baseUsername = Str::slug(explode('@', $googleUser->getEmail())[0] ?: $googleUser->getName());
                if (empty($baseUsername)) {
                    $baseUsername = 'user' . rand(100, 999);
                }
                $username = $baseUsername;
                $counter = 1;
                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . rand(10, 999);
                    $counter++;
                    if ($counter > 10) break;
                }

                // Jika belum ada, buat user baru dengan role sesuai pilihan saat mendaftar (peserta / pembimbing)
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'username' => $username,
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => null, // Password kosong
                    'role' => $targetRole, // Otomatis terbuat dengan pilihan yang dipilih: peserta atau pembimbing
                    'email_verified_at' => now(), // Otomatis verified karena dari Google
                ]);

                Auth::login($newUser);

                session()->forget('google_register_role');

                return $this->redirectBasedOnRole($newUser->role);
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

        return redirect('/');
    }
}