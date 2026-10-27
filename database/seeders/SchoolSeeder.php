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
            ['nama' => 'SMP Harapan', 'alamat' => 'Jl. Melati No. 10', 'kota' => 'Bandung'],
            ['nama' => 'SDN Melati', 'alamat' => 'Jl. Kenanga No. 4', 'kota' => 'Bandung'],
            ['nama' => 'SMA Nusantara', 'alamat' => 'Jl. Cendana No. 21', 'kota' => 'Jakarta'],
        ];

        foreach ($schools as $school) {
            School::updateOrCreate(
                ['nama' => $school['nama']],
                $school
            );
        }
    }
}
