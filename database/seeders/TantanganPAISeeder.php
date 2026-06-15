<?php
// database/seeders/TantanganPAISeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TantanganPAISeeder extends Seeder
{
    public function run(): void
    {
        $guruId  = 2;
        $kelasId = 1;
        $mapelId = 3;
        $now     = now();

        // Helper: insert soal PG
        $pg = function ($tantanganId, $pertanyaan, $a, $b, $c, $d, $jawaban) use ($now) {
            DB::table('soal')->insert([
                'tantangan_id'  => $tantanganId,
                'pertanyaan'    => $pertanyaan,
                'tipe'          => 'pg',
                'opsi_a'        => $a,
                'opsi_b'        => $b,
                'opsi_c'        => $c,
                'opsi_d'        => $d,
                'jawaban_benar' => $jawaban,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        };

        // Helper: insert soal essay
        $essay = function ($tantanganId, $pertanyaan, $jawaban) use ($now) {
            DB::table('soal')->insert([
                'tantangan_id'  => $tantanganId,
                'pertanyaan'    => $pertanyaan,
                'tipe'          => 'essay',
                'jawaban_benar' => $jawaban,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        };

        // Helper: insert soal matching
        $matching = function ($tantanganId, $pertanyaan, $kiri, $kanan, $pairs) use ($now) {
            DB::table('soal')->insert([
                'tantangan_id'   => $tantanganId,
                'pertanyaan'     => $pertanyaan,
                'tipe'           => 'matching',
                'kiri_items'     => json_encode($kiri),
                'kanan_items'    => json_encode($kanan),
                'matching_pairs' => json_encode($pairs),
                'matching_count' => count($pairs),
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        };

        // ══════════════════════════════════════════════════════
        // BAB 1 — Asmaul Husna (3 Tantangan, urutan 1-2-3)
        // ══════════════════════════════════════════════════════

        // BAB 1 — Tantangan 1
        $t1 = DB::table('tantangan')->insertGetId([
            'judul'       => 'BAB 1 — Tantangan 1: Mengenal Asmaul Husna',
            'deskripsi'   => 'Uji pemahamanmu tentang pengertian dan dasar-dasar Asmaul Husna.',
            'mapel_id'    => $mapelId,
            'guru_id'     => $guruId,
            'kelas_id'    => $kelasId,
            'status'      => 'published',
            'batas_waktu' => now()->addDays(30),
            'poin'        => 50,
            'urutan'      => 1,
            'difficulty'  => 'chapter_1',
            'bab'         => 1,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        $pg($t1, 'Apa arti Asmaul Husna?',
            'Nama-nama yang indah', 'Sifat-sifat buruk', 'Kitab suci umat Islam', 'Doa sehari-hari', 'A');
        $pg($t1, 'Berapa jumlah Asmaul Husna yang dimiliki Allah?',
            '77', '88', '99', '100', 'C');
        $pg($t1, 'Al-Alim adalah nama Allah yang berarti...',
            'Yang Maha Mendengar', 'Yang Maha Mengetahui', 'Yang Maha Melihat', 'Yang Maha Kuasa', 'B');
        $pg($t1, "As-Sami' berarti Allah Yang Maha...",
            'Melihat', 'Mengetahui', 'Mendengar', 'Mengampuni', 'C');
        $pg($t1, 'Salah satu hikmah mempelajari Asmaul Husna adalah...',
            'Mendapat nilai bagus di sekolah', 'Meningkatkan keimanan dan ketakwaan',
            'Bisa menghafal Al-Quran dengan cepat', 'Terhindar dari penyakit', 'B');

        // BAB 1 — Tantangan 2
        $t2 = DB::table('tantangan')->insertGetId([
            'judul'       => 'BAB 1 — Tantangan 2: Memahami Sifat-Sifat Allah',
            'deskripsi'   => 'Uji pemahaman lebih dalam tentang makna dan pengamalan Asmaul Husna.',
            'mapel_id'    => $mapelId,
            'guru_id'     => $guruId,
            'kelas_id'    => $kelasId,
            'status'      => 'published',
            'batas_waktu' => now()->addDays(30),
            'poin'        => 100,
            'urutan'      => 2,
            'difficulty'  => 'chapter_1',
            'bab'         => 1,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        $pg($t2, 'Al-Bashir berarti Allah Maha Melihat. Apa dampak keyakinan ini terhadap perilaku seorang Muslim?',
            'Merasa bebas berbuat apa saja',
            'Berhati-hati dalam setiap tindakan karena merasa diawasi Allah',
            'Tidak perlu beribadah karena Allah sudah tahu segalanya',
            'Cukup beribadah di masjid saja', 'B');
        $matching($t2,
            'Pasangkan nama Asmaul Husna dengan artinya yang tepat!',
            ['Al-Alim', "As-Sami'", 'Al-Bashir', 'Al-Khabir'],
            ['Yang Maha Mengetahui', 'Yang Maha Mendengar', 'Yang Maha Melihat', 'Yang Maha Mengenal'],
            [['kiri'=>0,'kanan'=>0],['kiri'=>1,'kanan'=>1],['kiri'=>2,'kanan'=>2],['kiri'=>3,'kanan'=>3]]
        );
        $essay($t2,
            'Sebutkan dan jelaskan 2 cara mengamalkan Asmaul Husna dalam kehidupan sehari-hari!',
            'Contoh: 1) Membaca dan menjadikannya wirid/dzikir harian, 2) Meneladani sifat-sifat Allah seperti bersikap jujur karena Allah Al-Haqq.');
        $pg($t2, 'Mengapa Allah disebut Al-Khabir (Yang Maha Mengenal)?',
            'Karena Allah hanya mengenal orang-orang yang shalih',
            'Karena Allah mengenal secara mendalam segala sesuatu di alam semesta',
            'Karena Allah hanya mengenal para nabi dan rasul',
            'Karena Allah mengenal manusia saja', 'B');

        // BAB 1 — Tantangan 3
        $t3 = DB::table('tantangan')->insertGetId([
            'judul'       => 'BAB 1 — Tantangan 3: Penguasaan Asmaul Husna',
            'deskripsi'   => 'Tantangan tingkat lanjut — butuh pemahaman mendalam tentang seluruh materi BAB 1.',
            'mapel_id'    => $mapelId,
            'guru_id'     => $guruId,
            'kelas_id'    => $kelasId,
            'status'      => 'published',
            'batas_waktu' => now()->addDays(30),
            'poin'        => 150,
            'urutan'      => 3,
            'difficulty'  => 'chapter_1',
            'bab'         => 1,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        $pg($t3, 'Seorang siswa selalu berbuat baik meskipun tidak ada yang melihatnya. Asmaul Husna mana yang paling tepat menjadi landasan sikapnya?',
            'Al-Malik', 'Al-Bashir dan Al-Alim', 'Ar-Rahman', 'Al-Ghaffar', 'B');
        $essay($t3,
            'Apa perbedaan antara Al-Alim dan Al-Khabir? Jelaskan dengan contoh!',
            'Al-Alim: Allah mengetahui segala sesuatu secara umum. Al-Khabir: Allah mengenal secara mendalam dan terperinci, termasuk isi hati manusia.');
        $matching($t3,
            'Pasangkan pernyataan dengan Asmaul Husna yang sesuai!',
            [
                'Allah mendengar doa hambanya walau berbisik',
                'Allah tahu isi hati yang tersembunyi',
                'Allah melihat perbuatan baik yang dilakukan diam-diam',
                'Allah mengenal karakter setiap makhluk-Nya',
            ],
            ["As-Sami'", 'Al-Alim', 'Al-Bashir', 'Al-Khabir'],
            [['kiri'=>0,'kanan'=>0],['kiri'=>1,'kanan'=>1],['kiri'=>2,'kanan'=>2],['kiri'=>3,'kanan'=>3]]
        );
        $essay($t3,
            "Bagaimana cara meneladani sifat As-Sami' dalam kehidupan sehari-hari sebagai seorang pelajar?",
            "Meneladani As-Sami' dengan cara: mendengarkan penjelasan guru dengan seksama, tidak memotong pembicaraan orang lain, mendengarkan nasihat orang tua.");
        $pg($t3, 'Manakah pernyataan yang SALAH tentang Asmaul Husna?',
            'Asmaul Husna berjumlah 99 nama',
            'Asmaul Husna hanya boleh dibaca saat sholat',
            'Asmaul Husna mencerminkan keagungan Allah',
            'Mempelajari Asmaul Husna meningkatkan keimanan', 'B');

        // ══════════════════════════════════════════════════════
        // BAB 2 — Kejujuran, Amanah, Istiqomah (3 Tantangan)
        // ══════════════════════════════════════════════════════

        // BAB 2 — Tantangan 1
        $t4 = DB::table('tantangan')->insertGetId([
            'judul'       => 'BAB 2 — Tantangan 1: Mengenal Kejujuran dan Amanah',
            'deskripsi'   => 'Uji pemahamanmu tentang pengertian kejujuran, amanah, dan istiqomah.',
            'mapel_id'    => $mapelId,
            'guru_id'     => $guruId,
            'kelas_id'    => $kelasId,
            'status'      => 'published',
            'batas_waktu' => now()->addDays(30),
            'poin'        => 60,
            'urutan'      => 1,
            'difficulty'  => 'chapter_2',
            'bab'         => 2,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        $pg($t4, 'Shiddiq adalah istilah Arab untuk sifat...',
            'Amanah', 'Kejujuran', 'Istiqomah', 'Sabar', 'B');
        $pg($t4, 'Rasulullah SAW mendapat gelar Al-Amin karena...',
            'Beliau seorang pedagang kaya',
            'Beliau selalu menjaga amanah yang dipercayakan',
            'Beliau berasal dari keluarga terhormat',
            'Beliau pandai berdakwah', 'B');
        $pg($t4, 'Berikut ini yang BUKAN contoh sikap amanah adalah...',
            'Mengembalikan barang yang dipinjam',
            'Menjaga rahasia yang dipercayakan',
            'Menggunakan uang jajan untuk hal lain tanpa izin orang tua',
            'Mengerjakan tugas yang diberikan guru dengan sungguh-sungguh', 'C');
        $pg($t4, 'Istiqomah berarti...',
            'Mudah menyerah saat menghadapi cobaan',
            'Teguh pendirian dan konsisten dalam kebaikan',
            'Selalu menuruti keinginan hawa nafsu',
            'Bersikap acuh pada lingkungan sekitar', 'B');
        $essay($t4,
            'Sebutkan 2 manfaat memiliki sifat jujur dalam kehidupan sehari-hari!',
            'Contoh: 1) Mendapat kepercayaan dari orang lain, 2) Hidup menjadi tenang karena tidak ada yang disembunyikan.');

        // BAB 2 — Tantangan 2
        $t5 = DB::table('tantangan')->insertGetId([
            'judul'       => 'BAB 2 — Tantangan 2: Penerapan Kejujuran dalam Kehidupan',
            'deskripsi'   => 'Analisis kasus dan penerapan nilai kejujuran, amanah, istiqomah.',
            'mapel_id'    => $mapelId,
            'guru_id'     => $guruId,
            'kelas_id'    => $kelasId,
            'status'      => 'published',
            'batas_waktu' => now()->addDays(30),
            'poin'        => 120,
            'urutan'      => 2,
            'difficulty'  => 'chapter_2',
            'bab'         => 2,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        $pg($t5, 'Ahmad menemukan dompet berisi uang di jalan. Sikap yang mencerminkan amanah adalah...',
            'Mengambil uangnya dan membuang dompetnya',
            'Menyerahkan ke kantor polisi atau mencari pemiliknya',
            'Menyimpannya karena tidak ada yang tahu',
            'Membagi uangnya dengan teman-teman', 'B');
        $matching($t5,
            'Pasangkan sikap dengan nilai yang sesuai!',
            [
                'Tidak berbohong meskipun situasi sulit',
                'Mengembalikan buku yang dipinjam dari perpustakaan',
                'Tetap rajin sholat meskipun sedang liburan',
            ],
            ['Shiddiq (Jujur)', 'Amanah', 'Istiqomah'],
            [['kiri'=>0,'kanan'=>0],['kiri'=>1,'kanan'=>1],['kiri'=>2,'kanan'=>2]]
        );
        $essay($t5,
            'Tuliskan contoh penerapan sifat istiqomah yang bisa kamu lakukan sebagai pelajar kelas 7!',
            'Contoh: Konsisten belajar setiap hari meskipun tidak ada ulangan, selalu mengerjakan PR tepat waktu, rutin membaca Al-Quran setiap selesai sholat.');

        // BAB 2 — Tantangan 3
        $t6 = DB::table('tantangan')->insertGetId([
            'judul'       => 'BAB 2 — Tantangan 3: Penguasaan Akhlak Terpuji',
            'deskripsi'   => 'Tantangan tingkat lanjut — uji penguasaan menyeluruh materi BAB 2.',
            'mapel_id'    => $mapelId,
            'guru_id'     => $guruId,
            'kelas_id'    => $kelasId,
            'status'      => 'published',
            'batas_waktu' => now()->addDays(30),
            'poin'        => 150,
            'urutan'      => 3,
            'difficulty'  => 'chapter_2',
            'bab'         => 2,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        $pg($t6, 'Dina selalu mengerjakan sholat 5 waktu meski sedang berlibur dan tidak ada yang memantaunya. Sikap Dina mencerminkan...',
            'Amanah', 'Shiddiq', 'Istiqomah', 'Tawadhu', 'C');
        $essay($t6,
            'Jelaskan perbedaan antara kejujuran (shiddiq) dan amanah, beserta masing-masing satu contohnya!',
            'Shiddiq: selalu berkata dan berbuat benar. Contoh: mengakui belum mengerjakan PR. Amanah: dapat dipercaya dan bertanggung jawab. Contoh: mengembalikan pensil yang dipinjam dari teman.');
        $matching($t6,
            'Pasangkan situasi dengan nilai akhlak yang diterapkan!',
            [
                'Berkata benar meski akibatnya tidak menyenangkan',
                'Menjaga titipan teman dengan baik',
                'Tetap belajar rutin walau tidak ada PR',
                'Tidak bercerita rahasia keluarga kepada orang lain',
            ],
            ['Kejujuran', 'Amanah (menjaga titipan)', 'Istiqomah', 'Amanah (menjaga rahasia)'],
            [['kiri'=>0,'kanan'=>0],['kiri'=>1,'kanan'=>1],['kiri'=>2,'kanan'=>2],['kiri'=>3,'kanan'=>3]]
        );
        $pg($t6, 'Apa akibat jika seseorang tidak memiliki sifat amanah dalam kehidupan bermasyarakat?',
            'Semakin banyak teman yang percaya',
            'Kehilangan kepercayaan dan dijauhi masyarakat',
            'Mendapat pujian dari semua orang',
            'Tidak ada pengaruhnya sama sekali', 'B');
        $essay($t6,
            'Bagaimana cara melatih sikap istiqomah dalam ibadah sehari-hari? Sebutkan minimal 3 cara!',
            'Cara melatih istiqomah: 1) Membuat jadwal sholat dan ibadah harian yang ditaati, 2) Mulai dari target kecil yang konsisten, 3) Bergaul dengan teman yang rajin beribadah agar saling mengingatkan.');
    }
}