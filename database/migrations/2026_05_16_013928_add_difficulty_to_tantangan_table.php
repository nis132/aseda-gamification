<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tantangan', function (Blueprint $table) {
            // VARCHAR agar support chapter_1..chapter_8 dan legacy easy/medium/hard/expert
            $table->string('difficulty', 20)
                  ->default('chapter_1')
                  ->after('urutan');
        });
    }

    public function down(): void
    {
        Schema::table('tantangan', function (Blueprint $table) {
            $table->dropColumn('difficulty');
        });
    }
};