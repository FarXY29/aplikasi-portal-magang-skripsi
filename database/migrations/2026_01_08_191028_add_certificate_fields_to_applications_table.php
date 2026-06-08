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
            $table->string('nomor_sertifikat')->nullable()->unique(); // Contoh: MAG-2026-0001
            $table->string('token_verifikasi')->nullable()->unique(); // String acak untuk URL QR
        });
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['nomor_sertifikat', 'token_verifikasi']);
        });
    }
};
