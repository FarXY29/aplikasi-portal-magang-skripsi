<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\DailyLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigratePublicDocumentsToPrivate extends Command
{
    protected $signature = 'documents:migrate-private {--dry-run : Only report files that would be moved}';

    protected $description = 'Move legacy applicant letters, logbook evidence, and attendance proofs from public to private storage';

    public function handle(): int
    {
        $moved = 0;
        $missing = 0;
        $dryRun = (bool) $this->option('dry-run');

        $migrate = function (?string $path) use (&$moved, &$missing, $dryRun): void {
            if (! $path || $path === '-' || Storage::disk('private')->exists($path)) {
                return;
            }

            if (! Storage::disk('public')->exists($path)) {
                $missing++;
                return;
            }

            if (! $dryRun) {
                $stream = Storage::disk('public')->readStream($path);
                Storage::disk('private')->writeStream($path, $stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }
                Storage::disk('public')->delete($path);
            }

            $moved++;
        };

        Application::query()->select(['id', 'surat_pengantar_path'])->orderBy('id')
            ->chunkById(200, fn ($records) => $records->each(fn (Application $record) => $migrate($record->surat_pengantar_path)));
        DailyLog::query()->select(['id', 'bukti_foto_path'])->orderBy('id')
            ->chunkById(200, fn ($records) => $records->each(fn (DailyLog $record) => $migrate($record->bukti_foto_path)));
        Attendance::query()->select(['id', 'proof_file'])->orderBy('id')
            ->chunkById(200, fn ($records) => $records->each(fn (Attendance $record) => $migrate($record->proof_file)));

        $verb = $dryRun ? 'Would move' : 'Moved';
        $this->info("{$verb} {$moved} file(s); {$missing} referenced file(s) were not found.");

        return self::SUCCESS;
    }
}
