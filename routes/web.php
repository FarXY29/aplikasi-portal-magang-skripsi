<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    if ($role === 'admin_kota') {
        return redirect()->route('admin.dashboard');
    }
    if ($role === 'admin_instansi') {
        return redirect()->route('dinas.dashboard');
    }
    if ($role === 'pembimbing_lapangan') {
        return redirect()->route('pembimbing_lapangan.dashboard');
    }
    if ($role === 'peserta') {
        return redirect()->route('peserta.dashboard');
    }
    if ($role === 'pembimbing') {
        return redirect()->route('pembimbing.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Private Storage Guard Route
use App\Http\Controllers\StorageAccessController;
Route::get('/storage-access/{type}/{filename}', [StorageAccessController::class, 'serveFile'])
    ->middleware('auth')
    ->name('storage.access');

require __DIR__.'/public.php';
require __DIR__.'/peserta.php';
require __DIR__.'/admin_instansi.php';
require __DIR__.'/pembimbing.php';
require __DIR__.'/admin_kota.php';
require __DIR__.'/profile.php';
require __DIR__.'/auth.php';
