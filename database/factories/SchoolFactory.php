<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->randomElement(['SD', 'SMP', 'SMA']) . ' ' . fake()->city(),
            'alamat' => fake()->streetAddress(),
            'kota' => fake()->city(),
        ];
    }
}
