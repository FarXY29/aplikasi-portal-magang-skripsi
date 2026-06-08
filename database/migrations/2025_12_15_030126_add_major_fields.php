<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('major')->nullable()->after('username'); // Kolom Jurusan Mahasiswa
        });

        Schema::table('internship_positions', function (Blueprint $table) {
            $table->string('required_major')->nullable()->after('judul_posisi'); // Syarat Jurusan
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('major');
        });
        Schema::table('internship_positions', function (Blueprint $table) {
            $table->dropColumn('required_major');
        });
    }
};
