<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\DailyLog;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class StorageAccessController extends Controller
{
    /** Escape LIKE wildcards so a filename cannot match multiple rows. */
    private function like(string $value): string
    {
        return addcslashes($value, '%_\\');
    }

    /** Serve a document only after resolving its owning record and policy. */
    public function serveFile(string $type, string $filename): Response
    {
        abort_unless($filename === basename($filename), 404);

        [$path, $model] = match ($type) {
            'surat' => $this->applicationDocument($filename),
            'logbook' => $this->logbookDocument($filename),
            'attendance' => $this->attendanceDocument($filename),
            'signature' => $this->signatureDocument($filename),
            default => abort(404),
        };

        if ($type !== 'signature') {
            $this->authorize('view', $model);
        }

        $disk = Storage::disk('private')->exists($path) ? 'private' : 'public';
        abort_unless(Storage::disk($disk)->exists($path), 404, 'Berkas tidak ditemukan.');

        return Storage::disk($disk)->response($path, null, [
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
            'Cache-Control' => 'private, no-store, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }

    /** @return array{string, Application} */
    private function applicationDocument(string $filename): array
    {
        $application = Application::query()
            ->with(['user', 'position.instansi', 'pembimbing_lapangan'])
            ->where('surat_pengantar_path', 'like', '%/'.$this->like($filename))
            ->firstOrFail();

        return [$application->surat_pengantar_path, $application];
    }

    /** @return array{string, DailyLog} */
    private function logbookDocument(string $filename): array
    {
        $log = DailyLog::query()
            ->with('application.user')
            ->where('bukti_foto_path', 'like', '%/'.$this->like($filename))
            ->firstOrFail();

        return [$log->bukti_foto_path, $log];
    }

    /** @return array{string, Attendance} */
    private function attendanceDocument(string $filename): array
    {
        $attendance = Attendance::query()
            ->with('application.user')
            ->where('proof_file', 'like', '%/'.$this->like($filename))
            ->firstOrFail();

        return [$attendance->proof_file, $attendance];
    }

    /** @return array{string, object} */
    private function signatureDocument(string $filename): array
    {
        $user = auth()->user();
        abort_unless($user, 401);

        // 1. Check Instansi ttd_kepala
        $instansi = \App\Models\Instansi::where('ttd_kepala', 'like', '%/'.$this->like($filename))->first();
        if ($instansi) {
            abort_unless(
                $user->hasPortalRole('admin_kota') ||
                ($user->hasPortalRole('admin_instansi') && (int) $user->instansi_id === (int) $instansi->id),
                403
            );
            return [$instansi->ttd_kepala, $instansi];
        }

        // 2. Check Setting ttd_image
        $setting = \App\Models\Setting::where('key', 'ttd_image')
            ->where('value', 'like', '%/'.$this->like($filename))
            ->first();
        if ($setting) {
            abort_unless($user->hasPortalRole('admin_kota'), 403);
            return [$setting->value, $setting];
        }

        // 3. Check User signature
        $targetUser = \App\Models\User::where('signature', 'like', '%/'.$this->like($filename))->first();
        if ($targetUser) {
            abort_unless(
                $user->hasPortalRole('admin_kota') ||
                $user->id === $targetUser->id ||
                ($user->hasPortalRole('admin_instansi') && (int) $user->instansi_id === (int) $targetUser->instansi_id),
                403
            );
            return [$targetUser->signature, $targetUser];
        }

        abort(404, 'Berkas tanda tangan tidak ditemukan.');
    }
}
