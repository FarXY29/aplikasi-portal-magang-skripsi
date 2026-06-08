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
        Schema::table('instansis', function (Blueprint $table) {
            $table->string('nama_pejabat')->nullable();    // Contoh: H. Agung Saptoto
            $table->string('nip_pejabat')->nullable();     // Contoh: 1975...
            $table->string('jabatan_pejabat')->default('Kepala Dinas'); // Contoh: Kabid. Aplikasi Informatika
        });
    }

    public function down()
    {
        Schema::table('instansis', function (Blueprint $table) {
            $table->dropColumn(['nama_pejabat', 'nip_pejabat', 'jabatan_pejabat']);
        });
    }
};
