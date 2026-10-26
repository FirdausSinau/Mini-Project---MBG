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
            ['username' => 'Giorno', 'nama_lengkap' => 'Giorno Giovanna', 'role' => 'reporter', 'password' => 'rahasia123'],
            ['username' => 'Jotaro', 'nama_lengkap' => 'Jotaro Kujo', 'role' => 'reporter', 'password' => 'rahasia123'],
            ['username' => 'Dramok', 'nama_lengkap' => 'Dramok Reza Ladesh', 'role' => 'reviewer', 'password' => 'rahasia123'],
            ['username' => 'Perrell', 'nama_lengkap' => 'Laquarius Brown', 'role' => 'coordinator', 'password' => 'rahasia123'],
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
