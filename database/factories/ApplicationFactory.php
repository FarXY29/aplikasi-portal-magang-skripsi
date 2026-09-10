<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'internship_position_id' => InternshipPosition::factory(),
            'cv_path' => 'cvs/sample.pdf',
            'surat_pengantar_path' => 'surats/sample.pdf',
            'status' => ApplicationStatus::Pending,
            'tanggal_mulai' => now()->addDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(35)->toDateString(),
        ];
    }

    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ApplicationStatus::Diterima,
        ]);
    }

    public function rejected(?string $reason = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ApplicationStatus::Ditolak,
            'rejected_reason' => $reason ?? 'Kualifikasi tidak sesuai',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ApplicationStatus::Selesai,
        ]);
    }

    public function cancelled(?string $reason = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ApplicationStatus::Dibatalkan,
            'canceled_at' => now(),
            'rejected_reason' => $reason ?? 'Dibatalkan oleh pemohon',
        ]);
    }

    public function waiting(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ApplicationStatus::Menunggu,
        ]);
    }
}
