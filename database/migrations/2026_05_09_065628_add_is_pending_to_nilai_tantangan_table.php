<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('nilai_tantangan')) {

            Schema::table('nilai_tantangan', function (Blueprint $table) {

                if (!Schema::hasColumn('nilai_tantangan', 'is_pending')) {

                    $table->boolean('is_pending')
                        ->default(false)
                        ->comment('true = ada soal uraian menunggu penilaian guru')
                        ->after('waktu_submit');

                }

            });

        }
    }

    public function down(): void
    {
        if (
            Schema::hasTable('nilai_tantangan') &&
            Schema::hasColumn('nilai_tantangan', 'is_pending')
        ) {

            Schema::table('nilai_tantangan', function (Blueprint $table) {
                $table->dropColumn('is_pending');
            });

        }
    }
};