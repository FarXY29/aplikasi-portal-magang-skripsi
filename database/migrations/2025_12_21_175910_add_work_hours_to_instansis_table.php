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
        Schema::table('instansis', function (Blueprint $table) {
            // Default jam 07:30 pagi dan 16:00 sore
            $table->time('jam_mulai_masuk')->default('07:30:00'); 
            $table->time('jam_mulai_pulang')->default('16:00:00');
        });
    }

    public function down()
        {
            Schema::table('instansis', function (Blueprint $table) {
                $table->dropColumn(['jam_mulai_masuk', 'jam_mulai_pulang']);
            });
        }
};
