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
        Schema::create('badge', function (Blueprint $table) {
            $table->id();
            $table->string('nama_badge', 100);
            $table->string('deskripsi', 255);
            $table->string('icon', 100);
            $table->integer('poin_minimal');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badge');
    }
};
