<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            // Pastikan kolom ini ada dan bertipe DATE
            if (!Schema::hasColumn('applications', 'tanggal_mulai')) {
                $table->date('tanggal_mulai')->nullable();
            }
            if (!Schema::hasColumn('applications', 'tanggal_selesai')) {
                $table->date('tanggal_selesai')->nullable();
            }
        });
    }

    public function down()
    {
        // Tidak perlu drop column agar data aman
    }
};
