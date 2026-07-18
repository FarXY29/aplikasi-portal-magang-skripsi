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
        Schema::table('instansis', function (Blueprint $table) {
            if (!Schema::hasColumn('instansis', 'max_total_quota')) {
                $table->integer('max_total_quota')->default(0)->after('nama_pejabat')->comment('Batas atas kuota gabungan seluruh posisi');
            }
            if (!Schema::hasColumn('instansis', 'contact_whatsapp')) {
                $table->string('contact_whatsapp', 30)->nullable()->after('alamat');
            }
        });

        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'letter_number')) {
                $table->string('letter_number', 100)->nullable()->after('surat_pengantar_path')->comment('Nomor Surat Pengantar Kampus');
            }
            if (!Schema::hasColumn('applications', 'verified_by')) {
                $table->foreignId('verified_by')->nullable()->after('pembimbing_lapangan_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('applications', 'rejected_reason')) {
                $table->text('rejected_reason')->nullable()->after('status')->comment('Catatan/alasan penolakan dari admin instansi');
            }
            if (!Schema::hasColumn('applications', 'canceled_at')) {
                $table->timestamp('canceled_at')->nullable()->after('updated_at')->comment('Waktu pembatalan/pengunduran diri');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'sso_provider')) {
                $table->string('sso_provider', 50)->nullable()->after('google_id');
            }
            if (!Schema::hasColumn('users', 'sso_id')) {
                $table->string('sso_id', 100)->nullable()->index()->after('sso_provider');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('users', 'sso_id')) $columns[] = 'sso_id';
            if (Schema::hasColumn('users', 'sso_provider')) $columns[] = 'sso_provider';
            if (!empty($columns)) $table->dropColumn($columns);
        });

        Schema::table('applications', function (Blueprint $table) {
            if (Schema::hasColumn('applications', 'verified_by')) {
                $table->dropForeign(['verified_by']);
                $table->dropColumn('verified_by');
            }
            $columns = [];
            if (Schema::hasColumn('applications', 'letter_number')) $columns[] = 'letter_number';
            if (Schema::hasColumn('applications', 'rejected_reason')) $columns[] = 'rejected_reason';
            if (Schema::hasColumn('applications', 'canceled_at')) $columns[] = 'canceled_at';
            if (!empty($columns)) $table->dropColumn($columns);
        });

        Schema::table('instansis', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('instansis', 'max_total_quota')) $columns[] = 'max_total_quota';
            if (Schema::hasColumn('instansis', 'contact_whatsapp')) $columns[] = 'contact_whatsapp';
            if (!empty($columns)) $table->dropColumn($columns);
        });
    }
};
