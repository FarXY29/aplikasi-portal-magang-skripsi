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
            ->with('requester')
            ->latest()
            ->take(10)
            ->get();

        $backups->each(function (DatabaseBackup $backup): void {
            $backup->download_url = $backup->isDownloadable()
                ? URL::temporarySignedRoute('admin.settings.backups.download', $backup->expires_at, ['backup' => $backup])
                : null;
        });

        return view('admin_kota.settings.index', compact('settings', 'backups'));
    }

    public function update(UpdateSystemSettingsRequest $request, AuditLogService $auditLogService)
    {
        $validated = $request->validated();
        foreach (['app_name', 'announcement', 'pejabat_name', 'pejabat_nip', 'pejabat_jabatan', 'kop_line1', 'kop_line2', 'kop_line3'] as $key) {
            if (array_key_exists($key, $validated)) {
                Setting::updateOrCreate(['key' => $key], ['value' => $validated[$key]]);
            }
        }

        if ($request->hasFile('ttd_image')) {
            $oldImage = Setting::where('key', 'ttd_image')->value('value');
            if ($oldImage) {
                if (Storage::disk('private')->exists($oldImage)) {
                    Storage::disk('private')->delete($oldImage);
                } elseif (Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $path = $request->file('ttd_image')->store('signatures', 'private');
            Setting::updateOrCreate(['key' => 'ttd_image'], ['value' => $path]);
        }

        if ($request->hasFile('kop_logo')) {
            $oldLogo = Setting::where('key', 'kop_logo')->value('value');
            if ($oldLogo) {
                if (Storage::disk('private')->exists($oldLogo)) {
                    Storage::disk('private')->delete($oldLogo);
                } elseif (Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
            }

            $path = $request->file('kop_logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'kop_logo'], ['value' => $path]);
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

        try {
            CreateDatabaseBackup::dispatchSync($backup);
            $backup->refresh();

            if ($backup->status === 'completed') {
                $auditLogService->record('database_backup.completed', $backup, ['filename' => $backup->filename]);

                return back()->with('success', 'Backup database berhasil dibuat dan siap diunduh.');
            }

            return back()->with('error', 'Backup database gagal: '.($backup->error_message ?? 'Terjadi kesalahan sistem.'));
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memproses backup database: '.$e->getMessage());
        }
    }

    public function downloadBackup(Request $request, DatabaseBackup $backup)
    {
        abort_unless($request->user()?->hasPortalRole('admin_kota') || $backup->requested_by === $request->user()->id, 403);
        abort_unless($backup->isDownloadable(), 404);

        return Storage::disk('private')->download($backup->stored_path, $backup->filename, [
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }

    public function destroyBackup(Request $request, DatabaseBackup $backup, AuditLogService $auditLogService)
    {
        abort_unless($request->user()?->hasPortalRole('admin_kota') || $backup->requested_by === $request->user()->id, 403);

        if ($backup->stored_path && Storage::disk('private')->exists($backup->stored_path)) {
            Storage::disk('private')->delete($backup->stored_path);
        }

        $filename = $backup->filename;
        $backup->delete();

        $auditLogService->record('database_backup.deleted', null, ['filename' => $filename]);

        return back()->with('success', "Berkas backup '{$filename}' berhasil dihapus.");
    }
}
