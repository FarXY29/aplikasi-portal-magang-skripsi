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
        Schema::table('applications', function (Blueprint $table) {
            // Drop old assessment columns if they exist
            $columnsToDrop = [];
            foreach (['nilai_sikap', 'nilai_kesungguhan', 'nilai_mandiri', 'nilai_kerjasama', 'nilai_ketelitian', 'nilai_pendapat', 'nilai_serap_hal_baru', 'nilai_inisiatif', 'nilai_kepuasan'] as $col) {
                if (Schema::hasColumn('applications', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (count($columnsToDrop) > 0) {
                $table->dropColumn($columnsToDrop);
            }

            // Add new columns (nilai_disiplin should already exist, we add others)
            $table->integer('nilai_kerajinan')->nullable();
            $table->integer('nilai_adaptasi')->nullable();
            $table->integer('nilai_kreatifitas')->nullable();
            $table->integer('nilai_skill_pengetahuan')->nullable();
            
            if (!Schema::hasColumn('applications', 'nilai_disiplin')) {
                $table->integer('nilai_disiplin')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'nilai_kerajinan', 'nilai_adaptasi', 'nilai_kreatifitas', 'nilai_skill_pengetahuan'
            ]);
            
            $table->integer('nilai_sikap')->nullable();
            $table->integer('nilai_kesungguhan')->nullable();
            $table->integer('nilai_mandiri')->nullable();
            $table->integer('nilai_kerjasama')->nullable();
            $table->integer('nilai_ketelitian')->nullable();
            $table->integer('nilai_pendapat')->nullable();
            $table->integer('nilai_serap_hal_baru')->nullable();
            $table->integer('nilai_inisiatif')->nullable();
            $table->integer('nilai_kepuasan')->nullable();
        });
    }
};
