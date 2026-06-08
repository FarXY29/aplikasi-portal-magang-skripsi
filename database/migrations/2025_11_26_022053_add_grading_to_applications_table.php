<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->integer('nilai_angka')->nullable()->after('pembimbing_lapangan_id'); // 0-100
            $table->string('predikat')->nullable()->after('nilai_angka');   // A, B, C
            $table->text('catatan_pembimbing_lapangan')->nullable()->after('predikat');  // Evaluasi Narasi
        });
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['nilai_angka', 'predikat', 'catatan_pembimbing_lapangan']);
        });
    }
};
