<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'in:peserta,pembimbing'],
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'major' => ['required_if:role,peserta', 'nullable', 'string', 'max:255'], // Validasi Jurusan
            'asal_instansi' => ['required_if:role,pembimbing', 'nullable', 'string', 'max:255'], // Validasi Asal Instansi
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'major' => $request->role === 'peserta' ? $request->major : null,
            'asal_instansi' => $request->role === 'pembimbing' ? $request->asal_instansi : null,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $user->syncPrimaryRole();

        event(new Registered($user));

        // Jika role peserta atau pembimbing, jangan langsung diloginkan.
        // Wajib verifikasi email terlebih dahulu sebelum bisa login.
        if (in_array($user->role, ['peserta', 'pembimbing'])) {
            return redirect()->route('login')->with('status', 'Registrasi berhasil! Link verifikasi telah dikirim ke alamat email Anda (' . $user->email . '). Silakan verifikasi email Anda terlebih dahulu sebelum login.');
        }

        Auth::login($user);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
