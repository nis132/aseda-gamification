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
            $table->dateTime('review_dibuka_pada')
                ->nullable()
                ->after('is_pending')
                ->comment('Waktu guru membuka review untuk siswa. NULL = belum dibuka');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_tantangan', function (Blueprint $table) {
            $table->dropColumn('review_dibuka_pada');
        });
    }
};
