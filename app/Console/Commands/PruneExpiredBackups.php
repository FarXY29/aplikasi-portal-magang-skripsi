<?php

namespace App\Console\Commands;

use App\Models\DatabaseBackup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PruneExpiredBackups extends Command
{
    protected $signature = 'backups:prune';

    protected $description = 'Delete expired private database backup files and records';

    public function handle(): int
    {
        $backups = DatabaseBackup::query()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->get();

        foreach ($backups as $backup) {
            if ($backup->stored_path) {
                Storage::disk('private')->delete($backup->stored_path);
            }
            $backup->delete();
        }

        $this->info("Removed {$backups->count()} expired backup(s).");

        return self::SUCCESS;
    }
}
