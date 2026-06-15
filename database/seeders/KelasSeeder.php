<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kelas')->insert([
            [
                'nama_kelas' => '7A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kelas' => '7B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kelas' => '7C',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kelas' => '8A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kelas' => '8B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kelas' => '8C',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
