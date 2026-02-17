<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
     protected $table = 'soal';
    protected $fillable = [
        'tantangan_id', 'pertanyaan', 'pilihan_a', 'pilihan_b', 
        'pilihan_c', 'pilihan_d', 'jawaban_benar'
    ];

    // Relationship ke Tantangan
    public function tantangan()
    {
        return $this->belongsTo(Tantangan::class);
    }

    // 🔥 FIX: Relationship ke Jawaban (opsional)
    public function jawabanSiswa()
    {
        return $this->hasMany(JawabanSiswa::class, 'soal_id');
    }

    // Jawaban benar TIDAK PERLU relationship - itu column biasa!
    // $soal->jawaban_benar = 'A' langsung access
}
