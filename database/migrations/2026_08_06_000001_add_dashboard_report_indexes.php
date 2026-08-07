<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'users_role_idx');
        });

        Schema::table('daily_logs', function (Blueprint $table) {
            $table->index('status_validasi', 'daily_logs_status_validasi_idx');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->index('created_at', 'applications_created_at_idx');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('applications_created_at_idx');
        });

        Schema::table('daily_logs', function (Blueprint $table) {
            $table->dropIndex('daily_logs_status_validasi_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_idx');
        });
    }
};
