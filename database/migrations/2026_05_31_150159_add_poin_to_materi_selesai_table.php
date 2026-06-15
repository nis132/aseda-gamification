<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materi_selesai', function (Blueprint $table) {
            $table->unsignedSmallInteger('poin')
                ->default(0)
                ->after('materi_id')
                ->comment('Poin yang didapat siswa saat menandai materi ini selesai');
        });
    }

    public function down(): void
    {
        Schema::table('materi_selesai', function (Blueprint $table) {
            $table->dropColumn('poin');
        });
    }
};