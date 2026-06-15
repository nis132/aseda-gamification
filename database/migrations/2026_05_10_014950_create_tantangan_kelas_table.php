<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tantangan_kelas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tantangan_id')
                ->constrained('tantangan')
                ->onDelete('cascade');

            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->onDelete('cascade');

            // guru yang publish ke kelas ini
            $table->foreignId('guru_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->datetime('batas_waktu')->nullable();

            $table->enum('status', ['published', 'draft'])
                ->default('published');

            $table->timestamps();

            // supaya tidak double publish ke kelas sama
            $table->unique(['tantangan_id', 'kelas_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tantangan_kelas');
    }
};