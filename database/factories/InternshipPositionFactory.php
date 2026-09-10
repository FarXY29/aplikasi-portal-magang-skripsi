<?php

namespace Database\Factories;

use App\Models\Instansi;
use App\Models\InternshipPosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InternshipPosition>
 */
class InternshipPositionFactory extends Factory
{
    protected $model = InternshipPosition::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'instansi_id' => Instansi::factory(),
            'judul_posisi' => fake()->jobTitle(),
            'deskripsi' => fake()->paragraph(),
            'kuota' => 5,
            'batas_daftar' => now()->addMonth()->toDateString(),
            'status' => 'buka',
        ];
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'tutup',
        ]);
    }
}
