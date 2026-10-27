<?php

namespace Database\Factories;

use App\Enums\ReportStatus;
use App\Models\Distribution;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'distribution_id' => Distribution::factory(),
            'incident_at' => fake()->dateTimeBetween('-5 days', 'now'),
            'description' => fake()->paragraph(3),
            'location_detail' => fake()->optional()->randomElement([
                'Kelas 7A',
                'Kantin sekolah',
                'Halaman sekolah',
            ]),
            'status' => ReportStatus::Baru,
        ];
    }
}
