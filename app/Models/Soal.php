<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $table = 'soal';
    
protected $fillable = [
    'tantangan_id', 'pertanyaan', 'tipe', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d',
    'jawaban_benar', 'kiri_items', 'kanan_items', 'matching_pairs', 'matching_count'
];

protected $casts = [
    'kiri_items' => 'array',
    'kanan_items' => 'array', 
    'matching_pairs' => 'array'
];

    public function tantangan()
    {
        return $this->belongsTo(Tantangan::class, 'tantangan_id');
    }
}
