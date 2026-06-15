<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mapel')->insert([
            [
                'nama_mapel' => 'Matematika',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_mapel' => 'Bahasa Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_mapel' => 'Pendidikan Agama Islam (PAI)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_mapel' => 'Bahasa Inggris',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_mapel' => 'IPA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_mapel' => 'IPS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
