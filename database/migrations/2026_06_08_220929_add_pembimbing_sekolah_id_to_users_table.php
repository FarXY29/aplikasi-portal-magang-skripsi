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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('pembimbing_sekolah_id')->nullable()->after('nama_pembimbing_sekolah');
            
            // Opsional: Jika ingin strict foreign key
            // $table->foreign('pembimbing_sekolah_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // $table->dropForeign(['pembimbing_sekolah_id']);
            $table->dropColumn('pembimbing_sekolah_id');
        });
    }
};
