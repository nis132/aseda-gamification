<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin and guru users
        DB::table('users')->insert([
            [
                'nama' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'level' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Guru PAI',
                'username' => 'guru_pai',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'level' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Student users
            [
                'nama' => 'Ahmad Ridho',
                'username' => 'ahmad_ridho',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'level' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'username' => 'siti_nurhaliza',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'level' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Budi Santoso',
                'username' => 'budi_santoso',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'level' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dewi Lestari',
                'username' => 'dewi_lestari',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'level' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Eka Prasetya',
                'username' => 'eka_prasetya',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'level' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
