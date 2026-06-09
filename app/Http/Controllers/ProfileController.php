<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $pembimbings = [];

        if ($user->role === 'peserta' && $user->asal_instansi) {
            $pembimbings = \App\Models\User::where('role', 'pembimbing')
                ->where('asal_instansi', $user->asal_instansi)
                ->get();
        }

        return view('profile.edit', [
            'user' => $user,
            'pembimbings' => $pembimbings,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
        $user = $request->user();
        $user->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // TAMBAHAN: Handle Upload Signature
        if ($request->hasFile('signature')) {
            // Hapus file lama jika ada
            if ($user->signature && \Illuminate\Support\Facades\Storage::exists('public/' . $user->signature)) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $user->signature);
            }
            
            // Simpan file baru
            $path = $request->file('signature')->store('signatures', 'public');
            $user->signature = $path;
        }
        

        // Handle Upload Photo
        if ($request->hasFile('photo')) {
            if ($user->photo && \Illuminate\Support\Facades\Storage::exists('public/' . $user->photo)) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $user->photo);
            }
            $path = $request->file('photo')->store('photos', 'public');
            $user->photo = $path;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
