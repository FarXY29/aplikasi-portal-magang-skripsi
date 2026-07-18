<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->index(
                ['status', 'internship_position_id', 'tanggal_mulai', 'tanggal_selesai'],
                'applications_status_position_dates_idx'
            );
            $table->index(['user_id', 'status'], 'applications_user_status_idx');
            $table->index(['pembimbing_lapangan_id', 'status'], 'applications_mentor_status_idx');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->index(['application_id', 'date'], 'attendances_application_date_idx');
            $table->index(['application_id', 'validation_status'], 'attendances_application_validation_idx');
        });

        Schema::table('internship_positions', function (Blueprint $table) {
            $table->index(['instansi_id', 'status'], 'positions_instansi_status_idx');
        });
    }

    public function down(): void
    {
        Schema::table('internship_positions', function (Blueprint $table) {
            $table->dropIndex('positions_instansi_status_idx');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('attendances_application_validation_idx');
            $table->dropIndex('attendances_application_date_idx');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('applications_mentor_status_idx');
            $table->dropIndex('applications_user_status_idx');
            $table->dropIndex('applications_status_position_dates_idx');
        });
    }
};
