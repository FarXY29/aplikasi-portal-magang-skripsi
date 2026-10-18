<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PerformanceIndexIntegrityTest extends TestCase
{
    public function test_hotpath_performance_indexes_exist(): void
    {
        $this->assertTrue(Schema::hasTable('attendances'));
        $this->assertTrue(Schema::hasTable('attendance_attempts'));
        $this->assertTrue(Schema::hasTable('applications'));

        $attendanceIndexes = collect(Schema::getIndexes('attendances'))->pluck('name')->all();
        $this->assertContains('attendances_app_status_idx', $attendanceIndexes);
        $this->assertContains('attendances_date_fraud_idx', $attendanceIndexes);

        $attemptIndexes = collect(Schema::getIndexes('attendance_attempts'))->pluck('name')->all();
        $this->assertContains('attempts_instance_time_fraud_idx', $attemptIndexes);
        $this->assertContains('attempts_user_time_idx', $attemptIndexes);

        $appIndexes = collect(Schema::getIndexes('applications'))->pluck('name')->all();
        $this->assertContains('applications_pos_status_idx', $appIndexes);
        $this->assertContains('applications_created_status_idx', $appIndexes);
    }

    public function test_migration_is_idempotent(): void
    {
        $migration = require database_path('migrations/2026_10_02_000001_add_hotpath_performance_indexes.php');

        // Calling up() when indexes already exist should succeed cleanly without exceptions
        $migration->up();
        $this->assertTrue(true);
    }
}
