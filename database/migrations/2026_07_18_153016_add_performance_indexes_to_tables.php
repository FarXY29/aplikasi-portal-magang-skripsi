<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            Schema::table('applications', function (Blueprint $table) {
                $table->index('status');
                $table->index('internship_position_id');
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('attendances', function (Blueprint $table) {
                $table->index(['user_id', 'date']);
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('logbooks', function (Blueprint $table) {
                $table->index(['user_id', 'date']);
            });
        } catch (\Exception $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropIndex(['status']);
                $table->dropIndex(['internship_position_id']);
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dropIndex(['user_id', 'date']);
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('logbooks', function (Blueprint $table) {
                $table->dropIndex(['user_id', 'date']);
            });
        } catch (\Exception $e) {}
    }
};
