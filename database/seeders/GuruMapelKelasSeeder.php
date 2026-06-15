<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruMapelKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('guru_mapel_kelas')->insert([
            [
                'guru_id' => 2,  // Guru PAI
                'mapel_id' => 3, // Pendidikan Agama Islam
                'kelas_id' => 1, // Kelas 7A
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
