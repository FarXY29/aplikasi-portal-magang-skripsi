<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasPortalRole('admin_kota')) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->hasPortalRole('admin_instansi')) {
        return redirect()->route('dinas.dashboard');
    }
    if ($user->hasPortalRole('pembimbing_lapangan')) {
        return redirect()->route('pembimbing_lapangan.dashboard');
    }
    if ($user->hasPortalRole('peserta')) {
        return redirect()->route('peserta.dashboard');
    }
    if ($user->hasPortalRole('pembimbing')) {
        return redirect()->route('pembimbing.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Private Storage Guard Route
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StorageAccessController;

Route::get('/storage-access/{type}/{filename}', [StorageAccessController::class, 'serveFile'])
    ->middleware('auth')
    ->name('storage.access');

// Realtime In-App Notification Routes
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/unread', [NotificationController::class, 'getUnread'])->name('unread');
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
    Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read_all');
});

require __DIR__.'/public.php';
require __DIR__.'/peserta.php';
require __DIR__.'/admin_instansi.php';
require __DIR__.'/pembimbing.php';
require __DIR__.'/admin_kota.php';
require __DIR__.'/profile.php';
require __DIR__.'/auth.php';
