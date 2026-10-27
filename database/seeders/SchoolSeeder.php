<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            ['nama' => 'SMP 2 Mlati Sleman', 'alamat' => 'Jl. Melati No. 10', 'kota' => 'Sleman'],
            ['nama' => 'SD Muhammadiyah Kutoarjo', 'alamat' => 'Jl. Kenanga No. 4', 'kota' => 'Purworejo'],
            ['nama' => 'SMA Jujutsu', 'alamat' => 'Jl. Cendana No. 21', 'kota' => 'Tokyo'],
        ];

        foreach ($schools as $school) {
            School::updateOrCreate(
                ['nama' => $school['nama']],
                $school
            );
        }
    }
}
