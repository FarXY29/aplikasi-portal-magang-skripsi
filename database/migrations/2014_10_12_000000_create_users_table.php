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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            
            // --- TAMBAHAN PENTING DI SINI ---
            $table->string('asal_instansi')->nullable(); // <--- Tambahkan ini
            $table->enum('role', ['admin_kota', 'admin_instansi', 'pembimbing_lapangan', 'peserta', 'pembimbing']) // <--- Tambahkan 'pembimbing'
                  ->default('peserta');
            // --------------------------------
            
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // Kolom tambahan lain (jika Anda menaruhnya di file ini)
            $table->foreignId('instansi_id')->nullable();
            $table->string('nik')->nullable();
            $table->string('phone')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
