<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Tambah enum value 'ditolak' jika belum ada
        DB::statement("ALTER TABLE daily_logs MODIFY COLUMN status_validasi ENUM('pending', 'disetujui', 'revisi', 'ditolak') NOT NULL DEFAULT 'pending'");
        
        // 2. Migrasi data lama dari 'valid' ke 'disetujui'
        DB::table('daily_logs')->where('status_validasi', 'valid')->update(['status_validasi' => 'disetujui']);
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE daily_logs MODIFY COLUMN status_validasi ENUM('pending', 'disetujui', 'revisi') NOT NULL DEFAULT 'pending'");
    }
};
