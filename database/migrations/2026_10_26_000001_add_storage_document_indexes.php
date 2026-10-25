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
        if (Schema::hasTable('applications')) {
            Schema::table('applications', function (Blueprint $table) {
                if (! Schema::hasIndex('applications', 'applications_surat_path_idx')) {
                    $table->index('surat_pengantar_path', 'applications_surat_path_idx');
                }
                if (! Schema::hasIndex('applications', 'applications_cv_path_idx')) {
                    $table->index('cv_path', 'applications_cv_path_idx');
                }
            });
        }

        if (Schema::hasTable('daily_logs')) {
            Schema::table('daily_logs', function (Blueprint $table) {
                if (! Schema::hasIndex('daily_logs', 'daily_logs_bukti_foto_path_idx')) {
                    $table->index('bukti_foto_path', 'daily_logs_bukti_foto_path_idx');
                }
            });
        }

        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (! Schema::hasIndex('attendances', 'attendances_proof_file_idx')) {
                    $table->index('proof_file', 'attendances_proof_file_idx');
                }
            });
        }

        if (Schema::hasTable('attendance_disputes')) {
            Schema::table('attendance_disputes', function (Blueprint $table) {
                if (! Schema::hasIndex('attendance_disputes', 'disputes_evidence_file_idx')) {
                    $table->index('evidence_file', 'disputes_evidence_file_idx');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('applications')) {
            Schema::table('applications', function (Blueprint $table) {
                if (Schema::hasIndex('applications', 'applications_surat_path_idx')) {
                    $table->dropIndex('applications_surat_path_idx');
                }
                if (Schema::hasIndex('applications', 'applications_cv_path_idx')) {
                    $table->dropIndex('applications_cv_path_idx');
                }
            });
        }

        if (Schema::hasTable('daily_logs')) {
            Schema::table('daily_logs', function (Blueprint $table) {
                if (Schema::hasIndex('daily_logs', 'daily_logs_bukti_foto_path_idx')) {
                    $table->dropIndex('daily_logs_bukti_foto_path_idx');
                }
            });
        }

        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (Schema::hasIndex('attendances', 'attendances_proof_file_idx')) {
                    $table->dropIndex('attendances_proof_file_idx');
                }
            });
        }

        if (Schema::hasTable('attendance_disputes')) {
            Schema::table('attendance_disputes', function (Blueprint $table) {
                if (Schema::hasIndex('attendance_disputes', 'disputes_evidence_file_idx')) {
                    $table->dropIndex('disputes_evidence_file_idx');
                }
            });
        }
    }
};
