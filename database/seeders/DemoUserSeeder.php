<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Akun demo untuk tiga fungsi pengguna.
     */
    public function run(): void
    {
        $users = [
            ['username' => 'rina', 'nama_lengkap' => 'Bu Rina', 'role' => 'reporter', 'password' => 'rahasia123'],
            ['username' => 'budi', 'nama_lengkap' => 'Pak Budi', 'role' => 'reporter', 'password' => 'rahasia123'],
            ['username' => 'dedi', 'nama_lengkap' => 'Pak Dedi', 'role' => 'reviewer', 'password' => 'rahasia123'],
            ['username' => 'sari', 'nama_lengkap' => 'Bu Sari', 'role' => 'coordinator', 'password' => 'rahasia123'],
        ];

        foreach ($users as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::firstOrNew(['username' => $data['username']]);
            $user->fill($data);
            $user->role = $role;
            $user->save();
        }
    }
}
