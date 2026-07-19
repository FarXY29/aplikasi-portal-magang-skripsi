<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\DailyLog;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class StorageAccessController extends Controller
{
    /** Serve a document only after resolving its owning record and policy. */
    public function serveFile(string $type, string $filename): Response
    {
        abort_unless($filename === basename($filename), 404);

        [$path, $model] = match ($type) {
            'surat' => $this->applicationDocument($filename),
            'logbook' => $this->logbookDocument($filename),
            'attendance' => $this->attendanceDocument($filename),
            default => abort(404),
        };

        $this->authorize('view', $model);

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
            ->where('surat_pengantar_path', 'like', '%/'.$filename)
            ->firstOrFail();

        return [$application->surat_pengantar_path, $application];
    }

    /** @return array{string, DailyLog} */
    private function logbookDocument(string $filename): array
    {
        $log = DailyLog::query()
            ->with('application.user')
            ->where('bukti_foto_path', 'like', '%/'.$filename)
            ->firstOrFail();

        return [$log->bukti_foto_path, $log];
    }

    /** @return array{string, Attendance} */
    private function attendanceDocument(string $filename): array
    {
        $attendance = Attendance::query()
            ->with('application.user')
            ->where('proof_file', 'like', '%/'.$filename)
            ->firstOrFail();

        return [$attendance->proof_file, $attendance];
    }
}
