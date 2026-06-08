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
        Schema::create('applications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained(); // Peserta
        $table->foreignId('internship_position_id')->constrained();
        $table->string('cv_path'); // File PDF
        $table->string('surat_pengantar_path');
        $table->enum('status', ['pending', 'wawancara', 'menunggu', 'diterima', 'ditolak', 'selesai']);
        $table->date('tanggal_mulai')->nullable();
        $table->date('tanggal_selesai')->nullable();
        $table->foreignId('pembimbing_lapangan_id')->nullable()->constrained('users'); // User yang jadi pembimbing_lapangan
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
