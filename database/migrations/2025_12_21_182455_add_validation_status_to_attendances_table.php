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
        Schema::table('attendances', function (Blueprint $table) {
            // pending = butuh cek pembimbing_lapangan, approved = oke, rejected = tolak
            $table->enum('validation_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            $table->string('pembimbing_lapangan_note')->nullable()->after('validation_status'); // Catatan penolakan
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['validation_status', 'pembimbing_lapangan_note']);
        });
}
};
