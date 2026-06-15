<?php
// database/migrations/2026_06_01_000001_create_leaderboard_final_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaderboard_final', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kelas_id');
            $table->unsignedBigInteger('siswa_id');
            $table->integer('total_poin');
            $table->integer('jumlah_selesai');
            $table->integer('rank');                        // 1, 2, 3, dst
            $table->string('periode', 30);                 // contoh: "2025/2026 Genap"
            $table->unsignedBigInteger('di_kunci_oleh');   // guru/admin yang mengunci
            $table->timestamp('dikunci_pada');
            $table->timestamps();

            $table->unique(['kelas_id', 'siswa_id', 'periode']);
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
            $table->foreign('siswa_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('di_kunci_oleh')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaderboard_final');
    }
};