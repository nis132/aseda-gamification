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
            // urutan per mapel+kelas, nullable agar data lama tidak error
            $table->unsignedInteger('urutan')->nullable()->after('poin');
        });

        // Isi urutan otomatis untuk data lama berdasarkan id (per mapel + kelas)
        $groups = DB::table('tantangan')
            ->select('mapel_id', 'kelas_id')
            ->groupBy('mapel_id', 'kelas_id')
            ->get();

        foreach ($groups as $group) {
            $tantangans = DB::table('tantangan')
                ->where('mapel_id', $group->mapel_id)
                ->where('kelas_id', $group->kelas_id)
                ->orderBy('id')
                ->get();

            foreach ($tantangans as $index => $t) {
                DB::table('tantangan')
                    ->where('id', $t->id)
                    ->update(['urutan' => $index + 1]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('tantangan', function (Blueprint $table) {
            $table->dropColumn('urutan');
        });
    }
};
