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
        // 1. Applications -> internship_positions cascade
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['internship_position_id']);
            $table->foreign('internship_position_id')
                ->references('id')
                ->on('internship_positions')
                ->onDelete('cascade');
        });

        // 2. Internship positions -> instansis cascade
        Schema::table('internship_positions', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
            $table->foreign('instansi_id')
                ->references('id')
                ->on('instansis')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internship_positions', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
            $table->foreign('instansi_id')
                ->references('id')
                ->on('instansis');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['internship_position_id']);
            $table->foreign('internship_position_id')
                ->references('id')
                ->on('internship_positions');
        });
    }
};
