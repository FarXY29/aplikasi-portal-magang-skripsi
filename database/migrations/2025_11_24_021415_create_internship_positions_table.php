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
        Schema::create('internship_positions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('instansi_id')->constrained();
        $table->string('judul_posisi'); // Contoh: Programmer Web, Staff Admin
        $table->text('deskripsi');
        $table->integer('kuota');
        $table->date('batas_daftar');
        $table->enum('status', ['buka', 'tutup']);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_positions');
    }
};
