<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('internship_positions', function (Blueprint $table) {
            $table->integer('kuota')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('internship_positions', function (Blueprint $table) {
            $table->integer('kuota')->nullable(false)->change();
        });
    }
};
