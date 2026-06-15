<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tantangan_id')->constrained('tantangan')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->enum('tipe', ['pg', 'essay', 'matching'])->default('pg');
            $table->text('opsi_a')->nullable();
            $table->text('opsi_b')->nullable();
            $table->text('opsi_c')->nullable();
            $table->text('opsi_d')->nullable();
            $table->text('jawaban_benar')->nullable();
            $table->json('kiri_items')->nullable();
            $table->json('kanan_items')->nullable();
            $table->json('matching_pairs')->nullable();
            $table->integer('matching_count')->default(0);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal');
    }
};
