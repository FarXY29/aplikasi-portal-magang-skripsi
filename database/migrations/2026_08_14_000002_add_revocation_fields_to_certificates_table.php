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
        Schema::table('certificates', function (Blueprint $table) {
            if (! Schema::hasColumn('certificates', 'status')) {
                $table->enum('status', ['active', 'revoked'])->default('active')->after('signature_mock');
            }
            if (! Schema::hasColumn('certificates', 'revoked_at')) {
                $table->timestamp('revoked_at')->nullable()->after('status');
            }
            if (! Schema::hasColumn('certificates', 'revoked_reason')) {
                $table->text('revoked_reason')->nullable()->after('revoked_at');
            }
            if (! Schema::hasColumn('certificates', 'revoked_by')) {
                $table->foreignId('revoked_by')->nullable()->after('revoked_reason')->constrained('users')->nullOnDelete();
            }

            $table->index(['nomor_sertifikat', 'token_verifikasi', 'status'], 'idx_certificates_search');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropIndex('idx_certificates_search');
            if (Schema::hasColumn('certificates', 'revoked_by')) {
                $table->dropForeign(['revoked_by']);
                $table->dropColumn('revoked_by');
            }
            if (Schema::hasColumn('certificates', 'revoked_reason')) {
                $table->dropColumn('revoked_reason');
            }
            if (Schema::hasColumn('certificates', 'revoked_at')) {
                $table->dropColumn('revoked_at');
            }
            if (Schema::hasColumn('certificates', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
