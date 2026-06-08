<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Kunci (misal: 'registration_open')
            $table->text('value')->nullable(); // Nilai (misal: '1' atau '0')
            $table->timestamps();
        });

        // Insert Default Data (Seeding Langsung)
        DB::table('settings')->insert([
            ['key' => 'app_name', 'value' => 'SiMagang Banjarmasin', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'registration_open', 'value' => '1', 'created_at' => now(), 'updated_at' => now()], // 1 = Buka, 0 = Tutup
            ['key' => 'announcement', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
