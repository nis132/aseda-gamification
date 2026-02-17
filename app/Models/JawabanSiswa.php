<?php
// app/Models/JawabanSiswa.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanSiswa extends Model
{
    protected $table = 'jawaban_siswa';
    
    protected $fillable = [
        'siswa_id',
        'tantangan_id', 
        'soal_id',
        'jawaban',
        'nilai',
        'dinilai_manual'
    ];

    protected $casts = [
        'dinilai_manual' => 'boolean',
        'nilai' => 'integer'
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function tantangan()
    {
        return $this->belongsTo(Tantangan::class);
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}
