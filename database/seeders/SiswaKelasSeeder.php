<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Assign siswa ke kelas
     */
    public function run(): void
    {
        // Semua siswa (id 3-7) diassign ke Kelas 7A (id 1)
        DB::table('siswa_kelas')->insert([
            [
                'siswa_id' => 3,  // Ahmad Ridho
                'kelas_id' => 1,  // Kelas 7A
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'siswa_id' => 4,  // Siti Nurhaliza
                'kelas_id' => 1,  // Kelas 7A
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'siswa_id' => 5,  // Budi Santoso
                'kelas_id' => 1,  // Kelas 7A
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'siswa_id' => 6,  // Dewi Lestari
                'kelas_id' => 1,  // Kelas 7A
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'siswa_id' => 7,  // Eka Prasetya
                'kelas_id' => 1,  // Kelas 7A
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
