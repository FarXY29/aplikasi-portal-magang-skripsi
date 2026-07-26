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
        // 1. Daily logs -> applications cascade
        Schema::table('daily_logs', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->foreign('application_id')
                ->references('id')
                ->on('applications')
                ->onDelete('cascade');
        });

        // 2. Applications -> users cascade (user_id) & set null (pembimbing_lapangan_id)
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->dropForeign(['pembimbing_lapangan_id']);
            $table->foreign('pembimbing_lapangan_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['pembimbing_lapangan_id']);
            $table->foreign('pembimbing_lapangan_id')
                ->references('id')
                ->on('users');

            $table->dropForeign(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });

        Schema::table('daily_logs', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->foreign('application_id')
                ->references('id')
                ->on('applications');
        });
    }
};
