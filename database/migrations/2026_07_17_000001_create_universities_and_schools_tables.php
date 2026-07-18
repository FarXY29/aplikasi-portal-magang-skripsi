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
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('code_pddikti', 50)->nullable()->unique()->comment('Kode PT dari PDDIKTI');
            $table->string('name');
            $table->string('acronym', 50)->nullable();
            $table->string('city', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('npsn', 50)->nullable()->unique()->comment('Nomor Pokok Sekolah Nasional');
            $table->string('name');
            $table->string('level', 20)->nullable()->comment('SMK, SMA, MA, dll.');
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'university_id')) {
                $table->foreignId('university_id')->nullable()->after('instansi_id')->constrained('universities')->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'school_id')) {
                $table->foreignId('school_id')->nullable()->after('university_id')->constrained('schools')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'school_id')) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            }
            if (Schema::hasColumn('users', 'university_id')) {
                $table->dropForeign(['university_id']);
                $table->dropColumn('university_id');
            }
        });

        Schema::dropIfExists('schools');
        Schema::dropIfExists('universities');
    }
};
