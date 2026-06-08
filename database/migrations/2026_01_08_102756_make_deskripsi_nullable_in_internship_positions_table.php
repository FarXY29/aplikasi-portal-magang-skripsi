<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('internship_positions', function (Blueprint $table) {
            // Ubah kolom deskripsi menjadi nullable
            $table->text('deskripsi')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('internship_positions', function (Blueprint $table) {
            // Kembalikan menjadi required (tidak nullable) jika di-rollback
            $table->text('deskripsi')->nullable(false)->change();
        });
    }
};