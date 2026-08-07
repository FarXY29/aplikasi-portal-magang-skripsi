<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE daily_logs MODIFY COLUMN status_validasi ENUM('pending', 'disetujui', 'revisi', 'ditolak') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE daily_logs MODIFY COLUMN status_validasi ENUM('pending', 'disetujui', 'revisi') NOT NULL DEFAULT 'pending'");
    }
};