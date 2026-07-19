<?php

namespace App\Jobs;

use App\Models\AuditLog;
use App\Models\DatabaseBackup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class CreateDatabaseBackup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(public DatabaseBackup $backup)
    {
        $this->onQueue('maintenance');
    }

    public function handle(): void
    {
        $this->backup->update(['status' => 'processing', 'error_message' => null]);

        try {
            $connection = config('database.default');
            $settings = config("database.connections.{$connection}");
            if (($settings['driver'] ?? null) !== 'mysql') {
                throw new RuntimeException('Backup database hanya tersedia untuk koneksi MySQL.');
            }

            $directory = 'backups/'.now()->format('Y/m');
            $path = $directory.'/'.$this->backup->filename;
            Storage::disk('private')->makeDirectory($directory);

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s',
                $settings['host'],
                $settings['port'] ?? 3306,
                $settings['database'],
            );

            $dump = new \Ifsnop\Mysqldump\Mysqldump($dsn, $settings['username'], $settings['password']);
            $dump->start(Storage::disk('private')->path($path));

            $this->backup->update([
                'stored_path' => $path,
                'status' => 'completed',
                'completed_at' => now(),
                'expires_at' => now()->addDay(),
            ]);

            AuditLog::create([
                'user_id' => $this->backup->requested_by,
                'action' => 'database_backup.completed',
                'auditable_type' => DatabaseBackup::class,
                'auditable_id' => $this->backup->id,
                'metadata' => ['filename' => $this->backup->filename],
            ]);
        } catch (\Throwable $exception) {
            $this->backup->update([
                'status' => 'failed',
                'error_message' => 'Backup gagal dibuat. Periksa log aplikasi untuk detail.',
            ]);

            Log::error('Database backup failed.', [
                'backup_id' => $this->backup->id,
                'exception' => $exception,
            ]);

            throw $exception;
        }
    }
}
