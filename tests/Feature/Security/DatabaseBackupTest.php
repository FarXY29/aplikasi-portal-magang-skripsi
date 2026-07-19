<?php

namespace Tests\Feature\Security;

use App\Jobs\CreateDatabaseBackup;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DatabaseBackupTest extends TestCase
{
    use DatabaseTransactions;

    public function test_backup_requires_the_current_password_and_is_queued(): void
    {
        Queue::fake();
        $admin = User::factory()->create(['role' => 'admin_kota']);

        $this->actingAs($admin)
            ->post(route('admin.settings.backup'), ['password' => 'invalid'])
            ->assertSessionHasErrors('password');

        $this->actingAs($admin)
            ->post(route('admin.settings.backup'), ['password' => 'password'])
            ->assertRedirect();

        $this->assertDatabaseHas('database_backups', [
            'requested_by' => $admin->id,
            'status' => 'queued',
        ]);
        Queue::assertPushed(CreateDatabaseBackup::class);
    }
}
