<?php

namespace Tests\Feature\Security;

use App\Models\DatabaseBackup;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class DatabaseBackupTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $this->admin = User::factory()->create([
            'role' => 'admin_kota',
            'password' => bcrypt('password123'),
        ]);
        $this->admin->assignRole('admin_kota');
    }

    public function test_backup_requires_the_current_password(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.settings.backup'), ['password' => 'invalid-password'])
            ->assertSessionHasErrors('password');
    }

    public function test_backup_execution_creates_backup_record_with_valid_password(): void
    {
        Storage::fake('private');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.settings.backup'), ['password' => 'password123']);

        $response->assertRedirect();
        $this->assertDatabaseHas('database_backups', [
            'requested_by' => $this->admin->id,
        ]);
    }

    public function test_admin_kota_can_download_completed_backup(): void
    {
        Storage::fake('private');
        $storedPath = 'backups/2026/08/test_backup.sql';
        Storage::disk('private')->put($storedPath, '-- SQL Dump Content');

        $backup = DatabaseBackup::create([
            'requested_by' => $this->admin->id,
            'filename' => 'test_backup.sql',
            'stored_path' => $storedPath,
            'status' => 'completed',
            'completed_at' => now(),
            'expires_at' => now()->addDays(7),
        ]);

        $url = URL::temporarySignedRoute('admin.settings.backups.download', $backup->expires_at, ['backup' => $backup]);

        $response = $this->actingAs($this->admin)->get($url);

        $response->assertOk();
        $this->assertEquals('attachment; filename=test_backup.sql', $response->headers->get('content-disposition'));
    }

    public function test_admin_kota_can_delete_backup(): void
    {
        Storage::fake('private');
        $storedPath = 'backups/2026/08/test_delete_backup.sql';
        Storage::disk('private')->put($storedPath, '-- SQL Dump Content');

        $backup = DatabaseBackup::create([
            'requested_by' => $this->admin->id,
            'filename' => 'test_delete_backup.sql',
            'stored_path' => $storedPath,
            'status' => 'completed',
            'completed_at' => now(),
            'expires_at' => now()->addDays(7),
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.settings.backups.destroy', $backup));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('database_backups', ['id' => $backup->id]);
        Storage::disk('private')->assertMissing($storedPath);
    }

    public function test_expired_backup_cannot_be_downloaded(): void
    {
        Storage::fake('private');
        $storedPath = 'backups/2026/08/expired_backup.sql';
        Storage::disk('private')->put($storedPath, '-- SQL Dump Content');

        $backup = DatabaseBackup::create([
            'requested_by' => $this->admin->id,
            'filename' => 'expired_backup.sql',
            'stored_path' => $storedPath,
            'status' => 'completed',
            'completed_at' => now()->subDays(10),
            'expires_at' => now()->subDays(3),
        ]);

        $url = URL::temporarySignedRoute('admin.settings.backups.download', now()->addMinutes(10), ['backup' => $backup]);

        $response = $this->actingAs($this->admin)->get($url);
        $response->assertNotFound();
    }
}
