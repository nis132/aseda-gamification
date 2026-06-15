<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            // level 1 = semua bisa akses, level 2 = butuh level 2, dst
            $table->unsignedTinyInteger('level_required')->default(1)->after('guru_id');
        });
    }

    public function down(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->dropColumn('level_required');
        });
    }
};