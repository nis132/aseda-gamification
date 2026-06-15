<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nilai_tantangan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tantangan_id')
                ->constrained('tantangan')
                ->cascadeOnDelete();

            $table->foreignId('siswa_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->integer('nilai')->default(0);

            $table->integer('poin_didapat')->default(0);

            $table->timestamp('waktu_submit')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_tantangan');
    }
};