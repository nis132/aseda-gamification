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
    Schema::table('siswa_badge', function (Blueprint $table) {
        // Tambahkan is_new untuk animasi, default 1 (true) saat pertama dibuat
        $table->boolean('is_new')->default(1)->after('badge_id'); 
        
        // Tambahkan tantangan_id untuk validasi duplikat
        $table->foreignId('tantangan_id')->nullable()->after('siswa_id')->constrained('tantangan')->onDelete('set null');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa_badge', function (Blueprint $table) {
            //
        });
    }
};
