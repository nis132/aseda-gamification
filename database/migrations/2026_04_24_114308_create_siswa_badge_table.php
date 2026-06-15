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
        Schema::create('siswa_badge', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tantangan_id')->nullable()->constrained('tantangan')->onDelete('set null');
            $table->foreignId('badge_id')->constrained('badge')->onDelete('cascade');
            $table->boolean('is_new')->default(true);
            $table->timestamp('diterima_pada')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_badge');
    }
};
