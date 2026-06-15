<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nis', 20)->nullable()->unique()->after('nama')->comment('Nomor Induk Siswa');
            $table->string('nip', 30)->nullable()->unique()->after('nis')->comment('Nomor Induk Pegawai Guru');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nis', 'nip']);
        });
    }
};
