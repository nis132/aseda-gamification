<?php
// database/seeders/MateriPAISeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MateriPAISeeder extends Seeder
{
    public function run(): void
    {
        $guruId  = 2;  // ID guru PAI
        $kelasId = 1;  // ID kelas 7A
        $mapelId = 3;  // ID mapel PAI

        $now = now();

        // ══════════════════════════════════════════════════
        // Aturan: 1 bab = 3 materi, harus selesai semua
        //         sebelum bisa lanjut ke bab berikutnya.
        //         bab = level_required (disinkronkan)
        // ══════════════════════════════════════════════════

        DB::table('materi')->insert([

            // ─────────────────────────────────────────────
            // BAB 1 — Asmaul Husna (3 materi)
            // ─────────────────────────────────────────────
            [
                'judul'          => 'BAB 1 — Materi 1: Pengertian dan Jumlah Asmaul Husna',
                'deskripsi'      => "## Pengertian Asmaul Husna\n\nAsmaul Husna adalah nama-nama Allah yang baik dan indah. Kata **Asmaul Husna** berasal dari bahasa Arab:\n- *Asma* = nama-nama\n- *Husna* = yang baik/indah\n\n## Jumlah Asmaul Husna\n\nAllah memiliki **99 nama** yang semuanya mencerminkan sifat-sifat keagungan dan kesempurnaan-Nya.\n\n> *\"Allah memiliki 99 nama — seratus kurang satu — barangsiapa menghafalnya, ia masuk surga.\"* (HR. Bukhari & Muslim)\n\n## Dalil Al-Quran\n\nAllah berfirman dalam QS. Al-A'raf: 180:\n*\"Dan Allah memiliki Asmaul Husna (nama-nama yang terbaik), maka bermohonlah kepada-Nya dengan menyebut Asmaul Husna itu.\"*\n\n## Manfaat Mempelajari Asmaul Husna\n\n1. Meningkatkan keimanan dan ketakwaan\n2. Mendorong perilaku terpuji dalam kehidupan\n3. Menjadi motivasi untuk selalu berbuat baik\n4. Mendekatkan diri kepada Allah",
                'file_url'       => null,
                'video_url'      => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'link_referensi' => 'https://kemenag.go.id',
                'mapel_id'       => $mapelId,
                'guru_id'        => $guruId,
                'kelas_id'       => $kelasId,
                'level_required' => 1,
                'bab'            => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'judul'          => 'BAB 1 — Materi 2: Al-Alim, As-Sami\', Al-Bashir, Al-Khabir',
                'deskripsi'      => "## Empat Asmaul Husna Utama BAB 1\n\n### 1. Al-Alim (الْعَلِيمُ) — Yang Maha Mengetahui\nAllah mengetahui **segala sesuatu** tanpa batas, baik yang tampak maupun tersembunyi, yang lampau maupun yang akan datang.\n\n**Contoh pengamalan:** Selalu berhati-hati dalam setiap tindakan karena Allah mengetahui segalanya.\n\n### 2. As-Sami' (السَّمِيعُ) — Yang Maha Mendengar\nAllah mendengar **semua suara** di seluruh alam semesta, termasuk doa terlembut sekalipun.\n\n**Contoh pengamalan:** Selalu berdoa dengan sungguh-sungguh karena Allah pasti mendengar.\n\n### 3. Al-Bashir (الْبَصِيرُ) — Yang Maha Melihat\nAllah melihat **semua perbuatan** hamba-Nya, termasuk yang dilakukan secara sembunyi-sembunyi.\n\n**Contoh pengamalan:** Berbuat baik meskipun tidak ada yang melihat, karena Allah selalu melihat.\n\n### 4. Al-Khabir (الْخَبِيرُ) — Yang Maha Mengenal\nAllah mengenal secara **mendalam dan terperinci** segala sesuatu yang ada di alam semesta, termasuk isi hati manusia.\n\n**Perbedaan Al-Alim & Al-Khabir:**\n- Al-Alim: mengetahui secara umum\n- Al-Khabir: mengenal secara mendalam/terperinci",
                'file_url'       => null,
                'video_url'      => null,
                'link_referensi' => null,
                'mapel_id'       => $mapelId,
                'guru_id'        => $guruId,
                'kelas_id'       => $kelasId,
                'level_required' => 1,
                'bab'            => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'judul'          => 'BAB 1 — Materi 3: Cara Mengamalkan Asmaul Husna',
                'deskripsi'      => "## Cara Mengamalkan Asmaul Husna\n\nMempelajari Asmaul Husna tidak cukup hanya dihafalkan — harus **diamalkan** dalam kehidupan sehari-hari.\n\n## 3 Cara Utama Mengamalkan\n\n### 1. Membaca sebagai Dzikir & Doa\nGunakan Asmaul Husna dalam doa dan dzikir harian. Contoh:\n- *\"Ya Al-Alim, berikanlah aku ilmu yang bermanfaat\"*\n- *\"Ya As-Sami', dengarkanlah doaku\"*\n\n### 2. Meneladani Sifat-Sifat Allah\nTeladani sifat Allah dalam perilaku sehari-hari:\n- Meneladani **As-Sami'** → mendengarkan orang lain dengan penuh perhatian\n- Meneladani **Al-Khabir** → teliti dan memahami sesuatu secara mendalam\n\n### 3. Menghafalkan dengan Metode yang Menyenangkan\n- Mendengarkan lagu/nasyid Asmaul Husna\n- Membuat kartu hafalan\n- Belajar bersama teman\n\n## Dampak Positif Mengamalkan Asmaul Husna\n\n| Asmaul Husna | Dampak pada Perilaku |\n|---|---|\n| Al-Alim | Rajin belajar karena sadar Allah tahu usaha kita |\n| As-Sami' | Pandai mendengarkan dan berempati |\n| Al-Bashir | Jujur meskipun tidak ada yang mengawasi |\n| Al-Khabir | Teliti dan tidak ceroboh |",
                'file_url'       => null,
                'video_url'      => null,
                'link_referensi' => null,
                'mapel_id'       => $mapelId,
                'guru_id'        => $guruId,
                'kelas_id'       => $kelasId,
                'level_required' => 1,
                'bab'            => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // ─────────────────────────────────────────────
            // BAB 2 — Kejujuran, Amanah, Istiqomah (3 materi)
            // ─────────────────────────────────────────────
            [
                'judul'          => 'BAB 2 — Materi 1: Pengertian Kejujuran (Shiddiq)',
                'deskripsi'      => "## Pengertian Kejujuran\n\nKejujuran atau **Shiddiq** (صِدِّيق) adalah sifat yang mendorong seseorang untuk selalu berkata dan berbuat **benar**, sesuai dengan kenyataan yang ada, baik dalam keadaan senang maupun susah.\n\n## Dalil tentang Kejujuran\n\nRasulullah SAW bersabda:\n> *\"Hendaklah kalian berlaku jujur, karena kejujuran membawa kepada kebaikan, dan kebaikan membawa ke surga.\"* (HR. Bukhari & Muslim)\n\n## Bentuk-Bentuk Kejujuran\n\n1. **Jujur dalam perkataan** — tidak berbohong atau berkata dusta\n2. **Jujur dalam perbuatan** — tidak curang, tidak menyontek\n3. **Jujur dalam niat** — ikhlas dalam beribadah\n4. **Jujur pada diri sendiri** — mengakui kesalahan dan kekurangan\n\n## Manfaat Kejujuran\n\n- Mendapat kepercayaan dari orang lain\n- Hidup menjadi tenang dan tidak was-was\n- Dicintai Allah dan sesama manusia\n- Terhindar dari dosa dan azab Allah\n\n## Contoh Kejujuran dalam Kehidupan Pelajar\n\n- Tidak menyontek saat ujian\n- Mengakui jika belum mengerjakan PR\n- Melaporkan uang kembalian yang lebih",
                'file_url'       => null,
                'video_url'      => null,
                'link_referensi' => null,
                'mapel_id'       => $mapelId,
                'guru_id'        => $guruId,
                'kelas_id'       => $kelasId,
                'level_required' => 2,
                'bab'            => 2,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'judul'          => 'BAB 2 — Materi 2: Pengertian Amanah dan Contohnya',
                'deskripsi'      => "## Pengertian Amanah\n\nAmanah (أَمَانَة) berarti **dapat dipercaya**. Orang yang amanah akan menjalankan tugas dan tanggung jawabnya dengan sebaik-baiknya, dan tidak mengkhianati kepercayaan yang diberikan.\n\n## Rasulullah SAW — Teladan Amanah\n\nSebelum diangkat menjadi nabi, Rasulullah SAW sudah dikenal dengan gelar **Al-Amin** (yang dapat dipercaya) oleh penduduk Makkah. Beliau selalu:\n- Mengembalikan barang titipan tepat waktu\n- Tidak pernah berbohong dalam berdagang\n- Menjaga rahasia yang dipercayakan\n\n## Bentuk-Bentuk Amanah\n\n### Amanah kepada Allah\nMenjalankan perintah dan menjauhi larangan Allah.\n\n### Amanah kepada Sesama Manusia\n- Mengembalikan barang yang dipinjam\n- Menjaga rahasia teman\n- Melaksanakan tugas yang diberikan dengan sungguh-sungguh\n\n### Amanah kepada Diri Sendiri\n- Menjaga kesehatan\n- Menggunakan waktu dengan baik\n- Belajar dengan sungguh-sungguh\n\n## Akibat Tidak Amanah\n\n- Kehilangan kepercayaan orang lain\n- Dijauhi teman dan masyarakat\n- Mendapat dosa di sisi Allah\n- Rasulullah bersabda: *\"Tidak ada iman bagi orang yang tidak amanah.\"* (HR. Ahmad)",
                'file_url'       => null,
                'video_url'      => null,
                'link_referensi' => null,
                'mapel_id'       => $mapelId,
                'guru_id'        => $guruId,
                'kelas_id'       => $kelasId,
                'level_required' => 2,
                'bab'            => 2,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'judul'          => 'BAB 2 — Materi 3: Istiqomah dan Penerapannya',
                'deskripsi'      => "## Pengertian Istiqomah\n\nIstiqomah (اسْتِقَامَة) adalah sikap **teguh pendirian** dan konsisten dalam menjalankan kebaikan, tidak mudah goyah meskipun menghadapi godaan atau cobaan.\n\n## Dalil tentang Istiqomah\n\nAllah berfirman dalam QS. Fussilat: 30:\n> *\"Sesungguhnya orang-orang yang mengatakan 'Tuhan kami adalah Allah' kemudian mereka meneguhkan pendirian mereka, maka malaikat akan turun kepada mereka.\"*\n\n## Ciri-Ciri Orang yang Istiqomah\n\n1. Konsisten dalam beribadah, tidak hanya saat senang\n2. Tidak mudah terpengaruh ajakan buruk teman\n3. Tetap jujur meskipun ada tekanan\n4. Rajin belajar tanpa perlu diingatkan terus\n5. Menepati janji\n\n## Contoh Penerapan Istiqomah bagi Pelajar\n\n| Situasi | Sikap Istiqomah |\n|---|---|\n| Teman mengajak bolos | Menolak dengan sopan |\n| Tidak ada ulangan | Tetap belajar sesuai jadwal |\n| Liburan panjang | Tetap sholat 5 waktu |\n| Tugas sulit | Tidak menyerah, terus berusaha |\n\n## Cara Melatih Istiqomah\n\n1. Membuat jadwal harian dan ditaati\n2. Mulai dari hal-hal kecil secara konsisten\n3. Bergaul dengan teman-teman yang positif\n4. Selalu ingat tujuan dan manfaat kebaikan\n5. Berdoa memohon ketetapan hati kepada Allah",
                'file_url'       => null,
                'video_url'      => null,
                'link_referensi' => null,
                'mapel_id'       => $mapelId,
                'guru_id'        => $guruId,
                'kelas_id'       => $kelasId,
                'level_required' => 2,
                'bab'            => 2,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // ─────────────────────────────────────────────
            // BAB 3 — Al-Quran dan Hadis (3 materi)
            // ─────────────────────────────────────────────
            [
                'judul'          => 'BAB 3 — Materi 1: Kedudukan Al-Quran sebagai Sumber Hukum',
                'deskripsi'      => "## Pengertian Al-Quran\n\nAl-Quran adalah **kitab suci umat Islam** yang diturunkan kepada Nabi Muhammad SAW melalui malaikat Jibril selama ±23 tahun. Al-Quran merupakan sumber hukum pertama dan utama dalam Islam.\n\n## Keistimewaan Al-Quran\n\n- Terjaga keasliannya hingga akhir zaman (QS. Al-Hijr: 9)\n- Mukjizat terbesar Nabi Muhammad SAW\n- Bacaannya bernilai ibadah\n- Menjadi petunjuk bagi seluruh umat manusia\n\n## Kandungan Al-Quran\n\n| Kandungan | Penjelasan |\n|---|---|\n| **Akidah** | Keyakinan tentang Allah, malaikat, kitab, rasul, hari akhir, takdir |\n| **Ibadah** | Tata cara sholat, zakat, puasa, haji |\n| **Muamalah** | Aturan hubungan antar manusia |\n| **Akhlak** | Panduan perilaku yang baik dan terpuji |\n| **Sejarah** | Kisah para nabi dan umat terdahulu |\n\n## Cara Berinteraksi dengan Al-Quran\n\n1. Membaca dengan tartil (perlahan dan benar)\n2. Memahami makna dan tafsirnya\n3. Menghafalkan ayat-ayatnya\n4. Mengamalkan ajaran-ajarannya\n5. Mengajarkan kepada orang lain",
                'file_url'       => null,
                'video_url'      => null,
                'link_referensi' => null,
                'mapel_id'       => $mapelId,
                'guru_id'        => $guruId,
                'kelas_id'       => $kelasId,
                'level_required' => 3,
                'bab'            => 3,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'judul'          => 'BAB 3 — Materi 2: Hadis sebagai Penjelas Al-Quran',
                'deskripsi'      => "## Pengertian Hadis\n\nHadis adalah segala **ucapan, perbuatan, dan ketetapan** (taqrir) Nabi Muhammad SAW yang diriwayatkan oleh para sahabat.\n\n## Fungsi Hadis terhadap Al-Quran\n\n### 1. Penjelas (Bayan Tafsir)\nHadis menjelaskan ayat Al-Quran yang masih umum.\nContoh: Al-Quran memerintahkan sholat, Hadis menjelaskan tata caranya.\n\n### 2. Penguat (Bayan Taqrir)\nHadis memperkuat hukum yang sudah ada dalam Al-Quran.\n\n### 3. Penetap Hukum Baru (Bayan Tasyri')\nHadis menetapkan hukum yang belum disebutkan dalam Al-Quran.\nContoh: haramnya memakai cincin emas bagi laki-laki.\n\n## Tingkatan Kualitas Hadis\n\n| Tingkatan | Penjelasan |\n|---|---|\n| **Shahih** | Sanadnya bersambung, perawi adil & kuat hafalannya |\n| **Hasan** | Hampir seperti shahih, sedikit lebih lemah |\n| **Dha'if** | Ada kelemahan di sanad atau matan |\n| **Maudhu'** | Hadis palsu, tidak boleh diamalkan |\n\n## Kitab Hadis Utama (Kutub As-Sittah)\n\n1. Shahih Bukhari\n2. Shahih Muslim\n3. Sunan Abu Dawud\n4. Sunan Tirmidzi\n5. Sunan An-Nasa'i\n6. Sunan Ibnu Majah",
                'file_url'       => null,
                'video_url'      => null,
                'link_referensi' => null,
                'mapel_id'       => $mapelId,
                'guru_id'        => $guruId,
                'kelas_id'       => $kelasId,
                'level_required' => 3,
                'bab'            => 3,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'judul'          => 'BAB 3 — Materi 3: Mengamalkan Al-Quran dan Hadis',
                'deskripsi'      => "## Kewajiban Mengamalkan Al-Quran dan Hadis\n\nMemiliki Al-Quran dan Hadis saja tidak cukup — kita wajib **mengamalkan** isi dan ajarannya dalam kehidupan sehari-hari.\n\n## Cara Mengamalkan Al-Quran\n\n### Di Rumah\n- Membaca Al-Quran setiap hari minimal 1 halaman\n- Mendengarkan murottal Al-Quran\n- Mendiskusikan isi Al-Quran bersama keluarga\n\n### Di Sekolah\n- Mengikuti kegiatan tadarus Al-Quran\n- Mengikuti ekstrakurikuler tahfidz\n- Menerapkan nilai-nilai Al-Quran dalam pergaulan\n\n### Di Masyarakat\n- Mengikuti pengajian\n- Menyebarkan ajaran Al-Quran yang benar\n\n## Cara Mengamalkan Hadis\n\n1. Mempelajari hadis-hadis pendek yang mudah dihafal\n2. Menerapkan sunnah Nabi dalam kehidupan (makan dengan tangan kanan, dll)\n3. Menjauhi larangan yang disebutkan dalam hadis\n\n## Hambatan dan Solusi\n\n| Hambatan | Solusi |\n|---|---|\n| Malas membaca | Mulai dari target kecil (1 ayat/hari) |\n| Tidak faham bahasa Arab | Baca terjemahan |\n| Susah hafal | Ulang-ulang dengan metode mendengar |\n| Tidak ada waktu | Manfaatkan waktu setelah sholat |\n\n## Keutamaan Membaca Al-Quran\n\n> *\"Orang yang membaca Al-Quran dengan mahir, ia bersama para malaikat yang mulia. Orang yang membaca Al-Quran dengan terbata-bata dan susah, ia mendapat dua pahala.\"* (HR. Bukhari & Muslim)",
                'file_url'       => null,
                'video_url'      => null,
                'link_referensi' => null,
                'mapel_id'       => $mapelId,
                'guru_id'        => $guruId,
                'kelas_id'       => $kelasId,
                'level_required' => 3,
                'bab'            => 3,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // ─────────────────────────────────────────────
            // BAB 4 — Thaharah (3 materi)
            // ─────────────────────────────────────────────
            [
                'judul'          => 'BAB 4 — Materi 1: Pengertian dan Macam-Macam Hadas',
                'deskripsi'      => "## Pengertian Thaharah\n\nThaharah (طَهَارَة) artinya **bersuci** dari hadas dan najis. Thaharah merupakan syarat sah dalam melaksanakan ibadah sholat.\n\n## Macam-Macam Hadas\n\n### Hadas Kecil\nKeadaan tidak suci yang dapat dihilangkan dengan **wudhu** atau tayamum.\n\n**Penyebab hadas kecil:**\n- Buang air kecil atau besar\n- Keluar angin dari dubur (kentut)\n- Hilang akal: tidur, pingsan, mabuk\n- Menyentuh kemaluan sendiri\n\n**Cara mensucikan:** Berwudhu atau tayamum\n\n### Hadas Besar\nKeadaan tidak suci yang harus dihilangkan dengan **mandi wajib** (mandi junub).\n\n**Penyebab hadas besar:**\n- Berhubungan suami istri\n- Keluar air mani\n- Haid (menstruasi)\n- Nifas (darah setelah melahirkan)\n- Wiladah (setelah melahirkan)\n\n**Cara mensucikan:** Mandi wajib dengan niat\n\n## Perbedaan Hadas dan Najis\n\n| | Hadas | Najis |\n|---|---|---|\n| Pengertian | Keadaan tidak suci | Benda kotor yang harus dibersihkan |\n| Sifat | Tidak terlihat | Bisa terlihat/tercium |\n| Cara bersuci | Wudhu / mandi wajib | Dicuci dengan air |",
                'file_url'       => null,
                'video_url'      => null,
                'link_referensi' => null,
                'mapel_id'       => $mapelId,
                'guru_id'        => $guruId,
                'kelas_id'       => $kelasId,
                'level_required' => 4,
                'bab'            => 4,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'judul'          => 'BAB 4 — Materi 2: Macam-Macam Najis dan Cara Mensucikan',
                'deskripsi'      => "## Macam-Macam Najis\n\n### 1. Najis Mukhaffafah (Ringan)\n**Contoh:** Air kencing bayi laki-laki yang belum makan selain ASI\n**Cara mensucikan:** Diperciki air pada tempat yang terkena najis\n\n### 2. Najis Mutawassithah (Sedang)\nTerbagi menjadi dua:\n- **Najis 'Ainiyah**: masih ada wujud, bau, atau warnanya → dicuci hingga hilang\n- **Najis Hukmiyah**: sudah tidak ada wujudnya → cukup dialiri air\n\n**Contoh najis mutawassithah:**\n- Kotoran manusia dan hewan\n- Darah\n- Nanah\n- Minuman keras\n- Bangkai (selain ikan dan belalang)\n\n**Cara mensucikan:** Dicuci dengan air sampai hilang wujud, bau, dan warnanya\n\n### 3. Najis Mughallazhah (Berat)\n**Contoh:** Jilatan anjing atau babi\n**Cara mensucikan:** Dicuci **7 kali** dengan air, salah satunya dicampur tanah\n\n## Benda yang Suci Meskipun dari Hewan Najis\n\n- Telur yang sudah terpisah dari ayam\n- Madu dari lebah\n- Sutra dari ulat sutra\n\n## Hal-Hal yang Dimaafkan (Ma'fu)\n\nBeberapa najis dalam jumlah sangat sedikit dapat dimaafkan, seperti:\n- Percikan air kencing yang sangat sedikit\n- Darah dan nanah dalam jumlah sedikit",
                'file_url'       => null,
                'video_url'      => null,
                'link_referensi' => null,
                'mapel_id'       => $mapelId,
                'guru_id'        => $guruId,
                'kelas_id'       => $kelasId,
                'level_required' => 4,
                'bab'            => 4,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'judul'          => 'BAB 4 — Materi 3: Tata Cara Wudhu dan Tayamum',
                'deskripsi'      => "## Tata Cara Wudhu\n\n### Syarat Wudhu\n1. Islam\n2. Mumayyiz (bisa membedakan baik dan buruk)\n3. Tidak dalam keadaan haid/nifas\n4. Tidak ada penghalang air ke kulit\n5. Menggunakan air yang suci dan mensucikan\n\n### Fardhu (Rukun) Wudhu\n1. Niat\n2. Membasuh muka (dari batas rambut sampai dagu)\n3. Membasuh kedua tangan sampai siku\n4. Mengusap sebagian kepala\n5. Membasuh kedua kaki sampai mata kaki\n6. Tertib (berurutan)\n\n### Sunnah Wudhu\n- Membaca bismillah\n- Membasuh kedua telapak tangan\n- Berkumur-kumur\n- Membersihkan hidung\n- Mengusap seluruh kepala\n- Mengusap kedua telinga\n- Mendahulukan bagian kanan\n- Mengulang 3 kali\n- Membaca doa setelah wudhu\n\n## Tata Cara Tayamum\n\nTayamum adalah **pengganti wudhu/mandi** menggunakan debu/tanah suci ketika tidak ada air atau tidak bisa menggunakan air.\n\n### Cara Tayamum\n1. Niat tayamum\n2. Menepukkan kedua telapak tangan ke debu/tanah\n3. Mengusap muka\n4. Menepuk tangan lagi ke debu\n5. Mengusap kedua tangan hingga pergelangan\n\n### Tayamum Batal Karena\n- Semua yang membatalkan wudhu\n- Menemukan air (bagi yang bertayamum karena tidak ada air)\n- Sembuh (bagi yang bertayamum karena sakit)",
                'file_url'       => null,
                'video_url'      => null,
                'link_referensi' => null,
                'mapel_id'       => $mapelId,
                'guru_id'        => $guruId,
                'kelas_id'       => $kelasId,
                'level_required' => 4,
                'bab'            => 4,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

        ]);
    }
}