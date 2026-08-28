<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom hasil analisis fraud pada attendance.
     *
     * Backward-compatible: kedua kolom nullable sehingga attendance
     * lama tetap valid dan dapat dibaca tanpa perubahan.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedTinyInteger('risk_score')->nullable()->after('device_info');
            $table->string('fraud_status', 20)->nullable()->after('risk_score');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['risk_score', 'fraud_status']);
        });
    }
};
