<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\RequestDatabaseBackupRequest;
use App\Http\Requests\Admin\UpdateSystemSettingsRequest;
use App\Jobs\CreateDatabaseBackup;
use App\Models\DatabaseBackup;
use App\Models\Setting;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $backups = DatabaseBackup::query()
            ->where('requested_by', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $backups->each(function (DatabaseBackup $backup): void {
            $backup->download_url = $backup->status === 'completed' && $backup->expires_at?->isFuture()
                ? URL::temporarySignedRoute('admin.settings.backups.download', $backup->expires_at, ['backup' => $backup])
                : null;
        });

        return view('admin_kota.settings.index', compact('settings', 'backups'));
    }

    public function update(UpdateSystemSettingsRequest $request, AuditLogService $auditLogService)
    {
        $validated = $request->validated();
        foreach (['app_name', 'announcement', 'pejabat_name', 'pejabat_nip', 'pejabat_jabatan'] as $key) {
            Setting::updateOrCreate(['key' => $key], ['value' => $validated[$key] ?? null]);
        }

        if ($request->hasFile('ttd_image')) {
            $oldImage = Setting::where('key', 'ttd_image')->value('value');
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            $path = $request->file('ttd_image')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'ttd_image'], ['value' => $path]);
        }

        $auditLogService->record('system_settings.updated', null, [
            'updated_keys' => array_keys($validated),
        ]);

        return back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }

    public function requestBackup(RequestDatabaseBackupRequest $request, AuditLogService $auditLogService)
    {
        $database = Str::slug((string) config('database.connections.'.config('database.default').'.database'), '_');
        $backup = DatabaseBackup::create([
            'requested_by' => $request->user()->id,
            'filename' => "backup_{$database}_".now()->format('Ymd_His').'.sql',
            'status' => 'queued',
        ]);

        CreateDatabaseBackup::dispatch($backup);
        $auditLogService->record('database_backup.requested', $backup, ['filename' => $backup->filename]);

        return back()->with('success', 'Backup telah dimasukkan ke antrean. Halaman ini menampilkan tautan unduh setelah proses selesai.');
    }

    public function downloadBackup(Request $request, DatabaseBackup $backup)
    {
        abort_unless($backup->requested_by === $request->user()->id, 403);
        abort_unless($backup->status === 'completed' && $backup->expires_at?->isFuture(), 404);
        abort_unless($backup->stored_path && Storage::disk('private')->exists($backup->stored_path), 404);

        return Storage::disk('private')->download($backup->stored_path, $backup->filename, [
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }
}
