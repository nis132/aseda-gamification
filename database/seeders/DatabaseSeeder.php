<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ADMIN
        DB::table('users')->insert([
            'nama' => 'Admin Sistem',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 2. GURU (insert satu per satu untuk dapat ID)
        $budiId = DB::table('users')->insertGetId([
            'nama' => 'Budi Santoso',
            'username' => 'budi.guru',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $sitiId = DB::table('users')->insertGetId([
            'nama' => 'Siti Aisyah',
            'username' => 'siti.guru',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 3. SISWA
        $andiId = DB::table('users')->insertGetId([
            'nama' => 'Andi Wijaya',
            'username' => 'andi7a',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'total_poin' => 150,
            'level' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $citraId = DB::table('users')->insertGetId([
            'nama' => 'Citra Lestari',
            'username' => 'citra7a',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'total_poin' => 250,
            'level' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 4. KELAS
        $kelas7aId = DB::table('kelas')->insertGetId([
            'nama_kelas' => '7A',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $kelas7bId = DB::table('kelas')->insertGetId([
            'nama_kelas' => '7B',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 5. MAPEL
        $matematikaId = DB::table('mapel')->insertGetId([
            'nama_mapel' => 'Matematika',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $bindoId = DB::table('mapel')->insertGetId([
            'nama_mapel' => 'Bahasa Indonesia',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $ipaId = DB::table('mapel')->insertGetId([
            'nama_mapel' => 'IPA',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 6. GURU MAPEL (pakai ID yang sudah didapat)
        DB::table('guru_mapel')->insert([
            [
                'guru_id' => $budiId,
                'mapel_id' => $matematikaId,
                'kelas_id' => $kelas7aId,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guru_id' => $sitiId,
                'mapel_id' => $bindoId,
                'kelas_id' => $kelas7aId,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 7. SISWA KELAS
        DB::table('siswa_kelas')->insert([
            [
                'siswa_id' => $andiId,
                'kelas_id' => $kelas7aId,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'siswa_id' => $citraId,
                'kelas_id' => $kelas7aId,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 8. BADGE GAMIFIKASI
        DB::table('badge')->insert([
            [
                'nama_badge' => 'Aktif 3 Hari',
                'deskripsi' => 'Mengumpulkan 3 tugas berturut-turut tepat waktu',
                'icon' => '🏆',
                'poin_minimal' => 50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_badge' => 'Quiz Master',
                'deskripsi' => 'Mendapatkan nilai kuis ≥ 80',
                'icon' => '⭐',
                'poin_minimal' => 100,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        $this->command->info('✅ SEEDER GAMIFIKASI BERHASIL!');
    }
}
