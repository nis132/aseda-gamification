<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tantangan', function (Blueprint $table) {
            // Remedial = muncul jika expired ATAU nilai < 60
            $table->tinyInteger('is_remedial')->default(0)->after('is_pengayaan');
        });
    }

    public function down(): void
    {
        Schema::table('tantangan', function (Blueprint $table) {
            $table->dropColumn('is_remedial');
        });
    }
};