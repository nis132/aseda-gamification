<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tantangan;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\User;

class TantanganPerBabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Membuat 3 tantangan per bab (bab 1-8) untuk setiap kelas.
     * Total: 8 bab × 3 tantangan = 24 tantangan per kelas.
     */
    public function run(): void
    {
        // Ambil kelas pertama (atau bisa loop untuk semua kelas)
        $kelas = Kelas::first();
        if (!$kelas) {
            $this->command->info('❌ Tidak ada kelas di database. Buat kelas terlebih dahulu.');
            return;
        }

        // Ambil guru pertama (atau bisa filter guru yang mengajar mapel tertentu)
        $guru = User::where('role', 'guru')->first();
        if (!$guru) {
            $this->command->info('❌ Tidak ada guru di database. Buat guru terlebih dahulu.');
            return;
        }

        // Ambil mapel pertama
        $mapel = Mapel::first();
        if (!$mapel) {
            $this->command->info('❌ Tidak ada mapel di database. Buat mapel terlebih dahulu.');
            return;
        }

        $this->command->info("🎯 Membuat 24 tantangan untuk:");
        $this->command->info("   Kelas: {$kelas->nama_kelas}");
        $this->command->info("   Guru: {$guru->nama}");
        $this->command->info("   Mapel: {$mapel->nama_mapel}");

        $jumlahBuat = 0;

        // Loop untuk bab 1-8
        for ($bab = 1; $bab <= 8; $bab++) {
            $this->command->info("\n📚 Bab $bab:");

            // Buat 3 tantangan per bab
            for ($no = 1; $no <= 3; $no++) {
                // Tentukan tingkat kesulitan: Easy, Medium, Hard
                $tingkatKesulitan = match($no) {
                    1 => 'easy',
                    2 => 'medium',
                    3 => 'hard',
                };

                $poin = match($tingkatKesulitan) {
                    'easy'   => 10,
                    'medium' => 20,
                    'hard'   => 30,
                };

                $batasWaktu = match($tingkatKesulitan) {
                    'easy'   => 60,      // 60 menit
                    'medium' => 90,      // 90 menit
                    'hard'   => 120,     // 120 menit
                };

                $tantangan = Tantangan::create([
                    'judul' => "Tantangan $no - $tingkatKesulitan | Bab $bab: " . $this->generateTitles($bab, $no),
                    'deskripsi' => $this->generateDeskripsi($bab, $no, $tingkatKesulitan),
                    'mapel_id' => $mapel->id,
                    'guru_id' => $guru->id,
                    'kelas_id' => $kelas->id,
                    'batas_waktu' => $batasWaktu,
                    'poin' => $poin,
                    'status' => 'published',
                    'urutan' => $no,
                    'difficulty' => $tingkatKesulitan,
                    'bab' => $bab,
                ]);

                $jumlahBuat++;
                $this->command->line("  ✅ {$tantangan->judul}");
            }
        }

        $this->command->info("\n✨ Berhasil membuat $jumlahBuat tantangan!");
    }

    /**
     * Generate judul tantangan berdasarkan bab dan nomor.
     */
    private function generateTitles(int $bab, int $no): string
    {
        $babjudul = [
            1 => 'Pengantar & Konsep Dasar',
            2 => 'Perkembangan & Aplikasi',
            3 => 'Analisis & Evaluasi',
            4 => 'Sintesis & Kreativitas',
            5 => 'Penerapan Kompleks',
            6 => 'Integrasi Konsep',
            7 => 'Masterclass & Eksplorasi',
            8 => 'Ultimate Challenge',
        ];

        $tipe = match($no) {
            1 => 'Memahami',
            2 => 'Menerapkan',
            3 => 'Menganalisis',
        };

        return "$tipe {$babjudul[$bab]}";
    }

    /**
     * Generate deskripsi tantangan.
     */
    private function generateDeskripsi(int $bab, int $no, string $difficulty): string
    {
        $babjudul = [
            1 => 'Pengantar & Konsep Dasar',
            2 => 'Perkembangan & Aplikasi',
            3 => 'Analisis & Evaluasi',
            4 => 'Sintesis & Kreativitas',
            5 => 'Penerapan Kompleks',
            6 => 'Integrasi Konsep',
            7 => 'Masterclass & Eksplorasi',
            8 => 'Ultimate Challenge',
        ];

        $levelDeskripsi = match($difficulty) {
            'easy'   => 'Tingkat kesulitan: **MUDAH** - Cocok untuk pemula yang baru belajar bab ini.',
            'medium' => 'Tingkat kesulitan: **SEDANG** - Menguji pemahaman Anda terhadap konsep yang dipelajari.',
            'hard'   => 'Tingkat kesulitan: **SULIT** - Tantangan tingkat lanjut untuk siswa yang mahir.',
        };

        return "Selesaikan tantangan soal untuk Bab $bab: {$babjudul[$bab]}\n\n$levelDeskripsi\n\nWaktu: Sesuai dengan tingkat kesulitan\nPoin: " . 
               match($difficulty) {
                   'easy'   => '10 poin',
                   'medium' => '20 poin',
                   'hard'   => '30 poin',
               };
    }
}
