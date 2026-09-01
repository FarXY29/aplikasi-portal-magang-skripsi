<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Major;
use App\Models\MajorCategory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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
        $categories = [];

        if ($user->role === 'peserta') {
            if ($user->asal_instansi) {
                $pembimbings = User::where('role', 'pembimbing')
                    ->where('asal_instansi', $user->asal_instansi)
                    ->get();
            }

            $categories = MajorCategory::with(['majors' => function ($q) {
                $q->where('is_active', true)->orderBy('degree_level')->orderBy('name');
            }])->orderBy('name')->get();
        }

        return view('profile.edit', [
            'user' => $user,
            'pembimbings' => $pembimbings,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Amankan photo & signature agar tidak ter-overwrite null saat fill()
        unset($validated['photo'], $validated['signature']);

        if ($user->hasPortalRole('peserta') || $user->role === 'peserta') {
            if (!empty($validated['major_id'])) {
                $majorObj = Major::find($validated['major_id']);
                if ($majorObj) {
                    $validated['major'] = $majorObj->name;
                }
            }
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            if ($user->isEmailVerificationExempt()) {
                $user->email_verified_at = now();
            } else {
                $user->email_verified_at = null;
            }
        }

        // Handle Upload Signature
        if ($request->hasFile('signature')) {
            if ($user->signature && Storage::disk('public')->exists($user->signature)) {
                Storage::disk('public')->delete($user->signature);
            }
            
            $path = $request->file('signature')->store('signatures', 'public');
            $user->signature = $path;
        }

        // Handle Upload Photo
        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $path = $request->file('photo')->store('photos', 'public');
            $user->photo = $path;
        }

        $user->save();

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

        return Redirect::to('/')->with('success', 'Akun Anda berhasil dihapus secara permanen.');
    }
}
