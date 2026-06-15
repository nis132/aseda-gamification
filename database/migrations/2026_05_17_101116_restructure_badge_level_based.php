<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom level_required dan ada_sertifikat ke tabel badge
        Schema::table('badge', function (Blueprint $table) {
            $table->unsignedTinyInteger('level_required')->default(1)->after('poin_minimal');
            $table->boolean('ada_sertifikat')->default(false)->after('level_required');
            $table->string('tipe_syarat', 30)->default('level')->after('ada_sertifikat');
        });

        // Hapus badge lama, ganti dengan yang baru berbasis level
        DB::table('siswa_badge')->delete();
        DB::table('badge')->delete();
        DB::statement('ALTER TABLE badge AUTO_INCREMENT = 1');

        DB::table('badge')->insert([
            [
                'nama_badge'     => 'Pemula',
                'deskripsi'      => 'Capai Level 2 — selesaikan 3 materi dan 3 tantangan',
                'icon'           => 'pemula.png',
                'poin_minimal'   => 0,
                'level_required' => 2,
                'ada_sertifikat' => false,
                'tipe_syarat'    => 'level',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'nama_badge'     => 'Penjelajah',
                'deskripsi'      => 'Capai Level 3 — selesaikan 6 materi dan 6 tantangan',
                'icon'           => 'penjelajah.png',
                'poin_minimal'   => 0,
                'level_required' => 3,
                'ada_sertifikat' => false,
                'tipe_syarat'    => 'level',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'nama_badge'     => 'Ahli',
                'deskripsi'      => 'Capai Level 4 — selesaikan 9 materi dan 9 tantangan',
                'icon'           => 'ahli.png',
                'poin_minimal'   => 0,
                'level_required' => 4,
                'ada_sertifikat' => false,
                'tipe_syarat'    => 'level',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'nama_badge'     => 'Grand Master',
                'deskripsi'      => 'Capai Level 5 — selesaikan 12 materi dan 12 tantangan',
                'icon'           => 'grandmaster.png',
                'poin_minimal'   => 0,
                'level_required' => 5,
                'ada_sertifikat' => true,
                'tipe_syarat'    => 'level',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'nama_badge'     => 'Penguasa Mapel',
                'deskripsi'      => 'Selesaikan SEMUA tantangan di satu mata pelajaran',
                'icon'           => 'penguasa.png',
                'poin_minimal'   => 0,
                'level_required' => 1,
                'ada_sertifikat' => true,
                'tipe_syarat'    => 'semua_mapel',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::table('badge', function (Blueprint $table) {
            $table->dropColumn(['level_required', 'ada_sertifikat', 'tipe_syarat']);
        });
    }
};