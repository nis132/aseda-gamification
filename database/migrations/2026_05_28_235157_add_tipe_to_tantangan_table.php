<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan kolom `tipe` ke tabel tantangan untuk membedakan
 * tantangan biasa (reguler), UTS, dan UAS.
 *
 * Lock rule:
 *   - 'reguler' : dikunci per-bab (isBabLockedFor)
 *   - 'uts'     : hanya bisa diakses jika level siswa >= 4
 *                 (berarti sudah menyelesaikan Bab 1–4)
 *   - 'uas'     : hanya bisa diakses jika level siswa >= 8
 *                 (berarti sudah menyelesaikan semua 8 bab)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tantangan', function (Blueprint $table) {
            if (!Schema::hasColumn('tantangan', 'tipe')) {
                $table->enum('tipe', ['reguler', 'uts', 'uas'])
                      ->default('reguler')
                      ->after('bab')
                      ->comment('reguler = tantangan biasa, uts = Ujian Tengah Semester (butuh level 4), uas = Ujian Akhir Semester (butuh level 8)');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tantangan', function (Blueprint $table) {
            if (Schema::hasColumn('tantangan', 'tipe')) {
                $table->dropColumn('tipe');
            }
        });
    }
};