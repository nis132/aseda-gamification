<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            if (!Schema::hasColumn('materi', 'bab')) {
                // bab 1-8, sesuai level_required
                $table->unsignedTinyInteger('bab')->default(1)->after('level_required');
            }
        });

        // Sinkronkan bab dari level_required yang sudah ada
        DB::statement('UPDATE materi SET bab = level_required WHERE bab = 0 OR bab IS NULL');
    }

    public function down(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            if (Schema::hasColumn('materi', 'bab')) {
                $table->dropColumn('bab');
            }
        });
    }
};