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
        Schema::table('users', function (Blueprint $table) {
            // Tambah field kontak orang tua
            $table->string('nama_orang_tua')->nullable()->after('nama');
            $table->string('nomor_wa_orang_tua')->nullable()->after('nama_orang_tua');
            $table->string('email_orang_tua')->nullable()->after('nomor_wa_orang_tua');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nama_orang_tua', 'nomor_wa_orang_tua', 'email_orang_tua']);
        });
    }
};
