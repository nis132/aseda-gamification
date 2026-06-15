<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed in the correct order to avoid foreign key constraint violations
        $this->call(UserSeeder::class);           // Must be first (users are referenced)
        $this->call(MapelSeeder::class);          // mapel table
        $this->call(KelasSeeder::class);          // kelas table
        $this->call(SiswaKelasSeeder::class);     // Assign siswa to kelas
        $this->call(GuruMapelKelasSeeder::class); // Link guru to mapel and kelas
        $this->call(MateriPAISeeder::class);      // materi table (depends on users, mapel, kelas)
        $this->call(TantanganPAISeeder::class);   // tantangan and soal table
        // Uncomment ini untuk seed tantangan 3 per bab:
        // $this->call(TantanganPerBabSeeder::class);
    }
}
