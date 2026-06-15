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
        Schema::table('nilai_tantangan', function (Blueprint $table) {
            // Tambah field total_nilai jika belum ada
            if (!Schema::hasColumn('nilai_tantangan', 'total_nilai')) {
                $table->float('total_nilai', 5, 2)->default(0)->after('nilai');
            }
            
            // Tambah field is_pending jika belum ada
            if (!Schema::hasColumn('nilai_tantangan', 'is_pending')) {
                $table->boolean('is_pending')->default(0)->after('poin_didapat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_tantangan', function (Blueprint $table) {
            if (Schema::hasColumn('nilai_tantangan', 'total_nilai')) {
                $table->dropColumn('total_nilai');
            }
            if (Schema::hasColumn('nilai_tantangan', 'is_pending')) {
                $table->dropColumn('is_pending');
            }
        });
    }
};
