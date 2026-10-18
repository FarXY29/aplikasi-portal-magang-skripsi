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
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (! Schema::hasIndex('attendances', 'attendances_app_status_idx')) {
                    $table->index(['application_id', 'status'], 'attendances_app_status_idx');
                }
                if (! Schema::hasIndex('attendances', 'attendances_date_fraud_idx')) {
                    $table->index(['date', 'fraud_status'], 'attendances_date_fraud_idx');
                }
            });
        }

        if (Schema::hasTable('attendance_attempts')) {
            Schema::table('attendance_attempts', function (Blueprint $table) {
                if (! Schema::hasIndex('attendance_attempts', 'attempts_instance_time_fraud_idx')) {
                    $table->index(['instance_id', 'server_received_at', 'fraud_status'], 'attempts_instance_time_fraud_idx');
                }
                if (! Schema::hasIndex('attendance_attempts', 'attempts_user_time_idx')) {
                    $table->index(['user_id', 'server_received_at'], 'attempts_user_time_idx');
                }
            });
        }

        if (Schema::hasTable('applications')) {
            Schema::table('applications', function (Blueprint $table) {
                if (! Schema::hasIndex('applications', 'applications_pos_status_idx')) {
                    $table->index(['internship_position_id', 'status'], 'applications_pos_status_idx');
                }
                if (! Schema::hasIndex('applications', 'applications_created_status_idx')) {
                    $table->index(['created_at', 'status'], 'applications_created_status_idx');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (Schema::hasIndex('attendances', 'attendances_app_status_idx')) {
                    $table->dropIndex('attendances_app_status_idx');
                }
                if (Schema::hasIndex('attendances', 'attendances_date_fraud_idx')) {
                    $table->dropIndex('attendances_date_fraud_idx');
                }
            });
        }

        if (Schema::hasTable('attendance_attempts')) {
            Schema::table('attendance_attempts', function (Blueprint $table) {
                if (Schema::hasIndex('attendance_attempts', 'attempts_instance_time_fraud_idx')) {
                    $table->dropIndex('attempts_instance_time_fraud_idx');
                }
                if (Schema::hasIndex('attendance_attempts', 'attempts_user_time_idx')) {
                    $table->dropIndex('attempts_user_time_idx');
                }
            });
        }

        if (Schema::hasTable('applications')) {
            Schema::table('applications', function (Blueprint $table) {
                if (Schema::hasIndex('applications', 'applications_pos_status_idx')) {
                    $table->dropIndex('applications_pos_status_idx');
                }
                if (Schema::hasIndex('applications', 'applications_created_status_idx')) {
                    $table->dropIndex('applications_created_status_idx');
                }
            });
        }
    }
};
