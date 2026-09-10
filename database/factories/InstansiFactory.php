<?php

namespace Database\Factories;

use App\Models\Instansi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instansi>
 */
class InstansiFactory extends Factory
{
    protected $model = Instansi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_dinas' => 'Dinas ' . fake()->unique()->company(),
            'kode_unit_kerja' => strtoupper(fake()->unique()->bothify('UNIT-###??')),
            'alamat' => fake()->address(),
            'max_total_quota' => 10,
            'jam_mulai_masuk' => '07:30:00',
            'jam_mulai_pulang' => '16:00:00',
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'radius_absen' => 100,
            'qr_absensi_enabled' => false,
        ];
    }
}
