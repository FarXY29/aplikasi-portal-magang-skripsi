<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('internship_positions', function (Blueprint $table) {
            // Ubah kolom menjadi nullable (boleh kosong)
            $table->date('batas_daftar')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('internship_positions', function (Blueprint $table) {
            // Kembalikan ke wajib isi jika di-rollback
            $table->date('batas_daftar')->nullable(false)->change();
        });
    }
};