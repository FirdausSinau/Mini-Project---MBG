<?php

namespace Database\Factories;

use App\Models\Distribution;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Distribution>
 */
class DistributionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'tanggal' => fake()->unique()->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
            'menu' => fake()->randomElement([
                'Nasi ayam sayur',
                'Nasi telur sayur',
                'Nasi ikan sayur',
                'Nasi tempe sayur',
            ]),
        ];
    }
}
