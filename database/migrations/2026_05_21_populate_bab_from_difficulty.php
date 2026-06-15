<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - populate bab field for existing tantangan
     */
    public function up()
    {
        // Untuk setiap tantangan yang bab-nya NULL, set berdasarkan difficulty (convert string to int)
        $difficultyMap = [
            'chapter_1' => 1,
            'chapter_2' => 2,
            'chapter_3' => 3,
            'chapter_4' => 4,
            'chapter_5' => 5,
            'chapter_6' => 6,
            'chapter_7' => 7,
            'chapter_8' => 8,
            'easy'      => 1,
            'medium'    => 2,
            'hard'      => 3,
            'expert'    => 4,
        ];

        $tantangan = DB::table('tantangan')->whereNull('bab')->get();

        foreach ($tantangan as $t) {
            $babInt = $difficultyMap[$t->difficulty] ?? null;
            if ($babInt) {
                DB::table('tantangan')->where('id', $t->id)->update(['bab' => $babInt]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Set bab back to NULL
        DB::table('tantangan')->update(['bab' => null]);
    }
};
