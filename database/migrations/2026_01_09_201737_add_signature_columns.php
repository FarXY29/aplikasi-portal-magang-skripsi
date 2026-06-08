<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        // Tambah kolom ttd_kepala di tabel instansis (untuk Kepala Dinas)
        Schema::table('instansis', function (Blueprint $table) {
            $table->string('ttd_kepala')->nullable()->after('nama_pejabat');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('signature');
        });

        Schema::table('instansis', function (Blueprint $table) {
            $table->dropColumn('ttd_kepala');
        });
    }
};