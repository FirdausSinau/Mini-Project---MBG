<?php

namespace Database\Seeders;

use App\Models\Distribution;
use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistributionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            'Nasi ayam sayur',
            'Nasi telur sayur',
            'Nasi ikan sayur',
        ];

        $schools = School::all();

        foreach ($schools as $schoolIndex => $school) {
            $dates = [
                now()->subDays(7)->toDateString(),
                now()->subDays(3)->toDateString(),
                now()->subDay()->toDateString(),
            ];

            foreach ($dates as $dateIndex => $date) {
                Distribution::updateOrCreate(
                    [
                        'school_id' => $school->id,
                        'tanggal' => $date,
                    ],
                    [
                        'menu' => $menus[($schoolIndex + $dateIndex) % count($menus)],
                    ]
                );
            }
        }
    }
}
